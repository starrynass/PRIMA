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
        @include('master.skala-predikat.partials.skala')
    </div>

    <div id="content-predikat" class="hidden">
        @include('master.skala-predikat.partials.predikat')
    </div>

    <script>
        function switchTab(tab) {
            const contentSkala = document.getElementById('content-skala');
            const contentPredikat = document.getElementById('content-predikat');
            const btnSkala = document.getElementById('btn-skala');
            const btnPredikat = document.getElementById('btn-predikat');

            if (tab === 'skala') {
                // Tampilkan Skala, Sembunyikan Predikat
                contentSkala.classList.remove('hidden');
                contentPredikat.classList.add('hidden');

                // Ubah gaya tombol aktif
                btnSkala.className = "px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600";
                btnPredikat.className = "px-4 py-2 font-semibold text-gray-500 hover:text-gray-700";
            } else {
                // Tampilkan Predikat, Sembunyikan Skala
                contentSkala.classList.add('hidden');
                contentPredikat.classList.remove('hidden');

                // Ubah gaya tombol aktif
                btnPredikat.className = "px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600";
                btnSkala.className = "px-4 py-2 font-semibold text-gray-500 hover:text-gray-700";
            }
        }
    </script>

</body>
</html>