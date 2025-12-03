<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Registrasi Siswa | Sistem Informasi Sekolah') ?></title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a192f, #112240);
            overflow: hidden;
        }

        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .register-wrapper {
            position: relative;
            z-index: 1;
            width: 450px;
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

        .register-header img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .register-header h4 {
            font-weight: 600;
            color: #ffd700;
        }

        .register-header p {
            color: #b0c4de;
            margin-bottom: 25px;
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.12);
            border: none;
            border-radius: 10px;
            color: #fff;
            padding-left: 40px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-select option {
            color: #000;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #ffd700;
            font-size: 1.1rem;
        }

        .btn-register {
            background: linear-gradient(135deg, #004aad, #ffd700);
            border: none;
            padding: 10px;
            border-radius: 10px;
            width: 100%;
            font-weight: 600;
            color: #fff;
            transition: 0.3s;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, #003380, #e5c100);
            transform: translateY(-2px);
        }

        .text-login a {
            color: #ffd700;
            text-decoration: none;
        }

        .alert-custom {
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 1rem;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: rgba(0, 255, 0, 0.1);
            color: #b6ffb6;
            border-left: 4px solid #00c851;
        }

        .alert-danger {
            background: rgba(255, 0, 0, 0.1);
            color: #ffb6b6;
            border-left: 4px solid #ff4444;
        }

        .close-btn {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.1rem;
            float: right;
            cursor: pointer;
        }

        .alert-auto {
            animation: fadeIn 0.5s ease;
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 1rem;
            position: relative;
            transition: all 0.5s ease;
        }

        .alert-success {
            background: rgba(0, 255, 0, 0.1);
            color: #b6ffb6;
            border-left: 4px solid #00c851;
        }

        .alert-danger {
            background: rgba(255, 0, 0, 0.1);
            color: #ffb6b6;
            border-left: 4px solid #ff4444;
        }

        .alert-auto.fade-out {
            opacity: 0;
            transform: translateY(-10px);
        }

        .close-btn {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.1rem;
            position: absolute;
            right: 10px;
            top: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="particles-js"></div>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="register-wrapper text-center">
            <div class="register-header">
                <img src="<?= smart_url('assets/img/logo3.png') ?>" alt="Logo Sekolah">
                <h4>Registrasi Siswa</h4>
                <p>Sistem Informasi Sekolah</p>
            </div>

            <!-- ✅ Alert Interaktif -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-custom">
                    <i class="bi bi-x-circle me-1"></i> <?= session()->getFlashdata('error') ?>
                    <button class="close-btn" onclick="this.parentElement.remove()">×</button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-custom">
                    <i class="bi bi-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
                    <button class="close-btn" onclick="this.parentElement.remove()">×</button>
                </div>
            <?php endif; ?>

            <!-- 🧾 Form Registrasi -->
            <form id="registerForm" action="<?= smart_url('register-siswa/submit') ?>" method="post" class="text-start">
                <div class="mb-3 position-relative">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Lengkap" required>
                </div>
                <div class="mb-3 position-relative">
                    <i class="bi bi-person input-icon"></i>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email contoh : zulfiqri@gmail.com" required>
                </div>
                <!-- <div class="mb-3">
                    <label for="email" class="form-label">Email Aktif</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="contoh: siswa@gmail.com" required>
                </div> -->

                <div class="mb-3 position-relative">
                    <i class="bi bi-credit-card input-icon"></i>
                    <input type="text" name="nisn" id="nisn" class="form-control" placeholder="NISN" required>
                </div>

                <div class="mb-3 position-relative">
                    <i class="bi bi-people input-icon"></i>
                    <select name="kelas" id="kelas" class="form-select" required>
                        <option value="" disabled selected>Pilih Kelas</option>
                        <?php foreach ($kelas as $k): ?>
                            <option value="<?= esc($k['nama_kelas']) ?>"><?= esc($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3 position-relative">
                    <i class="bi bi-mortarboard input-icon"></i>
                    <select name="jurusan" id="jurusan" class="form-select" required>
                        <option value="" disabled selected>Pilih Jurusan</option>
                        <?php foreach ($jurusan as $j): ?>
                            <option value="<?= esc($j['nama_jurusan']) ?>"><?= esc($j['nama_jurusan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-register">Daftar</button>
            </form>

            <div class="mt-3 text-login">
                Sudah punya akun? <a href="<?= smart_url('login') ?>">Login di sini</a>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
    <script>
        // Partikel latar belakang
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
                opacity: {
                    value: 0.3
                },
                size: {
                    value: 3
                },
                move: {
                    enable: true,
                    speed: 1
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: "#007bff",
                    opacity: 0.2,
                    width: 1
                }
            }
        });

        // Validasi interaktif sebelum submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const nama = document.getElementById('nama').value.trim();
            const nisn = document.getElementById('nisn').value.trim();
            const kelas = document.getElementById('kelas').value;
            const jurusan = document.getElementById('jurusan').value;

            if (!nama || !nisn || !kelas || !jurusan) {
                e.preventDefault();
                showToast('Harap lengkapi semua field sebelum daftar!');
            }
        });

        // Notifikasi interaktif
        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'alert alert-danger alert-custom';
            toast.innerHTML = `<i class="bi bi-exclamation-triangle me-1"></i> ${message} 
                <button class="close-btn" onclick="this.parentElement.remove()">×</button>`;
            document.querySelector('.register-wrapper').prepend(toast);
            setTimeout(() => toast.remove(), 4000);
        }

        // Auto-hide pesan sukses
        const alertSuccess = document.querySelector('.alert-success');
        if (alertSuccess) {
            setTimeout(() => {
                alertSuccess.style.opacity = '0';
                setTimeout(() => alertSuccess.remove(), 500);
            }, 5000);
        }
    </script>
    <script>
        // Auto-hide flash message
        document.addEventListener("DOMContentLoaded", () => {
            const alertBox = document.querySelector('.alert-auto');
            if (alertBox) {
                // auto close after 5 seconds
                setTimeout(() => {
                    alertBox.classList.add('fade-out');
                    setTimeout(() => alertBox.remove(), 500);
                }, 5000);
            }
        });

        // manual close button
        function closeAlert(button) {
            const alertBox = button.closest('.alert-auto');
            alertBox.classList.add('fade-out');
            setTimeout(() => alertBox.remove(), 400);
        }
    </script>

</body>

</html>