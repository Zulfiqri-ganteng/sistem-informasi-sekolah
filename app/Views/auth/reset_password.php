<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Sistem Informasi Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #004aad, #007bff);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: white;
            border-bottom: none;
            text-align: center;
            padding-top: 30px;
        }

        .card-header h4 {
            color: #004aad;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .card-body {
            padding: 30px;
            background: #fff;
            border-radius: 0 0 16px 16px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ccc;
            padding: 10px 15px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #004aad;
            box-shadow: 0 0 0 0.15rem rgba(0, 74, 173, 0.25);
        }

        .btn-primary {
            background: #004aad;
            border: none;
            border-radius: 10px;
            padding: 10px;
            width: 100%;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #003d91;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #fff;
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">
            <h4>🔒 Reset Password</h4>
            <p style="color:#777;">Masukkan password baru Anda</p>
        </div>
        <div class="card-body">
            <form action="<?= base_url('reset-password/' . esc($token)) ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Simpan Password</button>
            </form>
        </div>
    </div>

    <div class="footer-text">
        © <?= date('Y') ?> Sistem Informasi Sekolah
    </div>
</body>

</html>