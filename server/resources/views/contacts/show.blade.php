<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('contacts.index') }}" class="btn-ghost" style="padding:6px 10px;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div class="w-2 h-8 bg-emerald-400 rounded-full"></div>
                <h2 class="font-bold text-xl text-slate-800 tracking-tight">Contact Profile</h2>
            </div>
            <div class="flex items-center gap-2">
                @can('edit contacts')
                <button onclick="openEditModal()" class="btn-ghost">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </button>
                @endcan
                @can('delete contacts')
                <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST"
                      onsubmit="return confirm('Delete this contact? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ghost" style="color:#f43f5e;border-color:#fecdd3;">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

        .crm-body * { font-family: 'DM Sans', sans-serif; }
        .mono { font-family: 'DM Mono', monospace; }

        /* ── Buttons ── */
        .btn-primary {
            background: #1e293b; color: #fff;
            padding: 8px 18px; border-radius: 8px;
            font-size: 13px; font-weight: 600; transition: background 0.15s;
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none; border: none; cursor: pointer;
        }
        .btn-primary:hover { background: #0f172a; }
        .btn-ghost {
            color: #64748b; font-size: 13px; font-weight: 500;
            padding: 6px 12px; border-radius: 8px; transition: background 0.15s;
            display: inline-flex; align-items: center; gap: 5px;
            border: 1px solid #e2e8f0; cursor: pointer; background: transparent; text-decoration: none;
        }
        .btn-ghost:hover { background: #f1f5f9; color: #1e293b; }

        /* ── Cards ── */
        .info-card {
            background: #fff; border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.04);
            overflow: hidden;
            animation: fadeUp 0.3s ease both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .info-card:nth-child(2) { animation-delay: 0.05s; }
        .info-card:nth-child(3) { animation-delay: 0.10s; }
        .info-card:nth-child(4) { animation-delay: 0.15s; }

        .card-header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid #f8fafc;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title {
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.09em; color: #94a3b8;
        }
        .card-body { padding: 1.25rem 1.5rem; }

        /* ── Hero / Profile Header ── */
        .profile-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0f2942 100%);
            border-radius: 20px;
            padding: 2rem 2rem 0;
            position: relative;
            overflow: hidden;
            animation: fadeUp 0.25s ease both;
        }
        .profile-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 80% 20%, rgba(245,158,11,0.12) 0%, transparent 60%),
                        radial-gradient(ellipse at 10% 80%, rgba(16,185,129,0.08) 0%, transparent 50%);
            pointer-events: none;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1.5rem;
            align-items: flex-start;
            position: relative; z-index: 1;
        }
        .hero-avatar {
            width: 72px; height: 72px; border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; font-weight: 800;
            border: 2px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }
        .hero-name {
            font-size: 22px; font-weight: 800; color: #fff;
            letter-spacing: -0.02em; line-height: 1.2;
        }
        .hero-company {
            font-size: 13px; color: rgba(255,255,255,0.5);
            margin-top: 3px; font-weight: 500;
        }
        .hero-badges { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; }

        /* ── Stat strip ── */
        .stat-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-top: 1px solid rgba(255,255,255,0.06);
            margin-top: 1.5rem;
        }
        .stat-item {
            padding: 1rem;
            text-align: center;
            border-right: 1px solid rgba(255,255,255,0.06);
            position: relative; z-index: 1;
        }
        .stat-item:last-child { border-right: none; }
        .stat-value {
            font-size: 18px; font-weight: 800; color: #fff;
            font-family: 'DM Mono', monospace; line-height: 1;
        }
        .stat-label {
            font-size: 10px; font-weight: 600; color: rgba(255,255,255,0.35);
            text-transform: uppercase; letter-spacing: 0.08em; margin-top: 5px;
        }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 99px;
            font-size: 11px; font-weight: 600; letter-spacing: 0.03em;
        }
        .badge-active   { background: rgba(16,185,129,0.15); color: #34d399; }
        .badge-inactive { background: rgba(148,163,184,0.15); color: #94a3b8; }
        .badge-archived { background: rgba(244,63,94,0.15); color: #fb7185; }
        .badge-customer { background: rgba(99,102,241,0.15); color: #a5b4fc; }
        .badge-lead     { background: rgba(245,158,11,0.15); color: #fcd34d; }
        .badge-partner  { background: rgba(14,165,233,0.15); color: #7dd3fc; }
        .badge-vendor   { background: rgba(168,85,247,0.15); color: #d8b4fe; }
        .badge-priority-high   { background: rgba(244,63,94,0.12); color: #fb7185; }
        .badge-priority-medium { background: rgba(245,158,11,0.12); color: #fcd34d; }
        .badge-priority-low    { background: rgba(148,163,184,0.12); color: #94a3b8; }

        /* ── Info rows ── */
        .info-row {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 11px 0;
            border-bottom: 1px solid #f8fafc;
        }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .info-row:first-child { padding-top: 0; }
        .info-icon {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-top: 1px;
        }
        .info-label { font-size: 11px; font-weight: 600; color: #94a3b8; margin-bottom: 2px; }
        .info-value { font-size: 13.5px; font-weight: 600; color: #1e293b; }
        .info-value.muted { color: #94a3b8; font-weight: 400; font-style: italic; }
        .info-value.mono  { font-family: 'DM Mono', monospace; font-size: 12.5px; }

        /* ── Lead origin box ── */
        .origin-box {
            background: #fffbeb; border: 1px solid #fde68a;
            border-radius: 12px; padding: 14px 16px;
            display: flex; align-items: center; gap: 14px;
        }
        .origin-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: #fef3c7; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .origin-label { font-size: 11px; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: 0.07em; }
        .origin-name  { font-size: 13px; font-weight: 700; color: #78350f; margin-top: 1px; }
        .origin-date  { font-size: 11px; color: #b45309; margin-top: 2px; }
        .origin-link  {
            margin-left: auto; font-size: 11px; font-weight: 600; color: #92400e;
            text-decoration: none; padding: 5px 10px; border-radius: 6px;
            background: #fde68a; transition: background 0.15s; white-space: nowrap;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .origin-link:hover { background: #fcd34d; }

        /* ── Notes box ── */
        .notes-box {
            background: #f8fafc; border-radius: 10px;
            padding: 14px; font-size: 13px; color: #475569;
            line-height: 1.7; border: 1px solid #f1f5f9;
            white-space: pre-wrap;
        }

        /* ── Address box ── */
        .address-box {
            background: #f8fafc; border-radius: 10px;
            padding: 14px 16px; font-size: 13px; color: #475569;
            line-height: 1.7; border: 1px solid #f1f5f9;
        }

        /* ── Section label ── */
        .section-divider {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.1em; color: #cbd5e1;
            display: flex; align-items: center; gap: 8px; margin: 20px 0 14px;
        }
        .section-divider::before, .section-divider::after {
            content: ''; flex: 1; height: 1px; background: #f1f5f9;
        }

        /* ── Modal styles (matching leads UI) ── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.55); backdrop-filter: blur(5px);
            display: flex; align-items: center; justify-content: center;
            z-index: 50; padding: 1rem;
        }
        .modal-overlay.hidden { display: none !important; }
        .modal-box {
            background: #fff; border-radius: 20px;
            width: 100%; max-width: 560px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.18);
            animation: slideUp 0.22s cubic-bezier(0.34,1.56,0.64,1);
            overflow: hidden; max-height: 90vh; overflow-y: auto;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .modal-header {
            padding: 1.4rem 1.75rem 1.2rem; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; background: #fff; z-index: 10;
        }
        .modal-header-left { display: flex; align-items: center; gap: 12px; }
        .modal-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .modal-title { font-size: 16px; font-weight: 700; color: #1e293b; }
        .modal-subtitle { font-size: 12px; color: #94a3b8; margin-top: 1px; }
        .modal-close {
            width: 30px; height: 30px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            border: none; background: transparent; cursor: pointer; color: #94a3b8;
            transition: background 0.15s;
        }
        .modal-close:hover { background: #f1f5f9; color: #475569; }
        .modal-body { padding: 1.5rem 1.75rem; }
        .modal-footer {
            padding: 1rem 1.75rem 1.4rem;
            display: flex; justify-content: flex-end; gap: 8px;
            border-top: 1px solid #f1f5f9;
            position: sticky; bottom: 0; background: #fff;
        }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
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
            border-color: #10b981; background: #fff;
            box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
        }
        .form-input::placeholder { color: #cbd5e1; }
        .form-textarea { resize: none; min-height: 80px; }
        .form-section-label {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.1em; color: #cbd5e1;
            display: flex; align-items: center; gap: 8px; margin: 16px 0 10px;
        }
        .form-section-label::before, .form-section-label::after {
            content: ''; flex: 1; height: 1px; background: #f1f5f9;
        }
    </style>

    @php
        $avatarColors = ['#dbeafe','#fef3c7','#dcfce7','#fce7f3','#ede9fe','#cffafe','#fef9c3'];
        $avatarText   = ['#3b82f6','#f59e0b','#22c55e','#ec4899','#8b5cf6','#0891b2','#ca8a04'];
        $idx = $contact->id % 7;

        $typeBadge = [
            'lead'     => 'badge-lead',
            'customer' => 'badge-customer',
            'partner'  => 'badge-partner',
            'vendor'   => 'badge-vendor',
        ][$contact->type ?? 'lead'] ?? 'badge-lead';

        $statusBadge = [
            'active'   => 'badge-active',
            'inactive' => 'badge-inactive',
            'archived' => 'badge-archived',
        ][$contact->status ?? 'active'] ?? 'badge-active';

        $priorityBadge = [
            'high'   => 'badge-priority-high',
            'medium' => 'badge-priority-medium',
            'low'    => 'badge-priority-low',
        ][$contact->priority ?? 'medium'] ?? 'badge-priority-medium';
    @endphp

    <div class="py-8 crm-body">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Flash --}}
            @if(session('success'))
            <div class="flex items-center gap-3 px-5 py-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- ── HERO PROFILE CARD ── --}}
            <div class="profile-hero">
                <div class="hero-grid">
                    {{-- Avatar --}}
                    <div class="hero-avatar" style="background:{{ $avatarColors[$idx] }};color:{{ $avatarText[$idx] }};">
                        {{ strtoupper(substr($contact->name, 0, 2)) }}
                    </div>

                    {{-- Name + meta --}}
                    <div>
                        <div class="hero-name">{{ $contact->name }}</div>
                        <div class="hero-company">
                            {{ $contact->company ?? 'No company' }}
                            @if($contact->department)
                                <span style="color:rgba(255,255,255,0.25);margin:0 6px;">·</span>
                                {{ $contact->department }}
                            @endif
                        </div>
                        <div class="hero-badges">
                            <span class="badge {{ $statusBadge }}">{{ ucfirst($contact->status ?? 'active') }}</span>
                            <span class="badge {{ $typeBadge }}">{{ ucfirst($contact->type ?? 'lead') }}</span>
                            <span class="badge {{ $priorityBadge }}">{{ ucfirst($contact->priority ?? 'medium') }} Priority</span>
                        </div>
                    </div>

                    {{-- Owner pill --}}
                    <div style="text-align:right;">
                        @if($contact->user)
                        <div style="display:flex;align-items:center;gap:8px;justify-content:flex-end;">
                            <div style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:rgba(255,255,255,0.7);">
                                {{ strtoupper(substr($contact->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:11px;color:rgba(255,255,255,0.35);text-transform:uppercase;letter-spacing:0.07em;">Owner</div>
                                <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.75);">{{ $contact->user->name }}</div>
                            </div>
                        </div>
                        @endif
                        <div style="font-size:11px;color:rgba(255,255,255,0.25);margin-top:8px;">
                            Since {{ $contact->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>

                {{-- Stat strip --}}
                <div class="stat-strip">
                    <div class="stat-item">
                        <div class="stat-value">
                            {{ $contact->deal_value ? '₹' . number_format($contact->deal_value) : '—' }}
                        </div>
                        <div class="stat-label">Deal Value</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">
                            {{ $contact->source ? ucwords(str_replace('_',' ',$contact->source)) : '—' }}
                        </div>
                        <div class="stat-label">Source</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">
                            {{ $contact->wasConverted() ? 'Lead' : 'Direct' }}
                        </div>
                        <div class="stat-label">Origin</div>
                    </div>
                </div>
            </div>

            {{-- ── MAIN GRID ── --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

                {{-- Contact Information --}}
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-title">Contact Information</div>
                    </div>
                    <div class="card-body">

                        <div class="info-row">
                            <div class="info-icon" style="background:#eff6ff;">
                                <svg class="w-4 h-4" style="color:#3b82f6;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Email</div>
                                @if($contact->email)
                                <a href="mailto:{{ $contact->email }}" class="info-value mono" style="color:#3b82f6;text-decoration:none;">{{ $contact->email }}</a>
                                @else
                                <div class="info-value muted">Not provided</div>
                                @endif
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon" style="background:#f0fdf4;">
                                <svg class="w-4 h-4" style="color:#22c55e;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Phone</div>
                                @if($contact->phone)
                                <a href="tel:{{ $contact->phone }}" class="info-value mono" style="color:#1e293b;text-decoration:none;">{{ $contact->phone }}</a>
                                @else
                                <div class="info-value muted">Not provided</div>
                                @endif
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon" style="background:#fef3c7;">
                                <svg class="w-4 h-4" style="color:#f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Company</div>
                                <div class="info-value">{{ $contact->company ?? '—' }}</div>
                            </div>
                        </div>

                        @if($contact->assignee)
                        <div class="info-row">
                            <div class="info-icon" style="background:#f0f9ff;">
                                <svg class="w-4 h-4" style="color:#0ea5e9;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Assigned To</div>
                                <div class="info-value">{{ $contact->assignee->name }}</div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- Lead Details --}}
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-title">Lead Details</div>
                    </div>
                    <div class="card-body">

                        <div class="info-row">
                            <div class="info-icon" style="background:#f5f3ff;">
                                <svg class="w-4 h-4" style="color:#8b5cf6;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Type</div>
                                <span class="badge {{ $typeBadge }}">{{ ucfirst($contact->type ?? 'lead') }}</span>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon" style="background:#ecfdf5;">
                                <svg class="w-4 h-4" style="color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Status</div>
                                <span class="badge {{ $statusBadge }}">{{ ucfirst($contact->status ?? 'active') }}</span>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon" style="background:#fff7ed;">
                                <svg class="w-4 h-4" style="color:#f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Source</div>
                                <div class="info-value">{{ $contact->source ? ucwords(str_replace('_',' ',$contact->source)) : '—' }}</div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon" style="background:#fff1f2;">
                                <svg class="w-4 h-4" style="color:#f43f5e;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <div class="info-label">Priority</div>
                                <span class="badge {{ $priorityBadge }}">{{ ucfirst($contact->priority ?? 'medium') }}</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>{{-- end main grid --}}

            {{-- ── SECOND ROW ── --}}
            <div style="display:grid;grid-template-columns:{{ $contact->wasConverted() ? '1fr 1fr' : '1fr' }};gap:20px;">

                {{-- Lead Origin (only if converted from lead) --}}
                @if($contact->wasConverted() && $contact->lead)
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-title">Lead Origin</div>
                    </div>
                    <div class="card-body">
                        <div class="origin-box">
                            <div class="origin-icon">
                                <svg class="w-5 h-5" style="color:#f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="origin-label">Converted from Lead</div>
                                <div class="origin-name">{{ $contact->lead->name }}</div>
                                @if($contact->lead_converted_at)
                                <div class="origin-date">{{ $contact->lead_converted_at->format('d M Y, h:i A') }}</div>
                                @endif
                            </div>
                            <a href="{{ route('leads.show', $contact->lead->id) }}" class="origin-link">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                View Lead
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Notes --}}
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-title">Notes</div>
                    </div>
                    <div class="card-body">
                        @if($contact->notes)
                        <div class="notes-box">{{ $contact->notes }}</div>
                        @else
                        <p style="font-size:13px;color:#cbd5e1;font-style:italic;">No notes added yet.</p>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ── ADDRESS (if any) ── --}}
            @if($contact->address || $contact->city || $contact->state)
            <div class="info-card">
                <div class="card-header">
                    <div class="card-title">Address</div>
                </div>
                <div class="card-body">
                    <div class="address-box">
                        @if($contact->address)<div>{{ $contact->address }}</div>@endif
                        <div>
                            {{ collect([$contact->city, $contact->state, $contact->pincode])->filter()->implode(', ') }}
                        </div>
                        @if($contact->country)<div style="color:#94a3b8;">{{ $contact->country }}</div>@endif
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ════════════════════ EDIT MODAL ════════════════════ --}}
    <div id="editModal" class="modal-overlay hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-icon" style="background:#ecfdf5;">
                        <svg class="w-5 h-5" style="color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <div class="modal-title">Edit Contact</div>
                        <div class="modal-subtitle">{{ $contact->name }}</div>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal()">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('contacts.update', $contact->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">

                    <div class="form-section-label">Contact Info</div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Full Name <span style="color:#f43f5e;">*</span></label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $contact->name) }}" required />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Company</label>
                            <input type="text" name="company" class="form-input" value="{{ old('company', $contact->company) }}" />
                        </div>
                    </div>
                    <div class="form-grid-2" style="margin-top:14px;">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $contact->email) }}" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-input" value="{{ old('phone', $contact->phone) }}" />
                        </div>
                    </div>

                    <div class="form-section-label">Classification</div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                @foreach(['lead','customer','partner','vendor'] as $t)
                                <option value="{{ $t }}" {{ old('type',$contact->type)===$t ? 'selected':'' }}>{{ ucfirst($t) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status <span style="color:#f43f5e;">*</span></label>
                            <select name="status" class="form-select">
                                @foreach(['active','inactive','archived'] as $s)
                                <option value="{{ $s }}" {{ old('status',$contact->status)===$s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-grid-2" style="margin-top:14px;">
                        <div class="form-group">
                            <label class="form-label">Source</label>
                            <select name="source" class="form-select">
                                <option value="">— Select —</option>
                                @foreach(['website','referral','cold_call','social_media','email_campaign','other'] as $src)
                                <option value="{{ $src }}" {{ old('source',$contact->source)===$src ? 'selected':'' }}>{{ ucwords(str_replace('_',' ',$src)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select">
                                @foreach(['low','medium','high'] as $p)
                                <option value="{{ $p }}" {{ old('priority',$contact->priority)===$p ? 'selected':'' }}>{{ ucfirst($p) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-section-label">Financials</div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Deal Value (₹)</label>
                            <input type="number" name="deal_value" class="form-input" value="{{ old('deal_value', $contact->deal_value) }}" min="0" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Department</label>
                            <input type="text" name="department" class="form-input" value="{{ old('department', $contact->department) }}" placeholder="e.g. Credit, AR" />
                        </div>
                    </div>

                    <div class="form-section-label">Address</div>
                    <div class="form-group" style="margin-bottom:14px;">
                        <label class="form-label">Street Address</label>
                        <input type="text" name="address" class="form-input" value="{{ old('address', $contact->address) }}" placeholder="123, MG Road" />
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-input" value="{{ old('city', $contact->city) }}" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-input" value="{{ old('state', $contact->state) }}" />
                        </div>
                    </div>
                    <div class="form-grid-2" style="margin-top:14px;">
                        <div class="form-group">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="pincode" class="form-input" value="{{ old('pincode', $contact->pincode) }}" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-input" value="{{ old('country', $contact->country ?? 'India') }}" />
                        </div>
                    </div>

                    <div class="form-section-label">Notes</div>
                    <div class="form-group">
                        <textarea name="notes" class="form-textarea" placeholder="Notes about this contact…">{{ old('notes', $contact->notes) }}</textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal()" class="btn-ghost">Cancel</button>
                    <button type="submit" class="btn-primary" style="background:#10b981;">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal() {
            document.getElementById('editModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        // Auto-open edit modal if validation errors exist (after failed submit)
        @if($errors->any())
            openEditModal();
        @endif
    </script>

</x-app-layout>
