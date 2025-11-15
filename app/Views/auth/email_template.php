<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= esc($subject ?? 'SMK Galajuara') ?></title>
</head>
<body style="background:#f4f7fb;font-family:'Poppins',sans-serif;margin:0;padding:20px;">
  <table align="center" cellpadding="0" cellspacing="0" style="max-width:600px;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,0.1);">
    <tr>
      <td style="background:linear-gradient(135deg,#004aad,#e6b800);padding:20px;text-align:center;color:#fff;">
        <h2 style="margin:0;">Sistem Informasi Sekolah</h2>
        <p style="margin:0;">Kota Bekasi</p>
      </td>
    </tr>

    <tr>
      <td style="padding:30px;color:#333;">
        <?= $content ?? '' ?>
      </td>
    </tr>

    <tr>
      <td style="background:#004aad;color:#fff;text-align:center;padding:15px;font-size:13px;">
        © <?= date('Y') ?>Created By Zulfiqri,S.Kom
      </td>
    </tr>
  </table>
</body>
</html>
