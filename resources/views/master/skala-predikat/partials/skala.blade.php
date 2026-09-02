<div class="page-container mt-6">
    <!-- TOOLBAR SKALA -->
    <div class="toolbar-card">
        <div class="toolbar-header">
            <div class="toolbar-icon-wrapper">
                <svg class="icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="toolbar-title">Master Skala Nilai</h1>
                <p class="toolbar-subtitle">
                    Total Master Data: <span class="toolbar-subtitle-count">{{ count($skalaNilai) }} Data</span>
                </p>
            </div>
        </div>

        <div class="action-controls d-flex gap-2">
            <button type="button" id="btnRefreshSkala" onclick="window.location.reload()" class="btn btn-refresh">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Perbarui
            </button>

            <button type="button" id="btnAddSkala" onclick="openModalTambahSkala()" class="btn btn-add">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Data
            </button>

            <button type="button" class="btn btn-edit" id="btnEditSkala" onclick="openModalEditSkala()" disabled>
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Ubah
            </button>

            <button type="button" class="btn btn-delete" id="btnDeleteSkala" onclick="deleteSkalaRow()" disabled>
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus
            </button>
        </div>
    </div>

    <!-- TABEL DATA SKALA -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table" id="skalaTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 2.5rem;">PILIH</th>
                        <th class="text-center" style="width: 3.5rem;">#</th>
                        <th>KODE</th>
                        <th class="text-center">NAMA NILAI</th>
                        <th class="text-center">NILAI ANGKA</th>
                        <th>DESKRIPSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skalaNilai as $index => $item)
                        <tr onclick="selectRowSkala(this, '{{ $item->skala_id }}', '{{ $item->kode_nilai }}', '{{ $item->nama_nilai }}', '{{ $item->nilai_angka }}', '{{ $item->deskripsi }}')">
                            <td class="text-center" onclick="event.stopPropagation()">
                                <input type="radio" name="row_select_skala" value="{{ $item->skala_id }}" 
                                       onchange="onRadioChangeSkala(this, '{{ $item->skala_id }}', '{{ $item->kode_nilai }}', '{{ $item->nama_nilai }}', '{{ $item->nilai_angka }}', '{{ $item->deskripsi }}')"
                                       class="radio-input">
                            </td>
                            <td class="text-center font-mono text-muted">{{ sprintf('%02d', $index + 1) }}</td>
                            <td class="font-bold text-primary">{{ $item->kode_nilai }}</td>
                            <td class="text-center font-mono font-semibold">{{ $item->nama_nilai }}</td>
                            <td class="text-center font-mono font-semibold">{{ $item->nilai_angka }}</td>
                            <td class="font-bold text-primary">{{ $item->deskripsi }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 3rem; color: var(--text-muted);">
                                Belum ada data skala nilai yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL FORM SKALA -->
<div class="modal-backdrop" id="modalSkala" onclick="closeModalSkalaOnBackdrop(event)">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title-wrapper">
                <div class="modal-icon-badge" id="modalIconBadgeSkala">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="modalIconPathSkala" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="modal-title" id="modalTitleSkala">Tambah Skala Nilai</h3>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModalSkala()">
                <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="formSkala" method="POST" action="">
            @csrf
            <input type="hidden" id="formMethodSkala" name="_method" value="POST">
            <input type="hidden" id="skala_id" name="skala_id">

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="skala_kode_nilai">Kode Nilai</label>
                    <div class="input-addon-group">
                        <span class="input-addon">Kode</span>
                        <input type="text" id="skala_kode_nilai" name="kode_nilai" class="form-input" placeholder="contoh: A / B / C" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="skala_nama_nilai">Nama Nilai <span class="text-danger">*</span></label>
                        <div class="input-addon-group">
                            <span class="input-addon">Nama</span>
                            <input type="text" id="skala_nama_nilai" name="nama_nilai" class="form-input" placeholder="contoh: Sangat Baik" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="skala_nilai_angka">Nilai Angka <span class="text-danger">*</span></label>
                        <div class="input-addon-group">
                            <span class="input-addon">Nilai</span>
                            <input type="number" step="0.01" id="skala_nilai_angka" name="nilai_angka" class="form-input" placeholder="4.00" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="skala_deskripsi">Deskripsi <span class="text-danger">*</span></label>
                    <div class="input-addon-group">
                        <span class="input-addon">Deskripsi</span>
                        <input type="text" id="skala_deskripsi" name="deskripsi" class="form-input" placeholder="(opsional) Deskripsi tambahan" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-refresh" onclick="closeModalSkala()">Batal</button>
                <button type="submit" class="btn btn-add" id="btnSubmitModalSkala">
                    <svg class="icon-svg-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="btnSubmitTextSkala">Simpan Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DELETE SKALA -->
<div id="modalDeleteSkala" class="modal-backdrop-delete" onclick="closeModalDeleteSkalaOnBackdrop(event)" style="display: none;">
    <div class="delete-card">
        <div class="delete-accent-bar"></div>
        <div class="delete-card-body">
            <div class="delete-icon-wrapper">
                <svg class="delete-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="delete-title">Konfirmasi Hapus</h3>
            <p class="delete-message">
                Anda yakin akan menghapus skala <strong id="deleteSkalaName"></strong>?
            </p>
            <div class="delete-actions">
                <button type="button" class="btn-delete-confirm" onclick="confirmDeleteSkala()">HAPUS</button>
                <button type="button" class="btn-delete-cancel" onclick="closeModalDeleteSkala()">BATAL</button>
            </div>
        </div>
    </div>
</div>

<script>
    function selectRowSkala(tr, skala_id, kode_nilai, nama_nilai, nilai_angka, deskripsi) {
        const radio = tr.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
        onRadioChangeSkala(radio,  skala_id, kode_nilai, nama_nilai, nilai_angka, deskripsi);
    }

    function onRadioChangeSkala(radio, skala_id, kode_nilai, nama_nilai, nilai_angka, deskripsi) {
        // 1. Simpan data ke window.selectedData dengan nama property yang konsisten
        window.selectedSkalaData = { 
            skala_id: skala_id, 
            kode_nilai: kode_nilai, 
            nama_nilai: nama_nilai, 
            nilai_angka: nilai_angka, 
            deskripsi: deskripsi 
        };

        // 2. Aktifkan tombol Edit & Hapus khusus Skala
        const btnEdit = document.getElementById('btnEditSkala');
        const btnDelete = document.getElementById('btnDeleteSkala');

        if (btnEdit) {
            btnEdit.disabled = false;
            btnEdit.removeAttribute('disabled');
        }

        if (btnDelete) {
            btnDelete.disabled = false;
            btnDelete.removeAttribute('disabled');
        }

        const tbody = radio.closest('tbody');
        if (tbody) {
            tbody.querySelectorAll('tr').forEach(row => row.classList.remove('selected-row'));
        }
        radio.closest('tr').classList.add('selected-row');
    }

    // FUNGSI OPEN MODAL TAMBAH
    function openModalTambahSkala() {
        const form = document.getElementById('formSkala');
        if (form) form.reset();

        document.getElementById('skala_id').value = '';
        document.getElementById('formMethodSkala').value = 'POST';
        
        if (form) form.action = "{{ route('skala.store') }}";

        document.getElementById('modalTitleSkala').textContent = 'Tambah Skala Nilai';
        document.getElementById('btnSubmitTextSkala').textContent = 'Simpan Data';

        // Set Icon Modal ke Plus (+)
        const iconPath = document.getElementById('modalIconPathSkala');
        const iconBadge = document.getElementById('modalIconBadgeSkala');
        if (iconPath) iconPath.setAttribute('d', 'M12 4v16m8-8H4'); // <-- Langsung pakai string SVG plus
        if (iconBadge) {
            iconBadge.style.background = '';
            iconBadge.style.color = '';
        }

        // Reset Warna Tombol Submit (Hapus class btn-amber dari mode edit)
        const btnSubmit = document.getElementById('btnSubmitModalSkala');
        if (btnSubmit) {
            btnSubmit.classList.remove('btn-amber');
        }

        // Tampilkan Modal
        const modal = document.getElementById('modalSkala');
        if (modal) {
            modal.classList.add('active');
            modal.style.display = 'flex';
        }
    }

    
    // FUNGSI OPEN MODAL EDIT
    function openModalEditSkala() {
        if (!window.selectedSkalaData || !window.selectedSkalaData.skala_id) {
            alert('Silakan pilih data Skala terlebih dahulu!');
            return;
        }

        const form = document.getElementById('formSkala');
        
        document.getElementById('skala_id').value = window.selectedSkalaData.skala_id;
        document.getElementById('skala_kode_nilai').value = window.selectedSkalaData.kode_nilai || '';
        document.getElementById('skala_nama_nilai').value = window.selectedSkalaData.nama_nilai || '';
        document.getElementById('skala_nilai_angka').value = window.selectedSkalaData.nilai_angka || '';
        document.getElementById('skala_deskripsi').value = window.selectedSkalaData.deskripsi || '';

        document.getElementById('formMethodSkala').value = 'PUT';
        
        if (form) form.action = `{{ url('/master/skala') }}/${window.selectedSkalaData.skala_id}`;

        document.getElementById('modalTitleSkala').textContent = 'Ubah Skala Nilai';
        document.getElementById('btnSubmitTextSkala').textContent = 'Perbarui Data';

        const btnSubmit = document.getElementById('btnSubmitModalSkala');
        if (btnSubmit) {
            btnSubmit.classList.remove('btn-add');
            btnSubmit.classList.add('btn-amber');
        }

        const iconPath = document.getElementById('modalIconPathSkala');
        const iconBadge = document.getElementById('modalIconBadgeSkala');
        if (iconPath) iconPath.setAttribute('d', 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z'); // <-- Langsung pakai string SVG pencil
        if (iconBadge) {
            iconBadge.style.background = '#FEF3C7';
            iconBadge.style.color = '#D97706';
        }

        const modal = document.getElementById('modalSkala');
        if (modal) {
            modal.classList.add('active');
            modal.style.display = 'flex';
        }
    }

     function closeModalSkala() {
        const modal = document.getElementById('modalSkala');
        if (modal) {
            modal.classList.remove('active');
            modal.style.display = 'none';
        }
    }

    function closeModalSkalaOnBackdrop(event) {
        if (event.target.id === 'modalSkala') {
            closeModalSkala();
        }
    }

     // FUNGSI HAPUS DATA
    function deleteSkalaRow() {
        if (!window.selectedSkalaData || !window.selectedSkalaData.skala_id) {
            alert('Silakan pilih data predikat terlebih dahulu!');
            return;
        }

        const nameEl = document.getElementById('deleteSkalaName');
        if (nameEl) {
            nameEl.textContent = `"${window.selectedSkalaData.nama_nilai}"`; 
        }

        const modal = document.getElementById('modalDeleteSkala');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function confirmDeleteSkala() {
        if (!window.selectedSkalaData || !window.selectedSkalaData.skala_id) return;

        const deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = `{{ url('/master/skala') }}/${window.selectedSkalaData.skala_id}`;

        const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

        deleteForm.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;

        document.body.appendChild(deleteForm);
        deleteForm.submit();
    }

    function closeModalDeleteSkala() {
        const modal = document.getElementById('modalDeleteSkala');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function closeModalDeleteSkalaOnBackdrop(event) {
        if (event.target.id === 'modalDeleteSkala') {
            closeModalDeleteSkala();
        }
    }
   
</script>