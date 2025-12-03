<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Sistem Informasi Sekolah</title>

    <!-- Bootstrap & Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a192f, #112240);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Card container */
        .reset-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 215, 0, 0.2);
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            padding: 2.5rem 2rem;
            width: 400px;
            color: #fff;
            text-align: center;
            position: relative;
            z-index: 2;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h3 {
            color: #ffd700;
            font-weight: 600;
            margin-bottom: 15px;
        }

        p.subtext {
            color: #b0c4de;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            border-radius: 10px;
            color: #fff;
            padding-left: 40px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #ffd700;
        }

        .btn-reset {
            background: linear-gradient(135deg, #004aad, #ffd700);
            border: none;
            border-radius: 10px;
            width: 100%;
            color: #fff;
            font-weight: 600;
            padding: 10px;
            transition: 0.3s;
        }

        .btn-reset:hover {
            background: linear-gradient(135deg, #003380, #e6c400);
            transform: translateY(-2px);
            box-shadow: 0 0 12px rgba(255, 215, 0, 0.4);
        }

        a.back-login {
            color: #ffd700;
            text-decoration: none;
            font-weight: 500;
        }

        a.back-login:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        /* Particles background */
        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        @media (max-width: 430px) {
            .reset-card {
                width: 90%;
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div id="particles-js"></div>

    <div class="reset-card">
        <h3><i class="bi bi-shield-lock me-2"></i>Reset Password</h3>
        <p class="subtext">Masukkan email yang terdaftar untuk menerima tautan reset password.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="<?= smart_url('forgot-password') ?>" method="post" class="text-start">
            <div class="mb-3 position-relative">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email akun" required>
            </div>
            <button type="submit" class="btn btn-reset">
                <i class="bi bi-send me-1"></i> Kirim Link Reset
            </button>
        </form>

        <div class="mt-3">
            <a href="<?= smart_url('login') ?>" class="back-login"><i class="bi bi-arrow-left me-1"></i> Kembali ke login</a>
        </div>
    </div>

    <!-- Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 60,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffd700"
                },
                "shape": {
                    "type": "circle"
                },
                "opacity": {
                    "value": 0.3
                },
                "size": {
                    "value": 3
                },
                "move": {
                    "enable": true,
                    "speed": 1.2
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#007bff",
                    "opacity": 0.25,
                    "width": 1
                }
            }
        });
    </script>
</body>

</html>