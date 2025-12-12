<?php

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\SimulationResult;
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
            'indicator' => 'required|string',
            'old_score' => 'required|numeric',
            'change_percent' => 'required|numeric',
        ]);

        $newScore = $validated['old_score'] + ($validated['old_score'] * $validated['change_percent'] / 100);
        $newScore = min(100, max(0, round($newScore, 1))); // Clamp between 0-100

        $result = SimulationResult::create([
            'node_id' => $validated['node_id'],
            'user_id' => $request->user()->id,
            'indicator' => $validated['indicator'],
            'old_score' => $validated['old_score'],
            'new_score' => $newScore,
            'change_percent' => $validated['change_percent'],
        ]);

        return redirect()->back()->with([
            'success' => 'Simulasi berhasil dijalankan.',
            'result' => $result
        ]);
    }
}
