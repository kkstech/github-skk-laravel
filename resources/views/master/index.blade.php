@extends('layouts.app')

@section('title', 'Master Data')

@push('styles')
<style>
    .tab-btn {
        background: transparent;
        color: var(--text-muted);
        border: 1px solid transparent;
        transition: var(--transition);
        border-radius: var(--radius-sm);
    }
    .tab-btn:hover {
        color: white;
        background: rgba(255,255,255,0.05);
    }
    .tab-btn.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 12px var(--primary-glow);
    }
    .master-table th, .master-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--card-border);
    }
    .master-table tbody tr:hover {
        background: rgba(255,255,255,0.02);
    }
</style>
@endpush

@section('content')
<header class="app-header">
    <div class="header-title">
        <div class="icon-wrapper">
            <i data-lucide="database"></i>
        </div>
        <div>
            <h1>Master Data</h1>
            <p>Pengelolaan Data Pendukung Sertifikat</p>
        </div>
    </div>
</header>

<div class="tabs-container glass-panel" style="margin-bottom: 2rem; padding: 1rem; display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
    <button class="btn tab-btn active" data-tab="classifications" style="width: auto; padding: 0.5rem 1rem;">Klasifikasi</button>
    <button class="btn tab-btn" data-tab="subclassifications" style="width: auto; padding: 0.5rem 1rem;">Subklasifikasi</button>
    <button class="btn tab-btn" data-tab="work-positions" style="width: auto; padding: 0.5rem 1rem;">Jabatan Kerja</button>
    <button class="btn tab-btn" data-tab="qualifications" style="width: auto; padding: 0.5rem 1rem;">Kualifikasi</button>
    <button class="btn tab-btn" data-tab="lsps" style="width: auto; padding: 0.5rem 1rem;">LSP</button>
    <button class="btn tab-btn" data-tab="associations" style="width: auto; padding: 0.5rem 1rem;">Asosiasi</button>
</div>

<main class="app-main">
    <!-- Left Side: Form -->
    <section class="form-section glass-panel">
        <div class="section-header">
            <h2 id="form-title">Tambah Klasifikasi</h2>
            <i data-lucide="plus-circle" id="form-icon"></i>
        </div>
        <form id="master-form">
            @csrf
            <input type="hidden" id="record-id" value="">
            
            <div id="form-fields" style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Injected dynamically by JS -->
            </div>
            
            <div class="form-actions" style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                <button type="button" id="cancel-btn" class="btn btn-secondary hidden" style="width: 50%;">Batal</button>
                <button type="submit" id="submit-btn" class="btn btn-primary" style="width: 100%;">Simpan</button>
            </div>
        </form>
    </section>

    <!-- Right Side: List -->
    <section class="list-section glass-panel">
        <div class="section-header">
            <h2 id="list-title">Daftar Klasifikasi</h2>
            <span class="badge" id="record-count">0</span>
        </div>
        <div class="certs-container" style="max-height: 60vh; overflow-y: auto;">
            <table class="master-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr id="table-headers" style="border-bottom: 2px solid var(--card-border); color: var(--text-muted); font-size: 0.85rem;">
                        <!-- Injected dynamically by JS -->
                    </tr>
                </thead>
                <tbody id="records-table-body" style="font-size: 0.9rem;">
                    <!-- Injected dynamically by JS -->
                </tbody>
            </table>
            <div id="empty-state" class="empty-state hidden" style="text-align: center; padding: 3rem 1rem;">
                <div class="empty-icon-wrapper" style="margin: 0 auto 1rem; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(51, 65, 85, 0.5); border-radius: 50%;">
                    <i data-lucide="inbox" style="width: 24px; height: 24px; color: var(--text-muted);"></i>
                </div>
                <h3>Belum Ada Data</h3>
                <p>Silakan tambahkan data baru.</p>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
// ──────────────────────────────────────────────────────────────
// API Configuration
// ──────────────────────────────────────────────────────────────
const BASE_URL = '{{ url('/api/master') }}';
const API = {
    classifications: {
        index:   `${BASE_URL}/classifications`,
        store:   `${BASE_URL}/classifications`,
        update:  (id) => `${BASE_URL}/classifications/${id}`,
        destroy: (id) => `${BASE_URL}/classifications/${id}`,
    },
    subclassifications: {
        index:   `${BASE_URL}/subclassifications`,
        store:   `${BASE_URL}/subclassifications`,
        update:  (id) => `${BASE_URL}/subclassifications/${id}`,
        destroy: (id) => `${BASE_URL}/subclassifications/${id}`,
    },
    work_positions: {
        index:   `${BASE_URL}/work-positions`,
        store:   `${BASE_URL}/work-positions`,
        update:  (id) => `${BASE_URL}/work-positions/${id}`,
        destroy: (id) => `${BASE_URL}/work-positions/${id}`,
    },
    qualifications: {
        index:   `${BASE_URL}/qualifications`,
        store:   `${BASE_URL}/qualifications`,
        update:  (id) => `${BASE_URL}/qualifications/${id}`,
        destroy: (id) => `${BASE_URL}/qualifications/${id}`,
    },
    lsps: {
        index:   `${BASE_URL}/lsps`,
        store:   `${BASE_URL}/lsps`,
        update:  (id) => `${BASE_URL}/lsps/${id}`,
        destroy: (id) => `${BASE_URL}/lsps/${id}`,
    },
    associations: {
        index:   `${BASE_URL}/associations`,
        store:   `${BASE_URL}/associations`,
        update:  (id) => `${BASE_URL}/associations/${id}`,
        destroy: (id) => `${BASE_URL}/associations/${id}`,
    }
};

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// State
let currentTab = 'classifications';
let records = [];
let isEditing = false;

// DOM references
const tabs = document.querySelectorAll('.tab-btn');
const form = document.getElementById('master-form');
const formTitle = document.getElementById('form-title');
const formIcon = document.getElementById('form-icon');
const formFields = document.getElementById('form-fields');
const listTitle = document.getElementById('list-title');
const recordCount = document.getElementById('record-count');
const tableHeaders = document.getElementById('table-headers');
const tableBody = document.getElementById('records-table-body');
const emptyState = document.getElementById('empty-state');
const cancelBtn = document.getElementById('cancel-btn');
const submitBtn = document.getElementById('submit-btn');
const recordIdInput = document.getElementById('record-id');

// Toast
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toast-message');
    const toastIcon = document.getElementById('toast-icon');
    toastMsg.textContent = message;
    if (type === 'success') {
        toastIcon.setAttribute('data-lucide', 'check-circle');
        toastIcon.style.color = '#10b981';
    } else {
        toastIcon.setAttribute('data-lucide', 'alert-circle');
        toastIcon.style.color = '#ef4444';
    }
    lucide.createIcons();
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3500);
}

// ──────────────────────────────────────────────────────────────
// UI Logic & Rendering
// ──────────────────────────────────────────────────────────────
const TABS_CONFIG = {
    classifications: {
        label: 'Klasifikasi',
        fields: () => `
            <div class="input-group">
                <label for="nama">Nama Klasifikasi</label>
                <input type="text" id="nama" placeholder="Contoh: SIPIL" required autocomplete="off">
            </div>
        `,
        headers: '<th>Nama</th><th style="width: 150px; text-align: center;">Aksi</th>',
        renderRow: (r) => `
            <tr>
                <td>${esc(r.nama)}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn-icon btn-edit" data-id="${r.id}" style="display: inline-flex; margin-right: 0.5rem;"><i data-lucide="edit-2"></i></button>
                    <button type="button" class="btn-icon btn-delete" data-id="${r.id}" style="display: inline-flex;"><i data-lucide="trash-2"></i></button>
                </td>
            </tr>
        `,
        getFieldsData: () => ({ nama: document.getElementById('nama').value.trim() }),
        populateForm: (r) => { document.getElementById('nama').value = r.nama; }
    },
    subclassifications: {
        label: 'Subklasifikasi',
        fields: async () => {
            const res = await fetch(API.classifications.index);
            const classifications = await res.json();
            const options = classifications.map(c => `<option value="${c.id}">${esc(c.nama)}</option>`).join('');
            return `
                <div class="input-group">
                    <label for="classification_id">Klasifikasi</label>
                    <select id="classification_id" required>
                        <option value="">Pilih Klasifikasi</option>
                        ${options}
                    </select>
                </div>
                <div class="input-group">
                    <label for="nama">Nama Subklasifikasi</label>
                    <input type="text" id="nama" placeholder="Contoh: Ahli Muda Teknik Pantai" required autocomplete="off">
                </div>
            `;
        },
        headers: '<th>Nama</th><th>Klasifikasi</th><th style="width: 150px; text-align: center;">Aksi</th>',
        renderRow: (r) => `
            <tr>
                <td>${esc(r.nama)}</td>
                <td>${esc(r.classification?.nama || '')}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn-icon btn-edit" data-id="${r.id}" style="display: inline-flex; margin-right: 0.5rem;"><i data-lucide="edit-2"></i></button>
                    <button type="button" class="btn-icon btn-delete" data-id="${r.id}" style="display: inline-flex;"><i data-lucide="trash-2"></i></button>
                </td>
            </tr>
        `,
        getFieldsData: () => ({
            classification_id: document.getElementById('classification_id').value,
            nama: document.getElementById('nama').value.trim()
        }),
        populateForm: (r) => {
            document.getElementById('classification_id').value = r.classification_id;
            document.getElementById('nama').value = r.nama;
        }
    },
    'work-positions': {
        label: 'Jabatan Kerja',
        fields: async () => {
            const res = await fetch(API.subclassifications.index);
            const subclassifications = await res.json();
            const options = subclassifications.map(s => `<option value="${s.id}">${esc(s.nama)} (${esc(s.classification?.nama || '')})</option>`).join('');
            return `
                <div class="input-group">
                    <label for="subclassification_id">Subklasifikasi</label>
                    <select id="subclassification_id" required>
                        <option value="">Pilih Subklasifikasi</option>
                        ${options}
                    </select>
                </div>
                <div class="input-group">
                    <label for="kode_jabatan">Kode Jabatan Kerja</label>
                    <input type="text" id="kode_jabatan" placeholder="Contoh: SI091013" required autocomplete="off">
                </div>
                <div class="input-group">
                    <label for="nama">Jabatan Kerja</label>
                    <input type="text" id="nama" placeholder="Contoh: Ahli Muda Teknik Pantai" required autocomplete="off">
                </div>
            `;
        },
        headers: '<th>Kode</th><th>Nama</th><th>Subklasifikasi</th><th style="width: 150px; text-align: center;">Aksi</th>',
        renderRow: (r) => `
            <tr>
                <td><strong>${esc(r.kode_jabatan)}</strong></td>
                <td>${esc(r.nama)}</td>
                <td>${esc(r.subclassification?.nama || '')}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn-icon btn-edit" data-id="${r.id}" style="display: inline-flex; margin-right: 0.5rem;"><i data-lucide="edit-2"></i></button>
                    <button type="button" class="btn-icon btn-delete" data-id="${r.id}" style="display: inline-flex;"><i data-lucide="trash-2"></i></button>
                </td>
            </tr>
        `,
        getFieldsData: () => ({
            subclassification_id: document.getElementById('subclassification_id').value,
            kode_jabatan: document.getElementById('kode_jabatan').value.trim(),
            nama: document.getElementById('nama').value.trim()
        }),
        populateForm: (r) => {
            document.getElementById('subclassification_id').value = r.subclassification_id;
            document.getElementById('kode_jabatan').value = r.kode_jabatan;
            document.getElementById('nama').value = r.nama;
        }
    },
    qualifications: {
        label: 'Kualifikasi',
        fields: () => `
            <div class="input-group">
                <label for="nama">Nama Kualifikasi</label>
                <input type="text" id="nama" placeholder="Contoh: Ahli / Terampil" required autocomplete="off">
            </div>
        `,
        headers: '<th>Nama</th><th style="width: 150px; text-align: center;">Aksi</th>',
        renderRow: (r) => `
            <tr>
                <td>${esc(r.nama)}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn-icon btn-edit" data-id="${r.id}" style="display: inline-flex; margin-right: 0.5rem;"><i data-lucide="edit-2"></i></button>
                    <button type="button" class="btn-icon btn-delete" data-id="${r.id}" style="display: inline-flex;"><i data-lucide="trash-2"></i></button>
                </td>
            </tr>
        `,
        getFieldsData: () => ({ nama: document.getElementById('nama').value.trim() }),
        populateForm: (r) => { document.getElementById('nama').value = r.nama; }
    },
    lsps: {
        label: 'LSP',
        fields: () => `
            <div class="input-group">
                <label for="nama">Nama Lembaga Sertifikasi Profesi (LSP)</label>
                <input type="text" id="nama" placeholder="Contoh: LSP ASTEKINDO KONSTRUKSI MANDIRI" required autocomplete="off">
            </div>
        `,
        headers: '<th>Nama LSP</th><th style="width: 150px; text-align: center;">Aksi</th>',
        renderRow: (r) => `
            <tr>
                <td>${esc(r.nama)}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn-icon btn-edit" data-id="${r.id}" style="display: inline-flex; margin-right: 0.5rem;"><i data-lucide="edit-2"></i></button>
                    <button type="button" class="btn-icon btn-delete" data-id="${r.id}" style="display: inline-flex;"><i data-lucide="trash-2"></i></button>
                </td>
            </tr>
        `,
        getFieldsData: () => ({ nama: document.getElementById('nama').value.trim() }),
        populateForm: (r) => { document.getElementById('nama').value = r.nama; }
    },
    associations: {
        label: 'Asosiasi',
        fields: () => `
            <div class="input-group">
                <label for="nama">Nama Asosiasi</label>
                <input type="text" id="nama" placeholder="Contoh: PERPAKOM" required autocomplete="off">
            </div>
        `,
        headers: '<th>Nama Asosiasi</th><th style="width: 150px; text-align: center;">Aksi</th>',
        renderRow: (r) => `
            <tr>
                <td>${esc(r.nama)}</td>
                <td style="text-align: center;">
                    <button type="button" class="btn-icon btn-edit" data-id="${r.id}" style="display: inline-flex; margin-right: 0.5rem;"><i data-lucide="edit-2"></i></button>
                    <button type="button" class="btn-icon btn-delete" data-id="${r.id}" style="display: inline-flex;"><i data-lucide="trash-2"></i></button>
                </td>
            </tr>
        `,
        getFieldsData: () => ({ nama: document.getElementById('nama').value.trim() }),
        populateForm: (r) => { document.getElementById('nama').value = r.nama; }
    }
};

function esc(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
}

// Reset form
function resetForm() {
    form.reset();
    recordIdInput.value = '';
    isEditing = false;
    
    const config = TABS_CONFIG[currentTab];
    formTitle.textContent = `Tambah ${config.label}`;
    formIcon.setAttribute('data-lucide', 'plus-circle');
    submitBtn.innerHTML = 'Simpan';
    cancelBtn.classList.add('hidden');
    lucide.createIcons();
}

// Load data & Render
async function switchTab(tabName) {
    currentTab = tabName;
    resetForm();

    const config = TABS_CONFIG[currentTab];
    
    // Set headers
    tableHeaders.innerHTML = config.headers;
    listTitle.textContent = `Daftar ${config.label}`;

    // Render form fields (async if needed)
    formFields.innerHTML = '<span class="loading-spinner"></span>';
    const fieldsHtml = await Promise.resolve(config.fields());
    formFields.innerHTML = fieldsHtml;

    // Load records
    await loadRecords();
}

async function loadRecords() {
    const config = TABS_CONFIG[currentTab];
    const apiKey = currentTab.replace('-', '_');
    
    tableBody.innerHTML = '<tr><td colspan="4" style="text-align: center;"><span class="loading-spinner"></span></td></tr>';

    try {
        const res = await fetch(API[apiKey].index);
        records = await res.json();
        
        recordCount.textContent = records.length;
        tableBody.innerHTML = '';

        if (records.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        records.forEach(r => {
            tableBody.innerHTML += config.renderRow(r);
        });

        lucide.createIcons();
    } catch (e) {
        showToast('Gagal memuat data', 'error');
        tableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: var(--danger);">Gagal memuat data</td></tr>';
    }
}

// Event Listeners for tabs
tabs.forEach(btn => {
    btn.addEventListener('click', (e) => {
        tabs.forEach(t => t.classList.remove('active'));
        e.target.classList.add('active');
        switchTab(e.target.dataset.tab);
    });
});

// Cancel button click
cancelBtn.addEventListener('click', resetForm);

// Form Submit Handler (AJAX)
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const config = TABS_CONFIG[currentTab];
    const apiKey = currentTab.replace('-', '_');
    const data = config.getFieldsData();

    const id = recordIdInput.value;
    const btnOrig = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<span class="loading-spinner"></span>';
    submitBtn.disabled = true;

    try {
        if (isEditing) {
            const res = await fetch(API[apiKey].update(id), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(data)
            });
            if (!res.ok) throw await res.json();
            showToast('Data berhasil diperbarui');
        } else {
            const res = await fetch(API[apiKey].store, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(data)
            });
            if (!res.ok) throw await res.json();
            showToast('Data berhasil ditambahkan');
        }

        resetForm();
        // Reload form fields to update dropdown options inside subclassifications & work positions
        const fieldsHtml = await Promise.resolve(config.fields());
        formFields.innerHTML = fieldsHtml;
        
        await loadRecords();
    } catch (err) {
        const msg = err?.message || (err?.errors ? Object.values(err.errors)[0][0] : 'Terjadi kesalahan');
        showToast(msg, 'error');
        submitBtn.innerHTML = btnOrig;
    } finally {
        submitBtn.disabled = false;
        lucide.createIcons();
    }
});

// Table/Records Edit & Delete Action Delegations
tableBody.addEventListener('click', async (e) => {
    const btn = e.target.closest('button[data-id]');
    if (!btn) return;

    const id = btn.dataset.id;
    const record = records.find(r => r.id == id);
    const config = TABS_CONFIG[currentTab];
    const apiKey = currentTab.replace('-', '_');

    if (btn.classList.contains('btn-edit')) {
        if (!record) return;
        recordIdInput.value = record.id;
        config.populateForm(record);
        
        isEditing = true;
        formTitle.textContent = `Edit ${config.label}`;
        formIcon.setAttribute('data-lucide', 'edit');
        submitBtn.innerHTML = 'Update';
        cancelBtn.classList.remove('hidden');
        lucide.createIcons();
        document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
    }

    if (btn.classList.contains('btn-delete')) {
        if (!confirm('Yakin ingin menghapus data master ini? Menghapus data ini mungkin akan mempengaruhi data berelasi di bawahnya.')) return;
        try {
            const res = await fetch(API[apiKey].destroy(id), {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            if (!res.ok) throw await res.json();
            showToast('Data berhasil dihapus');
            
            // Re-render form fields to update dropdown options inside subclassifications & work positions
            const fieldsHtml = await Promise.resolve(config.fields());
            formFields.innerHTML = fieldsHtml;
            
            await loadRecords();
            if (isEditing && recordIdInput.value == id) resetForm();
        } catch (e) {
            showToast('Gagal menghapus data', 'error');
        }
    }
});

// ──────────────────────────────────────────────────────────────
// Initialization
// ──────────────────────────────────────────────────────────────
lucide.createIcons();
switchTab('classifications');
</script>
@endpush
