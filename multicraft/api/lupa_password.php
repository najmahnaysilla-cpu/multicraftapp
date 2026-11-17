<?php
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0); // Mencegah warning PHP merusak format JSON
include_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $new_password = $_POST['password'] ?? '';

    if (empty($email) || empty($new_password)) {
        echo json_encode([
            "status" => "error",
            "message" => "Email dan kata sandi baru wajib diisi!"
        ]);
        exit();
    }

    try {
        // Cek apakah email terdaftar di database
        $stmtCheck = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmtCheck->execute([$email]);

        if ($stmtCheck->rowCount() === 0) {
            echo json_encode([
                "status" => "error",
                "message" => "Email tidak terdaftar di database!"
            ]);
            exit();
        }

        // Hash password baru agar aman
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password di database (tambahkan nama tabel 'users')
        $stmtUpdate = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        
        if ($stmtUpdate->execute([$hashed_password, $email])) {
            echo json_encode([
                "status" => "success",
                "message" => "Kata sandi berhasil diubah! Silakan masuk kembali."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Gagal memperbarui kata sandi."
            ]);
        }

    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Kesalahan database: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode request tidak diizinkan!"
    ]);
}
?>