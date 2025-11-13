<?= $this->extend('layouts/template') ?>

<?= $this->section('title') ?>View Contestants<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-4">

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold">View Contestants</h1>
        <p class="text-muted mb-0">Get to know the contestants you'll be scoring</p>
    </div>
    <a href="<?= base_url('judge/dashboard') ?>" class="btn btn-secondary text-white">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <div class="row g-3 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Search by name, number, or city...">
            </div>
        </div>
        <div class="col-md-3">
            <select id="sortSelect" class="form-select">
                <option value="name">Sort by Name</option>
                <option value="number">Sort by Number</option>
                <option value="city">Sort by City</option>
            </select>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-secondary p-2 px-3">
                <i class="bi bi-people-fill me-1"></i>
                <strong id="totalCount"><?= count($contestants ?? []) ?></strong> Contestants
            </span>
        </div>
    </div>
</div>

<!-- Contestants Table -->
<?php if (empty($contestants)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No contestants available at the moment.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table contestants-table mb-0" id="contestantsTable">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="10%">Photo</th>
                    <th width="30%">Name</th>
                    <th width="15%">City</th>
                    <th width="10%">Age</th>
                    <th width="10%">Gender</th>
                    <th width="10%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contestants as $contestant): ?>
                    <tr data-name="<?= esc($contestant['first_name'] . ' ' . $contestant['last_name']) ?>" 
                        data-number="<?= $contestant['contestant_number'] ?>" 
                        data-city="<?= esc($contestant['city'] ?? '') ?>">
                        <td>
                            <span class="contestant-number-badge">#<?= $contestant['contestant_number'] ?></span>
                        </td>
                        <td>
                            <?php if (!empty($contestant['profile_picture'])): ?>
                                <img src="<?= base_url('uploads/contestants/' . $contestant['profile_picture']) ?>" 
                                     alt="<?= esc($contestant['first_name']) ?>" 
                                     class="contestant-photo">
                            <?php else: ?>
                                <div class="contestant-photo bg-secondary d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person text-white"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold"><?= esc($contestant['first_name'] . ' ' . $contestant['last_name']) ?></div>
                            <small class="text-muted"><?= esc($contestant['email'] ?? '') ?></small>
                        </td>
                        <td><?= esc($contestant['city'] ?? 'N/A') ?></td>
                        <td><?= $contestant['age'] ?? 'N/A' ?></td>
                        <td><?= ucfirst($contestant['gender'] ?? 'N/A') ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-primary text-white view-btn" data-bs-toggle="modal" data-bs-target="#contestantModal<?= $contestant['id'] ?>">
                                <i class="bi bi-eye"></i> View
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Modals -->
    <?php foreach ($contestants as $contestant): ?>
        <div class="modal fade" id="contestantModal<?= $contestant['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">Contestant #<?= $contestant['contestant_number'] ?> - <?= esc($contestant['first_name'] . ' ' . $contestant['last_name']) ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <?php if (!empty($contestant['profile_picture'])): ?>
                                        <img src="<?= base_url('uploads/contestants/' . $contestant['profile_picture']) ?>" alt="<?= esc($contestant['first_name']) ?>" class="img-fluid rounded" style="width: 100%; height: 300px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 300px; border-radius: 10px;">
                                            <i class="bi bi-person" style="font-size: 5rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <h4 class="fw-bold">#<?= $contestant['contestant_number'] ?></h4>
                            </div>
                            <div class="col-md-8">
                                <h4 class="mb-4 fw-bold"><?= esc($contestant['first_name'] . ' ' . ($contestant['middle_name'] ?? '') . ' ' . $contestant['last_name']) ?></h4>
                                
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3 fw-bold" style="border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">Basic Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="text-muted fw-semibold" width="40%" style="background: #f8f9fa;">Contestant Number:</td>
                                            <td class="fw-bold">#<?= $contestant['contestant_number'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Birthdate:</td>
                                            <td><?= $contestant['birthdate'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Age:</td>
                                            <td><?= $contestant['age'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Gender:</td>
                                            <td><?= ucfirst($contestant['gender'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Height:</td>
                                            <td><?= $contestant['height'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Weight:</td>
                                            <td><?= $contestant['weight'] ?? 'N/A' ?></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3 fw-bold" style="border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">Contact Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="text-muted fw-semibold" width="40%" style="background: #f8f9fa;">Address:</td>
                                            <td><?= esc($contestant['address'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">City:</td>
                                            <td><?= esc($contestant['city'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Province:</td>
                                            <td><?= esc($contestant['province'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Contact Number:</td>
                                            <td><?= esc($contestant['phone'] ?? $contestant['contact_number'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Email:</td>
                                            <td><?= esc($contestant['email'] ?? 'N/A') ?></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-primary mb-3 fw-bold" style="border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">Additional Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="text-muted fw-semibold" width="40%" style="background: #f8f9fa;">Talent/Special Skill:</td>
                                            <td><?= esc($contestant['talent'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Advocacy/Platform:</td>
                                            <td><?= esc($contestant['advocacy'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Hobbies/Interests:</td>
                                            <td><?= esc($contestant['hobbies'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Educational Background:</td>
                                            <td><?= esc($contestant['education'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold" style="background: #f8f9fa;">Status:</td>
                                            <td><span class="badge bg-<?= $contestant['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($contestant['status']) ?></span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#contestantsTable tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const number = row.getAttribute('data-number').toLowerCase();
        const city = row.getAttribute('data-city').toLowerCase();
        
        if (name.includes(searchTerm) || number.includes(searchTerm) || city.includes(searchTerm)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    document.getElementById('totalCount').textContent = visibleCount;
});

// Sort functionality
document.getElementById('sortSelect').addEventListener('change', function() {
    const sortBy = this.value;
    const tbody = document.querySelector('#contestantsTable tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        let aValue, bValue;
        
        if (sortBy === 'name') {
            aValue = a.getAttribute('data-name').toLowerCase();
            bValue = b.getAttribute('data-name').toLowerCase();
        } else if (sortBy === 'number') {
            aValue = parseInt(a.getAttribute('data-number'));
            bValue = parseInt(b.getAttribute('data-number'));
        } else if (sortBy === 'city') {
            aValue = a.getAttribute('data-city').toLowerCase();
            bValue = b.getAttribute('data-city').toLowerCase();
        }
        
        if (aValue < bValue) return -1;
        if (aValue > bValue) return 1;
        return 0;
    });
    
    rows.forEach(row => tbody.appendChild(row));
});
</script>
<?= $this->endSection() ?>