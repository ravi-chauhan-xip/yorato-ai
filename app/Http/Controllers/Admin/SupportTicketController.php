<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ListBuilders\Admin\SupportTicketListBuilder;
use App\Models\Admin;
use App\Models\Member;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
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
    public function get(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return SupportTicketListBuilder::render();
    }

    public function getDetails($id): Renderable|RedirectResponse
    {
        SupportTicketMessage::where('support_ticket_id', $id)->where('messageable_type', Member::class)->update(['is_read' => 1]);
        $supportTickets = SupportTicketMessage::with('supportTicket')->where('support_ticket_id', $id)->with('member.user', 'admin', 'member.media')->get();
        $supportTicket = SupportTicket::find($id);

        return view('admin.support-ticket.chat', ['supportTicket' => $supportTicket, 'supportTickets' => $supportTickets, 'id' => $id]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): Renderable|RedirectResponse
    {
        if ($request->buttonName == 2) {
            SupportTicket::where('id', $request->id)->update(['status' => SupportTicket::STATUS_CLOSE]);

            return redirect()->route('admin.support-ticket.get')->with(['success' => 'Ticket closed successfully']);
        }

        try {
            if ($request->buttonName == 1) {
                return DB::transaction(function () use ($request) {
                    if ($request->input('message') == null && ! $request->input('image')) {
                        return redirect()->back()->with(['error' => 'The message is required']);
                    }

                    $supportTicketMessage = SupportTicketMessage::create([
                        'support_ticket_id' => $request->id,
                        'messageable_id' => $this->admin->id,
                        'messageable_type' => Admin::class,
                        'body' => $request->message ?? '',
                    ]);

                    if ($image = $request->get('image')) {
                        $supportTicketMessage->addMediaFromDisk($image)
                            ->toMediaCollection(SupportTicketMessage::MC_IMAGE);
                    }

                    return redirect()->back()->with(['success' => 'Ticket reply sent successfully']);
                });
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong. Please try again']);
            }
        } catch (Throwable $e) {
            dd($e);

            return redirect()->back()->with(['error' => 'Something went wrong. Please try again']);
        }
    }

    public function clearAll(): Renderable|RedirectResponse
    {
        SupportTicketMessage::query()->update(['is_read' => 1]);

        return redirect()->back()->with(['success' => 'Clear all notification successfully']);
    }
}
