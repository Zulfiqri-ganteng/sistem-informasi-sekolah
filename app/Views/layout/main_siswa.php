<div class="sidebar">
    <div class="logo">
        <i class="fa fa-graduation-cap"></i>
        <span>Sistem Informasi Sekolah</span>
    </div>
    <ul>
        <li><a href="<?= smart_url('siswa/dashboard') ?>" class="<?= url_is('siswa/dashboard') ? 'active' : '' ?>"><i class="fa fa-home"></i> Dashboard</a></li>
        <li><a href="<?= smart_url('siswa/transaksi') ?>" class="<?= url_is('siswa/transaksi') ? 'active' : '' ?>"><i class="fa fa-wallet"></i> Transaksi</a></li>
        <li><a href="<?= smart_url('siswa/profil') ?>" class="<?= url_is('siswa/profil') ? 'active' : '' ?>"><i class="fa fa-user"></i> Profil</a></li>
        <li><a href="<?= smart_url('logout') ?>" class="text-danger"><i class="fa fa-sign-out-alt"></i> Keluar</a></li>
    </ul>
</div>