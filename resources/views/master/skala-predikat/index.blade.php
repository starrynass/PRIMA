<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --font-main: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        
        /* Merah Maroon Theme Palette */
        --maroon-primary: #7A1C38;
        --maroon-hover: #5C1329;
        --maroon-soft: #FBF0F3;
        --maroon-border: #F3D5DD;
        --maroon-glow: rgba(122, 28, 56, 0.25);

        --text-dark: #0F172A;
        --text-muted: #64748b;
        --border-color: #E2E8F0;
    }

    * { box-sizing: border-box; }

    .main-wrapper {
        font-family: var(--font-main);
        -webkit-font-smoothing: antialiased;
        padding: 1.5rem;
        background-color: #ffffff;
        min-height: 100vh;
        color: var(--text-dark);
    }

    /* --- Unik Breadcrumb Header --- */
    .breadcrumb-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--maroon-primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .breadcrumb-title span {
        color: var(--text-muted);
        font-weight: 500;
    }

    .breadcrumb-badge {
        background-color: var(--maroon-soft);
        color: var(--maroon-primary);
        border: 1px solid var(--maroon-border);
        font-size: 0.725rem;
        font-weight: 800;
        padding: 0.15rem 0.6rem;
        border-radius: 9999px;
        letter-spacing: 0.03em;
    }

    /* --- Tab Navigation Container --- */
    .tab-header-wrapper {
        border-bottom: 2px solid var(--border-color);
        margin-bottom: 1.75rem;
        width: 100%;
        position: relative;
    }

    .tab-nav-container {
        display: flex;
        width: 100%;
    }

    /* --- Desain Tab Unik (Merah Maroon) --- */
    .tab-btn-item {
        flex: 1;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 1rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        outline: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        margin-bottom: -2px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .tab-btn-item:hover:not(.active) {
        color: var(--maroon-primary);
        background-color: var(--maroon-soft);
    }

    /* Tab Aktif Unik dengan Aksen Kapsul Glow */
    .tab-btn-item.active {
        color: var(--maroon-primary);
        border-bottom-color: var(--maroon-primary);
        font-weight: 800;
        background: linear-gradient(180deg, rgba(251, 240, 243, 0) 0%, rgba(251, 240, 243, 0.7) 100%);
    }

    .tab-btn-item.active::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 30%;
        right: 30%;
        height: 3px;
        background-color: var(--maroon-primary);
        box-shadow: 0 -2px 8px var(--maroon-glow);
        border-radius: 3px;
    }

    .tab-icon {
        width: 1.35rem;
        height: 1.35rem;
        fill: currentColor;
        transition: transform 0.2s ease;
    }

    .tab-btn-item:hover .tab-icon,
    .tab-btn-item.active .tab-icon {
        transform: translateY(-2px);
    }

    /* --- Visibility Konten Tab --- */
    .tab-content {
        display: block;
        animation: fadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .tab-content.hidden {
        display: none !important;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

@extends('layout.app')

@section('content')
<div class="main-wrapper">

    <div class="breadcrumb-title">
        Master Data <span>» Skala & Predikat Nilai</span>
        <span class="breadcrumb-badge">PRIMA</span>
    </div>

    <div class="tab-header-wrapper">
        <nav class="tab-nav-container" aria-label="Tabs">
            <button type="button" id="btn-skala" onclick="switchTab('skala', event)" class="tab-btn-item active">
                <svg class="tab-icon" viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
                <span>Skala Nilai</span>
            </button>
            
            <button type="button" id="btn-predikat" onclick="switchTab('predikat', event)" class="tab-btn-item">
                <svg class="tab-icon" viewBox="0 0 24 24">
                    <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
                </svg>
                <span>Predikat Nilai</span>
            </button>
        </nav>
    </div>

    <div id="content-skala" class="tab-content">
        @include('master.skala-predikat.partials.skala', ['skalaNilai' => $skalaNilai ?? []])
    </div>

    <div id="content-predikat" class="tab-content hidden">
        @include('master.skala-predikat.partials.predikat', ['predikatNilai' => $predikatNilai ?? []])
    </div>

</div>

<script>
    window.PATH_ICON_PLUS = "M12 4v16m8-8H4";
    window.PATH_ICON_PENCIL = "M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z";
    // 1. Fungsi Reset Pilihan saat Pindah Tab (Spesifik Per Tab)
    function resetTabSelection() {
        window.selectedSkalaData = null;
        window.selectedPredikatData = null;

        // Uncheck semua radio button
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.checked = false;
        });

        // Hapus highlight baris terpilih
        document.querySelectorAll('tr.selected-row').forEach(row => {
            row.classList.remove('selected-row');
        });

        // Disable tombol Ubah & Hapus untuk SKALA
        const btnEditSkala = document.getElementById('btnEditSkala');
        const btnDeleteSkala = document.getElementById('btnDeleteSkala');
        if (btnEditSkala) btnEditSkala.disabled = true;
        if (btnDeleteSkala) btnDeleteSkala.disabled = true;

        // Disable tombol Ubah & Hapus untuk PREDIKAT
        const btnEditPredikat = document.getElementById('btnEditPredikat');
        const btnDeletePredikat = document.getElementById('btnDeletePredikat');
        if (btnEditPredikat) btnEditPredikat.disabled = true;
        if (btnDeletePredikat) btnDeletePredikat.disabled = true;
    }

    // 2. Fungsi Pindah Tab
    function switchTab(tabName, evt) {
        if (evt) evt.preventDefault();

        // Reset data pilihan setiap ganti tab
        resetTabSelection();

        const contentSkala = document.getElementById('content-skala');
        const contentPredikat = document.getElementById('content-predikat');
        const btnSkala = document.getElementById('btn-skala');
        const btnPredikat = document.getElementById('btn-predikat');

        if (!contentSkala || !contentPredikat) return;

        if (tabName === 'predikat') {
            contentSkala.classList.add('hidden');
            contentPredikat.classList.remove('hidden');

            if (btnSkala && btnPredikat) {
                btnSkala.classList.remove('active');
                btnPredikat.classList.add('active');
            }
        } else {
            contentPredikat.classList.add('hidden');
            contentSkala.classList.remove('hidden');

            if (btnSkala && btnPredikat) {
                btnPredikat.classList.remove('active');
                btnSkala.classList.add('active');
            }
        }
    }

    // Run saat dokumen siap
    document.addEventListener('DOMContentLoaded', function () {
        const activeTab = "{{ session('active_tab') }}";
        if (activeTab === 'predikat') {
            switchTab('predikat');
        }
    });
</script>
@endsection