<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get data mahasiswa by ID
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

// Get current data
$query = "SELECT * FROM mahasiswa WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mahasiswa) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $semester = $_POST['semester'];
    
    // Handle file upload
    $foto = $mahasiswa['foto']; // keep existing photo
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_extensions)) {
            // Delete old photo if exists
            if ($mahasiswa['foto'] && file_exists('../uploads/' . $mahasiswa['foto'])) {
                unlink('../uploads/' . $mahasiswa['foto']);
            }
            
            $foto = uniqid() . '_' . date('Ymd') . '.' . $file_extension;
            move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $foto);
        }
    }
    
    $query = "UPDATE mahasiswa SET 
              nim = :nim, 
              nama = :nama, 
              jurusan = :jurusan, 
              semester = :semester, 
              foto = :foto 
              WHERE id = :id";
              
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(':nim', $nim);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':jurusan', $jurusan);
    $stmt->bindParam(':semester', $semester);
    $stmt->bindParam(':foto', $foto);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Data mahasiswa berhasil diupdate!";
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal mengupdate data mahasiswa!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Edit Data Mahasiswa</h4>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nim" class="form-label">NIM</label>
                                        <input type="text" class="form-control" id="nim" name="nim" 
                                               value="<?php echo htmlspecialchars($mahasiswa['nim']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama" name="nama" 
                                               value="<?php echo htmlspecialchars($mahasiswa['nama']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jurusan" class="form-label">Jurusan</label>
                                        <select class="form-select" id="jurusan" name="jurusan" required>
                                            <option value="">Pilih Jurusan</option>
                                            <option value="Informatika" <?php echo $mahasiswa['jurusan'] == 'Informatika' ? 'selected' : ''; ?>>Informatika</option>
                                            <option value="Sistem Informasi" <?php echo $mahasiswa['jurusan'] == 'Sistem Informasi' ? 'selected' : ''; ?>>Sistem Informasi</option>
                                            <option value="Teknik Komputer" <?php echo $mahasiswa['jurusan'] == 'Teknik Komputer' ? 'selected' : ''; ?>>Teknik Komputer</option>
                                            <option value="Manajemen Informatika" <?php echo $mahasiswa['jurusan'] == 'Manajemen Informatika' ? 'selected' : ''; ?>>Manajemen Informatika</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="semester" class="form-label">Semester</label>
                                        <select class="form-select" id="semester" name="semester" required>
                                            <option value="">Pilih Semester</option>
                                            <?php for($i = 1; $i <= 14; $i++): ?>
                                                <option value="<?php echo $i; ?>" 
                                                    <?php echo $mahasiswa['semester'] == $i ? 'selected' : ''; ?>>
                                                    Semester <?php echo $i; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Mahasiswa</label>
                                <?php if($mahasiswa['foto']): ?>
                                    <div class="mb-2">
                                        <img src="../uploads/<?php echo $mahasiswa['foto']; ?>" 
                                             alt="Foto <?php echo $mahasiswa['nama']; ?>" 
                                             class="img-thumbnail" width="150">
                                        <br>
                                        <small>Foto saat ini</small>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah foto.</div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary">⬅️ Kembali</a>
                                <button type="submit" class="btn btn-primary">💾 Update Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>