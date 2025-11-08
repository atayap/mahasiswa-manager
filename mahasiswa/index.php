<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM mahasiswa ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$mahasiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .table-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        .no-photo {
            width: 50px;
            height: 50px;
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 12px;
        }
        .action-buttons {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-people-fill"></i> Data Mahasiswa</h2>
            <a href="create.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
            </a>
        </div>

        <!-- Success Message -->
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Mahasiswa</h5>
            </div>
            <div class="card-body">
                <?php if(count($mahasiswa) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">No</th>
                                    <th width="80">Foto</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Jurusan</th>
                                    <th width="100">Semester</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach($mahasiswa as $mhs): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td class="text-center">
                                            <?php if($mhs['foto'] && file_exists('../uploads/' . $mhs['foto'])): ?>
                                                <img src="../uploads/<?php echo $mhs['foto']; ?>" 
                                                     alt="Foto <?php echo $mhs['nama']; ?>" 
                                                     class="table-img"
                                                     data-bs-toggle="tooltip" 
                                                     data-bs-title="<?php echo $mhs['nama']; ?>">
                                            <?php else: ?>
                                                <div class="no-photo" data-bs-toggle="tooltip" data-bs-title="Tidak ada foto">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($mhs['nim']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($mhs['nama']); ?></td>
                                        <td>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($mhs['jurusan']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary"><?php echo $mhs['semester']; ?></span>
                                        </td>
                                        <td class="text-center action-buttons">
                                            <a href="edit.php?id=<?php echo $mhs['id']; ?>" 
                                               class="btn btn-warning btn-sm"
                                               data-bs-toggle="tooltip"
                                               data-bs-title="Edit Data">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <a href="delete.php?id=<?php echo $mhs['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus data <?php echo htmlspecialchars($mhs['nama']); ?>?')"
                                               data-bs-toggle="tooltip"
                                               data-bs-title="Hapus Data">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i> Total: <strong><?php echo count($mahasiswa); ?></strong> mahasiswa
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                                    <i class="bi bi-printer"></i> Print
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-download"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-people display-1 text-muted"></i>
                        </div>
                        <h4 class="text-muted">Tidak ada data mahasiswa</h4>
                        <p class="text-muted">Belum ada data mahasiswa yang terdaftar dalam sistem.</p>
                        <a href="create.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Mahasiswa Pertama
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Stats -->
        <?php if(count($mahasiswa) > 0): ?>
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Mahasiswa</h6>
                                <h4 class="mb-0"><?php echo count($mahasiswa); ?></h4>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Jurusan</h6>
                                <h4 class="mb-0">
                                    <?php 
                                    $jurusan_count = count(array_unique(array_column($mahasiswa, 'jurusan')));
                                    echo $jurusan_count;
                                    ?>
                                </h4>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-bookmarks-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Rata-rata Semester</h6>
                                <h4 class="mb-0">
                                    <?php 
                                    $avg_semester = array_sum(array_column($mahasiswa, 'semester')) / count($mahasiswa);
                                    echo number_format($avg_semester, 1);
                                    ?>
                                </h4>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-graph-up fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Dengan Foto</h6>
                                <h4 class="mb-0">
                                    <?php 
                                    $with_photo = count(array_filter($mahasiswa, function($mhs) {
                                        return !empty($mhs['foto']);
                                    }));
                                    echo $with_photo;
                                    ?>
                                </h4>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-camera-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>