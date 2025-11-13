<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Round Rankings<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.podium-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    align-items: end;
}
.podium-place {
    position: relative;
    background: #ffffff;
    border: 2px solid #eef2ff;
    border-radius: 16px;
    padding: 18px 16px 20px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    text-align: center;
}
.podium-place.first {
    border-color: #fde68a;
    background: linear-gradient(180deg, #fff7ed 0%, #fffde7 100%);
}
.podium-place.second {
    border-color: #e5e7eb;
    background: linear-gradient(180deg, #f9fafb 0%, #f3f4f6 100%);
}
.podium-place.third {
    border-color: #f3e8ff;
    background: linear-gradient(180deg, #faf5ff 0%, #fff7ed 100%);
}
.podium-avatar-container {
    position: relative;
    margin-bottom: 10px;
}
.podium-avatar {
    width: 140px;
    height: 140px;
    object-fit: cover;
    border-radius: 16px;
    border: 4px solid #fff;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    background: #f3f4f6;
}
.place-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    padding: 6px 10px;
    font-size: 11px;
    font-weight: 800;
    border-radius: 10px;
    letter-spacing: .5px;
    color: #111827;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    box-shadow: 0 6px 14px rgba(0,0,0,0.08);
}
.place-badge.first { background: #fef3c7; border-color: #fde68a; }
.place-badge.second { background: #e5e7eb; border-color: #d1d5db; }
.place-badge.third { background: #ffedd5; border-color: #fdba74; }
.podium-name {
    font-weight: 700;
    font-size: 16px;
    color: #1f2937;
    margin-top: 6px;
}
.podium-score {
    font-weight: 800;
    color: #111827;
    margin-top: 4px;
    font-size: 15px;
}
</style>
<?= $this->endSection() ?>

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
                                <th class="text-center" style="width: 80px;">Rank</th>
                                <th>Contestant</th>
                                <th class="text-center" style="width: 140px;">Average Score</th>
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

