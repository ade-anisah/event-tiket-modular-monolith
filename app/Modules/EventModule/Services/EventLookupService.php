<?php

namespace App\Modules\EventModule\Services;

use App\Modules\EventModule\Contracts\EventLookupContract;
use App\Modules\EventModule\Contracts\EventSummary;
use App\Modules\EventModule\Exceptions\EventTidakDibukaException;
use App\Modules\EventModule\Models\Event;

class EventLookupService implements EventLookupContract
{
    public function find(int $eventId): ?EventSummary
    {
        $event = Event::find($eventId);

        return $event ? $this->ringkas($event) : null;
    }

    public function findPublished(int $eventId): ?EventSummary
    {
        $event = Event::published()->find($eventId);

        return $event ? $this->ringkas($event) : null;
    }

    public function ensureOpen(int $eventId): void
    {
        $event = Event::find($eventId);

        if (! $event || ! $event->isPublished()) {
            throw EventTidakDibukaException::untuk($event?->judul ?? "#{$eventId}");
        }
    }

    private function ringkas(Event $event): EventSummary
    {
        return new EventSummary(
            id: $event->id,
            judul: $event->judul,
            lokasi: $event->lokasi,
            status: $event->status,
            mulaiAt: $event->mulai_at,
        );
    }
}