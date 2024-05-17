<?php
namespace Modules\User\Controllers;

use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Modules\FrontendController;
use Modules\User\Models\Support\Ticket;
use Modules\User\Models\Support\TicketMessage;
use Modules\User\Requests\CreateSupportTicketMessageRequest;
use Modules\User\Requests\CreateSupportTicketRequest;

class SupportController extends FrontendController
{

    public function ticketsPage(): View
    {
        return view('User::frontend.support.index', [
            'tickets' => Ticket::query()
                ->where('user_id', auth()->user()->id)
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
        ]);
    }

    public function create(): View
    {
        return view('User::frontend.support.create');
    }

    public function store(CreateSupportTicketRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $ticket = new Ticket;
        $ticket->setAttribute('subject', $request->input('subject'));
        $ticket->user()->associate($user);
        $ticket->save();

        $ticketMessage = new TicketMessage;
        $ticketMessage->setAttribute('content', $request->input('content'));
        $ticketMessage->ticket()->associate($ticket);
        $ticketMessage->user()->associate($user);
        $ticketMessage->save();

        return redirect()->route('user.support.index')->with('success', __('Your issue was successfully sent'));
    }

    public function show(Ticket $ticket): View
    {
        return view('User::frontend.support.show', ['ticket' => $ticket]);
    }

    public function storeMessage(Ticket $ticket, ?TicketMessage $message = null, CreateSupportTicketMessageRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (null === $message) {
            $message = new TicketMessage;
            $message->ticket()->associate($ticket);
            $message->user()->associate($user);
        }

        $message->setAttribute('content', $request->input('content'));

        $message->save();

        $ticket->setAttribute('status', $ticket->user->id === $user->id ? Ticket::WAITING : Ticket::ANSWERED);
        $ticket->touch();

        return redirect()->route('user.support.show', ['ticket' => $ticket->id])->with('success', __('The message was sent'));
    }

    public function editMessage(Ticket $ticket, TicketMessage $message): View
    {
        return view('User::frontend.support.message.edit', ['ticket' => $ticket, 'message' => $message]);
    }

}
