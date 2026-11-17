<?php
header("Content-Type: application/json; charset=UTF-8");
include_once 'config/database.php';

try {
    $stmt = $conn->prepare("SELECT * FROM tasks ORDER BY id DESC");
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $tasks
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal memuat data: " . $e->getMessage()
    ]);
}
?>