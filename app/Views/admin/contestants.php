<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Manage Contestants<?= $this->endSection() ?>

<?= $this->section('content') ?>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="bi bi-people"></i> Manage Contestants</h1>
                <button class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Add New Contestant
                </button>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <p class="text-muted">Contestant management page - Coming soon!</p>
                </div>
            </div>
<?= $this->endSection() ?>
