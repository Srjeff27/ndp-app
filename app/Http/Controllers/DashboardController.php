<?php

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\AtlasEntry;
use App\Models\Lab;
use App\Models\SimulationResult;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'nodes_count' => Node::count(),
                'indicators_count' => AtlasEntry::count(),
                'discussions_count' => Lab::count(),
                'avg_score' => AtlasEntry::avg('score') ?? 0,
            ],
            'recent_simulations' => SimulationResult::with('node')->latest()->take(5)->get(),
            'recent_discussions' => Lab::latest()->take(5)->get(),
        ]);
    }
}
