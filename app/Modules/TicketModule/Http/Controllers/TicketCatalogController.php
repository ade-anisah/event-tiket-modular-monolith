<?php

namespace App\Modules\TicketModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\EventModule\Contracts\EventLookupContract;
use App\Modules\TicketModule\Models\TicketType;
use Illuminate\View\View;

class TicketCatalogController extends Controller
{
    public function index(int $eventId, EventLookupContract $events): View
    {
        $event = $events->findPublished($eventId)
            ?? abort(404, 'Event tidak ditemukan atau belum dibuka.');

        return view('ticket::catalog.index', [
            'event' => $event,
            'types' => TicketType::where('event_id', $eventId)->orderBy('harga')->get(),
        ]);
    }
}