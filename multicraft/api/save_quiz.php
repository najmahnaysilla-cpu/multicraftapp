<?php
header("Content-Type: application/json; charset=UTF-8");
include_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $score = intval($_POST['score'] ?? 0);

    if (empty($username) || empty($category)) {
        echo json_encode([
            "status" => "error",
            "message" => "Data tidak lengkap!"
        ]);
        exit();
    }

    try {
        $stmt = $conn->prepare("INSERT INTO quiz_submissions (username, category, score) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$username, $category, $score])) {
            echo json_encode([
                "status" => "success",
                "message" => "Nilai berhasil disimpan!"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Gagal menyimpan nilai."
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