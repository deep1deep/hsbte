<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class NoticeController extends Controller
{
    public function index()
    {
        // saari active announcements (newest first) — scope se
        $announcements = Announcement::active()->get();

        return view('notices', compact('announcements'));
    }
}