<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

// Get current data untuk hapus foto
$query = "SELECT * FROM mahasiswa WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

if ($mahasiswa) {
    // Delete foto jika ada
    if ($mahasiswa['foto'] && file_exists('../uploads/' . $mahasiswa['foto'])) {
        unlink('../uploads/' . $mahasiswa['foto']);
    }
    
    // Delete data dari database
    $query = "DELETE FROM mahasiswa WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Data mahasiswa berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus data mahasiswa!";
    }
}

header("Location: index.php");
exit();
?>