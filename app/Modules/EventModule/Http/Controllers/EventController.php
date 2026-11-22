<?php

namespace App\Modules\EventModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\EventModule\Http\Requests\EventRequest;
use App\Modules\EventModule\Models\Category;
use App\Modules\EventModule\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $events = Event::query()
            ->with('categories')
            ->when(! $request->user()?->isAdmin(), fn ($query) => $query->published())
            ->orderBy('mulai_at')
            ->get();

        return view('event::events.index', ['events' => $events]);
    }

    public function show(Request $request, Event $event): View
    {
        abort_unless($event->isPublished() || $request->user()?->isAdmin(), 404);

        $event->load('categories');

        return view('event::events.show', ['event' => $event]);
    }

    public function create(): View
    {
        return view('event::events.form', [
            'event' => new Event(['status' => Event::STATUS_DRAFT]),
            'categories' => Category::orderBy('nama')->get(),
            'selected' => [],
        ]);
    }

    public function store(EventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $event = DB::transaction(function () use ($data, $request) {
            $event = Event::create([
                ...Arr::except($data, ['category_ids']),
                'slug' => Event::uniqueSlug($data['judul']),
                'created_by' => $request->user()->id,
            ]);
            $event->categories()->sync($data['category_ids']);

            return $event;
        });

        return redirect()->route('event.show', $event)->with('status', 'Event berhasil dibuat.');
    }

    public function edit(Event $event): View
    {
        return view('event::events.form', [
            'event' => $event,
            'categories' => Category::orderBy('nama')->get(),
            'selected' => $event->categories()->pluck('categories.id')->all(),
        ]);
    }

    public function update(EventRequest $request, Event $event): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $event) {
            $payload = Arr::except($data, ['category_ids']);

            if ($data['judul'] !== $event->judul) {
                $payload['slug'] = Event::uniqueSlug($data['judul'], $event->id);
            }

            $event->update($payload);
            $event->categories()->sync($data['category_ids']);
        });

        return redirect()->route('event.show', $event)->with('status', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        if ($event->status !== Event::STATUS_DRAFT || $event->peserta_terdaftar > 0) {
            return back()->with('error', 'Hanya event berstatus draft tanpa peserta yang dapat dihapus.');
        }

        $event->delete();

        return redirect()->route('event.index')->with('status', 'Event berhasil dihapus.');
    }
}
