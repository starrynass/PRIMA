<style>
    /* --- Global & Container --- */
    * {
        box-sizing: border-box;
    }

    .main-wrapper {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        -webkit-font-smoothing: antialiased;
        padding: 1.25rem;
        background-color: #f8fafc;
        min-height: 100vh;
        color: #334155;
    }

    /* --- Tab Navigation Styles --- */
    .tab-header {
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }

    .tab-nav {
        display: flex;
        gap: 0.5rem;
        margin-bottom: -1px; /* Menempelkan border tab ke border container */
    }

    .tab-btn {
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 0.75rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        outline: none;
        transition: color 0.15s ease, border-color 0.15s ease;
    }

    .tab-btn:hover {
        color: #1e293b;
        border-bottom-color: #cbd5e1;
    }

    .tab-btn.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
    }

    /* --- Tab Content Visibility --- */
    .tab-content {
        display: block;
    }

    .tab-content.hidden {
        display: none !important;
    }
</style>

@extends('layout.app')

@section('content')
<div class="main-wrapper">

    <!-- Tab Navigation Header -->
    <div class="tab-header">
        <nav class="tab-nav" aria-label="Tabs">
            <button type="button" 
                    id="btn-skala" 
                    onclick="switchTab('skala')" 
                    class="tab-btn active">
                Skala Nilai
            </button>
            
            <button type="button" 
                    id="btn-predikat" 
                    onclick="switchTab('predikat')" 
                    class="tab-btn">
                Predikat Nilai
            </button>
        </nav>
    </div>

    <!-- Content Sections -->
    <div id="content-skala" class="tab-content">
        @include('master.skala-predikat.partials.skala', ['skalaNilai' => $skalaNilai ?? []])
    </div>

    <div id="content-predikat" class="tab-content hidden">
        @include('master.skala-predikat.partials.predikat', ['predikatNilai' => $predikatNilai ?? []])
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Switch tab otomatis dari session controller (jika ada)
        const activeTab = "{{ session('active_tab') }}";
        if (activeTab === 'predikat') {
            switchTab('predikat');
        }
    });

    function switchTab(tabName) {
        const contentSkala = document.getElementById('content-skala');
        const contentPredikat = document.getElementById('content-predikat');
        const btnSkala = document.getElementById('btn-skala');
        const btnPredikat = document.getElementById('btn-predikat');

        if (tabName === 'predikat') {
            contentSkala.classList.add('hidden');
            contentPredikat.classList.remove('hidden');

            btnPredikat.classList.add('active');
            btnSkala.classList.remove('active');
        } else {
            contentPredikat.classList.add('hidden');
            contentSkala.classList.remove('hidden');

            btnSkala.classList.add('active');
            btnPredikat.classList.remove('active');
        }
    }
</script>
@endsection