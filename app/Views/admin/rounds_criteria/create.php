<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Add New Round<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .segment-section {
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        border: 2px solid #667eea;
    }
    
    .segment-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .criteria-row {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
    }
    
    .criteria-row:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .remove-criteria-btn {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    
    .add-criteria-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .add-criteria-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(40,167,69,0.3);
    }
    
    .percentage-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    .percentage-ok {
        background: #28a745;
        color: white;
    }
    
    .percentage-warning {
        background: #ffc107;
        color: #000;
    }
    
    .percentage-error {
        background: #dc3545;
        color: white;
    }
    
    .segment-count-selector {
        display: flex;
        gap: 15px;
    }
    
    .segment-option {
        flex: 1;
        padding: 20px;
        border: 3px solid #e0e0e0;
        border-radius: 15px;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s;
        background: white;
    }
    
    .segment-option:hover {
        border-color: #667eea;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(102,126,234,0.2);
    }
    
    .segment-option.active {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
    }
    
    .segment-option input[type="radio"] {
        display: none;
    }
    
    .segment-icon {
        font-size: 48px;
        margin-bottom: 10px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-plus-circle-fill"></i> Add New Round</h1>
        <p class="text-muted">Create a round with 1 or 2 segments and their judging criteria</p>
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
    
    <!-- Round Information -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle-fill"></i> Round Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
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
                
                <div class="col-md-9 mb-3">
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
            </div>
            
            <div class="mb-3">
                <label for="round_description" class="form-label">Description (Optional)</label>
                <textarea 
                    class="form-control" 
                    id="round_description" 
                    name="round_description" 
                    rows="2"
                    placeholder="Brief description of this round..."
                ></textarea>
            </div>
        </div>
    </div>
    
    <!-- Segment Count Selection -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-ui-checks"></i> How Many Segments?</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Some rounds have only 1 segment (e.g., Interview), while others have 2 segments (e.g., Evening Gown + Swimsuit)</p>
            
            <div class="segment-count-selector">
                <label class="segment-option active" id="option-1-segment">
                    <input type="radio" name="segment_count" value="1" checked>
                    <div class="segment-icon">1️⃣</div>
                    <h5>Single Segment</h5>
                    <p class="text-muted mb-0">One judging segment<br>(100% weight)</p>
                </label>
                
                <label class="segment-option" id="option-2-segments">
                    <input type="radio" name="segment_count" value="2">
                    <div class="segment-icon">1️⃣2️⃣</div>
                    <h5>Two Segments</h5>
                    <p class="text-muted mb-0">Two judging segments<br>(50% weight each)</p>
                </label>
            </div>
        </div>
    </div>
    
    <!-- Segment 1 -->
    <div class="segment-section" id="segment1-section">
        <div class="segment-header">
            <h4 class="mb-0"><i class="bi bi-1-circle-fill"></i> Segment 1</h4>
            <span class="badge bg-light text-dark">Weight: <span id="segment1-weight">100</span>%</span>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">
                    Segment Name <span class="text-danger">*</span>
                </label>
                <input 
                    type="text" 
                    class="form-control" 
                    name="segment1_name" 
                    placeholder="e.g., Evening Gown, Talent, Interview"
                    required
                >
            </div>
            <div class="col-md-6">
                <label class="form-label">Description (Optional)</label>
                <input 
                    type="text" 
                    class="form-control" 
                    name="segment1_description" 
                    placeholder="Brief description..."
                >
            </div>
        </div>
        
        <h6 class="mb-3">
            <i class="bi bi-list-check"></i> Judging Criteria for Segment 1
            <span class="percentage-badge percentage-ok ms-2" id="segment1-total">0%</span>
        </h6>
        
        <div id="segment1-criteria-container">
            <!-- Criteria rows will be added here -->
        </div>
        
        <button type="button" class="add-criteria-btn" onclick="addCriteria(1)">
            <i class="bi bi-plus-circle"></i> Add Criteria
        </button>
    </div>
    
    <!-- Segment 2 (Hidden by default) -->
    <div class="segment-section" id="segment2-section" style="display: none;">
        <div class="segment-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h4 class="mb-0"><i class="bi bi-2-circle-fill"></i> Segment 2</h4>
            <span class="badge bg-light text-dark">Weight: 50%</span>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">
                    Segment Name <span class="text-danger">*</span>
                </label>
                <input 
                    type="text" 
                    class="form-control" 
                    name="segment2_name" 
                    placeholder="e.g., Swimsuit, Q&A"
                >
            </div>
            <div class="col-md-6">
                <label class="form-label">Description (Optional)</label>
                <input 
                    type="text" 
                    class="form-control" 
                    name="segment2_description" 
                    placeholder="Brief description..."
                >
            </div>
        </div>
        
        <h6 class="mb-3">
            <i class="bi bi-list-check"></i> Judging Criteria for Segment 2
            <span class="percentage-badge percentage-ok ms-2" id="segment2-total">0%</span>
        </h6>
        
        <div id="segment2-criteria-container">
            <!-- Criteria rows will be added here -->
        </div>
        
        <button type="button" class="add-criteria-btn" onclick="addCriteria(2)">
            <i class="bi bi-plus-circle"></i> Add Criteria
        </button>
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
            <small class="text-muted d-block mt-2">
                💡 Tip: Make sure criteria percentages for each segment add up to 100%
            </small>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let criteriaCounters = { 1: 0, 2: 0 };

// Segment count selection
document.querySelectorAll('input[name="segment_count"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const segmentCount = parseInt(this.value);
        
        // Update UI
        document.querySelectorAll('.segment-option').forEach(opt => opt.classList.remove('active'));
        this.closest('.segment-option').classList.add('active');
        
        // Show/hide segment 2
        const segment2Section = document.getElementById('segment2-section');
        const segment2NameInput = document.querySelector('input[name="segment2_name"]');
        
        if (segmentCount === 2) {
            segment2Section.style.display = 'block';
            segment2NameInput.required = true;
            document.getElementById('segment1-weight').textContent = '50';
        } else {
            segment2Section.style.display = 'none';
            segment2NameInput.required = false;
            document.getElementById('segment1-weight').textContent = '100';
        }
    });
});

// Add criteria row
function addCriteria(segmentNum) {
    criteriaCounters[segmentNum]++;
    const index = criteriaCounters[segmentNum];
    const container = document.getElementById(`segment${segmentNum}-criteria-container`);
    
    const row = document.createElement('div');
    row.className = 'criteria-row';
    row.id = `segment${segmentNum}-criteria-${index}`;
    row.innerHTML = `
        <button type="button" class="btn btn-sm btn-danger remove-criteria-btn" onclick="removeCriteria(${segmentNum}, ${index})">
            <i class="bi bi-x-lg"></i>
        </button>
        
        <div class="row">
            <div class="col-md-4 mb-2">
                <label class="form-label small">Criteria Name *</label>
                <input 
                    type="text" 
                    class="form-control" 
                    name="segment${segmentNum}_criteria_name[]" 
                    placeholder="e.g., Poise, Beauty, Stage Presence"
                    required
                >
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small">Percentage (%) *</label>
                <input 
                    type="number" 
                    class="form-control criteria-percentage" 
                    name="segment${segmentNum}_criteria_percentage[]" 
                    placeholder="e.g., 25"
                    min="0"
                    max="100"
                    step="0.01"
                    data-segment="${segmentNum}"
                    required
                    onchange="updatePercentageTotal(${segmentNum})"
                >
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small">Max Score *</label>
                <input 
                    type="number" 
                    class="form-control" 
                    name="segment${segmentNum}_criteria_max_score[]" 
                    value="100"
                    required
                >
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small">Description</label>
                <input 
                    type="text" 
                    class="form-control" 
                    name="segment${segmentNum}_criteria_description[]" 
                    placeholder="Optional"
                >
            </div>
        </div>
    `;
    
    container.appendChild(row);
    updatePercentageTotal(segmentNum);
}

// Remove criteria row
function removeCriteria(segmentNum, index) {
    const row = document.getElementById(`segment${segmentNum}-criteria-${index}`);
    if (row) {
        row.remove();
        updatePercentageTotal(segmentNum);
    }
}

// Update percentage total
function updatePercentageTotal(segmentNum) {
    const inputs = document.querySelectorAll(`input.criteria-percentage[data-segment="${segmentNum}"]`);
    let total = 0;
    
    inputs.forEach(input => {
        const value = parseFloat(input.value) || 0;
        total += value;
    });
    
    const badge = document.getElementById(`segment${segmentNum}-total`);
    badge.textContent = total.toFixed(2) + '%';
    
    // Update badge color
    badge.className = 'percentage-badge ms-2';
    if (Math.abs(total - 100) < 0.01) {
        badge.classList.add('percentage-ok');
    } else if (total < 100) {
        badge.classList.add('percentage-warning');
    } else {
        badge.classList.add('percentage-error');
    }
}

// Initialize with one criteria for segment 1
window.addEventListener('DOMContentLoaded', function() {
    addCriteria(1);
});

// Form validation before submit
document.getElementById('roundForm').addEventListener('submit', function(e) {
    const segmentCount = parseInt(document.querySelector('input[name="segment_count"]:checked').value);
    
    // Check segment 1
    const segment1Total = parseFloat(document.getElementById('segment1-total').textContent);
    if (Math.abs(segment1Total - 100) > 0.01) {
        e.preventDefault();
        alert('Segment 1 criteria percentages must add up to 100%! Current total: ' + segment1Total + '%');
        return false;
    }
    
    // Check segment 2 if applicable
    if (segmentCount === 2) {
        const segment2Total = parseFloat(document.getElementById('segment2-total').textContent);
        if (Math.abs(segment2Total - 100) > 0.01) {
            e.preventDefault();
            alert('Segment 2 criteria percentages must add up to 100%! Current total: ' + segment2Total + '%');
            return false;
        }
    }
});
</script>
<?= $this->endSection() ?>
