<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title><?= esc($subject ?? 'Sistem Informasi Sekolah') ?></title>
</head>

<body style="background:#eef2f7; font-family:'Poppins',sans-serif; margin:0; padding:25px;">

  <table align="center" cellpadding="0" cellspacing="0"
    style="
            max-width:600px;
            width:100%;
            background:#ffffff;
            border-radius:14px;
            overflow:hidden;
            box-shadow:0 6px 28px rgba(0,0,0,0.14);
        ">

    <!-- HEADER -->
    <tr>
      <td style="
                background:linear-gradient(135deg,#004aad,#e6b800);
                padding:28px 20px;
                text-align:center;
                color:#ffffff;
            ">
        <h2 style="
                    margin:0;
                    font-size:24px;
                    font-weight:600;
                    letter-spacing:0.5px;
                ">
          Sistem Informasi Sekolah
        </h2>

        <p style="
                    margin:6px 0 0;
                    font-size:14px;
                    opacity:0.92;
                ">
          Kota Bekasi
        </p>
      </td>
    </tr>

    <!-- CONTENT -->
    <tr>
      <td style="
                padding:38px 32px;
                color:#333333;
                font-size:15px;
                line-height:1.75;
            ">
        <?= $content ?? '' ?>
      </td>
    </tr>

    <!-- FOOTER -->
    <tr>
      <td style="
                background:#004aad;
                color:#ffffff;
                text-align:center;
                padding:18px 15px;
                font-size:13px;
                line-height:1.5;
                letter-spacing:0.3px;
            ">
        © <?= date('Y') ?> — Created By <strong>Zulfiqri, S.Kom</strong><br>
        <span style="opacity:0.85;">Email ini dikirim otomatis, mohon tidak membalas.</span>
      </td>
    </tr>

  </table>

</body>

</html>