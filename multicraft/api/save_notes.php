<?php
header("Content-Type: application/json; charset=UTF-8");
include_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_title = trim($_POST['project_title'] ?? '');
    $tahap_nama = trim($_POST['tahap_nama'] ?? '');
    $catatan = trim($_POST['catatan'] ?? '');

    if (empty($project_title) || empty($tahap_nama)) {
        echo json_encode([
            "status" => "error",
            "message" => "Judul proyek dan nama tahap wajib ada!"
        ]);
        exit();
    }

    try {
        // Cek apakah catatan untuk tahap ini sudah ada
        $stmtCheck = $conn->prepare("SELECT id FROM stage_notes WHERE project_title = ? AND tahap_nama = ?");
        $stmtCheck->execute([$project_title, $tahap_nama]);

        if ($stmtCheck->rowCount() > 0) {
            // Update jika sudah ada
            $stmt = $conn->prepare("UPDATE stage_notes SET catatan = ? WHERE project_title = ? AND tahap_nama = ?");
            $stmt->execute([$catatan, $project_title, $tahap_nama]);
        } else {
            // Insert jika belum ada
            $stmt = $conn->prepare("INSERT INTO stage_notes (project_title, tahap_nama, catatan) VALUES (?, ?, ?)");
            $stmt->execute([$project_title, $tahap_nama, $catatan]);
        }

        echo json_encode([
            "status" => "success",
            "message" => "Catatan berhasil disimpan ke database!"
        ]);

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