<?php

namespace App\Http\Controllers\Customer;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketDescription;
use Illuminate\Http\Request;
use Response;

class TicketController extends Controller
{
    public function index()
    {
        return view('customer.ticket.index');
    }

    public function show()
    {
        $customers = auth('customer')->user()->tickets()->select(['id', 'subject', 'status']);
        return datatables()->of($customers)
            ->addColumn('description', function ($q) {
                $ticketDesc=TicketDescription::where('ticket_id', $q->id)->first();
                $desc= substr($ticketDesc->description, 0,25);
                return $desc;
            })
            ->addColumn('action', function ($q) {
                return "<a target='_blank' class='btn btn-sm btn-info' data-toggle='tooltip' data-placement='top' title='Reply' href='" . route('customer.ticket.details', ['id'=>$q->id]) . "'><i class='fas fa-reply'></i></a> &nbsp; &nbsp;";
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'description' => 'required'
        ]);

        $user = auth('customer')->user();

        $ticket = new Ticket();
        $ticket->subject = $request->subject;
        $ticket->admin_id = $user->admin_id;
        $ticket->status = 'pending';
        $ticket->customer_id = $user->id;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $imageName = time() . '.' . $file->extension();
            $file->move(public_path('/uploads'), $imageName);
            $ticket->document = $imageName;
        }
        $ticket->save();

        $ticket_description = new TicketDescription();
        $ticket_description->ticket_id = $ticket->id;
        $ticket_description->description = $request->description;
        $ticket_description->sender = $user->id;
        $ticket_description->sent_status = 'sent';
        $ticket_description->save();

        /*$template = EmailTemplate::where('type','ticket')->first();
        if ($template) {
            $template = str_replace('{customer_name}', $user->first_name, $template->body);
            $template = str_replace('{message}', $request->description, $template->body);
            SendMail::dispatch($user->email, $template->subject, $template);
        }*/

        return redirect()->route('customer.ticket.index')->with('success', trans('customer.messages.ticket_submitted'));
    }

    public function details(Request $request)
    {
        $data['customer']=$customer = auth('customer')->user();
        $data['ticket'] = $ticket = Ticket::where('customer_id', $customer->id)->where('id', $request->id)->firstOrFail();

        $data['conversations'] = TicketDescription::where('ticket_id', $ticket->id)->get();

        return view('customer.ticket.details', $data);
    }


    public function reply(Request $request)
    {
        $customer = auth('customer')->user();
        $ticket = Ticket::where('id', $request->id)->where('customer_id', $customer->id)->firstOrFail();

        $ticket_description = new TicketDescription();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $imageName = time() . '.' . $file->extension();
            $file->move(public_path('/uploads'), $imageName);
            $ticket_description->document = $imageName;
        }

        $ticket_description->ticket_id = $ticket->id;
        $ticket_description->description = $request->description;
        $ticket_description->sender = $ticket->customer_id;
        $ticket_description->receiver = $ticket->admin_id;
        $ticket_description->sent_status = 'customer';
        $ticket_description->save();

    /*    $template = EmailTemplate::where('type','ticket')->first();
        if ($template) {
            $template = str_replace('{customer_name}', $customer->first_name, $template->body);
            $template = str_replace('{message}', $request->description, $template->body);
            SendMail::dispatch($customer->email, $template->subject, $template);
        }*/


        return redirect()->route('customer.ticket.details', ['id' => $ticket->id]);
    }

    public function documentDownload(Request $request)
    {
        $ticketDescription = TicketDescription::where('id',$request->id)->where('document',$request->file)->firstOrFail();
        $filepath = public_path('uploads/') . "$ticketDescription->document";

        return Response::download($filepath);
    }

}
