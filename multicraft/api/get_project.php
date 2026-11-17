<?php
header("Content-Type: application/json; charset=UTF-8");
include_once 'config/database.php';

try {
    // Mengambil seluruh data proyek dari database MySQL
    $stmt = $conn->prepare("SELECT * FROM projects ORDER BY id DESC");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $projects
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal memuat data: " . $e->getMessage()
    ]);
}
?>