<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-amber-400 rounded-full"></div>
                <h2 class="font-bold text-xl text-slate-800 tracking-tight">
                    Agent Dashboard
                </h2>
            </div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <span class="inline-block w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                Online &mdash; {{ now()->format('D, d M Y') }}
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

        .crm-body * { font-family: 'DM Sans', sans-serif; }
        .mono { font-family: 'DM Mono', monospace; }

        .stat-card {
            position: relative;
            overflow: hidden;
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 120px; height: 120px;
            border-radius: 50%;
            opacity: 0.06;
        }
        .card-amber::after  { background: #f59e0b; }
        .card-sky::after    { background: #0ea5e9; }
        .card-rose::after   { background: #f43f5e; }
        .card-emerald::after{ background: #10b981; }

        .lead-row {
            transition: background 0.15s;
            border-radius: 10px;
        }
        .lead-row:hover { background: #f8fafc; }

        .badge {
            display: inline-flex; align-items: center;
            padding: 2px 10px; border-radius: 99px;
            font-size: 11px; font-weight: 600; letter-spacing: 0.03em;
        }
        .badge-new       { background: #eff6ff; color: #3b82f6; }
        .badge-follow    { background: #fff7ed; color: #f59e0b; }
        .badge-qualified { background: #f0fdf4; color: #22c55e; }
        .badge-lost      { background: #fff1f2; color: #f43f5e; }

        .priority-dot {
            width: 8px; height: 8px; border-radius: 50%;
            display: inline-block; margin-right: 6px;
        }
        .priority-high   { background: #f43f5e; }
        .priority-medium { background: #f59e0b; }
        .priority-low    { background: #94a3b8; }

        .task-item {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 12px 0; border-bottom: 1px solid #f1f5f9;
        }
        .task-item:last-child { border-bottom: none; }

        .task-checkbox {
            width: 18px; height: 18px; border-radius: 5px;
            border: 2px solid #cbd5e1; flex-shrink: 0; margin-top: 2px;
            cursor: pointer; transition: border-color 0.15s;
        }
        .task-checkbox:hover { border-color: #f59e0b; }

        .pipeline-bar {
            height: 8px; border-radius: 99px; background: #f1f5f9;
            overflow: hidden;
        }
        .pipeline-fill {
            height: 100%; border-radius: 99px;
            transition: width 0.8s cubic-bezier(0.4,0,0.2,1);
        }

        .activity-dot {
            width: 10px; height: 10px; border-radius: 50%;
            flex-shrink: 0; margin-top: 4px;
        }

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
        }
        .btn-primary:hover { background: #0f172a; }

        .btn-ghost {
            color: #64748b; font-size: 13px; font-weight: 500;
            padding: 8px 14px; border-radius: 8px;
            transition: background 0.15s; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-ghost:hover { background: #f1f5f9; color: #1e293b; }

        .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; flex-shrink: 0;
        }

        .greeting-card {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 16px; padding: 1.75rem 2rem;
            position: relative; overflow: hidden;
        }
        .greeting-card::before {
            content: '';
            position: absolute; top: -60px; right: -40px;
            width: 200px; height: 200px; border-radius: 50%;
            background: rgba(251,191,36,0.12);
        }
        .greeting-card::after {
            content: '';
            position: absolute; bottom: -80px; right: 60px;
            width: 160px; height: 160px; border-radius: 50%;
            background: rgba(251,191,36,0.06);
        }

        .quota-ring {
            width: 80px; height: 80px; position: relative;
        }
    </style>

    <div class="py-8 crm-body">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── Greeting + Today's Snapshot ── --}}
            <div class="greeting-card">
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-amber-400 text-sm font-semibold mb-1">
                            {{ now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening') }},
                        </p>
                        <h1 class="text-white text-2xl font-bold tracking-tight">
                            {{ auth()->user()->name }} 👋
                        </h1>
                        <p class="text-slate-400 text-sm mt-1">
                            You have <span class="text-white font-semibold">{{ $open_leads ?? 12 }} open leads</span>
                            and <span class="text-amber-400 font-semibold">{{ $tasks_due_today ?? 5 }} tasks due today.</span>
                        </p>
                    </div>
                    <div class="flex gap-3">
                    </div>
                </div>
            </div>

            {{-- ── Stat Cards ── --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <div class="stat-card card-amber">
                    <div class="section-title mb-3">My Leads</div>
                    <div class="text-3xl font-bold text-slate-800 mono">{{ $my_leads ?? 24 }}</div>
                    <div class="text-xs text-slate-400 mt-1">
                        <span class="text-amber-500 font-semibold">↑ 3</span> since yesterday
                    </div>
                    <div class="absolute top-5 right-5 text-amber-300 opacity-40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>

                <div class="stat-card card-sky">
                    <div class="section-title mb-3">Calls Today</div>
                    <div class="text-3xl font-bold text-slate-800 mono">{{ $calls_today ?? 8 }}</div>
                    <div class="text-xs text-slate-400 mt-1">
                        Target: <span class="font-semibold text-slate-600">15</span>
                    </div>
                    <div class="absolute top-5 right-5 text-sky-300 opacity-40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                </div>

                <div class="stat-card card-emerald">
                    <div class="section-title mb-3">Deals Won</div>
                    <div class="text-3xl font-bold text-slate-800 mono">{{ $deals_won ?? 3 }}</div>
                    <div class="text-xs text-slate-400 mt-1">
                        ₹<span class="font-semibold text-emerald-500">{{ number_format($revenue_won ?? 245000) }}</span> revenue
                    </div>
                    <div class="absolute top-5 right-5 text-emerald-300 opacity-40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                </div>

                <div class="stat-card card-rose">
                    <div class="section-title mb-3">Monthly Quota</div>
                    <div class="text-3xl font-bold text-slate-800 mono">{{ $quota_percent ?? 62 }}%</div>
                    <div class="pipeline-bar mt-2">
                        <div class="pipeline-fill" style="width: {{ $quota_percent ?? 62 }}%; background: #f43f5e;"></div>
                    </div>
                    <div class="text-xs text-slate-400 mt-1">{{ 100 - ($quota_percent ?? 62) }}% remaining</div>
                    <div class="absolute top-5 right-5 text-rose-300 opacity-40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>

            </div>

            {{-- ── Main Grid: Leads + Tasks ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- My Leads Table --}}
                <div class="lg:col-span-2 section-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="section-title mb-0">Recent Leads</div>
                        <a href="{{ route('leads.index') }}" class="btn-ghost text-xs">
                            View all
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="space-y-1">
                        @php
                            $leads = $recent_leads ?? [
                                ['name' => 'Arjun Mehta',   'company' => 'Techwave Pvt Ltd',    'value' => '₹1,20,000', 'status' => 'qualified', 'priority' => 'high'],
                                ['name' => 'Priya Sharma',  'company' => 'Nova Retail',          'value' => '₹85,000',  'status' => 'follow',    'priority' => 'medium'],
                                ['name' => 'Ravi Gupta',    'company' => 'Greenfield Exports',   'value' => '₹2,50,000', 'status' => 'new',       'priority' => 'high'],
                                ['name' => 'Sunita Joshi',  'company' => 'Apex Manufacturing',   'value' => '₹60,000',  'status' => 'follow',    'priority' => 'low'],
                                ['name' => 'Kiran Patel',   'company' => 'Sunrise Solutions',    'value' => '₹1,75,000', 'status' => 'new',       'priority' => 'medium'],
                            ];
                            $badgeMap = [
                                'new'       => ['class' => 'badge-new',       'label' => 'New'],
                                'follow'    => ['class' => 'badge-follow',    'label' => 'Follow Up'],
                                'qualified' => ['class' => 'badge-qualified', 'label' => 'Qualified'],
                                'lost'      => ['class' => 'badge-lost',      'label' => 'Lost'],
                            ];
                            $avatarColors = ['#dbeafe','#fef3c7','#dcfce7','#fce7f3','#ede9fe'];
                            $avatarText   = ['#3b82f6','#f59e0b','#22c55e','#ec4899','#8b5cf6'];
                        @endphp

                        @foreach($leads as $i => $lead)
                        <div class="lead-row flex items-center gap-4 px-3 py-3">
                            <div class="avatar" style="background:{{ $avatarColors[$i % 5] }};color:{{ $avatarText[$i % 5] }};">
                                {{ strtoupper(substr($lead['name'], 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-slate-800 text-sm truncate">{{ $lead['name'] }}</div>
                                <div class="text-xs text-slate-400 truncate">{{ $lead['company'] }}</div>
                            </div>
                            <div class="text-sm font-semibold text-slate-700 mono hidden sm:block">{{ $lead['value'] }}</div>
                            <div>
                                <span class="badge {{ $badgeMap[$lead['status']]['class'] }}">
                                    {{ $badgeMap[$lead['status']]['label'] }}
                                </span>
                            </div>
                            <div class="flex items-center text-xs text-slate-400 hidden md:flex">
                                <span class="priority-dot priority-{{ $lead['priority'] }}"></span>
                                {{ ucfirst($lead['priority']) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tasks for Today --}}
                <div class="section-card">
                    <div class="flex items-center justify-between mb-4">
                    </div>

                    @php
                        $tasks = $todays_tasks ?? [
                            ['label' => 'Call Arjun re: proposal',         'time' => '10:00 AM', 'done' => true],
                            ['label' => 'Send quote to Nova Retail',        'time' => '11:30 AM', 'done' => false],
                            ['label' => 'Follow up — Greenfield Exports',   'time' => '01:00 PM', 'done' => false],
                            ['label' => 'Update pipeline for Q2 review',    'time' => '03:00 PM', 'done' => false],
                            ['label' => 'Demo prep — Sunrise Solutions',    'time' => '05:00 PM', 'done' => false],
                        ];
                    @endphp

                    <div>
                        @foreach($tasks as $task)
                        <div class="task-item">
                            <div class="task-checkbox {{ $task['done'] ? 'border-amber-400 bg-amber-50' : '' }}">
                                @if($task['done'])
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-500 m-auto mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-slate-700 {{ $task['done'] ? 'line-through text-slate-400' : '' }}">
                                    {{ $task['label'] }}
                                </div>
                                <div class="text-xs text-slate-400 mono mt-0.5">{{ $task['time'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ── Pipeline + Activity ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Pipeline Stages --}}
                <div class="section-card">
                    <div class="section-title">My Pipeline</div>
                    @php
                        $pipeline = $pipeline_stages ?? [
                            ['stage' => 'New / Uncontacted', 'count' => 8,  'value' => '₹3,20,000', 'color' => '#94a3b8', 'pct' => 40],
                            ['stage' => 'Contacted',         'count' => 6,  'value' => '₹2,85,000', 'color' => '#60a5fa', 'pct' => 55],
                            ['stage' => 'Proposal Sent',     'count' => 5,  'value' => '₹4,50,000', 'color' => '#f59e0b', 'pct' => 70],
                            ['stage' => 'Negotiation',       'count' => 3,  'value' => '₹2,10,000', 'color' => '#a78bfa', 'pct' => 85],
                            ['stage' => 'Closed Won',        'count' => 3,  'value' => '₹2,45,000', 'color' => '#34d399', 'pct' => 100],
                        ];
                    @endphp
                    <div class="space-y-4">
                        @foreach($pipeline as $stage)
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-sm font-medium text-slate-700">{{ $stage['stage'] }}</span>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-slate-400 mono">{{ $stage['count'] }} leads</span>
                                    <span class="text-xs font-semibold text-slate-600 mono">{{ $stage['value'] }}</span>
                                </div>
                            </div>
                            <div class="pipeline-bar">
                                <div class="pipeline-fill" style="width: {{ $stage['pct'] }}%; background: {{ $stage['color'] }};"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="section-card">
                    <div class="section-title">Recent Activity</div>
                    @php
                        $activities = $recent_activities ?? [
                            ['icon' => '📞', 'text' => 'Called Arjun Mehta — no answer, left voicemail',        'time' => '2h ago',  'color' => '#bfdbfe'],
                            ['icon' => '📧', 'text' => 'Email sent to Nova Retail with revised quote',           'time' => '3h ago',  'color' => '#fde68a'],
                            ['icon' => '📝', 'text' => 'Lead "Greenfield Exports" moved to Proposal Sent',       'time' => '5h ago',  'color' => '#bbf7d0'],
                            ['icon' => '🤝', 'text' => 'Meeting scheduled with Sunrise Solutions — Friday 3 PM', 'time' => 'Yesterday','color' => '#e9d5ff'],
                            ['icon' => '✅', 'text' => 'Deal closed: Apex Manufacturing — ₹60,000',              'time' => '2d ago',  'color' => '#bbf7d0'],
                        ];
                    @endphp
                    <div class="space-y-4">
                        @foreach($activities as $activity)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm flex-shrink-0"
                                 style="background: {{ $activity['color'] }};">
                                {{ $activity['icon'] }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-slate-700 leading-snug">{{ $activity['text'] }}</p>
                                <span class="text-xs text-slate-400 mono">{{ $activity['time'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ── Upcoming Follow-Ups ── --}}
            <div class="section-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="section-title mb-0">Upcoming Follow-Ups</div>
                </div>
                @php
                    $followups = $upcoming_followups ?? [
                        ['name' => 'Priya Sharma',  'company' => 'Nova Retail',        'date' => 'Today,  2:00 PM',   'type' => 'Call',    'notes' => 'Discuss revised pricing'],
                        ['name' => 'Ravi Gupta',    'company' => 'Greenfield Exports', 'date' => 'Tomorrow, 11:00 AM','type' => 'Meeting', 'notes' => 'Product demo — online'],
                        ['name' => 'Kiran Patel',   'company' => 'Sunrise Solutions',  'date' => 'Fri,  3:00 PM',    'type' => 'Meeting', 'notes' => 'Final proposal walkthrough'],
                        ['name' => 'Meena Raj',     'company' => 'Horizon Builders',   'date' => 'Mon,  10:30 AM',   'type' => 'Email',   'notes' => 'Send contract draft'],
                    ];
                    $typeColors = ['Call' => '#dbeafe', 'Meeting' => '#fef3c7', 'Email' => '#dcfce7'];
                    $typeText   = ['Call' => '#3b82f6', 'Meeting' => '#f59e0b', 'Email' => '#22c55e'];
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    @foreach($followups as $i => $fu)
                    <div class="border border-slate-100 rounded-xl p-4 hover:border-amber-200 hover:bg-amber-50/30 transition">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="avatar w-7 h-7 text-xs"
                                 style="background:{{ $avatarColors[$i % 5] }};color:{{ $avatarText[$i % 5] }};">
                                {{ strtoupper(substr($fu['name'], 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-slate-800 truncate">{{ $fu['name'] }}</div>
                                <div class="text-xs text-slate-400 truncate">{{ $fu['company'] }}</div>
                            </div>
                        </div>
                        <div class="text-xs mono text-slate-500 mb-2">{{ $fu['date'] }}</div>
                        <div class="flex items-center justify-between">
                            <span class="badge" style="background:{{ $typeColors[$fu['type']] ?? '#f1f5f9' }};color:{{ $typeText[$fu['type']] ?? '#64748b' }};">
                                {{ $fu['type'] }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $fu['notes'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
