<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="content-card">
    <h4><i class="fa fa-bug text-danger"></i> Log Error Sistem</h4>
    <hr>

    <table class="table table-striped table-bordered" id="errorLogTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Level</th>
                <th>Message</th>
                <th>URL</th>
                <th>User</th>
                <th>IP</th>
                <th>Time</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $l): ?>
            <tr>
                <td><?= $l['id'] ?></td>
                <td><span class="badge bg-danger"><?= $l['level'] ?></span></td>
                <td><?= substr(strip_tags($l['message']), 0, 50) ?>...</td>
                <td><?= $l['url'] ?></td>
                <td><?= $l['user_role'] ?> (<?= $l['user_id'] ?>)</td>
                <td><?= $l['ip_address'] ?></td>
                <td><?= $l['created_at'] ?></td>
                <td>
                    <a href="<?= smart_url('admin/error-log/'.$l['id']) ?>" class="btn btn-sm btn-primary">
                        Detail
                    </a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#errorLogTable').DataTable();
});
</script>

<?= $this->endSection() ?>
