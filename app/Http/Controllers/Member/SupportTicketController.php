<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\ListBuilders\Member\SupportTicketListBuilder;
use App\Models\Admin;
use App\Models\Member;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Auth;
use DB;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class SupportTicketController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(Request $request): RedirectResponse|Renderable|JsonResponse
    {
        return SupportTicketListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    public function create(): RedirectResponse|Renderable
    {
        return view('member.support.create', [
            'member' => $this->member,
        ]);
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse|Renderable
    {
        $this->validate($request, [
            'message' => 'required',
        ], [
            'message.required' => 'The message is required',
        ]);

        DB::transaction(function () use ($request) {
            $ticket = SupportTicket::create([
                'member_id' => Auth::user()->member->id,
                'title' => $request->message,
            ]);

            do {
                $ticketId = (string) mt_rand(11111111, 99999999);
            } while (SupportTicket::where('ticket_id', $ticketId)->exists());

            $ticket->ticket_id = $ticketId;
            $ticket->save();

            $supportTicketMessage = SupportTicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'messageable_id' => Auth::user()->member->id,
                'messageable_type' => Member::class,
                'body' => $request->message,
            ]);

            if ($image = $request->get('image')) {
                $supportTicketMessage->addMediaFromDisk($image)
                    ->toMediaCollection(SupportTicketMessage::MC_IMAGE);
            }
        });

        return redirect()->route('user.support.index')->with(['success' => 'Message Sent Successfully.']);
    }

    public function ticket(SupportTicket $id): RedirectResponse|Renderable
    {
        $supportTickets = SupportTicket::with('message.member.user', 'message.admin')
            ->where('member_id', $this->member->id)
            ->where('id', $id->id)
            ->first();

        SupportTicketMessage::where('support_ticket_id', $id->id)
            ->where('messageable_type', Admin::class)
            ->update(['is_read' => 1]);

        return view('member.support.ticket', [
            'member' => $this->member,
            'supportTickets' => $supportTickets,
        ]);
    }

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function ticketMessage(SupportTicket $id, Request $request): RedirectResponse|Renderable
    {
        if ($request->input('message') == null && ! $request->input('image')) {
            $this->validate($request, [
                'message' => 'required',
            ], [
                'message.required' => 'The message is required',
            ]);
        }

        DB::transaction(function () use ($id, $request) {
            $supportTicketMessage = SupportTicketMessage::create([
                'support_ticket_id' => $id->id,
                'messageable_id' => Auth::user()->member->id,
                'messageable_type' => Member::class,
                'body' => $request->message ?? '',
            ]);

            if ($image = $request->get('image')) {
                $supportTicketMessage->addMediaFromDisk($image)
                    ->toMediaCollection(SupportTicketMessage::MC_IMAGE);
            }
        });

        return redirect()->back()->with(['success' => 'Message Sent Successfully.']);
    }
}
