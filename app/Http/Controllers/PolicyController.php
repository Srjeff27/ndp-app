<?php

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\Policy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PolicyController extends Controller
{
    /**
     * Display a listing of policies (Atlas page - Peta Akuntabilitas)
     */
    public function index()
    {
        $policies = Policy::with(['node', 'reviews', 'recommendation'])
            ->withCount('reviews')
            ->latest()
            ->get()
            ->map(function ($policy) {
                $avgScore = $policy->averageScore();

                return [
                    'id' => $policy->id,
                    'title' => $policy->title,
                    'description' => $policy->description,
                    'category' => $policy->category,
                    'budget' => $policy->budget,
                    'implementation_date' => $policy->implementation_date?->format('Y-m-d'),
                    'status' => $policy->status,
                    'node' => $policy->node,
                    'reviews_count' => $policy->reviews_count,
                    'average_score' => round($avgScore, 2),
                    'status_color' => $avgScore >= 91 ? 'green' : 'red',
                    'is_approved' => $avgScore >= 91,
                    'recommendation' => $policy->recommendation,
                ];
            });

        $nodes = Node::all();

        return Inertia::render('Atlas/Index', [
            'policies' => $policies,
            'nodes' => $nodes,
        ]);
    }

    /**
     * Display policies for forum (Labs page - Forum Masyarakat)
     */
    public function forumIndex()
    {
        $policies = Policy::with(['node', 'reviews.user', 'recommendation'])
            ->withCount('reviews')
            ->where('status', '!=', 'rejected')
            ->latest()
            ->get()
            ->map(function ($policy) {
                $avgScore = $policy->averageScore();
                $userReview = $policy->reviews->where('user_id', auth()->id())->first();

                return [
                    'id' => $policy->id,
                    'title' => $policy->title,
                    'description' => $policy->description,
                    'category' => $policy->category,
                    'budget' => $policy->budget,
                    'implementation_date' => $policy->implementation_date?->format('d M Y'),
                    'status' => $policy->status,
                    'node' => $policy->node,
                    'reviews_count' => $policy->reviews_count,
                    'average_score' => round($avgScore, 2),
                    'status_color' => $avgScore >= 91 ? 'green' : 'red',
                    'user_has_reviewed' => $userReview !== null,
                    'user_review' => $userReview,
                ];
            });

        return Inertia::render('Labs/Index', [
            'policies' => $policies,
        ]);
    }

    /**
     * Display a specific policy with all reviews (Labs show page)
     */
    public function show(Policy $policy)
    {
        $policy->load(['node', 'reviews.user', 'recommendation']);

        $avgScore = $policy->averageScore();
        $userReview = $policy->reviews->where('user_id', auth()->id())->first();

        return Inertia::render('Labs/Show', [
            'policy' => [
                'id' => $policy->id,
                'title' => $policy->title,
                'description' => $policy->description,
                'category' => $policy->category,
                'budget' => $policy->budget,
                'budget_breakdown' => $policy->budget_breakdown,
                'implementation_date' => $policy->implementation_date?->format('d M Y'),
                'status' => $policy->status,
                'node' => $policy->node,
                'reviews' => $policy->reviews->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'score' => $review->score,
                        'comment' => $review->comment,
                        'sentiment' => $review->sentiment,
                        'user' => $review->user->only(['id', 'name']),
                        'created_at' => $review->created_at->diffForHumans(),
                    ];
                }),
                'average_score' => round($avgScore, 2),
                'status_color' => $avgScore >= 91 ? 'green' : 'red',
                'recommendation' => $policy->recommendation,
                'user_has_reviewed' => $userReview !== null,
                'user_review' => $userReview,
            ],
        ]);
    }

    /**
     * Store a new policy
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'node_id' => 'required|exists:nodes,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'budget' => 'nullable|numeric|min:0',
            'implementation_date' => 'nullable|date',
            'budget_breakdown' => 'nullable|array',
        ]);

        $policy = Policy::create($validated);

        return redirect()->back()->with('success', 'Kebijakan berhasil ditambahkan');
    }
}
