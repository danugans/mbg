@extends('skema.aplikasi')
@section('konten')
<div>
    <header class="mb-6">
        <div class="font-mono text-[11px] text-muted uppercase">Akses Pengguna</div>
        <h1 class="font-display text-2xl text-green-deep font-bold">Hak dan Pengaturan Akses Pengguna</h1>
    </header>

    <div class="grid grid-cols-1 gap-5">
        @php
            $aksesSections = [
                ['key' => 'ahliGizi', 'title' => 'Ahli Gizi'],
                ['key' => 'suplier', 'title' => 'Suplier'],
                ['key' => 'akuntan', 'title' => 'Akuntan'],
            ];
        @endphp

        @foreach ($aksesSections as $section)
        <div class="bg-card border border-paperline rounded-xl p-5 shadow-sm flex flex-col h-full">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <h2 class="font-display text-lg font-bold text-green-deep">{{ $section['title'] }}</h2>
                    <p class="text-xs text-muted mt-1">Kelola akses pengguna {{ strtolower($section['title']) }} di sini.</p>
                </div>
                <button onclick="addUser('{{ $section['key'] }}', '{{ $section['title'] }}')" class="rounded-full bg-green-deep text-white w-10 h-10 flex items-center justify-center shadow-md hover:bg-green-600" aria-label="Tambah {{ $section['title'] }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>

            <div class="overflow-x-auto border border-paperline rounded-xl bg-white">
                <table class="min-w-full text-sm text-center border-collapse">
                    <thead>
                        <tr class="text-xs uppercase text-muted tracking-wide bg-paper">
                            <th class="border border-paperline py-3 px-4">No</th>
                            <th class="border border-paperline py-3 px-4">Nama</th>
                            <th class="border border-paperline py-3 px-4">Email</th>
                            <th class="border border-paperline py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="{{ $section['key'] }}-body">
                        <tr class="bg-white">
                            <td colspan="4" class="border border-paperline py-10 text-center text-sm text-muted">Belum ada informasi {{ strtolower($section['title']) }}.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-xs text-muted">Tekan ikon plus untuk menambahkan pengguna {{ strtolower($section['title']) }}.</div>
        </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- keep default SweetAlert styling; use a simple grid with dividers inside modal HTML -->
<script>
    const aksesData = {
        ahliGizi: [],
        suplier: [],
        akuntan: [],
    };

    const labelMapping = {
        ahliGizi: 'Ahli Gizi',
        suplier: 'Suplier',
        akuntan: 'Akuntan',
    };

    function renderTable(roleKey) {
        const tbody = document.getElementById(`${roleKey}-body`);
        tbody.innerHTML = '';
        const items = aksesData[roleKey] || [];

        if (items.length === 0) {
            tbody.innerHTML = `<tr class="bg-white"><td colspan="4" class="py-8 text-center text-sm text-muted">Belum ada informasi ${labelMapping[roleKey].toLowerCase()}.</td></tr>`;
            return;
        }

        items.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = 'bg-white';
            row.innerHTML = `
                <td class="border border-paperline py-3 px-4 font-mono">${index + 1}</td>
                <td class="border border-paperline py-3 px-4">${item.name}</td>
                <td class="border border-paperline py-3 px-4 text-muted">${item.email}</td>
                <td class="border border-paperline py-3 px-4">
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        <button onclick="showDetail('${roleKey}', ${index})" class="inline-flex items-center gap-1 rounded-full bg-blue-100 text-blue-700 px-3 py-1 text-xs font-semibold border border-blue-200 hover:bg-blue-200" title="Info">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm.75 15h-1.5v-6h1.5v6zm0-8h-1.5V7h1.5v2z"/></svg>
                            Info
                        </button>
                        <button onclick="editUser('${roleKey}', ${index})" class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-700 px-3 py-1 text-xs font-semibold border border-amber-200 hover:bg-amber-200" title="Edit">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75l11-11.03-3.75-3.75L3 17.25zm18-10.08a1.25 1.25 0 00-.35-.88l-2.83-2.83a1.25 1.25 0 00-1.77 0l-1.83 1.83 3.75 3.75 1.83-1.83c.34-.34.44-.85.2-1.24z"/></svg>
                            Edit
                        </button>
                        <button onclick="deleteUser('${roleKey}', ${index})" class="inline-flex items-center gap-1 rounded-full bg-red-100 text-red-700 px-3 py-1 text-xs font-semibold border border-red-200 hover:bg-red-200" title="Hapus">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 3h6a1 1 0 011 1v1h3a1 1 0 110 2h-1v12a2 2 0 01-2 2H8a2 2 0 01-2-2V7H5a1 1 0 110-2h3V4a1 1 0 011-1zm1 3V4h4v2H10z"/></svg>
                            Hapus
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function renderAllTables() {
        Object.keys(aksesData).forEach(renderTable);
    }

    function validateUserField(field, value) {
        const nameRe = /^[A-Za-z\s]+$/;
        const usernameRe = /^[A-Za-z0-9]+$/;
        const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordRe = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{8,}$/;

        switch (field) {
            case 'name':
                if (!value.trim()) return 'Nama wajib diisi.';
                if (!nameRe.test(value.trim())) return 'Nama hanya boleh berisi huruf dan spasi.';
                return '';
            case 'email':
                if (!value.trim()) return 'Email wajib diisi.';
                if (!emailRe.test(value.trim())) return 'Format email tidak valid.';
                return '';
            case 'username':
                if (!value.trim()) return 'Username wajib diisi.';
                if (!usernameRe.test(value.trim())) return 'Username hanya boleh berisi huruf dan angka tanpa spasi.';
                return '';
            case 'password':
                if (!value.trim()) return 'Password wajib diisi.';
                if (!passwordRe.test(value.trim())) return 'Password harus minimal 8 karakter dan berisi huruf besar, huruf kecil, angka, dan simbol.';
                return '';
            default:
                return '';
        }
    }

    function validateUserInput(data, requirePassword = true) {
        const nameError = validateUserField('name', data.name);
        const emailError = validateUserField('email', data.email);
        const usernameError = validateUserField('username', data.username);
        const passwordError = requirePassword || data.password.trim()
            ? validateUserField('password', data.password)
            : '';

        if (nameError) return nameError;
        if (emailError) return emailError;
        if (usernameError) return usernameError;
        if (passwordError) return passwordError;
        return null;
    }

    function updateFieldError(field, message) {
        const errorElement = document.getElementById(`swal-${field}-error`);
        if (errorElement) {
            errorElement.textContent = message || '';
        }
    }

    function validateUserForm(requirePassword = true) {
        const values = {
            name: document.getElementById('swal-name')?.value || '',
            email: document.getElementById('swal-email')?.value || '',
            username: document.getElementById('swal-username')?.value || '',
            password: document.getElementById('swal-password')?.value || '',
        };

        updateFieldError('name', validateUserField('name', values.name));
        updateFieldError('email', validateUserField('email', values.email));
        updateFieldError('username', validateUserField('username', values.username));
        const passwordError = requirePassword || values.password.trim()
            ? validateUserField('password', values.password)
            : '';
        updateFieldError('password', passwordError);

        return ![ 'name', 'email', 'username', 'password' ].some((field) => {
            const errorText = document.getElementById(`swal-${field}-error`)?.textContent || '';
            return errorText.length > 0;
        });
    }

    function setupFormValidation(requirePassword = true) {
        [ 'name', 'email', 'username', 'password' ].forEach((field) => {
            const input = document.getElementById(`swal-${field}`);
            if (input) {
                input.addEventListener('input', () => validateUserForm(requirePassword));
            }
        });
        validateUserForm(requirePassword);
    }

    function togglePasswordVisibility(passwordInputId, toggleButtonId) {
        const input = document.getElementById(passwordInputId);
        const toggle = document.getElementById(toggleButtonId);
        if (!input || !toggle) {
            return;
        }
        const setIcon = (show) => {
            toggle.innerHTML = show ?
                '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20C5 20 1 12 1 12a21.79 21.79 0 0 1 5.06-5.94"/><path d="M9.88 9.88a3 3 0 0 0 4.24 4.24"/><path d="M1 1l22 22"/></svg>' :
                '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
        };

        setIcon(false);
        toggle.addEventListener('click', () => {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            setIcon(isPassword);
        });
    }

    function showDetail(roleKey, index) {
        const user = aksesData[roleKey][index];
        if (!user) return;

        Swal.fire({
            title: `Detail ${labelMapping[roleKey]}`,
            html: `
                <div class="text-left space-y-2 text-sm">
                    <p><strong>Nama:</strong> ${user.name}</p>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Username:</strong> ${user.username || '-'}</p>
                    <p><strong>Password:</strong> ${user.password || '-'}</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Tutup',
        });
    }

    function editUser(roleKey, index) {
        const user = aksesData[roleKey][index];
        if (!user) return;

        Swal.fire({
            title: `Edit ${labelMapping[roleKey]}`,
            html:
                `<div class="grid gap-3">` +
                `<div class="py-2">` +
                `<label class="text-xs font-semibold text-slate-600 block mb-1" for="swal-name">Nama</label>` +
                `<input id="swal-name" class="swal2-input w-full" placeholder="Nama" value="${user.name}">` +
                `<p id="swal-name-error" class="min-h-[18px] text-[11px] text-red-600 mt-1"></p>` +
                `<p class="text-[11px] text-muted">Contoh: Siti Aminah atau Budi Santoso.</p>` +
                `</div>` +
                `<div class="py-2">` +
                `<label class="text-xs font-semibold text-slate-600 block mb-1" for="swal-email">Email</label>` +
                `<input id="swal-email" class="swal2-input w-full" placeholder="Email" value="${user.email}">` +
                `<p id="swal-email-error" class="min-h-[18px] text-[11px] text-red-600 mt-1"></p>` +
                `<p class="text-[11px] text-muted">Contoh: nama@domain.com.</p>` +
                `</div>` +
                `<div class="py-2">` +
                `<label class="text-xs font-semibold text-slate-600 block mb-1" for="swal-username">Username</label>` +
                `<input id="swal-username" class="swal2-input w-full" placeholder="Username" value="${user.username || ''}">` +
                `<p id="swal-username-error" class="min-h-[18px] text-[11px] text-red-600 mt-1"></p>` +
                `<p class="text-[11px] text-muted">Tanpa spasi, hanya huruf dan angka.</p>` +
                `</div>` +
                `<div class="py-2">` +
                `<label class="text-xs font-semibold text-slate-600 block mb-1" for="swal-password">Password</label>` +
                `<div class="relative">` +
                `<input id="swal-password" type="password" class="swal2-input w-full pr-10" placeholder="Password" value="${user.password || ''}">` +
                `<button type="button" id="swal-password-toggle" class="absolute right-2 top-1/2 -translate-y-1/2"></button>` +
                `</div>` +
                `<p id="swal-password-error" class="min-h-[18px] text-[11px] text-red-600 mt-1"></p>` +
                `<p class="text-[11px] text-muted">Minimal 8 karakter, termasuk huruf besar, huruf kecil, angka, dan simbol.</p>` +
                `</div>` +
                `</div>`,
            focusConfirm: false,
            didOpen: () => {
                togglePasswordVisibility('swal-password', 'swal-password-toggle');
                setupFormValidation(false);
            },
            preConfirm: () => {
                return {
                    name: document.getElementById('swal-name').value,
                    email: document.getElementById('swal-email').value,
                    username: document.getElementById('swal-username').value,
                    password: document.getElementById('swal-password').value,
                };
            },
            showCancelButton: true,
            confirmButtonText: 'Simpan',
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                if (!validateUserForm(false)) {
                    Swal.showValidationMessage('Periksa kembali kolom yang berwarna merah.');
                    return false;
                }
                const error = validateUserInput(result.value, false);
                if (error) {
                    Swal.fire('Gagal', error, 'error');
                    return;
                }
                aksesData[roleKey][index].name = result.value.name.trim() || user.name;
                aksesData[roleKey][index].email = result.value.email.trim() || user.email;
                aksesData[roleKey][index].username = result.value.username.trim() || user.username || '';
                aksesData[roleKey][index].password = result.value.password.trim() || user.password || '';
                renderTable(roleKey);
                Swal.fire('Tersimpan', 'Data berhasil diperbarui.', 'success');
            }
        });
    }

    function deleteUser(roleKey, index) {
        Swal.fire({
            title: 'Hapus pengguna?',
            text: `Anda akan menghapus ${labelMapping[roleKey].toLowerCase()} ini.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                aksesData[roleKey].splice(index, 1);
                renderTable(roleKey);
                Swal.fire('Dihapus', 'Data pengguna telah dihapus.', 'success');
            }
        });
    }

    function addUser(roleKey, roleTitle) {
        Swal.fire({
            title: `Tambah ${roleTitle}`,
            html:
                `<div class="grid gap-3">` +
                `<div class="py-2">` +
                `<label class="text-xs font-semibold text-slate-600 block mb-1" for="swal-name">Nama</label>` +
                `<input id="swal-name" class="swal2-input w-full" placeholder="Nama">` +
                `<p id="swal-name-error" class="min-h-[18px] text-[11px] text-red-600 mt-1"></p>` +
                `<p class="text-[11px] text-muted">Contoh: Siti Aminah atau Budi Santoso.</p>` +
                `</div>` +
                `<div class="py-2">` +
                `<label class="text-xs font-semibold text-slate-600 block mb-1" for="swal-email">Email</label>` +
                `<input id="swal-email" class="swal2-input w-full" placeholder="Email">` +
                `<p id="swal-email-error" class="min-h-[18px] text-[11px] text-red-600 mt-1"></p>` +
                `<p class="text-[11px] text-muted">Contoh: nama@domain.com.</p>` +
                `</div>` +
                `<div class="py-2">` +
                `<label class="text-xs font-semibold text-slate-600 block mb-1" for="swal-username">Username</label>` +
                `<input id="swal-username" class="swal2-input w-full" placeholder="Username">` +
                `<p id="swal-username-error" class="min-h-[18px] text-[11px] text-red-600 mt-1"></p>` +
                `<p class="text-[11px] text-muted">Tanpa spasi, hanya huruf dan angka.</p>` +
                `</div>` +
                `<div class="py-2">` +
                `<label class="text-xs font-semibold text-slate-600 block mb-1" for="swal-password">Password</label>` +
                `<div class="relative">` +
                `<input id="swal-password" type="password" class="swal2-input w-full pr-10" placeholder="Password">` +
                `<button type="button" id="swal-password-toggle" class="absolute right-2 top-1/2 -translate-y-1/2"></button>` +
                `</div>` +
                `<p id="swal-password-error" class="min-h-[18px] text-[11px] text-red-600 mt-1"></p>` +
                `<p class="text-[11px] text-muted">Minimal 8 karakter, termasuk huruf besar, huruf kecil, angka, dan simbol.</p>` +
                `</div>` +
                `</div>`,
            focusConfirm: false,
            didOpen: () => {
                togglePasswordVisibility('swal-password', 'swal-password-toggle');
                setupFormValidation(true);
            },
            preConfirm: () => {
                return {
                    name: document.getElementById('swal-name').value,
                    email: document.getElementById('swal-email').value,
                    username: document.getElementById('swal-username').value,
                    password: document.getElementById('swal-password').value,
                };
            },
            showCancelButton: true,
            confirmButtonText: 'Tambah',
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                if (!validateUserForm(true)) {
                    Swal.showValidationMessage('Periksa kembali kolom yang berwarna merah.');
                    return false;
                }
                const error = validateUserInput(result.value, true);
                if (error) {
                    Swal.fire('Gagal', error, 'error');
                    return;
                }
                aksesData[roleKey].push({
                    name: result.value.name.trim(),
                    email: result.value.email.trim(),
                    username: result.value.username.trim(),
                    password: result.value.password.trim(),
                });
                renderTable(roleKey);
                Swal.fire('Berhasil', `${roleTitle} baru berhasil ditambahkan.`, 'success');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', renderAllTables);
</script>
@endsection
