<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
  public function index()
    {
        $user = auth()->user();

        // 1. Fetch leads based on Spatie roles
        // hasRole accepts an array and returns true if the user has ANY of those roles
        if ($user->hasRole(['master-admin', 'admin'])) {
            $leads = Lead::with(['user', 'assignee'])
                         ->latest()
                         ->paginate(10);
        } else {
            // For regular users/managers
            $leads = Lead::with(['user', 'assignee'])
                         ->whereIn('user_id', $this->getTeamIds($user))
                         ->latest()
                         ->paginate(10);
        }

        // 2. Fetch assignable users for the dropdown
        if ($user->hasRole(['master-admin', 'admin'])) {
            // Admins can see everyone
            $users = User::all();

            // PRO TIP: If you only want admins to assign leads to regular users
            // (and not assign leads to other admins), change the line above to:
            // $users = User::role('user')->get();
        } else {
            // Regular users only see their subordinates
            $users = User::where('manager_id', $user->id)->get();
        }

        return view('leads.index', compact('leads', 'users'));
    }
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('leads.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['nullable', 'email', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'company'  => ['nullable', 'string', 'max:255'],
            'status'   => ['nullable', 'string'],
            'source'   => ['nullable', 'string'],
            'priority' => ['nullable', 'in:low,medium,high'],
            'value'    => ['nullable', 'integer', 'min:0'],
            'notes'    => ['nullable', 'string'],
            'department' => ['nullable', 'string'],

        ]);

        Lead::create([
            ...$validated,
            'status'  => $validated['status']   ?? 'new',
            'priority'=> $validated['priority']  ?? 'medium',
            'source'  => $validated['source']    ?? 'manual',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('leads.index')
                         ->with('success', 'Lead created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $lead = Lead::with(['user', 'assignee', 'contact'])->findOrFail($id);

        return view('leads.show', compact('lead'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $lead = Lead::findOrFail($id);

        return view('leads.edit', compact('lead'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE  —  handles both assign (modal) and full edit (form)
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        // ── ASSIGN case: only assigned_to is sent from the assign modal ──
        if ($request->has('assigned_to') && count($request->all()) <= 3) {
            // <= 3 accounts for _token, _method, assigned_to
            $request->validate([
                'assigned_to' => ['required', 'exists:users,id'],
            ]);

            $lead->update(['assigned_to' => $request->assigned_to]);

            return back()->with('success', 'Lead assigned successfully.');
        }

        // ── FULL UPDATE case ──
       // ── FULL UPDATE case ──
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['nullable', 'email', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'company'    => ['nullable', 'string', 'max:255'],
            'status'     => ['required', 'string'],
            'source'     => ['nullable', 'string'],
            'priority'   => ['nullable', 'in:low,medium,high'],
            'value'      => ['nullable', 'integer', 'min:0'],
            'notes'      => ['nullable', 'string'],
            'department' => ['nullable', 'string'],
            'assigned_to'=> ['nullable', 'exists:users,id'],
        ]);


        $lead->update($validated);

        return redirect()->route('leads.index')
                         ->with('success', 'Lead updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);

        if ($lead->status === 'converted') {
            return back()->withErrors(['error' => 'Cannot delete a converted lead.']);
        }

        $lead->delete();

        return redirect()->route('leads.index')
                         ->with('success', 'Lead deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | CONVERT  —  delegates all logic to Lead::convertToContact()
    |--------------------------------------------------------------------------
    */
    public function convert($id)
    {
        $lead = Lead::findOrFail($id);

        if ($lead->isConverted()) {
            return back()->withErrors(['error' => 'This lead has already been converted.']);
        }

        $contact = $lead->convertToContact();

        return redirect()->route('contacts.show', $contact->id)
                         ->with('success', 'Lead converted to contact successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Private Helpers
    |--------------------------------------------------------------------------
    */
    private function getTeamIds(object $user): array
    {
        $ids = [$user->id];

        foreach ($user->subordinates as $sub) {
            $ids = array_merge($ids, $this->getTeamIds($sub));
        }

        return $ids;
    }
}
