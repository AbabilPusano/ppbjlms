<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Story;
use Illuminate\Contracts\View\View;

class StudentStoryController extends Controller
{
    /**
     * Daftar semua cerita yang tersedia untuk siswa, dikelompokkan per section/level.
     */
    public function index(): View
    {
        $sections = Section::query()
            ->with(['stories' => fn ($q) => $q->orderBy('order')])
            ->whereHas('stories')
            ->orderByDesc('name')
            ->get();

        $totalStories   = Story::query()->count();
        $readableStories = $totalStories; // semua cerita tersedia untuk siswa

        return view('stories-ui', compact('sections', 'totalStories', 'readableStories'));
    }

    /**
     * Tampilkan reader cerita untuk siswa.
     */
    public function show(Story $story): View
    {
        $story->load('section');

        return view('story-reader-ui', compact('story'));
    }
}
