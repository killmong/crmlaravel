<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user = auth()->user();

        // 1. Fetch contacts based on Spatie roles
        if ($user->hasRole(['master-admin', 'admin'])) {
            // Admins see everything
            $contacts = Contact::with(['user', 'assignee', 'lead'])
                               ->latest()
                               ->paginate(10);
        } else {
            // Regular users / Managers see their own + team's contacts
            $contacts = Contact::with(['user', 'assignee', 'lead'])
                               ->whereIn('user_id', $this->getTeamIds($user))
                               ->latest()
                               ->paginate(10);
        }

        // 2. Fetch assignable users for the assign modal dropdown
        if ($user->hasRole(['master-admin', 'admin'])) {
            // Admins can assign to anyone
            $users = User::all();
        } else {
            // Managers/Users can only assign to their direct subordinates
            // If they have no subordinates, this safely returns an empty collection []
            $users = User::where('manager_id', $user->id)->get();
        }

        return view('contacts.index', compact('contacts', 'users'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('contacts.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['nullable', 'email', 'max:255', 'unique:contacts,email'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'company'    => ['nullable', 'string', 'max:255'],
            'type'       => ['nullable', 'in:lead,customer,partner,vendor'],
            'status'     => ['nullable', 'in:active,inactive,archived'],
            'source'     => ['nullable', 'string'],
            'priority'   => ['nullable', 'in:low,medium,high'],
            'department' => ['nullable', 'string'],
            'deal_value' => ['nullable', 'integer', 'min:0'],
            'notes'      => ['nullable', 'string'],
            'address'    => ['nullable', 'string'],
            'city'       => ['nullable', 'string', 'max:100'],
            'state'      => ['nullable', 'string', 'max:100'],
            'country'    => ['nullable', 'string', 'max:100'],
            'pincode'    => ['nullable', 'string', 'max:20'],
        ]);

        Contact::create([
            ...$validated,
            'type'     => $validated['type']     ?? 'customer',
            'status'   => $validated['status']   ?? 'active',
            'priority' => $validated['priority'] ?? 'medium',
            'currency' => 'INR',
            'user_id'  => auth()->id(),
        ]);

        return redirect()->route('contacts.index')
                         ->with('success', 'Contact created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $contact = Contact::with(['user', 'assignee', 'lead'])->findOrFail($id);

        return view('contacts.show', compact('contact'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);

        return view('contacts.edit', compact('contact'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE  —  handles both assign (modal) and full edit (form)
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        // ── ASSIGN case ──
        if ($request->has('assigned_to') && count($request->all()) <= 3) {
            $request->validate([
                'assigned_to' => ['required', 'exists:users,id'],
            ]);

            $contact->update(['assigned_to' => $request->assigned_to]);

            return back()->with('success', 'Contact assigned successfully.');
        }

        // ── FULL UPDATE case ──
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['nullable', 'email', 'max:255', 'unique:contacts,email,' . $contact->id],
            'phone'      => ['nullable', 'string', 'max:30'],
            'company'    => ['nullable', 'string', 'max:255'],
            'type'       => ['nullable', 'in:lead,customer,partner,vendor'],
            'status'     => ['required', 'in:active,inactive,archived'],
            'source'     => ['nullable', 'string'],
            'priority'   => ['nullable', 'in:low,medium,high'],
            'department' => ['nullable', 'string'],
            'deal_value' => ['nullable', 'integer', 'min:0'],
            'notes'      => ['nullable', 'string'],
            'address'    => ['nullable', 'string'],
            'city'       => ['nullable', 'string', 'max:100'],
            'state'      => ['nullable', 'string', 'max:100'],
            'country'    => ['nullable', 'string', 'max:100'],
            'pincode'    => ['nullable', 'string', 'max:20'],
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index')
                         ->with('success', 'Contact updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY  —  soft delete via SoftDeletes trait on the model
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('contacts.index')
                         ->with('success', 'Contact deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | RESTORE  —  undo soft delete
    |--------------------------------------------------------------------------
    */
    public function restore($id)
    {
        $contact = Contact::withTrashed()->findOrFail($id);
        $contact->restore();

        return back()->with('success', 'Contact restored successfully.');
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
