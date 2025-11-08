<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Manage Judges<?= $this->endSection() ?>

<?= $this->section('content') ?>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="bi bi-person-badge"></i> Manage Judges</h1>
                <button class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Add New Judge
                </button>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <p class="text-muted">Judge management page - Coming soon!</p>
                </div>
            </div>
<?= $this->endSection() ?>
