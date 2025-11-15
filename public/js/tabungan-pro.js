$(function () {
  const base = '<?= smart_url() ?>';
  const modalTransaksi = new bootstrap.Modal($("#modalTransaksi")[0]);
  const saldoChart = new Chart($("#saldoChart"), { type: "bar", data: { labels: [], datasets: [{ label: "Total Saldo", data: [], backgroundColor: "#1e6fef" }] }, options: { responsive: true, scales: { y: { beginAtZero: true } } } });

  // DataTable
  const table = $("#tableTabungan").DataTable({
    ajax: { url: base + "/tabungan/list", dataSrc: "data" },
    columns: [
      { data: null, render: (d, t, r, m) => m.row + 1 },
      { data: "nama", render: (d) => `<strong>${d}</strong>` },
      { data: "kelas" },
      { data: "jurusan" },
      { data: "saldo", className: "text-end", render: (d) => "Rp " + Number(d).toLocaleString("id-ID") },
      {
        data: null,
        className: "text-center",
        render: (d) => `
        <button class="btn btn-sm btn-success btn-setor" data-id="${d.id}"><i class="fa-solid fa-wallet"></i></button>
        <button class="btn btn-sm btn-light border btn-mutasi" data-id="${d.id}"><i class="fa-solid fa-list"></i></button>`,
      },
    ],
  });

  // KPI & chart
  function refreshDashboard() {
    $.getJSON(base + "/tabungan/dashboard", (res) => {
      $("#kpiCount").text(res.totalSavers);
      $("#kpiSaldo").text("Rp " + Number(res.totalSaldo).toLocaleString("id-ID"));
      $("#kpiTopClass").text(res.kelasTop.kelas);
      saldoChart.data.labels = res.byKelas.map((x) => x.kelas);
      saldoChart.data.datasets[0].data = res.byKelas.map((x) => x.total);
      saldoChart.update();
    });
  }
  refreshDashboard();

  // Transaksi confirm
  $("#formTransaksi").on("submit", function (e) {
    e.preventDefault();
    const fd = $(this).serialize();
    const tipe = $("#tx_tipe").val();
    const jml = $("#tx_jumlah").val();
    Swal.fire({
      title: "Konfirmasi Transaksi",
      html: `<b>${tipe.toUpperCase()}</b> sejumlah <b>Rp ${Number(jml).toLocaleString("id-ID")}</b>?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Ya",
      cancelButtonText: "Batal",
    }).then((r) => {
      if (r.isConfirmed) {
        $.post(
          base + "/tabungan/transaction",
          fd,
          (res) => {
            if (res.success) {
              Swal.fire("Berhasil!", "Transaksi disimpan.", "success");
              modalTransaksi.hide();
              table.ajax.reload();
              refreshDashboard();
            } else Swal.fire("Gagal", res.msg || "Server error", "error");
          },
          "json"
        );
      }
    });
  });
});
