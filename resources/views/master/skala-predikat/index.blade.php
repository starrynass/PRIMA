<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skala & Predikat Nilai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-50">

    <div class="flex gap-2 border-b border-gray-200 pb-2 mb-6">
        <button type="button" 
                id="btn-skala" 
                onclick="switchTab('skala')" 
                class="px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600">
            Skala Nilai
        </button>
        
        <button type="button" 
                id="btn-predikat" 
                onclick="switchTab('predikat')" 
                class="px-4 py-2 font-semibold text-gray-500 hover:text-gray-700">
            Predikat Nilai
        </button>
    </div>

    <div id="content-skala">
    @include('master.skala-predikat.partials.skala', ['skalaNilai' => $skalaNilai ?? []])
    </div>

    <div id="content-predikat" class="hidden">
        @include('master.skala-predikat.partials.predikat', ['predikatNilai' => $predikatNilai])
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cek apakah ada session active_tab dari controller
        const activeTab = "{{ session('active_tab') }}";

        if (activeTab === 'predikat') {
            switchTab('predikat');
        }
    });

    function switchTab(tabName) {
        const contentSkala = document.getElementById('content-skala');
        const contentPredikat = document.getElementById('content-predikat');
        const tabSkalaBtn = document.getElementById('tab-skala-btn');
        const tabPredikatBtn = document.getElementById('tab-predikat-btn');

        if (tabName === 'predikat') {
            contentSkala.classList.add('hidden');
            contentPredikat.classList.remove('hidden');
            
            // Aktifkan styling tab predikat
            tabPredikatBtn.classList.add('border-blue-600', 'text-blue-600');
            tabSkalaBtn.classList.remove('border-blue-600', 'text-blue-600');
        } else {
            contentPredikat.classList.add('hidden');
            contentSkala.classList.remove('hidden');

            // Aktifkan styling tab skala
            tabSkalaBtn.classList.add('border-blue-600', 'text-blue-600');
            tabPredikatBtn.classList.remove('border-blue-600', 'text-blue-600');
        }
    }
</script>

</body>
</html>