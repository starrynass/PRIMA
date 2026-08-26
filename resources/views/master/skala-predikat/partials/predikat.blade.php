<div class="space-y-4">

    <!-- Flash Alert Sukses -->
    @if(session('success'))
        <div class="p-3 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-lg text-sm font-semibold flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <div class="p-2 bg-yellow-100 text-xs text-black">
    Jumlah Data Predikat: {{ count($predikatNilai) }}
</div>

    <!-- Alert Validasi Error (TAMBAHKAN DI SINI) -->
    @if ($errors->any())
        <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-xs font-semibold space-y-1 mb-4">
            <p class="font-bold">Gagal Menyimpan Data:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Toolbar Atas (Perbarui, Tambah, Ubah, Hapus) -->
    <div class="flex justify-end gap-2">
        <button onclick="window.location.reload()" class="px-3 py-1.5 bg-white border border-green-600 text-green-600 hover:bg-green-50 rounded text-xs font-bold flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            Perbarui
        </button>

        <button onclick="openModalTambah()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah
        </button>

        <button disabled class="px-3 py-1.5 bg-amber-200 text-amber-500 cursor-not-allowed rounded text-xs font-bold flex items-center gap-1.5 opacity-70">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            Ubah
        </button>

        <button disabled class="px-3 py-1.5 bg-red-200 text-red-500 cursor-not-allowed rounded text-xs font-bold flex items-center gap-1.5 opacity-70">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Hapus
        </button>
    </div>

    <!-- Tabel Data Predikat -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-xs text-left text-gray-600">
            <thead class="bg-gray-100 uppercase text-gray-600 font-bold border-b border-gray-200">
                <tr>
                    <th class="px-3 py-3 text-center border-r w-10">#</th>
                    <th class="px-4 py-3 border-r">ID Predikat</th>
                    <th class="px-4 py-3 border-r">Kode</th>
                    <th class="px-4 py-3 border-r text-center">Nilai Min</th>
                    <th class="px-4 py-3 border-r text-center">Nilai Max</th>
                    <th class="px-4 py-3 border-r">Predikat</th>
                    <th class="px-4 py-3 border-r text-center">Preview</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($predikatNilai as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-3 text-center border-r font-medium">{{ $index + 1 }}.</td>
                        <td class="px-4 py-3 border-r font-medium text-gray-700">{{ $item->predikat_id }}</td>
                        <td class="px-4 py-3 border-r font-medium text-gray-700">{{ $item->kode }}</td>
                        <td class="px-4 py-3 border-r text-center">{{ number_format($item->nilai_min, 2) }}</td>
                        <td class="px-4 py-3 border-r text-center">{{ number_format($item->nilai_max, 2) }}</td>
                        <td class="px-4 py-3 border-r font-medium">{{ $item->predikat }}</td>
                        <td class="px-4 py-3 border-r text-center">
                            @php
                                $badgeColor = 'bg-gray-500';
                                if ($item->nilai_min >= 90) $badgeColor = 'bg-emerald-600';
                                elseif ($item->nilai_min >= 80) $badgeColor = 'bg-blue-600';
                                elseif ($item->nilai_min >= 70) $badgeColor = 'bg-amber-500';
                                elseif ($item->nilai_min >= 60) $badgeColor = 'bg-orange-500';
                                else $badgeColor = 'bg-red-600';
                            @endphp
                            <span class="px-3 py-1 {{ $badgeColor }} text-white text-[11px] font-bold rounded-md shadow-sm inline-block">
                                {{ $item->predikat }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('predikat.destroy', $item->predikat_id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-[11px] font-semibold rounded transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-400">Belum ada data predikat nilai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Popup Tambah Data -->
    <div id="modalTambah" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-150">
            
            <!-- Header Modal -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-2 text-blue-600 font-bold text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span>Tambah Predikat Nilai</span>
                </div>
                <button onclick="closeModalTambah()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Body Form Modal -->
            <form action="{{ route('predikat.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-3">

                    <!-- Input ID Predikat (Otomatis / Input) -->
                    <div class="flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md text-xs text-gray-600 font-semibold w-32">
                            ID Predikat
                        </span>
                        <input type="text" name="predikat_id" placeholder="contoh: PRED001" required class="flex-1 border border-gray-300 rounded-r-md p-2 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Input Kode -->
                    <div class="flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md text-xs text-gray-600 font-semibold w-32">
                            Kode
                        </span>
                        <input type="text" name="kode" placeholder="contoh: PRED001 (opsional)" required class="flex-1 border border-gray-300 rounded-r-md p-2 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Input Nilai Minimum -->
                    <div class="flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md text-xs text-gray-600 font-semibold w-32">
                            Nilai Minimum
                        </span>
                        <input type="number" step="0.01" name="nilai_min" placeholder="0.00" required class="flex-1 border border-gray-300 rounded-r-md p-2 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Input Nilai Maksimum -->
                    <div class="flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md text-xs text-gray-600 font-semibold w-32">
                            Nilai Maksimum
                        </span>
                        <input type="number" step="0.01" name="nilai_max" placeholder="100.00" required class="flex-1 border border-gray-300 rounded-r-md p-2 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Input Predikat -->
                    <div class="flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md text-xs text-gray-600 font-semibold w-32">
                            Predikat
                        </span>
                        <input type="text" name="predikat" placeholder="contoh: Sangat Baik" required class="flex-1 border border-gray-300 rounded-r-md p-2 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <!-- Catatan Info -->
                    <div class="p-3 bg-cyan-50 border border-cyan-200 text-cyan-800 rounded-md text-xs flex items-center gap-2 mt-4">
                        <svg class="w-4 h-4 text-cyan-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <span><strong>Catatan:</strong> ID Predikat & range nilai tidak boleh bertabrakan dengan predikat lain.</span>
                    </div>

                </div>

                <!-- Footer Modal Button -->
                <div class="flex justify-end gap-2 px-6 py-3 bg-gray-50 border-t border-gray-200">
                    <button type="button" onclick="closeModalTambah()" class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 rounded-md text-xs font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-semibold shadow-sm transition">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

<!-- JavaScript Modal Control -->
<script>
    function openModalTambah() {
        const modal = document.getElementById('modalTambah');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModalTambah() {
        const modal = document.getElementById('modalTambah');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>