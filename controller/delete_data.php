<?php
include '../config/database.php';
session_start();

if (isset($_GET['id']) && isset($_GET['table'])) {
    $id = $_GET['id'];
    $table = $_GET['table']; // Menentukan tabel yang akan dihapus

    // Validasi tabel
    if ($table === 'buku' || $table === 'penerbit') {
        // Delete data berdasarkan id dan tabel
        $sql = "DELETE FROM $table WHERE id = ?";
        $deleteStatement = $db->prepare($sql);
        $deleteStatement->bind_param("s", $id); // "s" karena id adalah string

        if ($deleteStatement->execute()) {
            header("Location: ../admin.php");
            exit(); // Pastikan untuk keluar setelah header
        } else {
            echo "Gagal menghapus data!";
        }
        $deleteStatement->close();
    } else {
        // Jika nilai table tidak valid
        echo "Tabel tidak valid.";
    }
} else {
    echo "Parameter tidak lengkap.";
}
?>
