<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}"
               class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Add New User</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Create a team member and assign their role</p>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">

                {{-- FORM HEADER --}}
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">User Details</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">All fields marked * are required</p>
                        </div>
                    </div>
                </div>

                <form id="createUserForm" novalidate>
                    <div class="px-6 py-6 space-y-5">

                        {{-- NAME --}}
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-400 mb-1.5">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                   placeholder="e.g. Gourav Sharma"
                                   autocomplete="off"
                                   class="form-input block w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-400">
                            <p class="field-error hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="name-error">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span></span>
                            </p>
                        </div>

                        {{-- EMAIL with suggestions --}}
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-400 mb-1.5">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="email" id="email" name="email"
                                       placeholder="e.g. gourav@company.com"
                                       autocomplete="off"
                                       class="form-input block w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-400">

                                {{-- SUGGESTION DROPDOWN --}}
                                <div id="emailSuggestions"
                                     class="absolute z-30 left-0 right-0 top-full mt-1 hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden">
                                </div>
                            </div>
                            <p class="field-error hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="email-error">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span></span>
                            </p>
                        </div>

                        {{-- PASSWORD --}}
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-400 mb-1.5">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                       placeholder="Min 8 chars, upper + lower + number"
                                       class="form-input block w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 pr-11 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition placeholder-gray-400">
                                <button type="button" id="togglePassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                                    <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            {{-- Strength bar --}}
                            <div class="mt-2 space-y-1">
                                <div class="flex gap-1">
                                    <div class="h-1 flex-1 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                        <div id="strength-1" class="h-full w-0 rounded-full transition-all duration-300"></div>
                                    </div>
                                    <div class="h-1 flex-1 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                        <div id="strength-2" class="h-full w-0 rounded-full transition-all duration-300"></div>
                                    </div>
                                    <div class="h-1 flex-1 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                        <div id="strength-3" class="h-full w-0 rounded-full transition-all duration-300"></div>
                                    </div>
                                    <div class="h-1 flex-1 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                        <div id="strength-4" class="h-full w-0 rounded-full transition-all duration-300"></div>
                                    </div>
                                </div>
                                <p id="strengthLabel" class="text-xs text-gray-400"></p>
                            </div>
                            <p class="field-error hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="password-error">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span></span>
                            </p>
                        </div>

                        {{-- ROLE --}}
                        <div>
                            <label for="roleSelect" class="block text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-400 mb-1.5">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select id="roleSelect" name="role"
                                    class="form-input block w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                <option value="" disabled selected>Loading roles...</option>
                            </select>
                            <p class="field-error hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="role-error">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span></span>
                            </p>
                        </div>

                        {{-- PERMISSIONS (read-only, shown on role select) --}}
                        <div id="permissionsContainer" class="hidden rounded-xl border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/20 p-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-indigo-700 dark:text-indigo-300 mb-3 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Inherited Permissions
                            </p>
                            <div id="permissionsList" class="grid grid-cols-1 sm:grid-cols-2 gap-2"></div>
                        </div>

                        {{-- MANAGER --}}
                        <div>
                            <label for="managerId" class="block text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-400 mb-1.5">
                                Reports To <span class="text-gray-400 font-normal normal-case">(optional)</span>
                            </label>
                            <select id="managerId" name="manager_id"
                                    class="block w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                <option value="">No manager (top-level)</option>
                                @foreach(\App\Models\User::topLevel()->get() as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }} — {{ $manager->designation ?? 'No designation' }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <a href="{{ route('users.index') }}"
                           class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                            ← Back to users
                        </a>
                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Create User
                        </button>
                    </div>
                </form>

                {{-- API MESSAGE --}}
                <div id="apiMessage" class="hidden mx-6 mb-6 p-4 rounded-xl text-sm font-medium"></div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        let rolesData   = [];
        let suggestionIndex = -1;
        let debounceTimer;

        // ─── Helpers ──────────────────────────────────────────────

        function showError(fieldId, msg) {
            const el = document.getElementById(fieldId + '-error');
            const input = document.getElementById(fieldId);
            if (!el) return;
            el.classList.remove('hidden');
            el.querySelector('span').textContent = msg;
            input?.classList.add('border-red-400', 'focus:ring-red-400');
            input?.classList.remove('border-gray-200', 'dark:border-gray-700');
        }

        function clearError(fieldId) {
            const el = document.getElementById(fieldId + '-error');
            const input = document.getElementById(fieldId);
            if (!el) return;
            el.classList.add('hidden');
            input?.classList.remove('border-red-400', 'focus:ring-red-400');
            input?.classList.add('border-gray-200', 'dark:border-gray-700');
        }

        function clearAllErrors() {
            ['name','email','password','role'].forEach(clearError);
        }

        // ─── 1. Load Roles ────────────────────────────────────────

        fetch('/users/roles', { headers: { 'Accept': 'application/json' } })
            .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
            .then(data => {
                rolesData = data;
                const select = document.getElementById('roleSelect');
                select.innerHTML = '<option value="" disabled selected>Select a role…</option>';
                data.forEach(role => {
                    const opt = document.createElement('option');
                    opt.value       = role.id;
                    opt.textContent = role.label;
                    select.appendChild(opt);
                });
            })
            .catch(() => {
                document.getElementById('roleSelect').innerHTML =
                    '<option value="" disabled selected>⚠ Failed to load roles</option>';
            });

        // ─── 2. Show Permissions on Role Change ───────────────────

        document.getElementById('roleSelect').addEventListener('change', function () {
            clearError('role');
            const roleObj   = rolesData.find(r => r.id === this.value);
            const container = document.getElementById('permissionsContainer');
            const list      = document.getElementById('permissionsList');
            list.innerHTML  = '';

            if (roleObj?.permissions?.length > 0) {
                container.classList.remove('hidden');
                roleObj.permissions.forEach(perm => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center gap-2 text-xs text-indigo-700 dark:text-indigo-300';
                    div.innerHTML = `
                        <svg class="w-3.5 h-3.5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="capitalize">${perm.replace(/_/g, ' ')}</span>
                    `;
                    list.appendChild(div);
                });
            } else {
                container.classList.add('hidden');
            }
        });

        // ─── 3. Password Show/Hide + Strength ─────────────────────

        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const isText = input.type === 'text';
            input.type = isText ? 'password' : 'text';
            document.getElementById('eyeIcon').innerHTML = isText
                ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`
                : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
        });

        document.getElementById('password').addEventListener('input', function () {
            clearError('password');
            const val = this.value;
            let score = 0;
            if (val.length >= 8)               score++;
            if (/[A-Z]/.test(val))             score++;
            if (/[0-9]/.test(val))             score++;
            if (/[^A-Za-z0-9]/.test(val))      score++;

            const colors = ['', 'bg-red-400', 'bg-yellow-400', 'bg-blue-400', 'bg-emerald-500'];
            const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];

            for (let i = 1; i <= 4; i++) {
                const bar = document.getElementById('strength-' + i);
                bar.className = 'h-full rounded-full transition-all duration-300 ' +
                    (i <= score ? colors[score] : '');
                bar.style.width = i <= score ? '100%' : '0%';
            }
            document.getElementById('strengthLabel').textContent = val ? labels[score] : '';
        });

        // ─── 4. Email Suggestions (keyboard navigable) ────────────

        const emailInput   = document.getElementById('email');
        const suggestBox   = document.getElementById('emailSuggestions');

        function hideSuggestions() {
            suggestBox.classList.add('hidden');
            suggestBox.innerHTML = '';
            suggestionIndex = -1;
        }

        function buildSuggestions(users) {
            suggestBox.innerHTML = '';
            if (!users.length) { hideSuggestions(); return; }

            users.forEach((user, idx) => {
                const div = document.createElement('div');
                div.className = 'suggestion-item px-4 py-2.5 cursor-pointer flex items-center gap-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors text-sm';
                div.dataset.index = idx;
                div.innerHTML = `
                    <div class="h-7 w-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold text-xs flex-shrink-0">
                        ${user.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900 dark:text-white">${user.name}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">${user.email}</div>
                    </div>
                `;
                div.addEventListener('mousedown', (e) => {
                    e.preventDefault(); // prevent blur
                    emailInput.value = user.email;
                    hideSuggestions();
                    clearError('email');
                });
                suggestBox.appendChild(div);
            });

            suggestBox.classList.remove('hidden');
        }

        function highlightSuggestion(idx) {
            const items = suggestBox.querySelectorAll('.suggestion-item');
            items.forEach((el, i) => {
                el.classList.toggle('bg-indigo-50', i === idx);
                el.classList.toggle('dark:bg-indigo-900/30', i === idx);
            });
        }

        emailInput.addEventListener('input', function () {
            clearError('email');
            clearTimeout(debounceTimer);
            const q = this.value.trim();
            if (q.length < 2) { hideSuggestions(); return; }

            debounceTimer = setTimeout(() => {
                fetch(`/users/search?q=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(users => {
                    suggestionIndex = -1;
                    buildSuggestions(users);
                })
                .catch(() => hideSuggestions());
            }, 250);
        });

        emailInput.addEventListener('keydown', function (e) {
            const items = suggestBox.querySelectorAll('.suggestion-item');
            if (!items.length) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                suggestionIndex = Math.min(suggestionIndex + 1, items.length - 1);
                highlightSuggestion(suggestionIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                suggestionIndex = Math.max(suggestionIndex - 1, 0);
                highlightSuggestion(suggestionIndex);
            } else if (e.key === 'Enter' && suggestionIndex >= 0) {
                e.preventDefault();
                emailInput.value = items[suggestionIndex].querySelector('.text-xs').textContent.trim();
                hideSuggestions();
            } else if (e.key === 'Escape') {
                hideSuggestions();
            }
        });

        emailInput.addEventListener('blur', () => {
            setTimeout(hideSuggestions, 150);
        });

        // Clear on input field focus/change
        document.getElementById('name').addEventListener('input', () => clearError('name'));

        // ─── 5. Submit ────────────────────────────────────────────

        document.getElementById('createUserForm').addEventListener('submit', function (e) {
            e.preventDefault();
            clearAllErrors();

            const name     = document.getElementById('name').value.trim();
            const email    = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const role     = document.getElementById('roleSelect').value;

            // Client-side validation
            let hasError = false;

            if (!name || name.length < 2) {
                showError('name', 'Name must be at least 2 characters.'); hasError = true;
            }
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('email', 'Please enter a valid email address.'); hasError = true;
            }
            if (!password || password.length < 8) {
                showError('password', 'Password must be at least 8 characters.'); hasError = true;
            } else if (!/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
                showError('password', 'Password needs uppercase letters and numbers.'); hasError = true;
            }
            if (!role) {
                showError('role', 'Please select a role.'); hasError = true;
            }

            if (hasError) return;

            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Creating…
            `;

            fetch('/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    name, email, password,
                    role,
                    manager_id: document.getElementById('managerId').value || null,
                })
            })
            .then(r => r.json())
            .then(data => {
                const msg = document.getElementById('apiMessage');
                msg.classList.remove('hidden', 'bg-red-50', 'text-red-700', 'border-red-200',
                                                'bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
                msg.classList.remove('border');

                if (data.message === 'User created successfully!') {
                    msg.classList.add('bg-emerald-50', 'dark:bg-emerald-900/20', 'text-emerald-700',
                                      'dark:text-emerald-300', 'border', 'border-emerald-200', 'dark:border-emerald-800');
                    msg.innerHTML = `
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="font-semibold">User created successfully!</p>
                                <p class="text-xs mt-0.5 opacity-75">${data.user.name} · ${data.assigned_permissions.length} permission(s) active</p>
                            </div>
                        </div>
                    `;
                    this.reset();
                    document.getElementById('permissionsContainer').classList.add('hidden');
                    document.getElementById('strengthLabel').textContent = '';
                    for (let i = 1; i <= 4; i++) {
                        const b = document.getElementById('strength-' + i);
                        b.style.width = '0%';
                        b.className = 'h-full rounded-full transition-all duration-300';
                    }
                } else {
                    // Show server-side validation errors on fields
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, messages]) => {
                            const fieldId = field === 'manager_id' ? 'manager' : field;
                            showError(fieldId, messages[0]);
                        });
                    }
                    msg.classList.add('bg-red-50', 'dark:bg-red-900/20', 'text-red-700',
                                      'dark:text-red-300', 'border', 'border-red-200', 'dark:border-red-800');
                    msg.innerHTML = `
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-semibold">${data.message || 'Please fix the errors above.'}</p>
                        </div>
                    `;
                }
            })
            .catch(() => {
                const msg = document.getElementById('apiMessage');
                msg.classList.remove('hidden');
                msg.className = 'mx-6 mb-6 p-4 rounded-xl text-sm font-medium bg-red-50 text-red-700 border border-red-200';
                msg.textContent = 'Network error. Please try again.';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Create User
                `;
            });
        });
    });
    </script>
</x-app-layout>
