<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Scoring - Round <?= $round['round_number'] ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-4">
    <!-- Round Navigation -->
    <div class="rounds-navigation">
        <div class="round-progress-track">
            <?php foreach ($all_rounds as $index => $rnd): ?>
                <?php 
                $isActive = $rnd['id'] == $round['id'];
                $isCompleted = isset($rnd['is_completed']) && $rnd['is_completed'];
                $previousCompleted = $index > 0 && isset($all_rounds[$index - 1]['is_completed']) && $all_rounds[$index - 1]['is_completed'];
                $isLocked = !$isCompleted && !$isActive && !$previousCompleted;
                ?>
                <div class="round-step <?= $isActive ? 'active' : ($isCompleted ? 'completed' : ($isLocked ? 'locked' : '')) ?>"
                     <?php if (!$isLocked): ?>onclick="window.location.href='<?= base_url('judge/score-round/' . $rnd['id']) ?>'"<?php endif; ?>
                     title="<?= esc($rnd['round_name']) ?>">
                    <?= $rnd['round_number'] ?>
                </div>
                <?php if ($index < count($all_rounds) - 1): ?>
                    <div class="round-connector <?= ($isCompleted || $isActive) ? 'completed' : '' ?>"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Final Results Podium (Show only if final round and all judges completed) -->
    <?php if ($is_final_round && $all_judges_completed && !empty($top_contestants)): ?>
        <div class="final-results-container">
            <div class="text-end mb-3">
                <a href="<?= base_url('judge/dashboard') ?>" class="btn btn-secondary text-white">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
            <div class="final-results-title"> <?= esc(system_name()) ?> </div>
            <?php
            $winner = null;
            foreach ($top_contestants as $contestant) {
                if ((int) ($contestant['rank'] ?? 0) === 1) {
                    $winner = $contestant;
                    break;
                }
            }
            ?>
            <?php if ($winner): ?>
                <div class="winner-label">WINNER</div>
                <div class="crown-icon">👑</div>
                <div class="podium-container">
                    <div class="podium-place first">
                        <div class="podium-avatar-container">
                            <span class="place-badge first">WINNER</span>
                            <?php
                            $winnerName = $winner['contestant_name']
                                ?? trim(($winner['first_name'] ?? '') . ' ' . ($winner['last_name'] ?? ''));
                            ?>
                            <?php if (!empty($winner['photo_url'])): ?>
                                <img src="<?= $winner['photo_url'] ?>" alt="<?= esc($winnerName ?: 'Contestant') ?>" class="podium-avatar">
                            <?php else: ?>
                                <div class="podium-avatar d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person placeholder-icon-lg"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="podium-name"><?= esc($winnerName) ?></div>
                        <div class="podium-score"><?= number_format($winner['total_score'], 2) ?> pts</div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Round Info Card (Hide if final round and all judges completed) -->
    <?php if (!($is_final_round && $all_judges_completed)): ?>
    <div class="round-info-card">
        <div class="flex-grow-1">
            <div class="round-title"><?= esc($round['round_name']) ?></div>
            <p class="round-subtitle">Judges score all contestants in each category</p>
            <div class="round-info-stats">
                <div class="round-info-stat">
                    <span class="stat-value"><?= $stats['total_contestants'] ?></span>
                    <span class="stat-label">Contestants</span>
                </div>
                <div class="round-info-stat">
                    <span class="stat-value"><?= $stats['judges_completed'] ?>/<?= $stats['total_judges'] ?></span>
                    <span class="stat-label">Judges Completed</span>
                </div>
                <div class="round-info-stat">
                    <span class="stat-value">Round <?= $round['round_number'] ?></span>
                    <span class="stat-label">Current Round</span>
                </div>
            </div>
        </div>
        <div class="ms-lg-4">
            <a href="<?= base_url('judge/dashboard') ?>" class="btn btn-primary btn-lg text-white">
                <i class="bi bi-grid-1x2"></i> View Dashboard
            </a>
        </div>
    </div>
    
    <!-- Status Information Alert -->
    <?php if (!$is_final_round): ?>
        <div class="alert alert-info mb-4">
            <i class="bi bi-info-circle-fill"></i>
            <strong>Current Status:</strong> This is not the final round. After all judges complete, you will proceed to the next round.
        </div>
    <?php else: ?>
        <?php if (!$all_judges_completed): ?>
            <div class="alert alert-warning mb-4">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong>Final Round!</strong> This is the final round. Once all judges complete scoring, the Top 3 winners will be displayed.
                <br><strong>Progress:</strong> <?= $stats['judges_completed'] ?>/<?= $stats['total_judges'] ?> judges completed.
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php endif; ?>
    
    <?php if ($current_judge_completed && !$all_judges_completed): ?>
        <div class="alert alert-info mb-4">
            <i class="bi bi-hourglass-split"></i>
            You have completed scoring for this round. Waiting for other judges to finish (<?= $stats['judges_completed'] ?>/<?= $stats['total_judges'] ?> completed).
        </div>
    <?php endif; ?>
    
    <!-- Instructions Section (Hide if final round and all judges completed) -->
    <?php if (!($is_final_round && $all_judges_completed) && !empty($round['description'])): ?>
        <div class="instructions-section">
            <h5><i class="bi bi-info-circle-fill"></i> Instructions</h5>
            <p class="mb-0"><?= nl2br(esc($round['description'])) ?></p>
        </div>
    <?php endif; ?>
    
    <!-- Scoring Categories (Hide if final round and all judges completed) -->
    <?php if (!($is_final_round && $all_judges_completed)): ?>
    <div class="categories-section">
        <h5 class="fw-bold mb-0"><i class="bi bi-collection me-2"></i>Scoring Categories</h5>
        <div class="categories-grid mt-3">
            <?php if (!empty($round['criteria'])): ?>
                <?php foreach ($round['criteria'] as $criteria): ?>
                    <div class="category-badge">
                        <span class="category-name"><?= esc($criteria['criteria_name']) ?></span>
                        <?php if (!empty($criteria['description'])): ?>
                            <span class="category-desc"><?= esc($criteria['description']) ?></span>
                        <?php endif; ?>
                        <span class="category-points">Max: <?= $criteria['max_score'] ?> points</span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <?php endif; ?>
    
    <!-- Success Message -->
    <!-- Contestants Scoring (Hide if final round and all judges completed) -->
    <?php if (!($is_final_round && $all_judges_completed)): ?>
    <form id="scoringForm" method="post" action="<?= base_url('judge/submit-all-scores') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="round_id" value="<?= $round['id'] ?>">
        
        <!-- Sticky Floating Mark as Complete Button (Only show if judge hasn't completed) -->
        <?php if (!$current_judge_completed): ?>
        <a href="<?= base_url('judge/mark-complete/' . $round['id']) ?>" id="stickyCompleteBtn" class="btn btn-success btn-lg sticky-complete-btn text-white" onclick="return confirm('Are you sure you want to mark your scoring as complete? You won\'t be able to change your scores after this.')" title="Mark as Complete">
            <i class="bi bi-check-circle-fill"></i> MARK AS COMPLETE
        </a>
        <?php endif; ?>
        
        <?php foreach ($contestants as $contestant): ?>
            <div class="contestant-scoring-card">
                <div class="contestant-header">
                    <?php if (!empty($contestant['profile_picture'])): ?>
                        <img src="<?= base_url('uploads/contestants/' . $contestant['profile_picture']) ?>" 
                             alt="<?= esc($contestant['first_name']) ?>" 
                             class="contestant-avatar">
                    <?php else: ?>
                        <div class="contestant-avatar bg-secondary d-flex align-items-center justify-content-center">
                            <i class="bi bi-person fs-4 text-white"></i>
                        </div>
                    <?php endif; ?>
                    <div class="contestant-info">
                        <h5><?= esc($contestant['first_name'] . ' ' . $contestant['last_name']) ?></h5>
                        <div class="contestant-meta-row">
                            <span>Age: <?= $contestant['age'] ?? 'N/A' ?></span>
                            <?php if (!empty($contestant['city'])): ?>
                                <span><?= esc($contestant['city']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($contestant['province']) && strtolower($contestant['province']) !== strtolower($contestant['city'])): ?>
                                <span><?= esc($contestant['province']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($round['criteria'])): ?>
                    <div class="segment-criteria-grid">
                        <?php foreach ($round['criteria'] as $criteria): ?>
                            <?php
                            $existingScore = $existing_scores[$contestant['id']][$criteria['id']] ?? null;
                            $scoreValue = $existingScore ? $existingScore['score'] : 0;
                            ?>
                            <div class="criteria-card">
                                <div class="criteria-title"><?= esc($criteria['criteria_name']) ?></div>
                                <?php if (!empty($criteria['description'])): ?>
                                    <div class="criteria-description"><?= esc($criteria['description']) ?></div>
                                <?php endif; ?>
                                <div class="criteria-labels">
                                    <span class="badge bg-light text-muted border"><?= number_format($criteria['percentage'], 0) ?>% Weight</span>
                                    <span class="badge bg-light text-muted border">Max <?= $criteria['max_score'] ?></span>
                                </div>
                                <div class="criteria-slider-wrapper">
                                    <div class="score-display" id="score_<?= $contestant['id'] ?>_<?= $criteria['id'] ?>">
                                        <?= number_format($scoreValue, 1) ?>/<?= $criteria['max_score'] ?>
                                    </div>
                                    <input type="range" 
                                           class="form-range" 
                                           name="scores[<?= $contestant['id'] ?>][<?= $criteria['id'] ?>]" 
                                           min="0" 
                                           max="<?= $criteria['max_score'] ?>" 
                                           step="0.1" 
                                           value="<?= $scoreValue ?>"
                                           <?= $current_judge_completed ? 'disabled' : '' ?>
                                           oninput="updateScore(<?= $contestant['id'] ?>, <?= $criteria['id'] ?>, this.value, <?= $criteria['max_score'] ?>)">
                                    <div class="criteria-range-labels">
                                        <span>0</span>
                                        <span><?= $criteria['max_score'] ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        
        <!-- Complete Scoring / Next Round Button -->
        <div class="text-center mt-5 mb-4">
            <?php if ($current_judge_completed): ?>
                <!-- Judge has completed - show next round options -->
                <hr class="my-4">
                
                <?php if ($next_round): ?>
                    <?php if ($next_round_unlocked && $stats['total_judges'] > 0 && $stats['judges_completed'] == $stats['total_judges']): ?>
                        <a href="<?= base_url('judge/score-round/' . $next_round['id']) ?>" class="next-round-btn btn btn-lg">
                            <i class="bi bi-arrow-right-circle"></i> Proceed to Next Round
                        </a>
                    <?php else: ?>
                        <button type="button" class="next-round-btn btn btn-lg" disabled>
                            <i class="bi bi-lock-fill"></i> Next Round Locked
                        </button>
                        <div class="locked-message mt-3">
                            <i class="bi bi-info-circle"></i>
                            <strong>Waiting for other judges...</strong>
                            <p class="mb-0 mt-2">The next round will unlock when all judges complete scoring this round. (<?= $stats['judges_completed'] ?>/<?= $stats['total_judges'] ?> completed)</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Final Round - Show View Winners Button if all judges completed -->
                    <?php if ($all_judges_completed): ?>
                        <button type="button" class="btn btn-warning btn-lg text-white view-winners-btn" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
                            <i class="bi bi-trophy-fill"></i> VIEW WINNERS
                        </button>
                        <p class="text-muted mt-3">All judges have completed! Click to view the Top 3 winners.</p>
                    <?php else: ?>
                        <!-- Waiting for other judges to complete final round -->
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-hourglass-split"></i>
                            <strong>Final Round Completed!</strong>
                            <p class="mb-0 mt-2">You have completed your scoring for the final round. Please wait for other judges to finish. The winners will be displayed when all judges complete scoring. (<?= $stats['judges_completed'] ?>/<?= $stats['total_judges'] ?> completed)</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <!-- Judge hasn't completed - show info message -->
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle-fill"></i>
                    <strong>Review your scores above.</strong> When you're satisfied with all your ratings, scroll through all contestants and click the "Mark as Complete" button.
                </div>
                <p class="text-muted">Your scores are automatically saved as you adjust them. The completion button will appear when you've scrolled through all contestants.</p>
            <?php endif; ?>
        </div>
    </form>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function updateScore(contestantId, criteriaId, value, maxScore) {
    const displayElement = document.getElementById(`score_${contestantId}_${criteriaId}`);
    displayElement.textContent = `${parseFloat(value).toFixed(1)}/${maxScore}`;
    
    // Auto-save score to database
    autoSaveScore(contestantId, criteriaId, value);
}

// Auto-save score via AJAX
function autoSaveScore(contestantId, criteriaId, score) {
    const roundId = document.querySelector('input[name="round_id"]').value;
    const csrfToken = document.querySelector('input[name="csrf_test_name"]').value;
    
    // Create FormData
    const formData = new FormData();
    formData.append('csrf_test_name', csrfToken);
    formData.append('round_id', roundId);
    formData.append('contestant_id', contestantId);
    formData.append('criteria_id', criteriaId);
    formData.append('score', score);
    
    // Send AJAX request
    fetch('<?= base_url('judge/auto-save-score') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Optionally show a subtle save indicator
            console.log('Score saved:', contestantId, criteriaId, score);
        } else {
            console.error('Failed to save score:', data.message);
        }
    })
    .catch(error => {
        console.error('Error saving score:', error);
    });
}

// Check if all scores have been given
function checkAllScoresGiven() {
    const allSliders = document.querySelectorAll('input[type="range"]');
    let allScored = true;
    
    allSliders.forEach(slider => {
        const value = parseFloat(slider.value);
        if (value === 0 || value === 0.0) {
            allScored = false;
        }
    });
    
    return allScored;
}

// Update sticky button visibility
function updateStickyButtonVisibility() {
    const stickyBtn = document.getElementById('stickyCompleteBtn');
    if (!stickyBtn) return;
    
    // Calculate scroll position
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;
    const scrollPercentage = (scrollTop + windowHeight) / documentHeight;
    
    // Check if all scores are given
    const allScored = checkAllScoresGiven();
    
    // Show button only when:
    // 1. User has scrolled through 70% of the page
    // 2. All criteria have been scored (no zeros)
    if (scrollPercentage > 0.7 && allScored) {
        stickyBtn.classList.add('show');
    } else {
        stickyBtn.classList.remove('show');
    }
}

// Show sticky complete button only when scrolled through all contestants AND all scores given
window.addEventListener('scroll', updateStickyButtonVisibility);

// Also check when any slider changes
document.addEventListener('DOMContentLoaded', function() {
    const allSliders = document.querySelectorAll('input[type="range"]');
    allSliders.forEach(slider => {
        slider.addEventListener('input', updateStickyButtonVisibility);
    });
});
</script>
<?= $this->endSection() ?>
