<section class="space-y-6" aria-labelledby="skala-heading">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Master data</p>
            <h1 id="skala-heading" class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Skala Nilai</h1>
            <p class="mt-2 max-w-xl text-sm leading-6 text-slate-500">Kelola skala penilaian dan konversi nilai untuk kebutuhan evaluasi kinerja.</p>
        </div>
        <button type="button" id="open-skala-modal" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
            Tambah 
        </button>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-slate-900">Daftar skala nilai</h2>
                <p class="mt-1 text-xs text-slate-500">Daftar kode, nama, dan konversi penilaian.</p>
            </div>
            <div class="flex gap-2">
                <label class="relative block sm:w-64"><span class="sr-only">Cari skala nilai</span><svg class="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4" stroke-linecap="round"/></svg><input type="search" id="skala-search" placeholder="Cari skala..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50"></label>
                <a href="{{ route('skala-predikat.index') }}" title="Muat ulang data" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 11a8.1 8.1 0 0 0-14.9-4L3 10m0-4v4h4M4 13a8.1 8.1 0 0 0 14.9 4L21 14m0 4v-4h-4" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-3 font-semibold">Kode</th><th class="px-5 py-3 font-semibold">Nama nilai</th><th class="px-5 py-3 font-semibold">Nilai angka</th><th class="px-5 py-3 font-semibold">Deskripsi</th><th class="px-5 py-3 text-right font-semibold">Aksi</th></tr></thead>
                <tbody id="skala-table-body" class="divide-y divide-slate-100">
                    @forelse ($skalaNilai as $skala)
                        <tr data-skala-row class="transition hover:bg-slate-50"><td class="whitespace-nowrap px-5 py-4 font-semibold text-blue-600">{{ $skala->kode_nilai }}</td><td class="whitespace-nowrap px-5 py-4 font-medium text-slate-800">{{ $skala->nama_nilai }}</td><td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $skala->nilai_angka }}</td><td class="px-5 py-4 text-slate-500">{{ $skala->deskripsi ?: '-' }}</td><td class="whitespace-nowrap px-5 py-4 text-right"><div class="flex justify-end gap-2"><button type="button" data-edit-skala data-id="{{ $skala->skala_id }}" data-kode="{{ $skala->kode_nilai }}" data-nama="{{ $skala->nama_nilai }}" data-nilai="{{ $skala->nilai_angka }}" data-deskripsi="{{ $skala->deskripsi }}" class="inline-flex items-center gap-1.5 rounded-lg bg-amber-400 px-3 py-2 text-xs font-bold text-amber-950 shadow-sm transition hover:bg-amber-500 focus:outline-none focus:ring-4 focus:ring-amber-100"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="m16.5 3.5 4 4M4 20l4.5-1 11-11a2.8 2.8 0 0 0-4-4l-11 11L4 20Z" stroke-linecap="round" stroke-linejoin="round"/></svg>Edit</button><form method="POST" action="{{ route('skala-nilai.destroy', $skala) }}" onsubmit="return confirm('Hapus skala nilai ini?')">@csrf @method('DELETE')<button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-3 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-100"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3" stroke-linecap="round" stroke-linejoin="round"/></svg>Hapus</button></form></div></td></tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-14 text-center"><div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 17.5v-11Z"/><path d="M8 8h8M8 12h8M8 16h4" stroke-linecap="round"/></svg></div><h3 class="mt-4 font-semibold text-slate-900">Belum ada skala nilai</h3><p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Tambahkan skala nilai pertama untuk mulai mengatur konversi penilaian.</p><button type="button" id="empty-open-skala-modal" class="mt-5 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700">Tambah skala nilai</button></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<div id="skala-modal" class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-slate-950/50 px-4 py-6 backdrop-blur-sm sm:py-10">
    <div class="w-full max-w-xl overflow-hidden rounded-3xl border border-white/70 bg-white shadow-2xl shadow-slate-950/20">
        <div class="flex items-start justify-between border-b border-slate-100 px-6 py-5 sm:px-8">
            <div class="flex items-center gap-3"><div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg></div><div><h2 id="skala-modal-title" class="text-lg font-bold text-slate-900">Tambah skala nilai</h2><p class="mt-0.5 text-xs text-slate-500">Lengkapi informasi skala penilaian.</p></div></div>
            <button type="button" id="close-skala-modal" class="flex h-8 w-8 items-center justify-center rounded-full text-xl leading-none text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup">&times;</button>
        </div>
        <form id="skala-form" method="POST" action="{{ route('skala-nilai.store') }}">
            @csrf
            <div id="skala-method"></div>
            <div class="space-y-4 px-5 py-3 sm:px-6 sm:py-4">
                <label class="flex min-h-9 w-full"><span class="flex w-24 shrink-0 items-center rounded-l border border-slate-300 bg-slate-100 px-3 text-sm text-slate-600 sm:w-24">Kode Nilai</span><input name="kode_nilai" id="skala-kode" required placeholder="contoh: A, B, C" class="min-w-0 flex-1 rounded-r border border-l-0 border-slate-300 px-3 text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></label>
                <label class="flex min-h-9 w-full"><span class="flex w-24 shrink-0 items-center rounded-l border border-slate-300 bg-slate-100 px-3 text-sm text-slate-600 sm:w-24">Nama Nilai</span><input name="nama_nilai" id="skala-nama" required placeholder="contoh: Sangat Baik" class="min-w-0 flex-1 rounded-r border border-l-0 border-slate-300 px-3 text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></label>
                <label class="flex min-h-9 w-full"><span class="flex w-24 shrink-0 items-center rounded-l border border-slate-300 bg-slate-100 px-3 text-sm text-slate-600 sm:w-24">Nilai Angka</span><input type="number" step="any" name="nilai_angka" id="skala-nilai" required placeholder="0-100" class="min-w-0 flex-1 rounded-r border border-slate-300 px-3 text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></label>
                <label class="flex min-h-9 w-full"><span class="flex w-24 shrink-0 items-center rounded-l border border-slate-300 bg-slate-100 px-3 text-sm text-slate-600 sm:w-24">Deskripsi</span><input name="deskripsi" id="skala-deskripsi" placeholder="Deskripsi singkat (opsional)" class="min-w-0 flex-1 rounded-r border border-slate-300 px-3 text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></label>
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-100 bg-slate-50/80 px-6 py-4 sm:px-8"><button type="button" id="cancel-skala-modal" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Batal</button><button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700">Simpan</button></div>
        </form>
    </div>
</div>

<script>
(() => {
    const modal = document.getElementById('skala-modal');
    const form = document.getElementById('skala-form');
    const method = document.getElementById('skala-method');
    const title = document.getElementById('skala-modal-title');
    const fields = { kode: document.getElementById('skala-kode'), nama: document.getElementById('skala-nama'), nilai: document.getElementById('skala-nilai'), deskripsi: document.getElementById('skala-deskripsi') };
    const open = (data = null) => { form.reset(); method.innerHTML = data ? '@method("PUT")' : ''; form.action = data ? `{{ url('/master/skala-nilai') }}/${data.id}` : '{{ route('skala-nilai.store') }}'; title.textContent = data ? 'Edit skala nilai' : 'Tambah skala nilai'; if (data) { fields.kode.value = data.kode; fields.nama.value = data.nama; fields.nilai.value = data.nilai; fields.deskripsi.value = data.deskripsi; } modal.classList.remove('hidden'); modal.classList.add('flex'); fields.kode.focus(); };
    const close = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
    document.getElementById('open-skala-modal').addEventListener('click', () => open());
    const emptyButton = document.getElementById('empty-open-skala-modal');
    if (emptyButton) emptyButton.addEventListener('click', () => open());
    document.getElementById('close-skala-modal').addEventListener('click', close);
    document.getElementById('cancel-skala-modal').addEventListener('click', close);
    document.querySelectorAll('[data-edit-skala]').forEach((button) => button.addEventListener('click', () => open(button.dataset)));
    document.getElementById('skala-search').addEventListener('input', (event) => { const query = event.target.value.toLowerCase(); document.querySelectorAll('[data-skala-row]').forEach((row) => { row.classList.toggle('hidden', !row.textContent.toLowerCase().includes(query)); }); });
})();
</script>
