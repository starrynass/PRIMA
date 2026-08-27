<section class="skala-section">

<style>

/* =====================
   GLOBAL
===================== */

.skala-section{
    display:flex;
    flex-direction:column;
    gap:24px;
    font-family:Inter,Arial,sans-serif;
}



/* =====================
   HEADER
===================== */


.skala-header{

    display:flex;
    justify-content:space-between;
    align-items:flex-end;

}


.skala-label{

    margin-bottom:8px;

    font-size:12px;

    font-weight:700;

    text-transform:uppercase;

    letter-spacing:2px;

    color:#2563eb;

}


.skala-title{

    margin:0;

    font-size:30px;

    font-weight:700;

    color:#0f172a;

}


.skala-description{

    margin-top:8px;

    color:#64748b;

    font-size:14px;

}




/* =====================
 BUTTON
===================== */


.btn-primary{

    display:inline-flex;

    align-items:center;

    gap:8px;

    padding:10px 18px;

    background:#2563eb;

    color:white;

    border:none;

    border-radius:12px;

    font-size:14px;

    font-weight:600;

    cursor:pointer;

}


.btn-primary:hover{

    background:#1d4ed8;

}





/* =====================
 ALERT
===================== */


.alert-success{

    padding:12px 16px;

    border-radius:12px;

    border:1px solid #a7f3d0;

    background:#ecfdf5;

    color:#047857;

}


.alert-error{

    padding:12px 16px;

    border-radius:12px;

    border:1px solid #fecaca;

    background:#fff1f2;

    color:#be123c;

}



/* =====================
 TABLE CARD
===================== */


.table-card{

    background:white;

    border:1px solid #e2e8f0;

    border-radius:20px;

    overflow:hidden;

    box-shadow:
    0 8px 20px rgba(0,0,0,.04);

}


.table-header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    padding:20px;

    border-bottom:1px solid #f1f5f9;

}



.table-title{

    margin:0;

    font-weight:700;

    color:#0f172a;

}


.table-subtitle{

    margin-top:5px;

    font-size:12px;

    color:#64748b;

}




/* SEARCH */


.search-area{

    display:flex;

    gap:10px;

}


.search-box{

    width:260px;

    padding:10px 14px;

    border-radius:12px;

    border:1px solid #e2e8f0;

    background:#f8fafc;

}


.search-box:focus{

    outline:none;

    border-color:#2563eb;

    background:white;

}




.btn-refresh{

    width:40px;

    height:40px;

    border-radius:12px;

    border:1px solid #e2e8f0;

    background:white;

    cursor:pointer;

}




/* TABLE */


.table-wrapper{

    overflow-x:auto;

}


table{

    width:100%;

    border-collapse:collapse;

}


thead{

    background:#f8fafc;

}


th{

    padding:14px 20px;

    text-align:left;

    font-size:12px;

    color:#64748b;

    text-transform:uppercase;

}


td{

    padding:16px 20px;

    border-bottom:1px solid #f1f5f9;

    font-size:14px;

}


tbody tr:hover{

    background:#f8fafc;

}


.code{

    color:#2563eb;

    font-weight:700;

}




/* ACTION */


.action{

    display:flex;

    justify-content:flex-end;

    gap:8px;

}



.btn-edit{

    padding:8px 12px;

    border:none;

    border-radius:8px;

    background:#fbbf24;

    color:#78350f;

    font-size:12px;

    font-weight:bold;

    cursor:pointer;

}



.btn-delete{

    padding:8px 12px;

    border:none;

    border-radius:8px;

    background:#e11d48;

    color:white;

    font-size:12px;

    font-weight:bold;

    cursor:pointer;

}




/* EMPTY */

.empty{

    text-align:center;

    padding:60px;

}


.empty-icon{

    width:50px;

    height:50px;

    margin:auto;

    border-radius:15px;

    background:#eff6ff;

    display:flex;

    align-items:center;

    justify-content:center;

    color:#2563eb;

}



</style>



<div class="skala-header">


<div>

<p class="skala-label">
Master Data
</p>


<h1 class="skala-title">
Skala Nilai
</h1>


<p class="skala-description">
Kelola skala penilaian dan konversi nilai untuk kebutuhan evaluasi kinerja.
</p>


</div>



<button 
type="button"
id="open-skala-modal"
class="btn-primary">

＋ Tambah

</button>


</div>



@if(session('success'))

<div class="alert-success">

{{session('success')}}

</div>

@endif



@if($errors->any())

<div class="alert-error">

{{$errors->first()}}

</div>

@endif




<div class="table-card">


<div class="table-header">


<div>

<h2 class="table-title">
Daftar skala nilai
</h2>


<p class="table-subtitle">
Daftar kode, nama, dan konversi penilaian.
</p>


</div>



<div class="search-area">


<input 
type="search"
id="skala-search"
class="search-box"
placeholder="Cari skala...">


<a href="{{route('skala-predikat.index')}}">

<button class="btn-refresh">
↻
</button>

</a>


</div>


</div>





<div class="table-wrapper">


<table>


<thead>

<tr>

<th>Kode</th>

<th>Nama Nilai</th>

<th>Nilai Angka</th>

<th>Deskripsi</th>

<th>Aksi</th>

</tr>

</thead>



<tbody id="skala-table-body">


@forelse($skalaNilai as $skala)


<tr data-skala-row>


<td class="code">
{{$skala->kode_nilai}}
</td>


<td>
{{$skala->nama_nilai}}
</td>


<td>
{{$skala->nilai_angka}}
</td>


<td>
{{$skala->deskripsi ?: '-'}}
</td>


<td>


<div class="action">


<button 
class="btn-edit"
data-edit-skala
data-id="{{$skala->skala_id}}"
data-kode="{{$skala->kode_nilai}}"
data-nama="{{$skala->nama_nilai}}"
data-nilai="{{$skala->nilai_angka}}"
data-deskripsi="{{$skala->deskripsi}}">

Edit

</button>



<form method="POST"
action="{{route('skala-nilai.destroy',$skala)}}">

@csrf

@method('DELETE')


<button class="btn-delete"
onclick="return confirm('Hapus skala nilai ini?')">

Hapus

</button>


</form>



</div>


</td>


</tr>



@empty


<tr>

<td colspan="5">


<div class="empty">

<div class="empty-icon">
＋
</div>


<h3>
Belum ada skala nilai
</h3>


<p>
Tambahkan skala nilai pertama.
</p>


<button 
id="empty-open-skala-modal"
class="btn-primary">

Tambah

</button>


</div>


</td>

</tr>



@endforelse



</tbody>


</table>


</div>


</div>