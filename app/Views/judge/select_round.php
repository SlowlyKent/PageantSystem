<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Select Round<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="pt-3 pb-2 mb-3 border-bottom d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h2"><i class="bi bi-ui-checks-grid"></i> Select Round</h1>
        <p class="text-muted mb-0">Choose a round to score contestants or review completed results</p>
    </div>
    <div>
        <a href="<?= base_url('judge/dashboard') ?>" class="btn btn-secondary text-white">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<?php if (empty($rounds)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No active rounds available for scoring.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($rounds as $round): ?>
            <?php 
            $roundStatus = $round['status'];
            $isLocked = isset($round['is_locked']) && $round['is_locked'] == 1;
            $judgeCompleted = !empty($round['judge_completed']);
            $allJudgesCompleted = !empty($round['all_judges_completed']);
            $showResults = ($roundStatus === 'completed') || !empty($round['all_judges_completed']);
            $resultsUrl = $showResults ? base_url('judge/round-results/' . $round['id']) : null;

            $iconHtml = '<div class="round-status-icon neutral"><i class="bi bi-flag-fill"></i></div>';
            $cardClasses = 'round-selection-card';
            $statusBadge = '';
            $actionUrl = null;
            $actionLabel = '';
            $actionClass = 'btn-primary';
            $actionTextClass = 'text-white';
            $extraMessage = '';

            if ($allJudgesCompleted || $roundStatus === 'completed') {
                $iconHtml = '<div class="round-status-icon completed"><i class="bi bi-check-lg"></i></div>';
                $statusBadge = '<span class="badge bg-primary mt-2"><i class="bi bi-check-circle-fill"></i> Completed</span>';
                $actionUrl = $resultsUrl ?: base_url("judge/score-round/{$round['id']}");
                $actionLabel = '<i class="bi bi-bar-chart-line"></i> View Results';
                $actionClass = 'btn-primary';
                $actionTextClass = 'text-white';
            } elseif ($isLocked || $roundStatus !== 'active') {
                $iconHtml = '<div class="round-status-icon locked"><i class="bi bi-lock-fill"></i></div>';
                $cardClasses .= ' locked';
                $statusBadge = '<span class="badge bg-warning mt-2"><i class="bi bi-clock-fill"></i> Waiting for Admin</span>';
            } elseif ($judgeCompleted) {
                $iconHtml = '<div class="round-status-icon judge-completed"><i class="bi bi-person-check-fill"></i></div>';
                $statusBadge = '<span class="badge bg-success mt-2"><i class="bi bi-check-circle"></i> You Completed</span>';
                $actionUrl = base_url("judge/score-round/{$round['id']}");
                $actionLabel = '<i class="bi bi-eye"></i> View Round';
                $actionClass = 'btn-outline-primary';
                $actionTextClass = 'text-primary';
                if (!empty($round['total_judges']) && $round['completed_judges'] < $round['total_judges']) {
                    $extraMessage = '<small class="text-muted d-block mt-2">Waiting for other judges (' . $round['completed_judges'] . '/' . $round['total_judges'] . ' completed)</small>';
                }
            } else {
                $iconHtml = '<div class="round-status-icon neutral"><i class="bi bi-trophy-fill"></i></div>';
                $statusBadge = '<span class="badge bg-info mt-2"><i class="bi bi-play-circle"></i> Active</span>';
                $actionUrl = base_url("judge/score-round/{$round['id']}");
                $actionLabel = '<i class="bi bi-play-circle"></i> Start Scoring';
                $actionClass = 'btn-success';
                $actionTextClass = 'text-white';
            }
            ?>
            <div class="col-md-4 mb-4">
                <div class="card <?= $cardClasses ?>">
                    <div class="card-body text-center py-5">
                        <?= $iconHtml ?>
                        <h3>Round <?= $round['round_number'] ?></h3>
                        <h5 class="<?= ($actionUrl && !$judgeCompleted) ? 'text-primary' : 'text-muted' ?>"><?= esc($round['round_name']) ?></h5>
                        <span class="badge bg-secondary mb-2"><?= $round['criteria_count'] ?? 0 ?> Criteria</span>
                        <br>
                        <?= $statusBadge ?>
                        <?php if ($actionUrl): ?>
                            <div class="d-grid mt-3">
                                <a href="<?= $actionUrl ?>" class="btn <?= $actionClass ?> <?= $actionTextClass ?>"><?= $actionLabel ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if ($resultsUrl && (!$actionUrl || $resultsUrl !== $actionUrl)): ?>
                            <div class="d-grid mt-2">
                                <a href="<?= $resultsUrl ?>" class="btn btn-secondary text-white">
                                    <i class="bi bi-bar-chart-line"></i> View Rankings
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($extraMessage)): ?>
                            <div class="mt-2"><?= $extraMessage ?></div>
                        <?php endif; ?>
                        <?php if ($cardClasses === 'round-selection-card locked' && !$actionUrl): ?>
                            <div class="mt-3"><small class="text-muted">Please wait for the administrator to unlock this round.</small></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
