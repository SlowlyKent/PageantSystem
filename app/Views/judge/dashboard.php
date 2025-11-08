<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>Judge Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="bi bi-star"></i> Judge Dashboard
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-calendar"></i> <?= date('F d, Y') ?>
                    </button>
                </div>
            </div>

            <!-- Welcome Message -->
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle"></i>
                <strong>Welcome back, <?= esc($user['name'] ?? 'Judge') ?>!</strong> 
                You have 5 contestants pending for scoring.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <!-- Pending Scores Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pending Scores
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Scores Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Completed Scores
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">20</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-check-circle fs-2 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Contestants Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Contestants
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">25</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-people fs-2 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Score Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Average Score
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">8.5</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-graph-up-arrow fs-2 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="row">
                <!-- Pending Contestants to Score -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="bi bi-clipboard-check"></i> Pending Contestants
                            </h6>
                            <a href="<?= base_url('judge/contestants') ?>" class="btn btn-sm btn-primary">
                                View All
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Contestant Name</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Maria Santos</td>
                                            <td>Evening Gown</td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i> Score
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Ana Rodriguez</td>
                                            <td>Swimsuit</td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i> Score
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Sofia Cruz</td>
                                            <td>Talent</td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i> Score
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Recent Scores -->
                <div class="col-lg-4 mb-4">
                    <!-- Quick Actions -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="bi bi-lightning"></i> Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?= base_url('judge/contestants') ?>" class="btn btn-primary">
                                    <i class="bi bi-people"></i> Score Contestants
                                </a>
                                <a href="<?= base_url('judge/submit-score') ?>" class="btn btn-success">
                                    <i class="bi bi-pencil-square"></i> Submit Score
                                </a>
                                <a href="<?= base_url('judge/history') ?>" class="btn btn-info">
                                    <i class="bi bi-clock-history"></i> View History
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Scores -->
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="bi bi-star-fill"></i> Recent Scores
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <strong>Emma Johnson</strong>
                                        <span class="badge bg-success">9.5</span>
                                    </div>
                                    <small class="text-muted">Evening Gown - 10 mins ago</small>
                                </div>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <strong>Lisa Williams</strong>
                                        <span class="badge bg-success">8.8</span>
                                    </div>
                                    <small class="text-muted">Swimsuit - 30 mins ago</small>
                                </div>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <strong>Sarah Brown</strong>
                                        <span class="badge bg-success">9.2</span>
                                    </div>
                                    <small class="text-muted">Talent - 1 hour ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<?= $this->endSection() ?>
