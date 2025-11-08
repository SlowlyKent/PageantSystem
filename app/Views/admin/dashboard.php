<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
        <p>Welcome, System Administrator</p>
    </div>
    <button class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-gear"></i> Settings
    </button>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <!-- Total Contestants -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="section-card text-center">
            <div class="mb-3">
                <i class="bi bi-people-fill text-muted" style="font-size: 3rem;"></i>
            </div>
            <h3 class="mb-1"><?= $total_contestants ?? 0 ?></h3>
            <p class="text-muted mb-0">Total Contestants</p>
        </div>
    </div>
    
    <!-- Active Judges -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon green">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="stats-content">
                <h3><?= $active_judges ?? 0 ?></h3>
                <p>Active Judges</p>
            </div>
        </div>
    </div>
    
    <!-- Total Rounds -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon orange">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div class="stats-content">
                <h3><?= $total_rounds ?? 0 ?></h3>
                <p>Total Rounds</p>
            </div>
        </div>
    </div>
    
    <!-- Scores Submitted -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon blue">
                <i class="bi bi-star-fill"></i>
            </div>
            <div class="stats-content">
                <h3><?= $scores_submitted ?? 0 ?></h3>
                <p>Scores Submitted</p>
            </div>
        </div>
    </div>
</div>

<!-- Current Round & Judge Progress Section -->
<div class="row mb-4">
    <!-- Current Round -->
    <div class="col-lg-7 mb-3">
        <div class="section-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Current Round</h5>
                <span class="badge-active">Active</span>
            </div>
            
            <div class="round-info">
                <h6><i class="bi bi-trophy"></i> Current Round</h6>
                <h4>Swim Suite</h4>
                <p class="text-muted">blalalalla</p>
                <span class="badge bg-success">Round 1 - Active</span>
                <br><br>
                <button class="btn btn-warning btn-sm">
                    <i class="bi bi-gear"></i> Manage Rounds
                </button>
            </div>
        </div>
    </div>
    
    <!-- Judge Progress -->
    <div class="col-lg-5 mb-3">
        <div class="section-card">
            <h5>Judge Progress</h5>
            <p class="text-muted">No scoring progress available.</p>
        </div>
    </div>
</div>

<!-- Current Leaderboard Section -->
<div class="row">
    <div class="col-12">
        <div class="section-card">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Current Leaderboard</h5>
                <button class="btn btn-primary btn-sm">
                    <i class="bi bi-eye"></i> View Full Results
                </button>
            </div>
            <hr>
            <p class="text-muted">No rankings available yet. Scoring is in progress.</p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
