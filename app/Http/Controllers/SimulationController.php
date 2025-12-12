<?php

namespace App\Http\Controllers;

use App\Models\Node;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SimulationController extends Controller
{
    public function index()
    {
        return Inertia::render('Simulation/Index', [
            'nodes' => Node::with('atlasEntries')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'node_id' => 'required|exists:nodes,id',
            'indicator' => 'required|string', // Should match existing indicator for the node? Or arbitrary?
            // "pilih node + pilih indikator". Usually means selecting existing one.
            'old_score' => 'required|numeric',
            'change_percent' => 'required|numeric',
        ]);

        $newScore = $validated['old_score'] + ($validated['old_score'] * $validated['change_percent'] / 100);

        $result = $request->user()->simulations()->create([
            'node_id' => $validated['node_id'],
            'indicator' => $validated['indicator'],
            'old_score' => $validated['old_score'],
            'new_score' => $newScore,
            'change_percent' => $validated['change_percent'],
        ]);

        return redirect()->back()->with([
            'success' => 'Simulation run successfully.',
            'result' => $result
        ]);
    }
}
