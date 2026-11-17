<?php
header("Content-Type: application/json; charset=UTF-8");
include_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_email = trim($_POST['old_email'] ?? '');
    $new_name = trim($_POST['new_name'] ?? '');
    $new_email = trim($_POST['new_email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');

    if (empty($old_email) || empty($new_name) || empty($new_email)) {
        echo json_encode([
            "status" => "error",
            "message" => "Data tidak boleh kosong!"
        ]);
        exit();
    }

    try {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE email = ?");
        
        if ($stmt->execute([$new_name, $new_email, $old_email])) {
            echo json_encode([
                "status" => "success",
                "message" => "Profil berhasil diperbarui!"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Gagal memperbarui profil di database."
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