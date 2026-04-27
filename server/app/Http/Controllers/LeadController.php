<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of leads
     */
    public function index()
{
    $user = auth()->user();

    if ($user->designation === 'admin') {
        $leads = Lead::latest()->paginate(10);
    }

    elseif ($user->designation === 'manager') {
        $teamIds = $this->getTeamIds($user);
        $leads = Lead::whereIn('user_id', $teamIds)->paginate(10);
    }

    elseif ($user->designation === 'tl') {
        $teamIds = User::where('manager_id', $user->id)->pluck('id');
        $leads = Lead::whereIn('user_id', $teamIds)->paginate(10);
    }

    else {
        $leads = Lead::where('user_id', $user->id)->paginate(10);
    }

    // IMPORTANT: restrict assign dropdown
    $users = User::where('manager_id', $user->id)->get();

    return view('leads.index', compact('leads', 'users'));
}
    /**
     * Show form to create lead
     */
    public function create()
    {
        return view('leads.create');
    }

    /**
     * Store new lead
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable'],
        ]);

        Lead::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'source' => $request->source ?? 'manual',
            'status' => 'new',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully');
    }

    /**
     * Show single lead
     */
    public function show($id)
    {
        $lead = Lead::findOrFail($id);

        return view('leads.show', compact('lead'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $lead = Lead::findOrFail($id);

        return view('leads.edit', compact('lead'));
    }

    /**
     * Update lead
     */
public function update(Request $request, $id)
{
    // Fetch the lead once
    $lead = Lead::findOrFail($id);

    // 1. ASSIGN CASE (From the modal)
    if ($request->has('assigned_to')) {
        $lead->update([
            'assigned_to' => $request->assigned_to
        ]);

        return back()->with('success', 'Lead assigned successfully');
    }

    // 2. NORMAL UPDATE CASE (From the edit page)
    $request->validate([
        'name' => ['required', 'string'],
        'email' => ['nullable', 'email'],
        'phone' => ['nullable'],
        'status' => ['required'],
    ]);

    $lead->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'status' => $request->status,
    ]);

    return redirect()->route('leads.index')
        ->with('success', 'Lead updated successfully');
}
    /**
     * Delete lead
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);

        // Optional protection
        if ($lead->status === 'converted') {
            return back()->withErrors('Cannot delete converted lead');
        }

        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully');
    }

    /**
     * Convert lead to contact
     */
    public function convert($id)
    {
        $lead = Lead::findOrFail($id);

        if ($lead->status === 'converted') {
            return back()->withErrors('Already converted');
        }

        Contact::create([
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
        ]);

        $lead->update([
            'status' => 'converted'
        ]);

        return back()->with('success', 'Lead converted to contact');
    }

    /**
     * Get team hierarchy (recursive)
     */
    private function getTeamIds($user)
    {
        $ids = [$user->id];

        foreach ($user->subordinates as $sub) {
            $ids = array_merge($ids, $this->getTeamIds($sub));
        }

        return $ids;
    }
}
