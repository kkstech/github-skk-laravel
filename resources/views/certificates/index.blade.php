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
                    <select id="provinsi" required>
                        <option value="">Pilih Provinsi</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="kabupaten">Kabupaten / Kota</label>
                    <select id="kabupaten" required disabled>
                        <option value="">Pilih Kabupaten / Kota</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="klasifikasi">Klasifikasi</label>
                    <select id="klasifikasi" required>
                        <option value="">Pilih Klasifikasi</option>
                    </select>
                </div>

                <div class="input-group full-width">
                    <label for="subklasifikasi">Subklasifikasi</label>
                    <select id="subklasifikasi" required disabled>
                        <option value="">Pilih Subklasifikasi</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="kualifikasi">Kualifikasi</label>
                    <select id="kualifikasi" required>
                        <option value="">Pilih Kualifikasi</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="kode_jabatan">Kode Jabatan Kerja</label>
                    <input type="text" id="kode_jabatan" placeholder="Terisi otomatis" required readonly>
                </div>

                <div class="input-group full-width">
                    <label for="jabatan_kerja">Jabatan Kerja</label>
                    <select id="jabatan_kerja" required disabled>
                        <option value="">Pilih Jabatan Kerja</option>
                    </select>
                </div>

                <div class="input-group full-width">
                    <label for="nomor_registrasi">Nomor Registrasi</label>
                    <input type="text" id="nomor_registrasi" placeholder="Contoh: F 1993 15056 2026 0219554 SI 09" required autocomplete="off">
                </div>

                <div class="input-group full-width">
                    <label for="nama_lsp">Nama LSP</label>
                    <select id="nama_lsp" required>
                        <option value="">Pilih LSP</option>
                    </select>
                </div>

                <div class="input-group full-width">
                    <label for="nama_asosiasi">Nama Asosiasi</label>
                    <select id="nama_asosiasi" required>
                        <option value="">Pilih Asosiasi</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="tanggal_ditetapkan">Tanggal Ditetapkan</label>
                    <input type="datetime-local" id="tanggal_ditetapkan" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label for="tanggal_berlaku">Berlaku Sampai</label>
                    <input type="datetime-local" id="tanggal_berlaku" required autocomplete="off">
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
            <div style="margin-top: 1rem; display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap;">
                <a href="#" id="open-link-btn" target="_blank" class="btn btn-primary" style="text-decoration: none; display: inline-flex; width: auto; padding: 0.75rem 1.5rem; justify-content: center; align-items: center;">
                    <i data-lucide="external-link"></i> Buka di Tab Baru
                </a>
                <button id="download-qr-btn" class="btn btn-secondary" style="display: inline-flex; width: auto; padding: 0.75rem 1.5rem; justify-content: center; align-items: center;">
                    <i data-lucide="download"></i> Download QR
                </button>
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
    show:    (nomor_registrasi) => `{{ url('/certificates') }}/${encodeURIComponent(nomor_registrasi)}`,
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
const downloadQrBtn = document.getElementById('download-qr-btn');
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
let activeCertNameForQr = '';

// ──────────────────────────────────────────────────────────────
// Region API (EMSifa API Wilayah Indonesia)
// ──────────────────────────────────────────────────────────────
const REGION_API = {
    provinces: 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
    regencies: (provId) => `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`
};

const provSelect = document.getElementById('provinsi');
const kabSelect = document.getElementById('kabupaten');

let provincesData = [];

// Format ALL CAPS to Title Case
function formatTitleCase(str) {
    return str.replace(/\w\S*/g, (txt) => txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase());
}

// Fetch and load provinces
async function loadProvinces() {
    try {
        const res = await fetch(REGION_API.provinces);
        provincesData = await res.json();
        
        provSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
        provincesData.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.name;
            opt.dataset.id = p.id;
            opt.textContent = formatTitleCase(p.name);
            provSelect.appendChild(opt);
        });
    } catch (e) {
        showToast('Gagal memuat daftar provinsi', 'error');
    }
}

// Fetch and load regencies based on selected province
async function loadRegencies(provinceName, selectVal = '') {
    const selectedOpt = Array.from(provSelect.options).find(opt => opt.value === provinceName);
    if (!selectedOpt || !selectedOpt.dataset.id) {
        kabSelect.innerHTML = '<option value="">Pilih Kabupaten / Kota</option>';
        kabSelect.disabled = true;
        return;
    }

    const provId = selectedOpt.dataset.id;
    kabSelect.disabled = true;
    kabSelect.innerHTML = '<option value="">Memuat...</option>';

    try {
        const res = await fetch(REGION_API.regencies(provId));
        const regencies = await res.json();

        kabSelect.innerHTML = '<option value="">Pilih Kabupaten / Kota</option>';
        regencies.forEach(r => {
            const opt = document.createElement('option');
            opt.value = r.name;
            opt.dataset.id = r.id;
            opt.textContent = formatTitleCase(r.name);
            kabSelect.appendChild(opt);
        });

        kabSelect.disabled = false;

        if (selectVal) {
            selectOptionCaseInsensitive(kabSelect, selectVal);
        }
    } catch (e) {
        showToast('Gagal memuat daftar kabupaten/kota', 'error');
        kabSelect.innerHTML = '<option value="">Gagal memuat data</option>';
    }
}

// Case-insensitive selection utility
function selectOptionCaseInsensitive(selectEl, value) {
    const valUpper = value.toUpperCase();
    const opt = Array.from(selectEl.options).find(o => o.value.toUpperCase() === valUpper);
    if (opt) {
        selectEl.value = opt.value;
        return opt;
    }
    return null;
}

// Listen to province changes
provSelect.addEventListener('change', (e) => {
    loadRegencies(e.target.value);
});

// ──────────────────────────────────────────────────────────────
// Master Data API & Cascade
// ──────────────────────────────────────────────────────────────
const klasifikasiSelect = document.getElementById('klasifikasi');
const subklasifikasiSelect = document.getElementById('subklasifikasi');
const kualifikasiSelect = document.getElementById('kualifikasi');
const kodeJabatanInput = document.getElementById('kode_jabatan');
const jabatanKerjaSelect = document.getElementById('jabatan_kerja');
const lspSelect = document.getElementById('nama_lsp');
const asosiasiSelect = document.getElementById('nama_asosiasi');

let classificationsData = [];
let subclassificationsData = [];
let workPositionsData = [];
let qualificationsData = [];
let lspsData = [];
let associationsData = [];

function populateSelect(selectEl, data, placeholder) {
    selectEl.innerHTML = `<option value="">${placeholder}</option>`;
    data.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.nama;
        opt.dataset.id = item.id;
        opt.textContent = item.nama;
        selectEl.appendChild(opt);
    });
}

async function loadMasterData() {
    try {
        const [clsRes, subRes, wpRes, qualRes, lspRes, assocRes] = await Promise.all([
            fetch('{{ url("/api/master/classifications") }}'),
            fetch('{{ url("/api/master/subclassifications") }}'),
            fetch('{{ url("/api/master/work-positions") }}'),
            fetch('{{ url("/api/master/qualifications") }}'),
            fetch('{{ url("/api/master/lsps") }}'),
            fetch('{{ url("/api/master/associations") }}')
        ]);

        classificationsData = await clsRes.json();
        subclassificationsData = await subRes.json();
        workPositionsData = await wpRes.json();
        qualificationsData = await qualRes.json();
        lspsData = await lspRes.json();
        associationsData = await assocRes.json();

        // Populate independent dropdowns
        populateSelect(klasifikasiSelect, classificationsData, 'Pilih Klasifikasi');
        populateSelect(kualifikasiSelect, qualificationsData, 'Pilih Kualifikasi');
        populateSelect(lspSelect, lspsData, 'Pilih LSP');
        populateSelect(asosiasiSelect, associationsData, 'Pilih Asosiasi');
    } catch (e) {
        showToast('Gagal memuat master data', 'error');
    }
}

// Cascade Logic
klasifikasiSelect.addEventListener('change', (e) => {
    const selectedVal = e.target.value;
    const selectedOpt = e.target.options[e.target.selectedIndex];
    
    subklasifikasiSelect.innerHTML = '<option value="">Pilih Subklasifikasi</option>';
    subklasifikasiSelect.disabled = true;
    jabatanKerjaSelect.innerHTML = '<option value="">Pilih Jabatan Kerja</option>';
    jabatanKerjaSelect.disabled = true;
    kodeJabatanInput.value = '';

    if (!selectedVal || !selectedOpt || !selectedOpt.dataset.id) return;

    const classId = selectedOpt.dataset.id;
    const filtered = subclassificationsData.filter(s => s.classification_id == classId);
    
    populateSelect(subklasifikasiSelect, filtered, 'Pilih Subklasifikasi');
    subklasifikasiSelect.disabled = false;
});

subklasifikasiSelect.addEventListener('change', (e) => {
    const selectedVal = e.target.value;
    const selectedOpt = e.target.options[e.target.selectedIndex];

    jabatanKerjaSelect.innerHTML = '<option value="">Pilih Jabatan Kerja</option>';
    jabatanKerjaSelect.disabled = true;
    kodeJabatanInput.value = '';

    if (!selectedVal || !selectedOpt || !selectedOpt.dataset.id) return;

    const subId = selectedOpt.dataset.id;
    const filtered = workPositionsData.filter(w => w.subclassification_id == subId);

    jabatanKerjaSelect.innerHTML = '<option value="">Pilih Jabatan Kerja</option>';
    filtered.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.nama;
        opt.dataset.id = item.id;
        opt.dataset.kode = item.kode_jabatan;
        opt.textContent = `${item.nama} (${item.kode_jabatan})`;
        jabatanKerjaSelect.appendChild(opt);
    });

    jabatanKerjaSelect.disabled = false;
});

jabatanKerjaSelect.addEventListener('change', (e) => {
    const selectedVal = e.target.value;
    const selectedOpt = e.target.options[e.target.selectedIndex];

    if (!selectedVal || !selectedOpt || !selectedOpt.dataset.kode) {
        kodeJabatanInput.value = '';
        return;
    }

    kodeJabatanInput.value = selectedOpt.dataset.kode;
});

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
    kabSelect.innerHTML = '<option value="">Pilih Kabupaten / Kota</option>';
    kabSelect.disabled = true;
    subklasifikasiSelect.innerHTML = '<option value="">Pilih Subklasifikasi</option>';
    subklasifikasiSelect.disabled = true;
    jabatanKerjaSelect.innerHTML = '<option value="">Pilih Jabatan Kerja</option>';
    jabatanKerjaSelect.disabled = true;
    kodeJabatanInput.value = '';
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
        submitBtn.innerHTML = btnOrig;
    } finally {
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
        
        // Populate text inputs & dates
        ['nama', 'nomor_registrasi', 'tanggal_ditetapkan', 'tanggal_berlaku'].forEach(f => {
            let val = cert[f] || '';
            if ((f === 'tanggal_ditetapkan' || f === 'tanggal_berlaku') && val) {
                // Format to 'YYYY-MM-DDTHH:mm' for datetime-local input
                val = val.replace(' ', 'T').substring(0, 16);
            }
            document.getElementById(f).value = val;
        });

        // Set province and asynchronously load & set regencies
        const provOpt = selectOptionCaseInsensitive(provSelect, cert.provinsi || '');
        if (provOpt) {
            await loadRegencies(provOpt.value, cert.kabupaten);
        } else {
            kabSelect.innerHTML = '<option value="">Pilih Kabupaten / Kota</option>';
            kabSelect.disabled = true;
        }

        // Set independent selects: kualifikasi, nama_lsp, nama_asosiasi
        selectOptionCaseInsensitive(kualifikasiSelect, cert.kualifikasi || '');
        selectOptionCaseInsensitive(lspSelect, cert.nama_lsp || '');
        selectOptionCaseInsensitive(asosiasiSelect, cert.nama_asosiasi || '');

        // Set dependent selects: klasifikasi, subklasifikasi, jabatan_kerja
        const classOpt = selectOptionCaseInsensitive(klasifikasiSelect, cert.klasifikasi || '');
        if (classOpt && classOpt.dataset.id) {
            const classId = classOpt.dataset.id;
            const filteredSubs = subclassificationsData.filter(s => s.classification_id == classId);
            populateSelect(subklasifikasiSelect, filteredSubs, 'Pilih Subklasifikasi');
            subklasifikasiSelect.disabled = false;

            const subOpt = selectOptionCaseInsensitive(subklasifikasiSelect, cert.subklasifikasi || '');
            if (subOpt && subOpt.dataset.id) {
                const subId = subOpt.dataset.id;
                const filteredWps = workPositionsData.filter(w => w.subclassification_id == subId);
                
                jabatanKerjaSelect.innerHTML = '<option value="">Pilih Jabatan Kerja</option>';
                filteredWps.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.nama;
                    opt.dataset.id = item.id;
                    opt.dataset.kode = item.kode_jabatan;
                    opt.textContent = `${item.nama} (${item.kode_jabatan})`;
                    jabatanKerjaSelect.appendChild(opt);
                });
                jabatanKerjaSelect.disabled = false;

                const wpOpt = selectOptionCaseInsensitive(jabatanKerjaSelect, cert.jabatan_kerja || '');
                if (wpOpt && wpOpt.dataset.kode) {
                    kodeJabatanInput.value = wpOpt.dataset.kode;
                } else {
                    kodeJabatanInput.value = cert.kode_jabatan || '';
                }
            } else {
                jabatanKerjaSelect.innerHTML = '<option value="">Pilih Jabatan Kerja</option>';
                jabatanKerjaSelect.disabled = true;
                kodeJabatanInput.value = '';
            }
        } else {
            subklasifikasiSelect.innerHTML = '<option value="">Pilih Subklasifikasi</option>';
            subklasifikasiSelect.disabled = true;
            jabatanKerjaSelect.innerHTML = '<option value="">Pilih Jabatan Kerja</option>';
            jabatanKerjaSelect.disabled = true;
            kodeJabatanInput.value = '';
        }

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
        const certUrl = ROUTES.show(cert.nomor_registrasi);
        activeCertNameForQr = cert.nama;

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
// Download QR Code
// ──────────────────────────────────────────────────────────────
function downloadQRCode(name) {
    const canvas = qrContainer.querySelector('canvas');
    let dataUrl = '';
    if (canvas) {
        dataUrl = canvas.toDataURL('image/png');
    } else {
        const img = qrContainer.querySelector('img');
        if (img) {
            dataUrl = img.src;
        }
    }
    
    if (dataUrl) {
        const link = document.createElement('a');
        link.href = dataUrl;
        link.download = `QR_Code_${name.replace(/[^a-zA-Z0-9]/g, '_')}.png`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } else {
        showToast('Gagal mengunduh QR Code', 'error');
    }
}

downloadQrBtn.addEventListener('click', () => {
    downloadQRCode(activeCertNameForQr);
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
loadProvinces();
loadMasterData();
loadCerts();
</script>
@endpush
