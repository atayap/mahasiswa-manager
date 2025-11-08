<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get statistics
$total_mahasiswa = $db->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
$total_jurusan = $db->query("SELECT COUNT(DISTINCT jurusan) FROM mahasiswa")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container mt-4">
        <h2 class="mb-4">Dashboard Mahasiswa Manager</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Mahasiswa</h5>
                        <h2 class="mb-0"><?php echo $total_mahasiswa; ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card text-white bg-success mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Jurusan</h5>
                        <h2 class="mb-0"><?php echo $total_jurusan; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="../mahasiswa/index.php" class="btn btn-primary w-100">
                            👥 Lihat Data Mahasiswa
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="../mahasiswa/create.php" class="btn btn-success w-100">
                            ➕ Tambah Mahasiswa
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="../auth/logout.php" class="btn btn-danger w-100">
                            🚪 Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>