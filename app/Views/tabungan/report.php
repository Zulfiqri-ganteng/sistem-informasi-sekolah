<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Laporan Tabungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <h3>Laporan Rekap Tabungan</h3>
    <div class="mb-3">
        <select id="kelasFilter" class="form-select d-inline-block w-auto"></select>
        <select id="jurFilter" class="form-select d-inline-block w-auto"></select>
        <button id="btnLoad" class="btn btn-primary">Load</button>
        <a href="<?= site_url('tabungan/exportCsv') ?>" class="btn btn-outline-success">Ekspor CSV</a>
    </div>

    <table id="reportTable" class="table table-bordered">
        <thead>
            <tr>
                <th>NISN</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(function() {
            function loadFilters() {
                $.getJSON('<?= site_url() ?>/siswa/list', function(res) {
                    const list = res.data;
                    const kelas = Array.from(new Set(list.map(r => r.kelas).filter(x => x))).sort();
                    const jur = Array.from(new Set(list.map(r => r.jurusan).filter(x => x))).sort();
                    let ko = '<option value="">Semua Kelas</option>';
                    kelas.forEach(k => ko += `<option>${k}</option>`);
                    $('#kelasFilter').html(ko);
                    let jo = '<option value="">Semua Jurusan</option>';
                    jur.forEach(j => jo += `<option>${j}</option>`);
                    $('#jurFilter').html(jo);
                });
            }

            $('#btnLoad').on('click', function() {
                const kelas = $('#kelasFilter').val();
                const jur = $('#jurFilter').val();
                $.getJSON('<?= site_url() ?>/tabungan/reportData', {
                    kelas,
                    jur
                }, function(res) {
                    let html = '';
                    res.data.forEach(r => {
                        html += `<tr><td>${r.nisn}</td><td>${r.nama}</td><td>${r.kelas}</td><td>${r.jurusan}</td><td>Rp ${Number(r.saldo).toLocaleString('id-ID')}</td></tr>`;
                    });
                    $('#reportTable tbody').html(html);
                    if ($.fn.DataTable.isDataTable('#reportTable')) $('#reportTable').DataTable().destroy();
                    $('#reportTable').DataTable();
                });
            });

            loadFilters();
            $('#btnLoad').click();
        });
    </script>
</body>

</html>