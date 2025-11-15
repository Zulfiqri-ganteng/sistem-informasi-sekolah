<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="page-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><i class="fa fa-school me-2 text-primary"></i> Data Kelas</h4>
            <div>
                <button id="btnAdd" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Kelas</button>
            </div>
        </div>

        <!-- Grid Kelas -->
        <div id="kelasGrid" class="row g-3 mb-4"></div>

        <!-- Area detail (judul kelas + tabel siswa) -->
        <div id="kelasDetailArea" class="card shadow-sm border-0 d-none">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 id="detailTitle" class="mb-0 fw-bold"></h5>
                        <small id="detailSubtitle" class="text-muted"></small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-sm" id="detailTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 56px;"></th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th class="text-end">Saldo</th>
                            </tr>
                        </thead>
                        <tbody id="detailBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Form Kelas (single modal) -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <form id="formKelas" autocomplete="off">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Form Kelas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kelas</label>
                        <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" placeholder="Contoh: X TKJ 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Guru Wali</label>
                        <select name="guru_id" id="guru_id" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Slide Panel (detail siswa) -->
<div id="slidePanel" class="slide-panel">
    <div class="panel-header d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
        <div>
            <h5 id="panelTitle" class="mb-0 fw-bold"></h5>
            <small id="panelCount" class="text-muted"></small>
        </div>
        <div>
            <button id="slidePanelClose" class="btn btn-sm btn-light"><i class="fa fa-times"></i></button>
        </div>
    </div>

    <div class="panel-body p-3">
        <div class="list-group" id="panelList"></div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    $(function() {

        const base = '<?= smart_url("kelas") ?>';

        // small client cache to avoid extra requests when not needed
        const cache = {
            kelasList: null,
            siswaByKelas: {}
        };

        // utility: format rupiah
        function rupiah(val) {
            const n = Number(val) || 0;
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        // ======== load kelas dan render kartu ========
        async function loadKelas() {
            // try cache
            if (cache.kelasList) renderKelas(cache.kelasList);
            // fetch fresh
            try {
                const res = await $.getJSON(base + '/list');
                const data = res.data || [];
                cache.kelasList = data;
                renderKelas(data);
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Gagal memuat data kelas', 'error');
            }
        }

        function renderKelas(list) {
            const $grid = $('#kelasGrid').empty();

            if (!list.length) {
                $grid.html('<div class="col-12"><div class="alert alert-info">Belum ada data kelas.</div></div>');
                return;
            }

            list.forEach(k => {
                const jumlah = k.jumlah_siswa ?? 0; // optional from backend
                const totalSaldo = Number(k.total_saldo ?? 0);
                const avg = jumlah ? Math.round(totalSaldo / jumlah) : 0;

                // card color by size
                let badgeBg = 'bg-white';
                if (jumlah >= 40) badgeBg = 'bg-warning';
                else if (jumlah < 5) badgeBg = 'bg-info';

                const card = `
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <h5 class="fw-bold mb-1">${k.nama_kelas}</h5>
                    <div class="text-muted small">Wali: <strong>${k.guru_nama ?? '-'}</strong></div>
                  </div>
                  <div class="text-end">
                    <button class="btn btn-sm btn-outline-primary view-btn" data-id="${k.id}" title="Lihat Siswa">
                      <i class="fa fa-eye"></i>
                    </button>
                  </div>
                </div>

                <div class="mt-3 d-flex gap-2 align-items-center">
                  <div class="flex-grow-1">
                    <div class="small text-muted">Siswa</div>
                    <div class="fw-semibold">${k.jumlah_siswa ?? '—'}</div>
                  </div>

                  <div class="flex-grow-1">
                    <div class="small text-muted">Total Saldo</div>
                    <div class="fw-semibold">${ rupiah(k.total_saldo ?? 0) }</div>
                  </div>

                  <div class="flex-grow-1">
                    <div class="small text-muted">Rata-rata</div>
                    <div class="fw-semibold">${ rupiah(avg) }</div>
                  </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                  <button class="btn btn-warning btn-sm edit-btn flex-grow-1" data-id="${k.id}">
                    <i class="fa fa-pen me-1"></i> Edit
                  </button>
                  <button class="btn btn-danger btn-sm del-btn" data-id="${k.id}">
                    <i class="fa fa-trash me-1"></i> Hapus
                  </button>
                </div>
              </div>
            </div>
          </div>
        `;

                $grid.append(card);
            });
        }

        // ======== Load dropdown guru ========
        function loadGuruDropdown(selectedId = null) {
            $.getJSON(base + '/getGuruDropdown', function(list) {
                let html = '<option value="">-- Pilih Guru --</option>';
                list.forEach(g => {
                    let disabled = g.is_wali && g.id != selectedId ? 'disabled' : '';
                    let selected = g.id == selectedId ? 'selected' : '';
                    html += `<option value="${g.id}" ${disabled} ${selected}>${g.nama}${g.is_wali ? ' — Wali ' + g.kelas_wali : ''}</option>`;
                });
                $('#guru_id').html(html);
            }).fail(() => {
                $('#guru_id').html('<option value="">-- Gagal memuat guru --</option>');
            });
        }

        // ======== Add Kelas ========
        $('#btnAdd').on('click', function(e) {
            e.preventDefault();
            $('#formKelas')[0].reset();
            $('#id').val('');
            loadGuruDropdown();
            const modal = new bootstrap.Modal(document.getElementById('modalForm'));
            modal.show();
        });

        // ======== Submit form (save) ========
        $('#formKelas').on('submit', function(e) {
            e.preventDefault();
            const data = $(this).serialize();

            $.post(base + '/save', data, res => {
                if (res.success) {
                    $('#modalForm').modal('hide');
                    cache.kelasList = null; // refresh cache
                    loadKelas();
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan',
                        timer: 1200,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal', res.message || 'Gagal menyimpan', 'error');
                }
            }).fail(() => Swal.fire('Error', 'Tidak dapat terhubung ke server', 'error'));
        });

        // ======== Delegated events: Edit / Delete / View ========
        // Use event delegation so dynamically created cards work.

        // Edit: open modal with data
        $(document).on('click', '.edit-btn', function(e) {
            e.stopPropagation();
            const id = $(this).data('id');

            $.getJSON(base + '/get/' + id, res => {
                if (!res) return Swal.fire('Error', 'Data tidak ditemukan', 'error');

                $('#id').val(res.id);
                $('#nama_kelas').val(res.nama_kelas);
                loadGuruDropdown(res.guru_id);
                const modal = new bootstrap.Modal(document.getElementById('modalForm'));
                modal.show();
            }).fail(() => Swal.fire('Error', 'Gagal memuat data kelas', 'error'));
        });

        // Delete: confirm
        $(document).on('click', '.del-btn', function(e) {
            e.stopPropagation();
            const id = $(this).data('id');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data kelas tidak dapat dipulihkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!'
            }).then(result => {
                if (result.isConfirmed) {
                    $.getJSON(base + '/delete/' + id, res => {
                        cache.kelasList = null;
                        loadKelas();
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }).fail(() => Swal.fire('Error', 'Gagal menghapus', 'error'));
                }
            });
        });

        // View: open slide panel (or detail area on page)
        $(document).on('click', '.view-btn', function(e) {
            e.stopPropagation();
            const id = $(this).data('id');
            openSlidePanel(id);
        });

        // ======== Slide panel functions ========
        function openSlidePanel(kelasId) {
            const $panel = $('#slidePanel');
            // mobile detection: if small screen use full-screen modal style
            const isMobile = window.matchMedia('(max-width: 768px)').matches;

            // show loading
            $('#panelTitle').text('Memuat...');
            $('#panelCount').text('');
            $('#panelList').html('<div class="text-center py-4"><i class="fa fa-spinner fa-spin"></i></div>');
            $panel.addClass('open');

            // fetch siswa (cache)
            if (cache.siswaByKelas[kelasId]) {
                renderPanel(kelasId, cache.siswaByKelas[kelasId]);
                return;
            }

            $.getJSON(base + '/siswa/' + kelasId)
                .done(res => {
                    if (res.error) {
                        Swal.fire('Error', res.error, 'error');
                        $panel.removeClass('open');
                        return;
                    }
                    cache.siswaByKelas[kelasId] = res; // cache
                    renderPanel(kelasId, res);
                })
                .fail(() => {
                    Swal.fire('Error', 'Gagal memuat siswa', 'error');
                    $panel.removeClass('open');
                });

            function renderPanel(kelasId, res) {
                $('#panelTitle').text(res.kelas.nama_kelas);
                $('#panelCount').text((res.jumlah || 0) + ' siswa');

                if (!res.siswa || !res.siswa.length) {
                    $('#panelList').html('<div class="text-center text-muted py-4">Belum ada siswa</div>');
                    return;
                }

                let html = '';
                res.siswa.forEach((s, i) => {
                    const foto = s.foto ? '<?= smart_url("uploads/siswa") ?>/' + s.foto : '<?= smart_url("assets/img/default-avatar.png") ?>';
                    html += `
            <div class="d-flex align-items-center py-2 panel-item">
              <img src="${foto}" class="rounded-circle me-3" width="48" height="48" />
              <div class="flex-grow-1">
                <div class="fw-semibold">${s.nama}</div>
                <small class="text-muted">${s.nisn ?? '-'}</small>
              </div>
              <div class="text-end">
                <div class="fw-semibold">${rupiah(s.saldo)}</div>
                <a href="<?= smart_url('siswa') ?>/${s.id}" target="_blank" class="btn btn-sm btn-outline-primary mt-1"><i class="fa fa-eye"></i></a>
              </div>
            </div>
            <hr class="my-2"/>
          `;
                });

                $('#panelList').html(html);
            }
        }

        $('#slidePanelClose').on('click', function() {
            $('#slidePanel').removeClass('open');
        });

        // allow clicking outside slide panel to close (desktop)
        $(document).on('click', function(e) {
            const $panel = $('#slidePanel');
            if ($panel.hasClass('open')) {
                const inside = $(e.target).closest('#slidePanel').length;
                const isBtn = $(e.target).closest('.view-btn, .edit-btn, .del-btn').length;
                if (!inside && !isBtn) $panel.removeClass('open');
            }
        });

        // ======== Detail area on page (below grid) ========
        // Optional: when you want the full table on the page instead of slide panel
        // For now we show detail area if user clicks card title (not implemented as default)
        // You can call showDetailOnPage(kelasId) to render detail on page.

        function showDetailOnPage(kelasId) {
            $('#kelasDetailArea').removeClass('d-none');
            $('#detailBody').html('<tr><td colspan="5" class="text-center py-3"><i class="fa fa-spinner fa-spin"></i></td></tr>');
            $.getJSON(base + '/siswa/' + kelasId)
                .done(res => {
                    $('#detailTitle').text(res.kelas.nama_kelas);
                    $('#detailSubtitle').text((res.jumlah || 0) + ' siswa');
                    let rows = '';
                    (res.siswa || []).forEach((s, i) => {
                        const foto = s.foto ? '<?= smart_url("uploads/siswa") ?>/' + s.foto : '<?= smart_url("assets/img/default-avatar.png") ?>';
                        rows += `
              <tr>
                <td>${i+1}</td>
                <td><img src="${foto}" class="rounded-circle" width="36" height="36"></td>
                <td>${s.nisn ?? '-'}</td>
                <td>${s.nama}</td>
                <td class="text-end fw-semibold">${rupiah(s.saldo)}</td>
              </tr>
            `;
                    });
                    $('#detailBody').html(rows || '<tr><td colspan="5" class="text-center py-3">Belum ada siswa</td></tr>');
                })
                .fail(() => {
                    $('#detailBody').html('<tr><td colspan="5" class="text-center text-danger py-3">Gagal memuat</td></tr>');
                });
        }

        // ======== Prevent accidental double UI (modal + slide) ========
        // We specifically stopPropagation on action buttons above, and don't attach row click handlers.

        // ======== Initial load ========
        loadKelas();

        // Optional: refresh every X minutes for large school (set to 3 minutes)
        setInterval(() => {
            cache.kelasList = null;
            loadKelas();
        }, 3 * 60 * 1000);

    });
</script>

<style>
    /* Slide panel */
    .slide-panel {
        position: fixed;
        right: 0;
        top: 0;
        width: 420px;
        height: 100vh;
        background: #fff;
        box-shadow: -12px 0 40px rgba(0, 0, 0, .08);
        transform: translateX(100%);
        transition: transform .3s ease;
        z-index: 2200;
        display: flex;
        flex-direction: column;
    }

    .slide-panel.open {
        transform: translateX(0);
    }

    .slide-panel .panel-body {
        overflow-y: auto;
        flex: 1 1 auto;
    }

    .panel-item img {
        object-fit: cover;
    }

    /* Mobile: slide panel full screen */
    @media (max-width: 768px) {
        .slide-panel {
            width: 100%;
            transform: translateX(100%);
        }

        .slide-panel.open {
            transform: translateX(0);
        }
    }

    /* Card tweaks */
    #kelasGrid .card {
        border-radius: 10px;
    }

    #kelasGrid .card .view-btn {
        min-width: 40px;
    }

    /* Detail area */
    #kelasDetailArea {
        margin-top: 1.5rem;
    }

    /* small helpers */
    .flex-grow-1 {
        flex: 1 1 auto;
    }
</style>
<?= $this->endSection() ?>