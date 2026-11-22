<?php

namespace App\Modules\TicketModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\EventModule\Contracts\EventLookupContract;
use App\Modules\TicketModule\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyTicketController extends Controller
{
    public function index(Request $request, EventLookupContract $events): View
    {
        $tickets = Ticket::with('ticketType')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $judulEvent = $tickets->pluck('ticketType.event_id')
            ->unique()
            ->mapWithKeys(fn ($id) => [$id => $events->find($id)?->judul ?? "Event #{$id}"]);

        return view('ticket::tickets.mine', [
            'tickets' => $tickets,
            'judulEvent' => $judulEvent,
        ]);
    }
}
