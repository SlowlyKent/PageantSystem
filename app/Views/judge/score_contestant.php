<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Score Contestant<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .segment-card {
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 25px;
    }
    
    .segment-header-1 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
    }
    
    .segment-header-2 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 20px;
    }
    
    .criteria-card {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
    }
    
    .score-input {
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
    }
    
    .contestant-info {
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2">Enter Scores</h1>
        <p class="text-muted">Round <?= $round['round_number'] ?>: <?= esc($round['round_name']) ?></p>
    </div>
    <a href="<?= base_url("judge/score-round/{$round['id']}") ?>" class="btn btn-secondary text-white">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<!-- Contestant Info -->
<div class="contestant-info">
    <div class="d-flex align-items-center">
        <?php if (!empty($contestant['profile_picture'])): ?>
            <img src="<?= base_url('uploads/contestants/' . $contestant['profile_picture']) ?>" 
                 class="rounded-circle me-3" 
                 width="80" 
                 height="80"
                 style="object-fit: cover;">
        <?php else: ?>
            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                 style="width: 80px; height: 80px;">
                <i class="bi bi-person fs-2 text-white"></i>
            </div>
        <?php endif; ?>
        <div>
            <h3 class="mb-0"><?= esc($contestant['first_name'] . ' ' . $contestant['last_name']) ?></h3>
            <p class="text-muted mb-0">Contestant #<?= esc($contestant['contestant_number']) ?></p>
        </div>
    </div>
</div>

<!-- Scoring Form -->
<form action="<?= base_url('judge/submit-scores') ?>" method="post" id="scoringForm">
    <?= csrf_field() ?>
    <input type="hidden" name="round_id" value="<?= $round['id'] ?>">
    <input type="hidden" name="contestant_id" value="<?= $contestant['id'] ?>">
    
    <?php if (!empty($round['criteria'])): ?>
        <div class="segment-card">
            <div class="segment-header-1">
                <h4 class="mb-0">
                    <i class="bi bi-list-check"></i>
                    Criteria
                </h4>
                <small class="opacity-75">Total Weight: 100%</small>
            </div>
            <div class="card-body">
                <?php foreach ($round['criteria'] as $criteria): ?>
                        <?php
                    $existingScore = $existing_scores[$criteria['id']] ?? null;
                        $scoreValue = $existingScore['score'] ?? '';
                        ?>
                        
                        <div class="criteria-card">
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <h5 class="mb-1"><?= esc($criteria['criteria_name']) ?></h5>
                                    <?php if (!empty($criteria['description'])): ?>
                                        <small class="text-muted"><?= esc($criteria['description']) ?></small>
                                    <?php endif; ?>
                                    <div class="mt-2">
                                        <span class="badge bg-info">Weight: <?= number_format($criteria['percentage'], 0) ?>%</span>
                                        <span class="badge bg-secondary">Max: <?= $criteria['max_score'] ?></span>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label small">Score *</label>
                                    <input 
                                        type="number" 
                                        class="form-control score-input" 
                                        name="scores[<?= $criteria['id'] ?>]" 
                                        min="0" 
                                        max="<?= $criteria['max_score'] ?>" 
                                        step="0.01"
                                        value="<?= esc($scoreValue) ?>"
                                        required
                                        placeholder="0.00"
                                    >
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label small">Remarks (Optional)</label>
                                    <textarea 
                                        class="form-control" 
                                        name="remarks_<?= $criteria['id'] ?>" 
                                        rows="2"
                                        placeholder="Optional comments..."
                                    ><?= $existingScore['remarks'] ?? '' ?></textarea>
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No criteria defined for this round.</div>
    <?php endif; ?>
    
    <!-- Submit Button -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex gap-2 align-items-center">
                <button type="submit" class="btn btn-primary btn-lg text-white">
                    <i class="bi bi-check-circle"></i> Submit Scores
                </button>
                <a href="<?= base_url("judge/score-round/{$round['id']}") ?>" class="btn btn-secondary btn-lg text-white">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <div class="ms-auto text-muted">
                    <small>
                        <i class="bi bi-info-circle"></i> 
                        All scores are required. You can edit scores later.
                    </small>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Validate scores before submission
document.getElementById('scoringForm').addEventListener('submit', function(e) {
    const scoreInputs = document.querySelectorAll('.score-input');
    let isValid = true;
    
    scoreInputs.forEach(input => {
        const value = parseFloat(input.value);
        const max = parseFloat(input.max);
        
        if (isNaN(value) || value < 0 || value > max) {
            isValid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Please enter valid scores within the allowed range for all criteria.');
    }
});
</script>
<?= $this->endSection() ?>
