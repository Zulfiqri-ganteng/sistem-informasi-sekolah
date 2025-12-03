<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistemm Informasi Sekolah</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a192f, #112240);
            overflow: hidden;
        }

        /* Particle Background */
        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        /* Login Container */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 400px;
            background: rgba(10, 25, 47, 0.85);
            border: 1px solid rgba(255, 215, 0, 0.2);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.4);
            padding: 2.5rem 2rem;
            color: #fff;
            animation: fadeInUp 1s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .login-header h4 {
            font-weight: 600;
            color: #ffd700;
            /* emas */
        }

        .login-header p {
            color: #b0c4de;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.12);
            border: none;
            border-radius: 10px;
            color: #fff;
            padding-left: 40px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #ffd700;
            font-size: 1.1rem;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #ffd700;
            font-size: 1.1rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #004aad, #ffd700);
            border: none;
            padding: 10px;
            border-radius: 10px;
            width: 100%;
            font-weight: 600;
            color: #fff;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #003380, #e5c100);
            transform: translateY(-2px);
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        .text-register {
            color: #b0c4de;
            font-size: 0.9rem;
        }

        .text-register a {
            color: #ffd700;
            text-decoration: none;
            font-weight: 500;
        }

        .text-register a:hover {
            text-decoration: underline;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 25, 47, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: #fff;
            z-index: 9999;
        }

        .spinner-border {
            width: 2.8rem;
            height: 2.8rem;
            margin-bottom: 12px;
            color: #ffd700;
        }

        footer {
            margin-top: 25px;
            font-size: 0.85rem;
            color: #8892b0;
        }

        @media (max-width: 430px) {
            .login-wrapper {
                width: 90%;
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div id="particles-js"></div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner-border" role="status"></div>
        <p class="mt-2">Tunggu Sebentar ya...</p>
    </div>

    <!-- Login Form -->
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="login-wrapper text-center">
            <div class="login-header">
                <img src="<?= smart_url('assets/img/logo1.png') ?>" alt="Logo Sekolah">
                <h4>Sistem Informasi Sekolah</h4>
                <p>Kota Bekasi</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger p-2"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success p-2"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <form id="loginForm" action="<?= smart_url('login') ?>" method="post" class="text-start">
                <div class="mb-3 position-relative">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>

                <div class="mb-3 position-relative">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    <i class="bi bi-eye toggle-password" id="eyeIcon" onclick="togglePassword()"></i>
                </div>
                <div class="mt-2 text-end">
                    <a href="<?= smart_url('forgot-password') ?>" class="text-warning small">Lupa password?</a>
                </div>

                <button type="submit" class="btn btn-login">Masuk <i class="bi bi-box-arrow-in-right ms-1"></i></button>
            </form>

            <div class="mt-3 text-center text-register">
                Belum punya akun? <a href="<?= smart_url('register-siswa') ?>">Daftar Disini....!!</a>
            </div>

            <footer>© <?= date('Y') ?> Sistem Informasi Sekolah </footer>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
    <script>
        // Particle.js config
        particlesJS("particles-js", {
            particles: {
                number: {
                    value: 70,
                    density: {
                        enable: true,
                        value_area: 900
                    }
                },
                color: {
                    value: "#ffd700"
                },
                shape: {
                    type: "circle"
                },
                opacity: {
                    value: 0.3
                },
                size: {
                    value: 3
                },
                move: {
                    enable: true,
                    speed: 1,
                    direction: "none",
                    out_mode: "bounce"
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: "#007bff",
                    opacity: 0.2,
                    width: 1
                }
            },
            interactivity: {
                detect_on: "canvas",
                events: {
                    onhover: {
                        enable: true,
                        mode: "grab"
                    },
                    onclick: {
                        enable: false
                    },
                    resize: true
                },
                modes: {
                    grab: {
                        distance: 200,
                        line_linked: {
                            opacity: 0.4
                        }
                    }
                }
            },
            retina_detect: true
        });

        // Toggle Password
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        // Loading animation
        document.getElementById('loginForm').addEventListener('submit', function() {
            const overlay = document.getElementById('loadingOverlay');
            overlay.style.display = 'flex';
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 2500); // animasi sedikit lebih lama
        });
    </script>
</body>

</html>