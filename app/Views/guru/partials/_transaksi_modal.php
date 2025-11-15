<!-- ===================================================== -->
<!--  MODAL TRANSAKSI GURU — FINAL SUPER PREMIUM VERSION   -->
<!-- ===================================================== -->

<div class="modal fade" id="modalTransaksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius:18px; overflow:hidden;">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-wallet me-2"></i> Transaksi Tabungan
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="formTransaksiGuru">

                <div class="modal-body">

                    <!-- SISWA -->
                    <label class="fw-semibold">Nama Siswa</label>
                    <select id="siswa_id" name="siswa_id" class="form-select mb-3" required>
                        <option value="">Memuat data...</option>
                    </select>

                    <!-- JENIS -->
                    <label class="fw-semibold">Jenis Transaksi</label>
                    <select name="tipe" class="form-select mb-3" required>
                        <option value="setor">Setor</option>
                        <option value="tarik">Tarik</option>
                    </select>

                    <!-- JUMLAH -->
                    <label class="fw-semibold">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control mb-3" min="100"
                        placeholder="Masukkan jumlah" required>

                    <!-- KETERANGAN -->
                    <label class="fw-semibold">Keterangan</label>
                    <textarea class="form-control" name="keterangan" rows="3"
                        placeholder="Contoh: nabung harian"></textarea>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>

                    <button type="submit" class="btn btn-primary px-4 fw-bold neon-btn">
                        <i class="fa-solid fa-check me-1"></i> Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


<style>
    /* Tombol glow */
    .neon-btn {
        transition: .25s ease;
    }

    .neon-btn:hover {
        box-shadow: 0 0 12px rgba(0, 123, 255, .9);
        transform: translateY(-2px);
    }
</style>


<script>
    document.addEventListener("DOMContentLoaded", () => {

        const modalEl = document.getElementById("modalTransaksi");
        const siswaDropdown = document.getElementById("siswa_id");

        // ===========================================================
        // 1. LOAD SISWA SAAT MODAL TERBUKA
        // ===========================================================
        modalEl.addEventListener("shown.bs.modal", () => {

            siswaDropdown.innerHTML = `<option>Loading...</option>`;

            fetch("<?= smart_url('guru/getSiswaKelas') ?>")
                .then(res => res.json())
                .then(data => {

                    siswaDropdown.innerHTML = "";

                    if (!data || data.length === 0) {
                        siswaDropdown.innerHTML = `<option value="">Tidak ada siswa</option>`;
                        return;
                    }

                    data.forEach(s => {
                        siswaDropdown.innerHTML += `
                        <option value="${s.id}">
                            ${s.nama} (${s.kelas})
                        </option>`;
                    });

                })
                .catch(() => {
                    siswaDropdown.innerHTML = `<option value="">Gagal memuat data siswa</option>`;
                });
        });


        // ===========================================================
        // 2. SUBMIT FORM TRANSAKSI (AJAX)
        // ===========================================================
        document.getElementById("formTransaksiGuru").addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch("<?= smart_url('guru/transaksi/create') ?>", {
                    method: "POST",
                    body: formData
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        showToast("Transaksi berhasil disimpan!", "success");

                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();

                        setTimeout(() => location.reload(), 600);
                    } else {
                        showToast(res.message ?? "Transaksi gagal", "danger");
                    }
                })
                .catch(() => showToast("Gagal menghubungkan server!", "danger"));
        });

    });


    // ===========================================================
    // 3. TOAST PREMIUM
    // ===========================================================
    function showToast(message, type = "success") {
        const toast = document.createElement("div");

        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.style.position = "fixed";
        toast.style.top = "20px";
        toast.style.right = "20px";
        toast.style.zIndex = "999999";

        toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

        document.body.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        setTimeout(() => toast.remove(), 3500);
    }
</script>