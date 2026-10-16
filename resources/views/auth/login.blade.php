<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login DP3 Penilaian</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --maroon-primary: #7A1C38;
            --maroon-dark: #5d102a;
            --maroon-soft: #f8eaf0;
            --maroon-border: #efd2dc;
            --text: #243244;
            --muted: #697586;
            --white: #ffffff;
            --shadow: rgba(122, 28, 56, 0.15);
            --danger: #d92d20;
            --input-bg: #f9fafb;
        }

        * {
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f6e8ee 0%, #fdf2f5 32%, #f6f7fb 100%);
            color: var(--text);
        }

        .login-shell {
            width: min(100%, 430px);
            padding: 24px;
        }

        .login-card {
            background: rgba(255,255,255,0.95);
            border: 1px solid rgba(122, 28, 56, 0.08);
            border-radius: 24px;
            box-shadow: 0 20px 50px var(--shadow);
            padding: 28px 26px 22px;
            backdrop-filter: blur(4px);
        }

        .brand-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .brand-badge {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--maroon-primary) 0%, var(--maroon-dark) 100%);
            box-shadow: 0 12px 25px rgba(122, 28, 56, 0.25);
            color: var(--white);
            font-size: 24px;
        }

        .login-title {
            margin: 0;
            text-align: center;
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            color: var(--maroon-primary);
        }

        .subtitle {
            margin: 8px 0 22px;
            text-align: center;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--text);
        }

        .field-wrap {
            position: relative;
            margin-bottom: 18px;
        }

        .form-input {
            width: 100%;
            border: 1px solid var(--maroon-border);
            background: var(--input-bg);
            border-radius: 12px;
            padding: 14px 14px;
            font-size: 0.97rem;
            color: var(--text);
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(122, 28, 56, 0.55);
            box-shadow: 0 0 0 4px rgba(122, 28, 56, 0.09);
            background: var(--white);
        }

        .password-wrap .form-input {
            padding-right: 52px;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 65%; 
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #8a7d86;
            cursor: pointer;
            font-size: 1rem;
            width: 20px;
            height: 20px;
            border-radius: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.95;
            box-shadow: none;
        }

        .toggle-password:hover,
        .toggle-password:focus-visible {
            background: transparent;
            border: none;
            color: var(--maroon-primary);
            outline: none;
            box-shadow: none;
        }

        .login-btn {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 14px 18px;
            background: linear-gradient(135deg, var(--maroon-primary) 0%, var(--maroon-dark) 100%);
            color: var(--white);
            font-size: 0.98rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 10px 20px rgba(122, 28, 56, 0.2);
        }

        .login-btn:hover {
            transform: translateY(-1px);
        }

        .credentials-box {
            margin-top: 18px;
            padding: 10px 12px;
            border-radius: 10px;
            background: var(--maroon-soft);
            border: 1px solid var(--maroon-border);
            font-size: 0.8rem;
            color: var(--maroon-dark);
            text-align: center;
        }

        .credentials-box strong {
            color: var(--maroon-primary);
        }

        .error-box {
            margin-bottom: 18px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(217, 45, 32, 0.08);
            border: 1px solid rgba(217, 45, 32, 0.2);
            color: var(--danger);
            font-size: 0.8rem;
            font-weight: 600;
        }

        @media (max-width: 520px) {
            .login-card {
                padding: 22px 18px 18px;
            }

            .login-title {
                font-size: 1.45rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-card">
                @if(session('status'))
                    <div role="status" style="margin-bottom: 16px; padding: 12px; border: 1px solid #efd2dc; border-radius: 10px; color: #7A1C38; background: #f8eaf0;">
                        {{ session('status') }}
                    </div>
                @endif
            <div class="brand-wrap">
                <div class="brand-badge">
                    <i class="fa-solid fa-chart-column"></i>
                </div>
            </div>

            <h1 class="login-title">DP3 Penilaian</h1>
            <p class="subtitle">Sistem Penilaian Kinerja</p>

            @if ($errors->any())
                <div class="error-box" id="login-alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-wrap">
                    <label class="form-label" for="username">Username</label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        class="form-input"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        placeholder="Masukkan username"
                        required
                    >
                </div>

                <div class="field-wrap password-wrap">
                    <label class="form-label" for="password">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-input"
                        value=""
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        required
                    >
                    <button type="button" class="toggle-password" aria-label="Lihat password" id="togglePassword">
                        <i class="fa-solid fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>

                <button type="submit" class="login-btn">Masuk</button>
            </form>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        toggleButton.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            toggleIcon.classList.toggle('fa-eye', !isHidden);
            toggleIcon.classList.toggle('fa-eye-slash', isHidden);
            this.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Lihat password');
        });
    </script>
</body>
</html>
