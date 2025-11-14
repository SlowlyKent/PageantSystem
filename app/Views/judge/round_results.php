<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Round Rankings<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-bar-chart-line"></i> Round Rankings</h1>
            <p class="text-muted mb-0">
                Round <?= $round['round_number'] ?> · <?= esc($round['round_name']) ?>
            </p>
        </div>
        <a href="<?= base_url('judge/select-round') ?>" class="btn btn-secondary text-white">
            <i class="bi bi-arrow-left"></i> Back to Rounds
        </a>
    </div>

    <?php if ($stats['judges_completed'] < $stats['total_judges']): ?>
        <div class="alert alert-warning d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <strong>Judging in progress.</strong>
                <span class="text-muted">Rankings update as judges finish scoring. Progress: <?= $stats['judges_completed'] ?>/<?= $stats['total_judges'] ?> completed.</span>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-success d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <strong>All judges have completed this round.</strong>
                <span class="text-muted">Final rankings are locked in.</span>
            </div>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-bar-chart-fill me-2"></i>Round Rankings</h5>
            <?php if (!empty($round['is_elimination']) && !empty($round['elimination_quota'])): ?>
                <span class="badge bg-warning text-dark fw-semibold">
                    Eliminating <?= (int)$round['elimination_quota'] ?> contestant<?= (int)$round['elimination_quota'] > 1 ? 's' : '' ?>
                </span>
            <?php endif; ?>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($rankings)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center table-col-rank">Rank</th>
                                <th>Contestant</th>
                                <th class="text-center table-col-score">Average Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rankings as $entry): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $entry['rank'] ?></td>
                                    <td>
                                        <strong>#<?= esc($entry['contestant_number']) ?></strong><br>
                                        <span class="text-muted"><?= esc($entry['contestant_name']) ?></span>
                                    </td>
                                    <td class="text-center fw-semibold"><?= number_format($entry['total_score'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-info-circle"></i> No scores recorded yet for this round.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

