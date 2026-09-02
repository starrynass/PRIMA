<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penilaian Kinerja</title>
    <!-- FontAwesome CDN untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root{/* Crimson Maroon Palette */
        --maroon-primary: #7A1C38;
        --maroon-hover: #5C1329;
        --maroon-soft: #FBF0F3;
        --maroon-border: #F3D5DD;
        --maroon-gradient: linear-gradient(135deg, #7A1C38 0%, #9E2A4B 100%);
        --maroon-glow: rgba(122, 28, 56, 0.2);}
       * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }


        body {
            display: flex;
            background-color: #f4f6f9;
            min-height: 100vh;
            overflow-x: hidden;
        }


        /* SIDEBAR */
        .sidebar {
            width: 65px;
            background-color: #f8f9fa;
            border-right: 1px solid #e0e0e0;
            position: fixed;
            top:56px;
            left:0;
            height: calc(100vh - 56px);
            transition: width .3s ease;
            z-index:99;
            overflow:hidden;
            display:flex;
            flex-direction:column;
        }


        .sidebar.expanded {
            width:250px;

        }

        /* HEADER SIDEBAR */
        .sidebar-header-bar {
            height:60px;
            display:flex;
            align-items:center;
            padding:0 20px;
            border-bottom:1px solid #e0e0e0;
            white-space:nowrap;

        }

        .hamburger-btn {
            background:none;
            border:none;
            font-size:18px;
            color:#444;
            cursor:pointer;
            min-width:25px;

        }

        /* BRAND */
        .brand-title {
            margin-left:15px;
            font-size:13px;
            font-weight:bold;
            color:#333;
            opacity:0;
            transition:.2s;
        }

        .sidebar.expanded .brand-title {
            opacity:1;

        }

        /* MENU */
        .nav-menu {
            list-style:none;
            padding:10px 0;
            flex-grow:1;
        }

        .nav-item {
            width:100%;
        }

        .nav-link,
        .submenu-link {
            display:flex;
            align-items:center;
            height:48px;
            padding:0 20px;
            color:#444;
            text-decoration:none;
            cursor:pointer;
            white-space:nowrap;
        }

        .nav-icon {
            min-width:25px;
            text-align:center;
            font-size:16px;
        }

        /* TEXT */
        .menu-text {
            margin-left:15px;
            font-size:11px;
            opacity:0;
            transition:.2s;
        }

        .sidebar.expanded .menu-text {
            opacity:1;
        }

        /* ARROW */
        .arrow {
            margin-left:auto;
            opacity:0;
            transition:.2s;
            font-size: 10px !important;
        }
        
        .sidebar.expanded .arrow {
            opacity:1;
        }

        .arrow.rotate {
            transform:rotate(-90deg);
        }

        /* ACTIVE */
        .selected-active {
            background:#e8f3ff !important;
            color:#0066cc !important;
        }



        /* DOT */

        .dot {
            width:6px;
            height:6px;
            background:#0066cc;
            border-radius:50%;
            display:inline-block;
            margin-right:8px;
        }

        /* SUBMENU */
        .submenu {
            list-style:none;
            background:#f1f3f5;
            max-height:0;
            overflow:hidden;
            transition:max-height .3s ease;
        }

        .submenu.open {
            max-height:300px;
        }

        .submenu-link {
            height:40px;
            padding-left:60px;
        }

        .sidebar:not(.expanded) .submenu {
            max-height:0;

        }

        /* PAGE HEADER (modern flat style, compact) */
        .page-header {
            position:fixed;
            top:0;
            left:0;
            right:0;
            height:56px;
            background:var(--maroon-gradient);
            color:#fff;
            display:flex;
            align-items:center;
            padding:0 14px;
            z-index:200;
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }

        .header-inner { z-index:2; display:flex; align-items:center; gap:8px; }

        .header-title { font-size:14px; font-weight:700; color:#fff; }

        .page-header .hamburger-btn {
            background:rgba(255,255,255,0.08);
            border:none;
            color: #fff;
            font-size:15px;
            padding:5px;
            border-radius:8px;
            cursor:pointer;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            box-shadow: none;
        }

        /* CONTENT */
        .main-content {
            flex:1;
            margin-left:65px;
            padding:20px;
            margin-top:56px;
            min-width:0;
            overflow-x:hidden;
            transition:.3s;
        }

        .sidebar.expanded ~ .main-content {
            margin-left:250px;
        }
        
    </style>
</head>
<body>
    </div>
    
    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">

        <ul class="nav-menu">
            <!-- 1. Dashboard -->
            <li class="nav-item">
                <a href="{{ route('dashboard-penilaian.index') }}" class="nav-link menu-item">
                    <i class="fa-solid fa-house nav-icon"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <!-- 2. Master -->
            <li class="nav-item">
                <div class="nav-link dropdown-toggle">
                    <i class="fa-solid fa-pen-to-square nav-icon"></i>
                    <span class="menu-text">Master</span>
                    <i class="fa-solid fa-chevron-left arrow"></i>
                </div>
                <ul class="submenu">
                    <li class="submenu-item">
                        <a href="{{ route('skala-predikat.index') }}" class="submenu-link menu-item">
                            <span class="menu-text">Skala & Predikat Nilai</span>
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="{{ route('template-penilaian.index') }}" class="submenu-link menu-item">
                            <span class="menu-text">Template, Fase dan Pertanyaan</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 3. Penilaian -->
            <li class="nav-item">
                <div class="nav-link dropdown-toggle">
                    <i class="fa-solid fa-users nav-icon"></i>
                    <span class="menu-text">Penilaian</span>
                    <i class="fa-solid fa-chevron-left arrow"></i>
                </div>
                <ul class="submenu">
                    <li class="submenu-item">
                        <a href="{{ route('periode-penilaian.index') }}" class="submenu-link menu-item">
                            <span class="menu-text">Periode Penilaian</span>
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="{{ route('kelola-penilaian.index') }}" class="submenu-link menu-item">
                            <span class="menu-text">Kelola Penilaian</span>
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="{{ route('verifikasi-penilaian.index') }}" class="submenu-link menu-item">
                            <span class="menu-text">Verifikasi Penilaian</span>
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="{{ route('catatan-penilaian.index') }}" class="submenu-link menu-item">
                            <span class="menu-text">Catatan Penilaian</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 4. Laporan -->
            <li class="nav-item">
                <div class="nav-link dropdown-toggle">
                    <i class="fa-solid fa-file-lines nav-icon"></i>
                    <span class="menu-text">Laporan</span>
                    <i class="fa-solid fa-chevron-left arrow"></i>
                </div>
                <ul class="submenu">
                    <li class="submenu-item">
                        <a href="{{ route('laporan-tahunan.index') }}" class="submenu-link menu-item">
                            <span class="menu-text">Laporan Tahunan</span>
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="{{ route('laporan-index-unit-kerja.index') }}" class="submenu-link menu-item">
                            <span class="menu-text">Laporan Index Unit Kerja</span>
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="{{ route('laporan-rekap-nilai.index') }}" class="submenu-link menu-item">
                            <span class="menu-text">Laporan Rekap Nilai</span>
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="{{ route('laporan-index-kompetensi.index') }}" class="submenu-link menu-item">
                            <span class="menu-text">Laporan Index Kompetensi</span>
                        </a>
                    </li>
                </ul> 
            </li>
        </ul>
    </div>


    <header class="page-header" role="banner">
        <div class="header-inner">
            <button class="hamburger-btn" id="toggleSidebar">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="header-title">(DP3) Penilaian Kinerja</div>
        </div>
    </header>

    
    <main class="main-content">
        @yield('content')
    </main>
  
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleSidebarBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');

             let activeSubmenu = null;

            // 1. Toggle Buka/Tutup Sidebar ke Kanan
            toggleSidebarBtn.addEventListener('click', function () {
                sidebar.classList.toggle('expanded');
                if (sidebar.classList.contains('expanded')) {

                    if (activeSubmenu) {
                        activeSubmenu.classList.add('open');

                        const arrow = activeSubmenu.previousElementSibling.querySelector('.arrow');

                        if (arrow) {
                            arrow.classList.add('rotate');
                        }
                    }

                }
            });

            // 2. Dropdown Toggle Click Event
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('click', function() {
                    if (!sidebar.classList.contains('expanded')) {
                        sidebar.classList.add('expanded');
                    }

                    const submenu = this.nextElementSibling;
                    const arrow = this.querySelector('.arrow');
                    const isOpen = submenu.classList.contains('open');

                    closeAllSubmenus();

                    if (!isOpen) {
                    submenu.classList.add('open');

                    if (arrow) {
                        arrow.classList.add('rotate');
                    }

                    activeSubmenu = submenu;
                    } else {
                    activeSubmenu = null;
                }
                });
            });

            // 3. Close Submenus Helper
            function closeAllSubmenus() {
                document.querySelectorAll('.submenu').forEach(submenu => {
                    
                        submenu.classList.remove('open');
                        const parentToggle = submenu.previousElementSibling;
                        if (parentToggle) {
                            const arrow = parentToggle.querySelector('.arrow');
                            if (arrow) arrow.classList.remove('rotate');
                        
                    }
                });
            }

           // Aktifkan menu berdasarkan URL sekarang
            const currentUrl = window.location.href;

            document.querySelectorAll('.menu-item').forEach(item => {

                if (item.href === currentUrl) {
                    item.classList.add('selected-active');

                    // Tambahkan dot hanya pada menu aktif
                    let text = item.querySelector('.menu-text');

                    if (text && !text.querySelector('.dot')) {
                        let dot = document.createElement('span');
                        dot.classList.add('dot');
                        text.prepend(dot);
                    }
                }

            });
        });
    </script>
</body>
</html> 