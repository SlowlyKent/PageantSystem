<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Add New Round<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-plus-circle-fill"></i> Add New Round</h1>
        <p class="text-muted">Create a round and define its judging criteria (no segments)</p>
    </div>
    <a href="<?= base_url('admin/rounds-criteria') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<!-- Validation Errors -->
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Form Card -->
<form action="<?= base_url('admin/rounds-criteria/store') ?>" method="post" id="roundForm">
    <?= csrf_field() ?>
    
    <!-- Round Information & Elimination Settings Row -->
    <div class="row mb-4">
        <!-- Left: Round Information -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle-fill"></i> Round Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="round_number" class="form-label">
                            Round Number <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="round_number" 
                            name="round_number" 
                            value="<?= $next_round_number ?>"
                            required
                            min="1"
                        >
                        <small class="text-muted">Auto-suggested</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="round_name" class="form-label">
                            Round Name <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="round_name" 
                            name="round_name" 
                            placeholder="e.g., Preliminary Round, Semi-Finals, Finals"
                            required
                        >
                    </div>
                    
                    <div class="mb-3">
                        <label for="round_description" class="form-label">Instructions</label>
                        <textarea 
                            class="form-control" 
                            id="round_description" 
                            name="round_description" 
                            rows="3"
                            placeholder="Guidelines visible to judges during scoring..."
                        ></textarea>
                    </div>
                    
                    <div class="mb-0">
                        <label for="max_score" class="form-label">
                            Round Max Score <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="max_score" 
                            name="max_score" 
                            placeholder="e.g., 100"
                            value="100"
                            min="1"
                            step="0.01"
                            required
                        >
                        <small class="text-muted">Total maximum score reference for the round</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right: Elimination Settings -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="bi bi-filter-circle-fill"></i> Elimination Settings</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_elimination" name="is_elimination" value="1">
                    <label class="form-check-label" for="is_elimination">
                        <strong>Enable Elimination in This Round</strong>
                    </label>
                </div>
                <small class="text-muted">If enabled, only top-ranked contestants will advance to the next round.</small>
            </div>

            <div id="elimination_quota_section" class="d-none">
                <label for="elimination_quota" class="form-label">
                    Number of Contestants to Advance (Top N) <span class="text-danger">*</span>
                </label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="elimination_quota" 
                    name="elimination_quota" 
                    min="1" 
                    placeholder="e.g., 5, 10, 15"
                >
            </div>

            <div class="mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_final" name="is_final" value="1">
                    <label class="form-check-label" for="is_final">
                        <strong>This is the Final Round</strong>
                    </label>
                </div>
                <small class="text-muted">If checked, results will show Winner, 1st Runner-Up, and 2nd Runner-Up.</small>
            </div>
        </div>
            </div>
        </div>
    </div>
    
    <!-- Criteria Management -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-ui-checks"></i> Round Criteria</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Add one or more criteria for this round. The total weight must equal 100%.</p>
            <div id="criteria-container"></div>
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mt-3">
                <div>
                    <span class="percentage-badge percentage-ok" id="criteria-total">0%</span>
                    <small class="text-muted ms-2">Total Weight</small>
                </div>
                <button type="button" class="add-criteria-btn" id="addCriteriaBtn" onclick="addCriteriaRow()">
                    <i class="bi bi-plus-circle"></i> Add Criteria
                </button>
            </div>
        </div>
    </div>
    
    <!-- Submit Buttons -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle"></i> Create Round
                </button>
                <a href="<?= base_url('admin/rounds-criteria') ?>" class="btn btn-secondary btn-lg">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
            <small class="text-muted d-block mt-2">💡 Tip: Make sure criteria weights add up to 100%</small>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let criteriaCounter = 0;
let addCriteriaBtn;

function getCriteriaTotal() {
    const inputs = document.querySelectorAll('input[name="criteria_percentage[]"]');
    let total = 0;
    inputs.forEach(input => total += parseFloat(input.value) || 0);
    return total;
}

function addCriteriaRow() {
    const currentTotal = getCriteriaTotal();
    if (currentTotal >= 99.99) {
        alert('The total weight is already 100%. Adjust existing weights before adding more criteria.');
        return;
    }

    const container = document.getElementById('criteria-container');
    const row = document.createElement('div');
    row.className = 'criteria-row';
    const index = ++criteriaCounter;
    row.id = `criteria-${index}`;
    row.innerHTML = `
        <button type="button" class="btn btn-sm btn-danger remove-criteria-btn" onclick="removeCriteriaRow(${index})">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="row">
            <div class="col-md-5 mb-2">
                <label class="form-label small">Criteria Name *</label>
                <input type="text" class="form-control" name="criteria_name[]" required placeholder="e.g., Stage Presence">
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small">Weight (%) *</label>
                <input type="number" class="form-control" name="criteria_percentage[]" min="0" max="100" step="0.01" required oninput="updateCriteriaTotal()">
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small">Max Score *</label>
                <input type="number" class="form-control" name="criteria_max_score[]" min="1" step="1" required placeholder="e.g., 50">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small">Description</label>
                <input type="text" class="form-control" name="criteria_description[]" placeholder="Optional">
            </div>
        </div>
    `;
    container.appendChild(row);
    updateCriteriaTotal();
}

function removeCriteriaRow(index) {
    const row = document.getElementById(`criteria-${index}`);
    if (row) {
        row.remove();
        updateCriteriaTotal();
    }
}

function updateCriteriaTotal() {
    const total = getCriteriaTotal();
    const badge = document.getElementById('criteria-total');
    badge.textContent = total.toFixed(2) + '%';
    badge.className = 'percentage-badge';
    if (Math.abs(total - 100) < 0.01) {
        badge.classList.add('percentage-ok');
    } else if (total < 100) {
        badge.classList.add('percentage-warning');
    } else {
        badge.classList.add('percentage-error');
    }

    if (addCriteriaBtn) {
        addCriteriaBtn.disabled = total >= 99.99;
    }
}

// Initialize with one criteria
window.addEventListener('DOMContentLoaded', () => {
    addCriteriaBtn = document.getElementById('addCriteriaBtn');
    addCriteriaRow();
});

// Toggle elimination quota field
document.getElementById('is_elimination').addEventListener('change', function() {
    const quotaSection = document.getElementById('elimination_quota_section');
    quotaSection.classList.toggle('d-none', !this.checked);
    if (this.checked) {
        document.getElementById('elimination_quota').setAttribute('required', 'required');
    } else {
        document.getElementById('elimination_quota').removeAttribute('required');
    }
});

// Form validation before submit
document.getElementById('roundForm').addEventListener('submit', function(e) {
    const criteriaRows = document.querySelectorAll('#criteria-container .criteria-row');
    if (criteriaRows.length === 0) {
        e.preventDefault();
        alert('Please add at least one criteria!');
        return false;
    }
    const total = Array.from(document.querySelectorAll('input[name="criteria_percentage[]"]'))
        .reduce((sum, el) => sum + (parseFloat(el.value) || 0), 0);
    if (Math.abs(total - 100) > 0.01) {
        e.preventDefault();
        alert(`Criteria weights must add up to 100%! Current total: ${total.toFixed(2)}%`);
        return false;
    }
});
</script>
<?= $this->endSection() ?>
