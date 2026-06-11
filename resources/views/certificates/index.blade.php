@extends('layouts.app')

@section('title', 'SKK Manager')

@section('content')
<header class="app-header">
    <div class="header-title">
        <div class="icon-wrapper">
            <i data-lucide="award"></i>
        </div>
        <div>
            <h1>SKK Manager</h1>
            <p>Sistem Manajemen Sertifikat Kompetensi Kerja</p>
        </div>
    </div>
</header>

<main class="app-main">
    <!-- Left Side: Form -->
    <section class="form-section glass-panel">
        <div class="section-header">
            <h2 id="form-title">Tambah Sertifikat Baru</h2>
            <i data-lucide="file-plus" id="form-icon"></i>
        </div>

        <form id="certificate-form">
            @csrf
            <input type="hidden" id="cert-id" value="">

            <div class="form-grid">
                <div class="input-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" placeholder="Contoh: SITI NUR AMALIA" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label for="provinsi">Provinsi</label>
                    <input type="text" id="provinsi" placeholder="Contoh: Sulawesi Tenggara" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label for="kabupaten">Kabupaten / Kota</label>
                    <input type="text" id="kabupaten" placeholder="Contoh: Kota Bau Bau" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label for="klasifikasi">Klasifikasi</label>
                    <input type="text" id="klasifikasi" placeholder="Contoh: SIPIL" required autocomplete="off">
                </div>

                <div class="input-group full-width">
                    <label for="subklasifikasi">Subklasifikasi</label>
                    <input type="text" id="subklasifikasi" placeholder="Contoh: Ahli Muda Teknik Pantai" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label for="kualifikasi">Kualifikasi</label>
                    <input type="text" id="kualifikasi" placeholder="Contoh: Ahli" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label for="kode_jabatan">Kode Jabatan Kerja</label>
                    <input type="text" id="kode_jabatan" placeholder="Contoh: SI091013" required autocomplete="off">
                </div>

                <div class="input-group full-width">
                    <label for="jabatan_kerja">Jabatan Kerja</label>
                    <input type="text" id="jabatan_kerja" placeholder="Contoh: Ahli Muda Teknik Pantai" required autocomplete="off">
                </div>

                <div class="input-group full-width">
                    <label for="nomor_registrasi">Nomor Registrasi</label>
                    <input type="text" id="nomor_registrasi" placeholder="Contoh: F 1993 15056 2026 0219554 SI 09" required autocomplete="off">
                </div>

                <div class="input-group full-width">
                    <label for="nama_lsp">Nama LSP</label>
                    <input type="text" id="nama_lsp" placeholder="Contoh: LSP ASTEKINDO KONSTRUKSI MANDIRI" required autocomplete="off">
                </div>

                <div class="input-group full-width">
                    <label for="nama_asosiasi">Nama Asosiasi</label>
                    <input type="text" id="nama_asosiasi" placeholder="Contoh: PERPAKOM" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label for="tanggal_ditetapkan">Tanggal Ditetapkan</label>
                    <input type="text" id="tanggal_ditetapkan" placeholder="YYYY-MM-DD HH:MM:SS" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label for="tanggal_berlaku">Berlaku Sampai</label>
                    <input type="text" id="tanggal_berlaku" placeholder="YYYY-MM-DD HH:MM:SS" required autocomplete="off">
                </div>
            </div>

            <div class="form-actions">
                <button type="button" id="cancel-btn" class="btn btn-secondary hidden">
                    <i data-lucide="x"></i> Batal
                </button>
                <button type="submit" id="submit-btn" class="btn btn-primary">
                    <i data-lucide="save"></i> Simpan Sertifikat
                </button>
            </div>
        </form>
    </section>

    <!-- Right Side: List -->
    <section class="list-section glass-panel">
        <div class="section-header">
            <h2>Daftar Sertifikat</h2>
            <span class="badge" id="cert-count">{{ count($certificates) }}</span>
        </div>

        <div class="search-bar">
            <div class="input-wrapper">
                <i data-lucide="search" class="input-icon"></i>
                <input type="text" id="search" class="search-input" placeholder="Cari nama, registrasi, atau LSP...">
            </div>
        </div>

        <div class="certs-container">
            <div class="contacts-grid" id="certs-grid">
                {{-- Cards injected by JS --}}
            </div>

            <div id="empty-state" class="empty-state hidden">
                <div class="empty-icon-wrapper">
                    <i data-lucide="inbox"></i>
                </div>
                <h3>Belum Ada Data</h3>
                <p>Tambahkan sertifikat baru untuk memulai.</p>
            </div>
        </div>
    </section>
</main>

<!-- QR Code Modal -->
<div id="qr-modal" class="modal-overlay hidden">
    <div class="modal-content glass-panel">
        <div class="modal-header">
            <h3>QR Code Sertifikat</h3>
            <button id="close-modal" class="btn-icon">
                <i data-lucide="x"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="qrcode-container"></div>
            <p class="qr-instruction">Scan menggunakan kamera HP untuk melihat desain sertifikat.</p>
            <div style="margin-top: 1rem;">
                <a href="#" id="open-link-btn" target="_blank" class="btn btn-primary" style="text-decoration: none; display: inline-flex; width: auto; padding: 0.75rem 1.5rem;">
                    <i data-lucide="external-link"></i> Buka di Tab Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ──────────────────────────────────────────────────────────────
// Config
// ──────────────────────────────────────────────────────────────
const ROUTES = {
    index:   '{{ route('certificates.index') }}',
    store:   '{{ route('certificates.store') }}',
    show:    (id) => `{{ url('/certificates') }}/${id}`,
    update:  (id) => `{{ url('/certificates') }}/${id}`,
    destroy: (id) => `{{ url('/certificates') }}/${id}`,
};

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ──────────────────────────────────────────────────────────────
// DOM Refs
// ──────────────────────────────────────────────────────────────
const certForm    = document.getElementById('certificate-form');
const certIdInput = document.getElementById('cert-id');
const certsGrid   = document.getElementById('certs-grid');
const emptyState  = document.getElementById('empty-state');
const certCount   = document.getElementById('cert-count');
const searchInput = document.getElementById('search');
const formTitle   = document.getElementById('form-title');
const formIcon    = document.getElementById('form-icon');
const cancelBtn   = document.getElementById('cancel-btn');
const submitBtn   = document.getElementById('submit-btn');
const qrModal     = document.getElementById('qr-modal');
const closeModal  = document.getElementById('close-modal');
const qrContainer = document.getElementById('qrcode-container');
const openLinkBtn = document.getElementById('open-link-btn');
const toast       = document.getElementById('toast');
const toastMsg    = document.getElementById('toast-message');
const toastIcon   = document.getElementById('toast-icon');

const FIELDS = [
    'nama','provinsi','kabupaten','klasifikasi','subklasifikasi',
    'kualifikasi','kode_jabatan','jabatan_kerja','nomor_registrasi',
    'nama_lsp','nama_asosiasi','tanggal_ditetapkan','tanggal_berlaku'
];

let isEditing    = false;
let qrInstance   = null;
let certificates = [];

// ──────────────────────────────────────────────────────────────
// Toast
// ──────────────────────────────────────────────────────────────
function showToast(message, type = 'success') {
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
// Render
// ──────────────────────────────────────────────────────────────
function renderCerts(certs) {
    certsGrid.innerHTML = '';
    certCount.textContent = certs.length;

    if (certs.length === 0) {
        emptyState.classList.remove('hidden');
        return;
    }

    emptyState.classList.add('hidden');

    certs.forEach(cert => {
        const card = document.createElement('div');
        card.className = 'contact-card';
        card.innerHTML = `
            <div class="contact-name">${escHtml(cert.nama)}</div>
            <div class="contact-detail"><strong>Reg:</strong> ${escHtml(cert.nomor_registrasi)}</div>
            <div class="contact-detail"><strong>LSP:</strong> ${escHtml(cert.nama_lsp)}</div>
            <div class="contact-detail"><strong>Jabatan:</strong> ${escHtml(cert.jabatan_kerja)}</div>
            <div class="card-actions">
                <button type="button" class="btn-icon btn-qr" data-id="${cert.id}">
                    <i data-lucide="qr-code"></i> QR
                </button>
                <button type="button" class="btn-icon btn-edit" data-id="${cert.id}">
                    <i data-lucide="edit-2"></i> Edit
                </button>
                <button type="button" class="btn-icon btn-delete" data-id="${cert.id}">
                    <i data-lucide="trash-2"></i> Hapus
                </button>
            </div>
        `;
        certsGrid.appendChild(card);
    });

    lucide.createIcons();
}

function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
}

// ──────────────────────────────────────────────────────────────
// API Calls
// ──────────────────────────────────────────────────────────────
async function loadCerts(search = '') {
    try {
        const url = search ? `${ROUTES.index}?search=${encodeURIComponent(search)}` : ROUTES.index;
        const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        certificates = await res.json();
        renderCerts(certificates);
    } catch (e) {
        showToast('Gagal memuat data', 'error');
    }
}

async function storeCert(data) {
    const res = await fetch(ROUTES.store, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: JSON.stringify(data)
    });

    const json = await res.json();
    if (!res.ok) throw json;
    return json;
}

async function updateCert(id, data) {
    const res = await fetch(ROUTES.update(id), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: JSON.stringify(data)
    });

    const json = await res.json();
    if (!res.ok) throw json;
    return json;
}

async function destroyCert(id) {
    const res = await fetch(ROUTES.destroy(id), {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        }
    });

    const json = await res.json();
    if (!res.ok) throw json;
    return json;
}

// ──────────────────────────────────────────────────────────────
// Form
// ──────────────────────────────────────────────────────────────
function resetForm() {
    certForm.reset();
    certIdInput.value = '';
    isEditing = false;
    formTitle.textContent = 'Tambah Sertifikat Baru';
    formIcon.setAttribute('data-lucide', 'file-plus');
    submitBtn.innerHTML = '<i data-lucide="save"></i> Simpan Sertifikat';
    cancelBtn.classList.add('hidden');
    lucide.createIcons();
}

certForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const data = {};
    let valid = true;

    FIELDS.forEach(f => {
        const val = document.getElementById(f).value.trim();
        if (!val) valid = false;
        data[f] = val;
    });

    if (!valid) {
        showToast('Mohon isi semua data', 'error');
        return;
    }

    const id = certIdInput.value;
    const btnOrig = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="loading-spinner"></span>';
    submitBtn.disabled = true;

    try {
        if (isEditing) {
            await updateCert(id, data);
            showToast('Sertifikat berhasil diupdate');
        } else {
            await storeCert(data);
            showToast('Sertifikat berhasil ditambahkan');
        }

        resetForm();
        loadCerts(searchInput.value);
    } catch (err) {
        const msg = err?.message || (err?.errors ? Object.values(err.errors)[0][0] : 'Terjadi kesalahan');
        showToast(msg, 'error');
    } finally {
        submitBtn.innerHTML = btnOrig;
        submitBtn.disabled = false;
        lucide.createIcons();
    }
});

cancelBtn.addEventListener('click', resetForm);

// ──────────────────────────────────────────────────────────────
// Card Actions (event delegation)
// ──────────────────────────────────────────────────────────────
certsGrid.addEventListener('click', async (e) => {
    const btn = e.target.closest('button[data-id]');
    if (!btn) return;

    const id   = btn.dataset.id;
    const cert = certificates.find(c => c.id == id);

    if (btn.classList.contains('btn-edit')) {
        if (!cert) return;
        certIdInput.value = cert.id;
        FIELDS.forEach(f => {
            document.getElementById(f).value = cert[f] || '';
        });
        isEditing = true;
        formTitle.textContent = 'Edit Sertifikat';
        formIcon.setAttribute('data-lucide', 'edit');
        submitBtn.innerHTML = '<i data-lucide="refresh-cw"></i> Update Sertifikat';
        cancelBtn.classList.remove('hidden');
        lucide.createIcons();
        document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
    }

    if (btn.classList.contains('btn-delete')) {
        if (!confirm('Yakin ingin menghapus sertifikat ini?')) return;
        try {
            await destroyCert(id);
            showToast('Sertifikat dihapus');
            loadCerts(searchInput.value);
            if (isEditing && certIdInput.value == id) resetForm();
        } catch {
            showToast('Gagal menghapus sertifikat', 'error');
        }
    }

    if (btn.classList.contains('btn-qr')) {
        if (!cert) return;
        const certUrl = ROUTES.show(cert.id);

        qrContainer.innerHTML = '';
        if (qrInstance) { qrInstance = null; }

        qrInstance = new QRCode(qrContainer, {
            text: certUrl,
            width: 200, height: 200,
            colorDark: '#000000', colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.L
        });

        openLinkBtn.href = certUrl;
        qrModal.classList.remove('hidden');
    }
});

// ──────────────────────────────────────────────────────────────
// Search
// ──────────────────────────────────────────────────────────────
let searchTimeout;
searchInput.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => loadCerts(e.target.value), 300);
});

// ──────────────────────────────────────────────────────────────
// Modal
// ──────────────────────────────────────────────────────────────
closeModal.addEventListener('click', () => qrModal.classList.add('hidden'));
qrModal.addEventListener('click', (e) => { if (e.target === qrModal) qrModal.classList.add('hidden'); });

// ──────────────────────────────────────────────────────────────
// Init
// ──────────────────────────────────────────────────────────────
lucide.createIcons();
loadCerts();
</script>
@endpush
