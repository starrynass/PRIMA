<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard DP3 - Aplikasi Penilaian Pegawai</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-slate-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 border-r border-slate-700 flex flex-col justify-between">
            <div>
                <div class="p-6 flex items-center gap-3 border-b border-slate-700">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-white">P</div>
                    <span class="text-xl font-bold tracking-wider text-white">PRIMA <span class="text-xs text-indigo-400">DP3</span></span>
                </div>

                <nav class="mt-6 px-4 space-y-2">
                    <a href="#" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white rounded-xl font-medium shadow-lg shadow-indigo-600/30">
                        <i class="fas fa-chart-pie w-5"></i> Dashboard
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-700/50 hover:text-slate-200 rounded-xl transition">
                        <i class="fas fa-users w-5"></i> Data Pegawai
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-700/50 hover:text-slate-200 rounded-xl transition">
                        <i class="fas fa-calendar-alt w-5"></i> Periode Penilaian
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-700/50 hover:text-slate-200 rounded-xl transition">
                        <i class="fas fa-clipboard-check w-5"></i> Transaksi DP3
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-700">
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center font-semibold text-slate-300">A</div>
                    <div>
                        <p class="text-sm font-medium text-white">Administrator</p>
                        <p class="text-xs text-slate-400">admin@prima.id</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-8">
            
            <!-- Top Header -->
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-white">Dashboard Penilaian Pegawai (DP3)</h1>
                    <p class="text-slate-400 text-sm">Selamat datang di sistem manajemen evaluasi & kinerja pegawai.</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-xs font-semibold">
                        DB Sync: Connected
                    </span>
                </div>
            </header>

            <!-- Metrics Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-slate-800/80 border border-slate-700 p-6 rounded-2xl backdrop-blur-xl">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Pegawai Aktif</p>
                            <h3 class="text-3xl font-bold text-white mt-1">{{ $totalPegawai }}</h3>
                        </div>
                        <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl">
                            <i class="fas fa-user-tie text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400">Total pegawai yang terdaftar di sistem</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-slate-800/80 border border-slate-700 p-6 rounded-2xl backdrop-blur-xl">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Periode Penilaian</p>
                            <h3 class="text-3xl font-bold text-white mt-1">{{ $totalPeriode }}</h3>
                        </div>
                        <div class="p-3 bg-cyan-500/10 text-cyan-400 rounded-xl">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400">Jumlah gelombang / periode evaluasi</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-slate-800/80 border border-slate-700 p-6 rounded-2xl backdrop-blur-xl">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-slate-400 text-sm font-medium">Total Transaksi DP3</p>
                            <h3 class="text-3xl font-bold text-white mt-1">{{ $totalPenilaian }}</h3>
                        </div>
                        <div class="p-3 bg-purple-500/10 text-purple-400 rounded-xl">
                            <i class="fas fa-file-invoice text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400">Dokumen penilaian yang sedang / telah diproses</p>
                </div>
            </div>

            <!-- Quick Status Section -->
            <div class="bg-slate-800/80 border border-slate-700 rounded-2xl p-6">
                <h2 class="text-lg font-bold text-white mb-4">Status Modul Sistem</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-slate-900/50 rounded-xl border border-slate-700/50">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-database text-indigo-400"></i>
                            <span class="text-sm font-medium text-slate-200">Skema & Migrasi Database (`db_prima`)</span>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs rounded-lg font-semibold">Migrated</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-slate-900/50 rounded-xl border border-slate-700/50">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-layer-group text-indigo-400"></i>
                            <span class="text-sm font-medium text-slate-200">Struktur Master DP3 & Transaksi</span>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs rounded-lg font-semibold">Ready</span>
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>
</html>
