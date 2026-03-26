<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('media')->orderBy('position')->orderBy('created_at', 'desc')->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
        ]);

        $data = $request->only('nama_kegiatan');
        $data['position'] = Event::max('position') + 1;

        $event = Event::create($data);

        return redirect()->route('admin.events.show', $event)->with('success', __('messages.flash_created', ['item' => 'Sub event']));
    }

    public function show(Event $event)
    {
        $event->load(['media', 'fotos', 'videos', 'heroes', 'galeriFotos', 'galeriVideos']);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'nama_kegiatan'    => 'nullable|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal_kegiatan' => 'nullable|date',
            'heroes'           => 'nullable|array',
            'heroes.*'         => 'nullable|file',
            'fotos'            => 'nullable|array',
            'fotos.*'          => 'nullable|file|image',
            'videos'           => 'nullable|array',
            'videos.*'         => 'nullable|file',
            'video_url'        => 'nullable|url|max:500',
        ]);

        // Update info — only include fields that are filled
        $data = [];
        if ($request->filled('nama_kegiatan')) {
            $data['nama_kegiatan'] = $request->nama_kegiatan;
        }
        if ($request->has('deskripsi')) {
            $data['deskripsi'] = $request->deskripsi;
        }
        if ($request->has('tanggal_kegiatan')) {
            $data['tanggal_kegiatan'] = $request->tanggal_kegiatan;
        }

        if ($request->filled('nama_kegiatan') && $request->nama_kegiatan !== $event->nama_kegiatan) {
            $slug = Str::slug($request->nama_kegiatan);
            $original = $slug;
            $count = 1;
            while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $original . '-' . $count++;
            }
            $data['slug'] = $slug;
        }

        $event->update($data);

        $uploaded = 0;

        // Upload hero/banner images
        if ($request->hasFile('heroes')) {
            foreach ($request->file('heroes') as $file) {
                if ($file->isValid()) {
                    $mime = $file->getMimeType();
                    $type = str_starts_with($mime, 'video/') ? 'video' : 'foto';
                    EventMedia::create([
                        'event_id'  => $event->id,
                        'type'      => $type,
                        'section'   => 'hero',
                        'file_path' => $file->store('events/media', 'public'),
                        'position'  => $event->media()->max('position') + 1 + $uploaded,
                    ]);
                    $uploaded++;
                }
            }
        }

        // Upload galeri fotos
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                if ($file->isValid()) {
                    EventMedia::create([
                        'event_id'  => $event->id,
                        'type'      => 'foto',
                        'section'   => 'galeri',
                        'file_path' => $file->store('events/media', 'public'),
                        'position'  => $event->media()->max('position') + 1 + $uploaded,
                    ]);
                    $uploaded++;
                }
            }
        }

        // Upload galeri videos
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                if ($file->isValid()) {
                    EventMedia::create([
                        'event_id'  => $event->id,
                        'type'      => 'video',
                        'section'   => 'galeri',
                        'file_path' => $file->store('events/media', 'public'),
                        'position'  => $event->media()->max('position') + 1 + $uploaded,
                    ]);
                    $uploaded++;
                }
            }
        }

        // Video URL
        if ($request->filled('video_url')) {
            EventMedia::create([
                'event_id'  => $event->id,
                'type'      => 'video',
                'section'   => 'galeri',
                'video_url' => $request->video_url,
                'position'  => $event->media()->max('position') + 1 + $uploaded,
            ]);
            $uploaded++;
        }

        $msg = 'Event berhasil diperbarui!';
        if ($uploaded > 0) {
            $msg .= " $uploaded media ditambahkan.";
        }

        return redirect()->route('admin.events.show', $event)->with('success', $msg);
    }

    public function destroy(Event $event)
    {
        foreach ($event->media as $media) {
            if ($media->file_path) {
                Storage::disk('public')->delete($media->file_path);
            }
        }

        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', __('messages.flash_deleted', ['item' => __('messages.admin_events')]));
    }

    public function addMedia(Request $request, Event $event)
    {
        $request->validate([
            'type'       => 'required|in:foto,video',
            'file'       => 'nullable|array',
            'file.*'     => 'nullable|file',
            'video_url'  => 'nullable|url|max:500',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $uploaded = 0;

        if ($request->type === 'foto' && $request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                if ($file->isValid()) {
                    EventMedia::create([
                        'event_id'   => $event->id,
                        'type'       => 'foto',
                        'file_path'  => $file->store('events/media', 'public'),
                        'keterangan' => $request->keterangan,
                        'position'   => $event->media()->max('position') + 1 + $uploaded,
                    ]);
                    $uploaded++;
                }
            }
        } elseif ($request->type === 'video') {
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    if ($file->isValid()) {
                        EventMedia::create([
                            'event_id'   => $event->id,
                            'type'       => 'video',
                            'file_path'  => $file->store('events/media', 'public'),
                            'keterangan' => $request->keterangan,
                            'position'   => $event->media()->max('position') + 1 + $uploaded,
                        ]);
                        $uploaded++;
                    }
                }
            } elseif ($request->video_url) {
                EventMedia::create([
                    'event_id'   => $event->id,
                    'type'       => 'video',
                    'video_url'  => $request->video_url,
                    'keterangan' => $request->keterangan,
                    'position'   => $event->media()->max('position') + 1,
                ]);
                $uploaded++;
            } else {
                return back()->withErrors(['file' => 'Upload file video atau isi URL video.']);
            }
        }

        $label = $uploaded > 1 ? "$uploaded media berhasil" : 'Media berhasil';
        return redirect()->route('admin.events.show', $event)->with('success', "$label ditambahkan!");
    }

    public function destroyMedia(Event $event, EventMedia $media)
    {
        if ($media->file_path) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return redirect()->route('admin.events.show', $event)->with('success', __('messages.flash_deleted', ['item' => 'Media']));
    }

    public function togglePublish(Event $event)
    {
        $event->update(['is_published' => !$event->is_published]);
        $status = $event->is_published ? 'dipublikasikan' : 'disembunyikan';
        return back()->with('success', "Event berhasil {$status}!");
    }
}
