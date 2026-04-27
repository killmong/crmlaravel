<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-amber-400 rounded-full"></div>
                <h2 class="font-bold text-xl text-slate-800 tracking-tight">
                    Leads
                </h2>
            </div>
            @can('create leads')
            <button onclick="openNewLead()" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Lead
            </button>
            @endcan
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

        .crm-body * { font-family: 'DM Sans', sans-serif; }
        .mono { font-family: 'DM Mono', monospace; }

        .section-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.04);
            padding: 1.5rem;
        }
        .section-title {
            font-size: 13px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: #94a3b8; margin-bottom: 1.25rem;
        }
        .btn-primary {
            background: #1e293b; color: #fff;
            padding: 8px 18px; border-radius: 8px;
            font-size: 13px; font-weight: 600;
            transition: background 0.15s;
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none; border: none; cursor: pointer;
        }
        .btn-primary:hover { background: #0f172a; }
        .btn-ghost {
            color: #64748b; font-size: 13px; font-weight: 500;
            padding: 6px 12px; border-radius: 8px;
            transition: background 0.15s; display: inline-flex; align-items: center; gap: 5px;
            border: 1px solid #e2e8f0; cursor: pointer; background: transparent; text-decoration: none;
        }
        .btn-ghost:hover { background: #f1f5f9; color: #1e293b; }
        .badge {
            display: inline-flex; align-items: center;
            padding: 2px 10px; border-radius: 99px;
            font-size: 11px; font-weight: 600; letter-spacing: 0.03em;
        }
        .badge-new          { background: #eff6ff; color: #3b82f6; }
        .badge-contacted    { background: #fff7ed; color: #f59e0b; }
        .badge-qualified    { background: #f0fdf4; color: #22c55e; }
        .badge-converted    { background: #ecfdf5; color: #10b981; }
        .badge-lost         { background: #fff1f2; color: #f43f5e; }
        .badge-proposal     { background: #f5f3ff; color: #8b5cf6; }
        .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; flex-shrink: 0;
        }
        .lead-row { transition: background 0.15s; border-radius: 10px; }
        .lead-row:hover { background: #f8fafc; }
        .filter-input {
            border: 1px solid #e2e8f0; border-radius: 8px;
            padding: 7px 14px 7px 36px; font-size: 13px; color: #1e293b;
            background: #f8fafc; outline: none;
            transition: border-color 0.15s, background 0.15s; width: 220px;
        }
        .filter-input:focus { border-color: #f59e0b; background: #fff; }
        .filter-select {
            border: 1px solid #e2e8f0; border-radius: 8px;
            padding: 7px 12px; font-size: 13px; color: #64748b;
            background: #f8fafc; outline: none; cursor: pointer;
            transition: border-color 0.15s;
        }
        .filter-select:focus { border-color: #f59e0b; }
        .action-link {
            font-size: 12px; font-weight: 600;
            padding: 4px 10px; border-radius: 6px;
            transition: background 0.15s, color 0.15s;
            display: inline-flex; align-items: center; gap: 4px;
            text-decoration: none; border: none; cursor: pointer; background: transparent;
        }
        .action-edit    { color: #6366f1; }
        .action-edit:hover { background: #eef2ff; }
        .action-assign  { color: #0ea5e9; }
        .action-assign:hover { background: #f0f9ff; }
        .action-convert { color: #10b981; }
        .action-convert:hover { background: #ecfdf5; }
        .action-delete  { color: #f43f5e; }
        .action-delete:hover { background: #fff1f2; }
        .page-btn {
            width: 32px; height: 32px; border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 500; border: 1px solid #e2e8f0;
            background: #fff; color: #64748b; text-decoration: none; transition: all 0.15s;
        }
        .page-btn:hover { background: #f8fafc; color: #1e293b; border-color: #cbd5e1; }
        .page-btn.active { background: #1e293b; color: #fff; border-color: #1e293b; }
        .stat-pill {
            background: #f8fafc; border-radius: 99px;
            padding: 4px 14px; font-size: 12px; font-weight: 600;
            color: #64748b; border: 1px solid #f1f5f9;
            display: inline-flex; align-items: center; gap: 6px;
        }

        /* ── Shared Modal Styles ── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.55);
            backdrop-filter: blur(5px);
            display: flex; align-items: center; justify-content: center;
            z-index: 50; padding: 1rem;
        }
        .modal-overlay.hidden { display: none !important; }

        .modal-box {
            background: #fff;
            border-radius: 20px;
            width: 100%; max-width: 520px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.18);
            animation: slideUp 0.22s cubic-bezier(0.34,1.56,0.64,1);
            overflow: hidden;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Modal header stripe */
        .modal-header {
            padding: 1.4rem 1.75rem 1.2rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-header-left { display: flex; align-items: center; gap: 12px; }
        .modal-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .modal-title {
            font-size: 16px; font-weight: 700; color: #1e293b;
        }
        .modal-subtitle {
            font-size: 12px; color: #94a3b8; margin-top: 1px;
        }
        .modal-close {
            width: 30px; height: 30px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            border: none; background: transparent; cursor: pointer; color: #94a3b8;
            transition: background 0.15s, color 0.15s;
        }
        .modal-close:hover { background: #f1f5f9; color: #475569; }

        /* Modal body */
        .modal-body { padding: 1.5rem 1.75rem; }

        /* Form fields */
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-grid-1 { display: grid; grid-template-columns: 1fr; gap: 14px; }

        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-label {
            font-size: 11px; font-weight: 700; color: #64748b;
            text-transform: uppercase; letter-spacing: 0.07em;
        }
        .form-input, .form-select, .form-textarea {
            border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 9px 13px; font-size: 13.5px; color: #1e293b;
            background: #f8fafc; outline: none;
            transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
            font-family: 'DM Sans', sans-serif; width: 100%;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #f59e0b;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(245,158,11,0.1);
        }
        .form-input::placeholder { color: #cbd5e1; }
        .form-textarea { resize: none; min-height: 80px; }
        .form-select { cursor: pointer; }

        /* Section divider inside modal */
        .form-section-label {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.1em; color: #cbd5e1;
            display: flex; align-items: center; gap: 8px; margin: 16px 0 10px;
        }
        .form-section-label::before, .form-section-label::after {
            content: ''; flex: 1; height: 1px; background: #f1f5f9;
        }

        /* Modal footer */
        .modal-footer {
            padding: 1rem 1.75rem 1.4rem;
            display: flex; justify-content: flex-end; gap: 8px;
        }

        /* Validation hint */
        .form-hint { font-size: 11px; color: #94a3b8; margin-top: 2px; }
        .form-error { font-size: 11px; color: #f43f5e; margin-top: 2px; }
    </style>

    <div class="py-8 crm-body">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Summary Pills --}}
            <div class="flex flex-wrap gap-3 items-center">
                <div class="stat-pill">
                    <span class="w-2 h-2 rounded-full bg-slate-400 inline-block"></span>
                    All &nbsp;<strong class="text-slate-700">{{ $leads->count() }}</strong>
                </div>
                <div class="stat-pill">
                    <span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span>
                    New &nbsp;<strong class="text-slate-700">{{ $leads->where('status','new')->count() }}</strong>
                </div>
                <div class="stat-pill">
                    <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                    Follow Up &nbsp;<strong class="text-slate-700">{{ $leads->where('status','follow_up')->count() }}</strong>
                </div>
                <div class="stat-pill">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
                    Converted &nbsp;<strong class="text-slate-700">{{ $leads->where('status','converted')->count() }}</strong>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="section-card" style="padding: 0; overflow: hidden;">

                <div class="flex flex-wrap gap-3 items-center justify-between px-6 py-4 border-b border-slate-100">
                    <div class="section-title mb-0">All Leads</div>
                    <div class="flex flex-wrap gap-2 items-center">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                            <input type="text" placeholder="Search leads…" class="filter-input" oninput="filterTable(this.value)" />
                        </div>
                        <select class="filter-select" onchange="filterStatus(this.value)">
                            <option value="">All Status</option>
                            <option value="new">New</option>
                            <option value="contacted">Contacted</option>
                            <option value="qualified">Qualified</option>
                            <option value="proposal">Proposal</option>
                            <option value="converted">Converted</option>
                            <option value="lost">Lost</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="leadsTable">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="px-6 py-3 text-left text-xs font-700 text-slate-400 uppercase tracking-wider">Lead</th>
                                <th class="px-4 py-3 text-left text-xs text-slate-400 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs text-slate-400 uppercase tracking-wider">Owner</th>
                                <th class="px-4 py-3 text-left text-xs text-slate-400 uppercase tracking-wider">Value</th>
                                <th class="px-6 py-3 text-right text-xs text-slate-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @php
                                $avatarColors = ['#dbeafe','#fef3c7','#dcfce7','#fce7f3','#ede9fe','#fef9c3','#cffafe'];
                                $avatarText   = ['#3b82f6','#f59e0b','#22c55e','#ec4899','#8b5cf6','#ca8a04','#0891b2'];
                                $badgeMap = [
                                    'new'        => 'badge-new',
                                    'contacted'  => 'badge-contacted',
                                    'qualified'  => 'badge-qualified',
                                    'converted'  => 'badge-converted',
                                    'lost'       => 'badge-lost',
                                    'proposal'   => 'badge-proposal',
                                    'follow_up'  => 'badge-contacted',
                                ];
                            @endphp

                            @forelse($leads as $i => $lead)
                            <tr class="lead-row"
                                data-name="{{ strtolower($lead->name) }} {{ strtolower($lead->email) }}"
                                data-status="{{ $lead->status }}">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:{{ $avatarColors[$i % 7] }};color:{{ $avatarText[$i % 7] }};">
                                            {{ strtoupper(substr($lead->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800">{{ $lead->name }}</div>
                                            <div class="text-xs text-slate-400">{{ $lead->company ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-slate-500 mono text-xs">{{ $lead->email }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="badge {{ $badgeMap[$lead->status] ?? 'badge-new' }}">
                                        {{ ucwords(str_replace('_',' ', $lead->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($lead->user)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-500">
                                            {{ strtoupper(substr($lead->user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-slate-600 text-sm">{{ $lead->user->name }}</span>
                                    </div>
                                    @else
                                    <span class="text-slate-300 text-xs italic">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 mono text-sm font-semibold text-slate-700">
                                    {{ $lead->value ? '₹' . number_format($lead->value) : '—' }}
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center justify-end gap-1">

                                        @can('edit leads')
                                        <button onclick="openEditLead({{ $lead->id }}, {{ $lead->toJson() }})"
                                                class="action-link action-edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>
                                        @endcan

                                        @can('assign leads')
                                        <button onclick="openAssign({{ $lead->id }})" class="action-link action-assign">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            Assign
                                        </button>
                                        @endcan

                                        @if($lead->status !== 'converted')
                                        <form action="{{ route('leads.convert', $lead->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="action-link action-convert">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Convert
                                            </button>
                                        </form>
                                        @endif

                                        @can('delete leads')
                                        <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Delete this lead?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-link action-delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                        @endcan

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="text-slate-300 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <p class="text-slate-400 font-medium">No leads found</p>
                                    <p class="text-slate-300 text-xs mt-1">Start by adding your first lead</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($leads, 'links'))
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-400">
                        Showing <span class="font-semibold text-slate-600">{{ $leads->firstItem() }}</span>
                        to <span class="font-semibold text-slate-600">{{ $leads->lastItem() }}</span>
                        of <span class="font-semibold text-slate-600">{{ $leads->total() }}</span> leads
                    </p>
                    <div class="flex gap-1">
                        @if($leads->onFirstPage())
                            <span class="page-btn opacity-40"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></span>
                        @else
                            <a href="{{ $leads->previousPageUrl() }}" class="page-btn"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
                        @endif
                        @foreach($leads->getUrlRange(max(1,$leads->currentPage()-2), min($leads->lastPage(),$leads->currentPage()+2)) as $page => $url)
                            <a href="{{ $url }}" class="page-btn {{ $page == $leads->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach
                        @if($leads->hasMorePages())
                            <a href="{{ $leads->nextPageUrl() }}" class="page-btn"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                        @else
                            <span class="page-btn opacity-40"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════ --}}
    {{-- ── NEW LEAD MODAL ──                     --}}
    {{-- ════════════════════════════════════════ --}}
    <div id="newLeadModal" class="modal-overlay hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-icon" style="background:#fef3c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color:#f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <div class="modal-title">New Lead</div>
                        <div class="modal-subtitle">Add a new lead to your pipeline</div>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal('newLeadModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('leads.store') }}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="form-section-label">Contact Info</div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Full Name <span style="color:#f43f5e;">*</span></label>
                            <input type="text" name="name" class="form-input" placeholder="e.g. Arjun Mehta" required />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Company</label>
                            <input type="text" name="company" class="form-input" placeholder="e.g. Techwave Pvt Ltd" />
                        </div>
                    </div>
                    <div class="form-grid-2" style="margin-top:14px;">
                        <div class="form-group">
                            <label class="form-label">Email <span style="color:#f43f5e;">*</span></label>
                            <input type="email" name="email" class="form-input" placeholder="name@company.com" required />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-input" placeholder="+91 98765 43210" />
                        </div>
                    </div>

                    <div class="form-section-label">Lead Details</div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="new">New</option>
                                <option value="contacted">Contacted</option>
                                <option value="qualified">Qualified</option>
                                <option value="proposal">Proposal Sent</option>
                                <option value="follow_up">Follow Up</option>
                                <option value="lost">Lost</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deal Value (₹)</label>
                            <input type="number" name="value" class="form-input" placeholder="e.g. 150000" min="0" />
                        </div>
                    </div>
                    <div class="form-grid-2" style="margin-top:14px;">
                        <div class="form-group">
                            <label class="form-label">Source</label>
                            <select name="source" class="form-select">
                                <option value="">— Select source —</option>
                                <option value="website">Website</option>
                                <option value="referral">Referral</option>
                                <option value="cold_call">Cold Call</option>
                                <option value="social_media">Social Media</option>
                                <option value="email_campaign">Email Campaign</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-section-label">Notes</div>
                    <div class="form-group">
                        <textarea name="notes" class="form-textarea" placeholder="Any initial notes about this lead…"></textarea>
                    </div>

                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f5f9;">
                    <button type="button" onclick="closeModal('newLeadModal')" class="btn-ghost">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary" style="background:#f59e0b;color:#1e293b;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Create Lead
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════ --}}
    {{-- ── EDIT LEAD MODAL ──                    --}}
    {{-- ════════════════════════════════════════ --}}
    <div id="editLeadModal" class="modal-overlay hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-icon" style="background:#eef2ff;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color:#6366f1;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <div class="modal-title">Edit Lead</div>
                        <div class="modal-subtitle" id="editModalSubtitle">Update lead information</div>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal('editLeadModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="editLeadForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <div class="form-section-label">Contact Info</div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Full Name <span style="color:#f43f5e;">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-input" required />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Company</label>
                            <input type="text" name="company" id="edit_company" class="form-input" />
                        </div>
                    </div>
                    <div class="form-grid-2" style="margin-top:14px;">
                        <div class="form-group">
                            <label class="form-label">Email <span style="color:#f43f5e;">*</span></label>
                            <input type="email" name="email" id="edit_email" class="form-input" required />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" id="edit_phone" class="form-input" />
                        </div>
                    </div>

                    <div class="form-section-label">Lead Details</div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="new">New</option>
                                <option value="contacted">Contacted</option>
                                <option value="qualified">Qualified</option>
                                <option value="proposal">Proposal Sent</option>
                                <option value="follow_up">Follow Up</option>
                                <option value="converted">Converted</option>
                                <option value="lost">Lost</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deal Value (₹)</label>
                            <input type="number" name="value" id="edit_value" class="form-input" min="0" />
                        </div>
                    </div>
                    <div class="form-grid-2" style="margin-top:14px;">
                        <div class="form-group">
                            <label class="form-label">Source</label>
                            <select name="source" id="edit_source" class="form-select">
                                <option value="">— Select source —</option>
                                <option value="website">Website</option>
                                <option value="referral">Referral</option>
                                <option value="cold_call">Cold Call</option>
                                <option value="social_media">Social Media</option>
                                <option value="email_campaign">Email Campaign</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Priority</label>
                            <select name="priority" id="edit_priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-section-label">Notes</div>
                    <div class="form-group">
                        <textarea name="notes" id="edit_notes" class="form-textarea" placeholder="Notes about this lead…"></textarea>
                    </div>

                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f5f9;">
                    <button type="button" onclick="closeModal('editLeadModal')" class="btn-ghost">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── ASSIGN MODAL ── --}}
    <div id="assignModal" class="modal-overlay hidden">
        <div class="modal-box" style="max-width:400px;">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-icon" style="background:#f0f9ff;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color:#0ea5e9;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <div class="modal-title">Assign Lead</div>
                        <div class="modal-subtitle">Transfer ownership to an agent</div>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal('assignModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="assignForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Assign to agent</label>
                        <select name="assigned_to" class="form-select">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f5f9;">
                    <button type="button" onclick="closeModal('assignModal')" class="btn-ghost">Cancel</button>
                    <button type="submit" class="btn-primary" style="background:#f59e0b;color:#1e293b;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Confirm Assign
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ── Generic helpers ──
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
        // Backdrop click closes
        ['newLeadModal','editLeadModal','assignModal'].forEach(id => {
            document.getElementById(id).addEventListener('click', function(e) {
                if (e.target === this) closeModal(id);
            });
        });
        // Escape key
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') ['newLeadModal','editLeadModal','assignModal'].forEach(closeModal);
        });

        // ── New Lead ──
        function openNewLead() {
            document.getElementById('newLeadModal').classList.remove('hidden');
        }

        // ── Edit Lead ──
        function openEditLead(id, lead) {
            const form = document.getElementById('editLeadForm');
            form.action = `{{ url('/leads') }}/${id}`;

            document.getElementById('edit_name').value     = lead.name    || '';
            document.getElementById('edit_company').value  = lead.company || '';
            document.getElementById('edit_email').value    = lead.email   || '';
            document.getElementById('edit_phone').value    = lead.phone   || '';
            document.getElementById('edit_value').value    = lead.value   || '';
            document.getElementById('edit_notes').value    = lead.notes   || '';

            // Set selects
            setSelect('edit_status',   lead.status);
            setSelect('edit_source',   lead.source);
            setSelect('edit_priority', lead.priority);

            document.getElementById('editModalSubtitle').textContent = lead.name + ' · ' + (lead.company || 'No company');
            document.getElementById('editLeadModal').classList.remove('hidden');
        }

        function setSelect(id, value) {
            const el = document.getElementById(id);
            if (!el || !value) return;
            [...el.options].forEach(o => o.selected = (o.value === value));
        }

        // ── Assign ──
        function openAssign(id) {
            document.getElementById('assignForm').action = `{{ url('/leads') }}/${id}`;
            document.getElementById('assignModal').classList.remove('hidden');

            
        }

        // ── Table filters ──
        function filterTable(query) {
            document.querySelectorAll('#leadsTable tbody tr[data-name]').forEach(row => {
                row.style.display = row.dataset.name.includes(query.toLowerCase()) ? '' : 'none';
            });
        }
        function filterStatus(status) {
            document.querySelectorAll('#leadsTable tbody tr[data-status]').forEach(row => {
                row.style.display = (!status || row.dataset.status === status) ? '' : 'none';
            });
        }
    </script>

</x-app-layout>
