<?php
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);
include_once 'config/database.php';

// Pastikan request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Metode request tidak diizinkan."
    ]);
    exit();
}

// Ambil data yang dikirimkan dari Flutter (ubah username menjadi nama)
$nama = isset($_POST['username']) ? trim($_POST['username']) : (isset($_POST['nama']) ? trim($_POST['nama']) : '');
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validasi jika ada field yang kosong
if (empty($nama) || empty($email) || empty($password)) {
    echo json_encode([
        "success" => false,
        "message" => "Semua kolom wajib diisi."
    ]);
    exit();
}

// Validasi format email sederhana
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "Format email tidak valid."
    ]);
    exit();
}

try {
    // Cek apakah email sudah terdaftar sebelumnya (gunakan $conn sesuai konfigurasi umum)
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $checkStmt->execute(['email' => $email]);
    
    if ($checkStmt->rowCount() > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Email sudah terdaftar. Silakan gunakan email lain."
        ]);
        exit();
    }

    // Hash password agar aman disimpan di database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan data user baru ke database (menggunakan kolom `nama`, bukan `username`)
    $stmt = $conn->prepare("INSERT INTO users (nama, email, password) VALUES (:nama, :email, :password)");
    $saved = $stmt->execute([
        'nama' => $nama,
        'email' => $email,
        'password' => $hashedPassword
    ]);

    if ($saved) {
        echo json_encode([
            "success" => true,
            "message" => "Registrasi berhasil! Silakan masuk."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Gagal mendaftarkan akun."
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Terjadi kesalahan pada database: " . $e->getMessage()
    ]);
}
?>