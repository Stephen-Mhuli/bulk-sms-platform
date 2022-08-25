<?php

namespace App\Imports;

use App\Models\ContactGroup;
use App\Models\Group;
use App\Models\Label;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;

class ContactsImport implements ToCollection, WithHeadingRow, SkipsOnError, WithChunkReading, ShouldQueue, WithEvents
{
    use SkipsErrors;

    public $group_id = '';

    public function __construct($group_id, $auth_user)
    {
        $this->auth_user = $auth_user;
        $this->group_id = $group_id;
    }


    /**
     * @param Collection $rows
     * @throws \Throwable
     */
    public function collection(Collection $rows)
    {
        $rows=$rows->unique('number');
        $errorMsg = "";
        DB::beginTransaction();
        $label= $this->auth_user->labels()->where('title', 'new')->first();
        if (!$label) {
            $label = new Label();
            $label->title = 'new';
            $label->status = 'active';
            $label->customer_id = $this->auth_user->id;
            $label->color = 'red';
            $label->save();
        }

            $i = 1;
        $contactGroup = [];
        foreach ($rows as $key => $row) {

            if (isset($row['number']) && $row['number']) {

                //You can validate other values using same steps.

                $data['number'] = "+".str_replace('+','',$row['number']);
                $data['first_name'] = $row['first_name'] ?? '';
                $data['last_name'] = $row['last_name'] ?? '';
                $data['email'] = $row['email'] ?? '';
                $data['company'] = $row['company'] ?? '';
                $data['address'] = $row['address'] ?? '';
                $data['city'] = $row['city'] ?? '';
                $data['state'] = $row['state'] ?? '';
                $data['zip_code'] = $row['zip_code'] ?? '';
                $data['note'] = $row['note'] ?? '';
                $data['label_id'] = $label ? $label->id : '';
                $contact = $this->auth_user->contacts()->create($data);
                $contactGroup[] = [
                    'customer_id' => $this->auth_user->id,
                    'group_id' => $this->group_id,
                    'contact_id' => $contact->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                if (!$contact) {
                    $errorMsg = "Error while inserting";
                    break;
                }
                $i++;
            }
        }
        if ($contactGroup) {
            foreach (array_chunk($contactGroup, 1000) as $contactChunk) {
                ContactGroup::insert($contactChunk);
            }
        }
        if (!empty($errorMsg)) {
            // Rollback in case there is error
            DB::rollBack();

            //  return redirect()->back()->withErrors(['error' => $errorMsg]);
        } else {
            // Commit to database
            DB::commit();


            //   return redirect()->back()->withErrors(['success' => 'Uploaded Successfully']);
        }
    }

    public function chunkSize(): int
    {
        return 2000;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function (ImportFailed $event) {
                Group::where('id', $this->group_id)->update(['import_status' => 'failed', 'import_fail_message' => substr($event->getException(), 0, 191)]);
            },
            BeforeImport::class => function (BeforeImport $event) {
                Group::where('id', $this->group_id)->update(['import_status' => 'running']);
            },
            AfterImport::class => function (AfterImport $event) {
                Group::where('id', $this->group_id)->update(['import_status' => 'completed']);
            }
        ];
    }

}
