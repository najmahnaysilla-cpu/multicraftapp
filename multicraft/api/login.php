<?php
// Mengatur header agar merespons dalam format JSON
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0); // Menyembunyikan notice agar respons JSON bersih

// Memanggil koneksi database dari folder config/database.php
include_once 'config/database.php';

// Memastikan request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Menerima input 'identifier' (bisa berupa email atau nama dari Flutter)
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi jika kosong
    if (empty($identifier) || empty($password)) {
        echo json_encode([
            "status" => "error",
            "message" => "Email/Nama dan Password wajib diisi!"
        ]);
        exit();
    }

    try {
        // Cari user di database berdasarkan email atau nama (karena kolom username tidak ada)
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR nama = ?");
        $stmt->execute([$identifier, $identifier]);
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifikasi password menggunakan password_verify
            if (password_verify($password, $user['password'])) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Login berhasil!",
                    "data" => [
                        "id" => $user['id'],
                        "nama" => $user['nama'],
                        "email" => $user['email']
                    ]
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Password salah!"
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Email atau Nama tidak ditemukan!"
            ]);
        }

    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Terjadi kesalahan pada database: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode request tidak diizinkan!"
    ]);
}
?>