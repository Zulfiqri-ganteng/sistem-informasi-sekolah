<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    .card-custom {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 1rem;
    }

    .btn-clean-6 {
        background: linear-gradient(45deg, #ff9800, #e65100);
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: .2s;
    }

    .btn-clean-6:hover {
        transform: translateY(-2px);
    }

    .btn-clean-all {
        background: linear-gradient(45deg, #d90429, #9a031e);
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: .2s;
    }

    .btn-clean-all:hover {
        transform: translateY(-2px);
    }

    .btn-export {
        background: linear-gradient(45deg, #43aa8b, #4d908e);
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: .2s;
    }

    .btn-export:hover {
        transform: translateY(-2px);
    }

    .refresh-btn {
        background: linear-gradient(135deg, #4895ef, #4361ee);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 15px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .table-responsive-custom {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .table thead th {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border: none;
        padding: 12px 8px;
        font-weight: 600;
        font-size: 0.875rem;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 10px 8px;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .badge-role {
        padding: 0.3em 0.6em;
        border-radius: 15px;
        font-weight: 600;
        font-size: 0.75rem;
        white-space: nowrap;
    }

    .badge-admin {
        background-color: #f72585;
        color: white;
    }

    .badge-guru {
        background-color: #f8961e;
        color: white;
    }

    .badge-siswa {
        background-color: #43aa8b;
        color: white;
    }

    .badge-module {
        background-color: #4895ef;
        color: white;
        font-size: 0.75rem;
        padding: 0.3em 0.6em;
    }

    .action-badge {
        padding: 0.2em 0.5em;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.7rem;
        white-space: nowrap;
    }

    .badge-create {
        background-color: #4cc9f0;
        color: white;
    }

    .badge-update {
        background-color: #f8961e;
        color: white;
    }

    .badge-delete {
        background-color: #f72585;
        color: white;
    }

    .badge-read {
        background-color: #43aa8b;
        color: white;
    }

    .badge-login {
        background-color: #7209b7;
        color: white;
    }

    .detail-modal .modal-content {
        border-radius: 10px;
        border: none;
    }

    .detail-modal .modal-header {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 1rem;
    }

    .detail-modal .modal-body {
        padding: 1rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .detail-item {
        margin-bottom: 0.8rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #e9ecef;
    }

    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .detail-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.2rem;
        font-size: 0.875rem;
    }

    .detail-value {
        color: #6c757d;
        word-break: break-word;
        font-size: 0.875rem;
    }

    .json-view {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 0.8rem;
        max-height: 200px;
        overflow-y: auto;
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn-mobile {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .table-responsive-custom {
            font-size: 0.8rem;
        }

        .table thead th,
        .table tbody td {
            padding: 8px 6px;
        }

        .btn-group-mobile {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding: 0 10px;
        }

        .card-custom {
            margin-bottom: 0.5rem;
        }

        .table thead th:nth-child(4),
        .table tbody td:nth-child(4),
        .table thead th:nth-child(6),
        .table tbody td:nth-child(6) {
            display: none;
        }

        .filter-card .col-md-3,
        .filter-card .col-md-2 {
            margin-bottom: 0.5rem;
        }
    }

    @media (max-width: 400px) {

        .table thead th:nth-child(7),
        .table tbody td:nth-child(7) {
            display: none;
        }

        h1 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header dengan Judul dan Actions -->
    <div class="card card-custom mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-2 mb-md-0">
                    <h1 class="h4 mb-1">
                        <i class="fa-solid fa-list-check text-primary me-2"></i>
                        Log Aktivitas
                    </h1>
                    <p class="text-muted mb-0 small">Pantau semua aktivitas sistem</p>
                </div>

                <div class="btn-group-mobile">
                    <button id="refreshTable" class="btn refresh-btn btn-mobile">
                        <i class="fa-solid fa-rotate"></i> Refresh
                    </button>

                    <?php if (session()->get('role') === 'admin'): ?>
                        <button id="btnExport" class="btn btn-export btn-mobile">
                            <i class="fa-solid fa-file-export"></i> Export
                        </button>
                        <button id="btnClean6" class="btn btn-clean-6 btn-mobile">
                            <i class="fa-solid fa-broom"></i> > 6 Bulan
                        </button>
                        <button id="btnCleanAll" class="btn btn-clean-all btn-mobile">
                            <i class="fa-solid fa-trash-can"></i> Hapus Semua
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card card-custom filter-card">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="fa-solid fa-filter me-2"></i>Filter Data
            </h5>

            <div class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label small">Rentang Tanggal</label>
                    <input id="dateRange" class="form-control form-control-sm" placeholder="Pilih tanggal">
                </div>

                <div class="col-md-2 col-sm-6">
                    <label class="form-label small">Role</label>
                    <select id="filterRole" class="form-select form-select-sm">
                        <option value="all">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                    </select>
                </div>

                <div class="col-md-3 col-sm-6">
                    <label class="form-label small">Pencarian</label>
                    <input id="q" class="form-control form-control-sm" placeholder="Cari...">
                </div>

                <div class="col-md-2 col-sm-6">
                    <label class="form-label small">Modul</label>
                    <select id="filterModule" class="form-select form-select-sm">
                        <option value="all">Semua Modul</option>
                    </select>
                </div>

                <div class="col-md-2 col-sm-12 d-flex align-items-end">
                    <div class="w-100 d-flex flex-column flex-sm-row gap-2">
                        <button id="applyFilter" class="btn btn-primary btn-sm flex-fill">
                            <i class="fa-solid fa-filter"></i> Terapkan
                        </button>
                        <button id="resetFilter" class="btn btn-outline-secondary btn-sm flex-fill">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-responsive table-responsive-custom">
        <table id="activityTable" class="table table-hover table-sm">
            <thead class="table-dark">
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Actor</th>
                    <th width="10%">Role</th>
                    <th width="12%">Module</th>
                    <th width="10%">Action</th>
                    <th width="20%">Detail</th>
                    <th width="10%">IP</th>
                    <th width="13%">Waktu</th>
                    <th width="5%">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL DETAIL -->
<div class="modal fade detail-modal" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-circle-info me-2"></i> Detail Aktivitas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="detail-item">
                    <div class="detail-label">ID</div>
                    <div class="detail-value" id="detail-id">-</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Actor</div>
                    <div class="detail-value" id="detail-actor">-</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Role</div>
                    <div class="detail-value" id="detail-role">-</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Module</div>
                    <div class="detail-value" id="detail-module">-</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Action</div>
                    <div class="detail-value" id="detail-action">-</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Detail</div>
                    <div class="detail-value" id="detail-detail">-</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">IP Address</div>
                    <div class="detail-value" id="detail-ip">-</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">User Agent</div>
                    <div class="detail-value" id="detail-useragent">-</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Waktu</div>
                    <div class="detail-value" id="detail-time">-</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Meta Data</div>
                    <div class="json-view" id="detail-meta">-</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    $(function() {
        // Format tanggal
        const formatDate = (dateString) => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleString('id-ID', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        // Get badge class berdasarkan role
        const getRoleBadgeClass = (role) => {
            switch (role) {
                case 'admin':
                    return 'badge-role badge-admin';
                case 'guru':
                    return 'badge-role badge-guru';
                case 'siswa':
                    return 'badge-role badge-siswa';
                default:
                    return 'badge-role badge-secondary';
            }
        };

        // Get badge class berdasarkan action
        const getActionBadgeClass = (action) => {
            switch (action) {
                case 'create':
                case 'setor':
                    return 'action-badge badge-create';
                case 'update':
                case 'edit':
                    return 'action-badge badge-update';
                case 'delete':
                case 'hapus':
                    return 'action-badge badge-delete';
                case 'read':
                case 'mutasi':
                    return 'action-badge badge-read';
                case 'login':
                    return 'action-badge badge-login';
                case 'clean':
                case 'cleanAll':
                    return 'action-badge badge-warning';
                default:
                    return 'action-badge badge-secondary';
            }
        };

        // INIT DATATABLE
        let table = $('#activityTable').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            ajax: {
                url: "<?= site_url('activity/ajaxList') ?>",
                type: "POST",
                data: function(d) {
                    let dateRange = $('#dateRange').val();
                    if (dateRange) {
                        let r = dateRange.split('-');
                        d.date_from = r[0]?.trim();
                        d.date_to = r[1]?.trim();
                    }
                    d.role = $('#filterRole').val();
                    d.q = $('#q').val();
                    d.module = $('#filterModule').val() === 'all' ? '' : $('#filterModule').val();
                },
                dataSrc: function(response) {
                    console.log('Response dari server:', response);

                    // Update module filter
                    if (response.data && response.data.length > 0) {
                        updateModuleFilter(response.data);
                    }

                    return response.data || [];
                },
                error: function(xhr, error, thrown) {
                    console.log('Error loading data:', error, xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data log aktivitas',
                        timer: 3000
                    });
                }
            },
            columns: [{
                    data: 'id',
                    className: 'text-center'
                },
                {
                    data: 'actor_name',
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fa-solid fa-user-circle me-1 text-muted small"></i>
                                </div>
                                <div class="flex-grow-1 ms-1">
                                    <div class="fw-semibold small">${data || 'Guest'}</div>
                                    <small class="text-muted" style="font-size: 0.7rem;">ID: ${row.actor_id || '-'}</small>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    data: 'actor_role',
                    render: function(data) {
                        return `<span class="${getRoleBadgeClass(data)}">${data}</span>`;
                    }
                },
                {
                    data: 'module',
                    render: function(data) {
                        return `<span class="badge badge-module">${data || '-'}</span>`;
                    }
                },
                {
                    data: 'action',
                    render: function(data) {
                        return `<span class="${getActionBadgeClass(data)}">${data || '-'}</span>`;
                    }
                },
                {
                    data: 'detail_short',
                    render: function(data, type, row) {
                        const detail = row.detail || '-';
                        const short = data || '-';
                        return `
                            <div class="text-truncate" style="max-width: 200px;" title="${detail}">
                                <small>${short}</small>
                            </div>
                        `;
                    }
                },
                {
                    data: 'ip_address',
                    className: 'text-nowrap'
                },
                {
                    data: 'created_at',
                    render: function(data) {
                        return `<small>${formatDate(data)}</small>`;
                    },
                    className: 'text-nowrap'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-info btn-sm btn-detail" data-id="${row.id}" title="Lihat Detail">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        `;
                    },
                    className: 'text-center'
                }
            ],
            order: [
                [0, 'desc']
            ],
            language: {
                emptyTable: "Tidak ada data log aktivitas",
                zeroRecords: "Tidak ada data yang cocok dengan filter",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                lengthMenu: "Tampilkan _MENU_ data",
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            pageLength: 25,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ]
        });

        // Update module filter options
        function updateModuleFilter(data) {
            const modules = [...new Set(data.map(item => item.module).filter(Boolean))].sort();
            const moduleFilter = $('#filterModule');

            // Simpan nilai yang dipilih
            const currentValue = moduleFilter.val();

            // Clear existing options (kecuali "Semua Modul")
            moduleFilter.find('option:not(:first)').remove();

            // Add module options
            modules.forEach(module => {
                if (module) {
                    moduleFilter.append(`<option value="${module}">${module}</option>`);
                }
            });

            // Kembalikan nilai yang dipilih jika masih ada
            if (currentValue && modules.includes(currentValue)) {
                moduleFilter.val(currentValue);
            }
        }

        // Event handlers
        $('#applyFilter').click(function() {
            table.ajax.reload();
        });

        $('#resetFilter').click(function() {
            $('#dateRange').val('');
            $('#filterRole').val('all');
            $('#filterModule').val('all');
            $('#q').val('');
            table.ajax.reload();
        });

        $('#refreshTable').click(function() {
            table.ajax.reload();
            Swal.fire({
                title: 'Memperbarui Data',
                text: 'Data log aktivitas sedang diperbarui...',
                icon: 'info',
                timer: 1000,
                showConfirmButton: false
            });
        });

        $('#btnExport').click(function() {
            window.location.href = "<?= site_url('activity/exportCsv') ?>";
        });

        // VIEW DETAIL
        $('#activityTable').on('click', '.btn-detail', function() {
            const id = $(this).data('id');

            // Tampilkan loading
            $('#detail-id, #detail-actor, #detail-role, #detail-module, #detail-action, #detail-detail, #detail-ip, #detail-useragent, #detail-time, #detail-meta')
                .text('Memuat...');

            $('#detailModal').modal('show');

            $.get("<?= site_url('activity/view') ?>/" + id, function(res) {
                // Format data untuk ditampilkan
                $('#detail-id').text(res.id || '-');
                $('#detail-actor').text(res.actor_name || '-');
                $('#detail-role').html(`<span class="${getRoleBadgeClass(res.role)}">${res.role || '-'}</span>`);
                $('#detail-module').text(res.module || '-');
                $('#detail-action').html(`<span class="${getActionBadgeClass(res.action)}">${res.action || '-'}</span>`);
                $('#detail-detail').text(res.detail || '-');
                $('#detail-ip').text(res.ip_address || '-');
                $('#detail-useragent').text(res.user_agent || '-');
                $('#detail-time').text(formatDate(res.created_at) || '-');

                // Format meta data
                if (res.meta_parsed) {
                    $('#detail-meta').text(JSON.stringify(res.meta_parsed, null, 2));
                } else if (res.meta) {
                    $('#detail-meta').text(res.meta);
                } else {
                    $('#detail-meta').text('-');
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memuat detail aktivitas',
                    timer: 3000
                });
                $('#detailModal').modal('hide');
            });
        });

        // HAPUS LOG > 6 BULAN
        $('#btnClean6').click(function() {
            Swal.fire({
                title: "Periksa Log Lama",
                text: "Mengambil jumlah log yang berusia lebih dari 6 bulan...",
                icon: "info",
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    fetch("<?= site_url('maintenance/getLogCount') ?>")
                        .then(r => r.json())
                        .then(res => {
                            Swal.fire({
                                title: "Konfirmasi Penghapusan",
                                html: `Ditemukan <b>${res.total}</b> log yang berusia lebih dari 6 bulan.<br>Yakin ingin menghapus?`,
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonText: "Ya, Hapus",
                                cancelButtonText: "Batal",
                                confirmButtonColor: "#ff9800"
                            }).then(v => {
                                if (!v.isConfirmed) return;
                                window.location.href = "<?= site_url('maintenance/cleanLog') ?>";
                            });
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal memeriksa log lama',
                                timer: 3000
                            });
                        });
                }
            });
        });

        // HAPUS SEMUA LOG
        $('#btnCleanAll').click(function() {
            Swal.fire({
                title: "Hapus Semua Log?",
                html: "<b class='text-danger'>Semua log aktivitas akan dihapus permanen!</b><br><small>Tindakan ini tidak dapat dibatalkan.</small>",
                icon: "error",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus Semua",
                cancelButtonText: "Batal",
                confirmButtonColor: "#d90429"
            }).then(v => {
                if (!v.isConfirmed) return;
                window.location.href = "<?= site_url('maintenance/cleanAll') ?>";
            });
        });

        // Initialize simple date range (basic version)
        $('#dateRange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Batal',
                applyLabel: 'Pilih',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            }
        });

        $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>

<?= $this->endSection() ?>