<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Menu Belum Tersedia</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #f4f6f9; color: #243244; font-family: sans-serif; }
        main { width: min(520px, calc(100% - 40px)); padding: 32px; text-align: center; background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 12px 30px rgba(15, 23, 42, .08); }
        h1 { margin-top: 0; color: #7A1C38; font-size: 1.4rem; }
        p { color: #64748b; line-height: 1.6; }
        button { margin-top: 12px; padding: 11px 18px; border: 0; border-radius: 9px; background: #7A1C38; color: #fff; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>
    <main>
        <h1>Belum Ada Menu yang Dapat Diakses</h1>
        <p>Akun Anda sudah masuk, tetapi belum ada menu yang diberikan untuk role ini. Hubungi Super Admin untuk mengatur Otorisasi Menu, atau keluar untuk masuk dengan akun lain.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Keluar dan kembali ke login</button>
        </form>
    </main>
</body>
</html>
