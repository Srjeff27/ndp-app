<?php

namespace App\Http\Controllers;

use App\Models\AtlasEntry;
use App\Models\Node;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AtlasController extends Controller
{
    public function index(Request $request)
    {
        // API endpoint mainly or Inertia page
        // Request says "API endpoint untuk mengambil semua indikator".
        // Also "CRUD indikator akuntabilitas per-node. Tampilkan indikator di peta (popup)"

        if ($request->wantsJson() && !$request->header('X-Inertia')) {
            return response()->json(AtlasEntry::with('node')->get());
        }

        return Inertia::render('Atlas/Index', [
            'entries' => AtlasEntry::with('node')->get()
        ]);
    }

    public function store(Request $request)
    {
        // Node admin can only add to their node.
        $validated = $request->validate([
            'node_id' => 'required|exists:nodes,id',
            'indicator' => 'required|string',
            'score' => 'required|numeric',
            'source' => 'nullable|string',
        ]);

        $node = Node::findOrFail($validated['node_id']);
        $this->authorize('update', $node); // Check if user can manage this node

        $node->atlasEntries()->create($validated);

        return redirect()->back()->with('success', 'Indicator added successfully.');
    }

    public function destroy(AtlasEntry $entry)
    {
        $this->authorize('update', $entry->node);
        $entry->delete();
        return redirect()->back()->with('success', 'Indicator deleted successfully.');
    }
}
