<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>{{ config('app.name', '3spro') }}</title>
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Google Fonts: Inter + Playfair Display for premium touch -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 (free icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0f1f 0%, #0c1222 50%, #0b1120 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated gradient orbs for premium depth */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(220, 38, 38, 0.15) 0%, rgba(220, 38, 38, 0) 70%);
            top: -200px;
            right: -150px;
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, rgba(59, 130, 246, 0) 70%);
            bottom: -250px;
            left: -200px;
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
        }

        /* main container */
        .login-container {
            width: 100%;
            max-width: 460px;
            margin: 1.5rem;
            position: relative;
            z-index: 2;
            animation: fadeSlideUp 0.8s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }

        /* Glassmorphic card with premium blur */
        .premium-card {
            background: rgba(18, 25, 45, 0.75);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-radius: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.45), 0 0 0 0.5px rgba(255, 255, 255, 0.05) inset;
            padding: 2.2rem 2rem 2.5rem;
            transition: all 0.3s ease;
        }

        .premium-card:hover {
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 30px 50px -18px rgba(0, 0, 0, 0.6);
        }

        /* Logo & brand area */
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #1e2a3e, #0f172a);
            width: 85px;
            height: 85px;
            border-radius: 28px;
            margin-bottom: 1.2rem;
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255,255,255,0.08);
            transition: transform 0.25s ease;
        }

        .logo-wrapper:hover {
            transform: scale(1.02);
        }

        .logo-wrapper img {
            max-width: 88px;
            max-height: 88px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .brand-header h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 1.9rem;
            background: linear-gradient(120deg, #ffffff, #cbd5e6);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
            margin-bottom: 0.4rem;
        }

        .brand-tagline {
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        /* Alert styles (premium subtle) */
        .alert-modern {
            padding: 0.9rem 1rem;
            border-radius: 1.2rem;
            margin-bottom: 1.6rem;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(4px);
            animation: shakeFade 0.3s ease-out;
        }

        .alert-modern i {
            font-size: 1.1rem;
        }

        .alert-danger-modern {
            background: rgba(153, 27, 27, 0.2);
            border-left: 3px solid #ef4444;
            color: #fecaca;
        }

        .alert-danger-modern i {
            color: #f87171;
        }

        /* Form group styling */
        .input-group-custom {
            margin-bottom: 1.4rem;
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c86a3;
            font-size: 1rem;
            transition: color 0.2s;
            pointer-events: none;
            z-index: 1;
        }

        .input-field {
            width: 100%;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.2rem;
            padding: 0.9rem 1rem 0.9rem 2.8rem;
            font-size: 0.95rem;
            font-weight: 500;
            color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            transition: all 0.25s ease;
            outline: none;
        }

        .input-field:focus {
            border-color: #dc2626;
            background: rgba(20, 30, 55, 0.85);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
        }

        .input-field:focus + .input-icon {
            color: #f87171;
        }

        .input-field::placeholder {
            color: #5b6e8c;
            font-weight: 400;
        }

        /* Submit button premium */
        .login-btn {
            width: 100%;
            background: linear-gradient(95deg, #b91c1c, #dc2626);
            border: none;
            border-radius: 1.5rem;
            padding: 0.9rem;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.6rem;
            box-shadow: 0 8px 18px -8px rgba(220, 38, 38, 0.4);
            letter-spacing: 0.3px;
        }

        .login-btn i {
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .login-btn:hover {
            background: linear-gradient(95deg, #9b2c2c, #b91c1c);
            transform: translateY(-2px);
            box-shadow: 0 12px 22px -10px rgba(220, 38, 38, 0.5);
        }

        .login-btn:active {
            transform: translateY(1px);
        }

        /* footer links */
        .footer-links {
            text-align: center;
            margin-top: 2rem;
            padding-top: 0.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .copyright-link {
            color: #6f85a3;
            font-size: 0.75rem;
            text-decoration: none;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .copyright-link i {
            font-size: 0.7rem;
        }

        .copyright-link:hover {
            color: #dc2626;
        }

        /* Additional micro-animations */
        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(25px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shakeFade {
            0% { opacity: 0; transform: translateX(-6px);}
            100% { opacity: 1; transform: translateX(0);}
        }

        /* Responsive touches */
        @media (max-width: 500px) {
            .premium-card {
                padding: 1.8rem 1.5rem 2rem;
            }
            .brand-header h1 {
                font-size: 1.6rem;
            }
            .logo-wrapper {
                width: 70px;
                height: 70px;
            }
            .logo-wrapper img {
                max-width: 48px;
            }
        }

        /* elegant focus ring for accessibility */
        .input-field:focus-visible {
            outline: none;
            border-color: #ef4444;
        }

        /* Hidden input (myname) remains but visually invisible */
        input[name="myname"] {
            display: none;
        }

        /* Additional floating effect */
        .premium-card {
            transition: transform 0.2s ease, border-color 0.2s;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="premium-card">
        
        <!-- Brand & Logo Section -->
        <div class="brand-header">
            <div class="logo-wrapper">
                {{-- Dynamic logo from env or fallback --}}
                <img src="{{ asset(env('APP_LOGO', 'src/3spro.png')) }}" alt="{{ config('app.name', '3spro') }}" onerror="this.src='https://placehold.co/80x80/1e293b/ffffff?text=3S'">
            </div>
            <h1>{{ config('app.name', '3spro') }}</h1>
            <div class="brand-tagline">Secure access · Premium experience</div>
        </div>

        {{-- Display Validation Errors (professional styling) --}}
        @if($errors->any())
            <div class="alert-modern alert-danger-modern">
                <i class="fas fa-exclamation-triangle"></i>
                <span>
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </span>
            </div>
        @endif

        {{-- Display Session Error (custom) --}}
        @if(session('error'))
            <div class="alert-modern alert-danger-modern">
                <i class="fas fa-lock"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('p_login') }}" autocomplete="off">
            @csrf
            {{-- hidden field as per original requirement --}}
            <input type="hidden" name="myname" value="1">
            
            <!-- Username field with icon -->
            <div class="input-group-custom">
                <i class="fas fa-user input-icon"></i>
                <input type="text" class="input-field" name="user_name" placeholder="Username or email" value="{{ old('user_name') }}" required autofocus>
            </div>
            
            <!-- Password field with icon -->
            <div class="input-group-custom">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="input-field" name="password" placeholder="Password" required>
            </div>
            
            <!-- Optional extra row (demo micro interaction) -->
            <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                <a href="#" style="color: #7e8aa8; font-size: 0.7rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#7e8aa8'">
                    <i class="fas fa-key"></i> Forgot password?
                </a>
            </div>
            
            <button type="submit" class="login-btn">
                <i class="fas fa-arrow-right-to-bracket"></i> Sign in
            </button>
        </form>

        {{-- Footer with dynamic year --}}
        <div class="footer-links">
            <a class="copyright-link" href="#">
                <i class="far fa-copyright"></i> {{ date('Y') }} {{ config('app.name', '3spro') }}. 
                <span>All rights reserved.</span>
            </a>
        </div>
    </div>
</div>

{{-- Optional subtle script for password toggle? (not needed but nice) --}}
<script>
    // optional micro-interaction: just for modern feel, no intrusive logic.
    // we keep the original behavior, but we ensure that any session messages are visible.
    (function() {
        // dynamic logo fallback: if logo fails to load, keep text alternative but not breaking
        const logoImg = document.querySelector('.logo-wrapper img');
        if(logoImg) {
            logoImg.addEventListener('error', function() {
                this.style.display = 'none';
                let fallbackSpan = document.createElement('span');
                fallbackSpan.innerText = '{{ substr(config('app.name', '3S'), 0, 2) }}';
                fallbackSpan.style.fontSize = '32px';
                fallbackSpan.style.fontWeight = 'bold';
                fallbackSpan.style.background = 'linear-gradient(145deg, #f87171, #dc2626)';
                fallbackSpan.style.backgroundClip = 'text';
                fallbackSpan.style.webkitBackgroundClip = 'text';
                fallbackSpan.style.color = 'transparent';
                this.parentNode.appendChild(fallbackSpan);
            });
        }
        
        // additional premium ripple effect on button click (light)
        const btn = document.querySelector('.login-btn');
        if(btn) {
            btn.addEventListener('click', function(e) {
                let ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.backgroundColor = 'rgba(255,255,255,0.3)';
                ripple.style.width = '100px';
                ripple.style.height = '100px';
                ripple.style.transform = 'translate(-50%, -50%) scale(0)';
                ripple.style.transition = 'transform 0.4s, opacity 0.4s';
                ripple.style.opacity = '1';
                ripple.style.pointerEvents = 'none';
                const rect = e.target.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                btn.style.position = 'relative';
                btn.style.overflow = 'hidden';
                btn.appendChild(ripple);
                setTimeout(() => {
                    ripple.style.transform = 'translate(-50%, -50%) scale(4)';
                    ripple.style.opacity = '0';
                }, 20);
                setTimeout(() => ripple.remove(), 400);
            });
        }
    })();
</script>
<style>
    /* ripple effect support */
    .ripple-effect {
        position: absolute;
        background: rgba(255,255,240,0.25);
        border-radius: 50%;
        transform: scale(0);
        animation: ripple-anim 0.5s linear forwards;
        pointer-events: none;
    }
    @keyframes ripple-anim {
        to {
            transform: scale(6);
            opacity: 0;
        }
    }
    /* ensures no overflow on button */
    .login-btn {
        overflow: hidden;
        position: relative;
    }
    /* Additional micro-glow for inputs */
    .input-field:-webkit-autofill,
    .input-field:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0px 1000px rgba(30, 41, 59, 0.9) inset;
        -webkit-text-fill-color: #f1f5f9;
        transition: background-color 5000s ease-in-out 0s;
    }
    /* custom scrollbar (premium) */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #0f172a;
    }
    ::-webkit-scrollbar-thumb {
        background: #dc2626;
        border-radius: 8px;
    }
</style>
</body>
</html>