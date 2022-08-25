<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\TicketDescription;
use Illuminate\Http\Request;
use Response;

class TicketController extends Controller
{
    public function index()
    {

        return view('admin.ticket.index');
    }

    public function show()
    {
        $customers = auth()->user()->tickets()->select(['id', 'subject','customer_id','status']);
        return datatables()->of($customers)
            ->addColumn('description', function ($q) {
                $ticketDesc=TicketDescription::where('ticket_id', $q->id)->first();
                $desc= substr($ticketDesc->description, 0,25);
                return $ticketDesc->description;
            })
            ->addColumn('customer_id', function ($q) {
                $customer = Customer::where('id', $q->customer_id)->first();
                $name = isset($customer) && isset($customer->first_name) ? $customer->first_name : '';
                return $name;
            })
            ->addColumn('status', function ($q) {
                if ($q->status=='pending'){
                    return '<button type="button" class="btn light btn-sm btn-default dropdown-toggle bg-danger" data-toggle="dropdown" aria-expanded="false">
                                                Pending
                               </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                     <button data-message="Are you sure, you want to open this ticket?" data-action=' . route('admin.ticket.status', ['id' => $q->id, 'status' => 'open']) . '
                                        data-input={"_method":"post"} data-toggle="modal" data-target="#modal-confirm" class="dropdown-item">
                                                    Open
                                     </button>
                                     <button data-message="Are you sure, you want to processing this ticket?" data-action=' . route('admin.ticket.status', ['id' => $q->id, 'status' => 'processing']) . '
                                        data-input={"_method":"post"} data-toggle="modal" data-target="#modal-confirm" class="dropdown-item">
                                                    Processing
                                     </button>
                                </div>';
                }elseif($q->status=='open'){
                    return '<button type="button" class="btn light btn-sm btn-default dropdown-toggle bg-warning" data-toggle="dropdown" aria-expanded="false">
                                                Open
                               </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                     <button data-message="Are you sure, you want to processing this ticket?" data-action=' . route('admin.ticket.status', ['id' => $q->id, 'status' => 'processing']) . '
                                        data-input={"_method":"post"} data-toggle="modal" data-target="#modal-confirm" class="dropdown-item">
                                                    Processing
                                     </button>
                                </div>';
                } elseif($q->status=='processing'){
                    return '<button type="button" class="btn light btn-sm btn-default dropdown-toggle bg-success" data-toggle="dropdown" aria-expanded="false">
                                                Processing
                               </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                     <button data-message="Are you sure, you want to solved this ticket?" data-action=' . route('admin.ticket.status', ['id' => $q->id, 'status' => 'solved']) . '
                                        data-input={"_method":"post"} data-toggle="modal" data-target="#modal-confirm" class="dropdown-item">
                                                    Solved
                                     </button>
                                </div>';
                }else{
                    return $q->status;
                }

            })->addColumn('action', function ($q) {
                if ($q->status=='solved'){
                    return "<a class='btn btn-sm btn-info disabled' href='#'><i class='fas fa-reply'></i></a> &nbsp; &nbsp;";
                }else {
                    return "<a target='_blank' class='btn btn-sm btn-info' href='" . route('admin.ticket.reply', ['id' => $q->id]) . "'><i class='fas fa-reply'></i></a> &nbsp; &nbsp;";
                }
            })->rawColumns(['description','customer_id','status','action'])->toJson();
    }

    public function store(Request $request)
    {
        $admin = auth()->user();
        $ticket = Ticket::where('id', $request->id)->where('admin_id', $admin->id)->firstOrFail();
        $customer = Customer::where('id',$ticket->customer_id)->first();

        $data['admin']=$admin = auth()->user();
        $ticket_description = new TicketDescription();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $imageName = time() . '.' . $file->extension();
            $file->move(public_path('/uploads'), $imageName);
            $ticket_description->document = $imageName;
        }

        $ticket_description->ticket_id = $ticket->id;
        $ticket_description->description = $request->description;
        $ticket_description->sender =$admin->admin_id;
        $ticket_description->receiver = $ticket->customer_id;
        $ticket_description->sent_status = 'admin';
        $ticket_description->save();
        /*    $template = EmailTemplate::where('type','ticket')->first();
            if ($template) {
                $template = str_replace('{customer_name}', $customer->first_name, $template->body);
                $template = str_replace('{message}', $request->description, $template->body);
                SendMail::dispatch($customer->email, $template->subject, $template);
            }*/

        return redirect()->route('admin.ticket.reply', ['id' => $ticket->id]);
    }

    public function reply(Request $request)
    {
        $customer = auth('customer')->user();
       $data['admin'] = auth()->user();
        $data['ticket'] =$ticket= Ticket::where('id', $request->id)->firstOrFail();

        $data['conversations'] = TicketDescription::where('ticket_id', $ticket->id)->orderBy('created_at','asc')->get();

        return view('admin.ticket.details', $data);
    }

    public function status(Request $request){

        $ticket=Ticket::where('id',$request->id)->firstOrFail();
        $ticket->status=$request->status;
        $ticket->save();

        return redirect()->route('admin.ticket.index')->with('success', trans('admin.message.status_changes'));
    }

    public function documentDownload(Request $request)
    {
        $ticketDescription = TicketDescription::where('id',$request->id)->where('document',$request->file)->firstOrFail();
        $filepath = public_path('uploads/') . "$ticketDescription->document";

        return Response::download($filepath);
    }

}
