<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // Manage page — list + add form
    public function index()
    {
        $announcements = Announcement::orderByDesc('published_at')->get();
        return view('admin.announcements', compact('announcements'));
    }

    // Naya notice add
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['nullable', 'string'],
        ]);

        Announcement::create([
            'title'        => $validated['title'],
            'body'         => $validated['body'] ?? null,
            'is_active'    => $request->boolean('is_active'),
            'published_at' => now(),
        ]);

        return back()->with('success', 'Notice added.');
    }

    // Notice update
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['nullable', 'string'],
        ]);

        $announcement->update([
            'title'     => $validated['title'],
            'body'      => $validated['body'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Notice updated.');
    }

    // Notice delete
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Notice deleted.');
    }
}