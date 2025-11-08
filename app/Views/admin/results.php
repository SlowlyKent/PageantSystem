<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Results<?= $this->endSection() ?>

<?= $this->section('content') ?>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="bi bi-trophy"></i> Results</h1>
                <button class="btn btn-primary">
                    <i class="bi bi-download"></i> Export Results
                </button>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <p class="text-muted">Results page - Coming soon!</p>
                </div>
            </div>
<?= $this->endSection() ?>
