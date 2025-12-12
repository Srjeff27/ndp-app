<?php

namespace App\Http\Controllers;

use App\Models\Node;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class NodeController extends Controller
{
    public function index()
    {
        $nodes = Node::with('admin')->get(); // For map data
        return Inertia::render('Nodes/Index', [
            'nodes' => $nodes
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('manage nodes');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $request->user()->nodes()->create($validated);

        return redirect()->back()->with('success', 'Node created successfully.');
    }

    public function update(Request $request, Node $node)
    {
        // Policy check: node_admin can only update own node, global_admin all
        $this->authorize('update', $node); // Need to create Policy

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $node->update($validated);

        return redirect()->back()->with('success', 'Node updated successfully.');
    }

    public function destroy(Node $node)
    {
        $this->authorize('delete', $node);
        $node->delete();
        return redirect()->back()->with('success', 'Node deleted successfully.');
    }
}
