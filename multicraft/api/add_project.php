<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include 'koneksi.php';

// Menangkap data dari request POST Flutter
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$type = isset($_POST['type']) ? trim($_POST['type']) : '';
$owner = isset($_POST['owner']) ? trim($_POST['owner']) : '';
$deadline = isset($_POST['deadline']) ? trim($_POST['deadline']) : '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';

// Validasi jika ada kolom yang kosong
if (empty($title) || empty($type) || empty($owner) || empty($deadline)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua kolom wajib diisi!'
    ]);
    exit;
}

// Masukkan ke database sesuai struktur kolom tabel projects
$query = "INSERT INTO projects (user_id, kategori, nama_project, deskripsi, status_project) VALUES ('$username', '$type', '$title', 'PJ: $owner', 'Deadline: $deadline')";

if (mysqli_query($conn, $query)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Proyek berhasil disimpan!'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal database: ' . mysqli_error($conn)
    ]);
}
?>