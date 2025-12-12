<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\LabPost;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class LabController extends Controller
{
    public function index()
    {
        return Inertia::render('Labs/Index', [
            'labs' => Lab::with(['creator', 'node'])->latest()->get()
        ]);
    }

    public function show(Lab $lab)
    {
        return Inertia::render('Labs/Show', [
            'lab' => $lab->load(['creator', 'posts.author']),
            'canModerate' => auth()->user()->can('manage labs')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'node_id' => 'nullable|exists:nodes,id',
        ]);

        $request->user()->labs()->create($validated);

        return redirect()->route('labs.index')->with('success', 'Discussion topic created.');
    }

    public function storePost(Request $request, Lab $lab)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $lab->posts()->create([
            'content' => $validated['content'],
            'user_id' => $request->user()->id
        ]);

        return redirect()->back()->with('success', 'Post added.');
    }

    public function destroy(Lab $lab)
    {
        // Allow owner to delete their own discussion OR moderator to delete any
        if ($lab->user_id !== auth()->id() && !auth()->user()->can('manage labs')) {
            abort(403, 'Unauthorized action.');
        }

        $lab->delete();

        return redirect()->route('labs.index')->with('success', 'Discussion deleted successfully.');
    }

    public function destroyPost(Lab $lab, LabPost $post)
    {
        // Allow owner to delete their own comment OR moderator to delete any
        if ($post->user_id !== auth()->id() && !auth()->user()->can('manage labs')) {
            abort(403, 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
