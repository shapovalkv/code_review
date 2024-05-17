<?php

namespace Modules\User\Admin;

use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\AdminController;
use Modules\User\Models\RolePermission;
use Modules\User\Models\Support\Ticket;
use Modules\User\Models\Support\TicketMessage;
use Modules\User\Requests\CreateMailingRequest;
use Modules\User\Requests\UpdateSupportTicketRequest;

class SupportController extends AdminController
{
    public function index(): View
    {
        $this->checkPermission('support_manage');

        return view('User::admin.support.index', [
            'tickets' => Ticket::query()
                ->with(Ticket::RELATION_MESSAGES)
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
        ]);
    }

    public function show(Ticket $ticket): View
    {
        return view('User::admin.support.show', [
            'ticket' => $ticket
        ]);
    }

    public function storeMessage(Ticket $ticket, Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $ticketMessage = new TicketMessage;
        $ticketMessage->setAttribute('content', $request->input('content'));
        $ticketMessage->ticket()->associate($ticket);
        $ticketMessage->user()->associate($user);
        $ticketMessage->save();

        $ticket->setAttribute('status', Ticket::ANSWERED);
        $ticket->touch();

        return redirect()->back()->with('success', __('The message was sent'));
    }

    public function updateStatus(Ticket $ticket, UpdateSupportTicketRequest $request): RedirectResponse
    {

        /** @var User $user */
        $user = auth()->user();

        $ticketMessage = new TicketMessage;
        $ticketMessage->setAttribute('content', 'The status was changed to: ' . strtoupper($request->input('status')));
        $ticketMessage->ticket()->associate($ticket);
        $ticketMessage->user()->associate($user);
        $ticketMessage->save();

        $ticket->setAttribute('status', $request->input('status'));
        $ticket->touch();

        return redirect()->route('user.admin.support.index')->with('success', __('The ticket was closed'));
    }

    public function delete(Ticket $ticket): RedirectResponse
    {
        $success = $ticket->delete();

        return redirect()->back()->with($success ? 'success' : 'error', $success ? 'Ticket successfully deleted' : 'Failed to delete ticket');
    }
}
