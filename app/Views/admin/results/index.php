<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Results & Rankings<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .result-card {
        transition: all 0.3s;
        border-left: 4px solid #28a745;
    }
    
    .result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    
    .overall-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        cursor: pointer;
    }
    
    .overall-card:hover {
        transform: scale(1.05);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-trophy-fill"></i> Results & Rankings</h1>
    <p class="text-muted">View scores and rankings for each round</p>
</div>

<!-- Overall Rankings Card -->
<div class="row mb-4">
    <div class="col-12">
        <a href="<?= base_url('admin/results/overall') ?>" class="text-decoration-none">
            <div class="card overall-card shadow">
                <div class="card-body text-center py-5">
                    <i class="bi bi-trophy" style="font-size: 64px;"></i>
                    <h3 class="mt-3">View Overall Rankings</h3>
                    <p class="mb-0">Combined rankings across all completed rounds</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Round Results -->
<h4 class="mb-3">Round-by-Round Results</h4>
<div class="row">
    <?php foreach ($rounds as $round): ?>
        <div class="col-md-6 mb-4">
            <div class="card result-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="mb-1">
                                <span class="badge bg-primary me-2">Round <?= $round['round_number'] ?></span>
                                <?= esc($round['round_name']) ?>
                            </h4>
                        </div>
                        <div>
                            <?php
                            $statusClass = match($round['status']) {
                                'active' => 'warning',
                                'completed' => 'success',
                                'inactive' => 'secondary',
                                default => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?= $statusClass ?>">
                                <?= ucfirst($round['status']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge bg-info"><?= $round['segment_count'] ?> Segment(s)</span>
                    </div>
                    
                    <a href="<?= base_url("admin/results/round/{$round['id']}") ?>" 
                       class="btn btn-success w-100">
                        <i class="bi bi-bar-chart-fill"></i> View Rankings
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
