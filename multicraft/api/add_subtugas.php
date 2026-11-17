<?php
header("Content-Type: application/json; charset=UTF-8");
include_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_title = trim($_POST['project_title'] ?? '');
    $task_title = trim($_POST['task_title'] ?? '');
    $pj = trim($_POST['pj'] ?? '');
    $deadline = trim($_POST['deadline'] ?? '');

    if (empty($task_title)) {
        echo json_encode([
            "status" => "error",
            "message" => "Judul sub-tugas wajib diisi!"
        ]);
        exit();
    }

    try {
        // Simpan ke tabel tasks (Pastikan tabel tasks ada di phpMyAdmin)
        $stmt = $conn->prepare("INSERT INTO tasks (project_title, task_title, pj, deadline, is_done) VALUES (?, ?, ?, ?, 0)");
        
        if ($stmt->execute([$project_title, $task_title, $pj, $deadline])) {
            echo json_encode([
                "status" => "success",
                "message" => "Sub-tugas berhasil disimpan ke database!"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Gagal menyimpan sub-tugas."
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