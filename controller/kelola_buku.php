<?php
include "../config/database.php";

session_start(); //memulai sesi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id = $_POST['id'];
    $kategori = $_POST['kategori'];
    $nama_buku = $_POST['nama_buku'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $penerbit = $_POST['penerbit'];
    
    // Cek apakah ini mode edit dengan memeriksa input hidden 'original_id'
    $is_editing = !empty($_POST['original_id']);
    
    if ($is_editing) { // Mode edit buku
        $original_id = $_POST['original_id'];
        
        // Jika ID telah diubah, lakukan pengecekan duplikasi
        if ($id !== $original_id) {
            $stmt = $db->prepare("SELECT id FROM buku WHERE id=?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error_message_buku = "ID sudah digunakan, harap menggunakan ID yang berbeda.";
            }
            $stmt->close();
        }
        
        // Jika tidak ada error, update data buku
        if (!isset($error_message_buku)) {
            // Jika ID diubah, update dengan mengganti key (id)
            $sql = "UPDATE buku SET id=?, kategori=?, nama_buku=?, harga=?, stok=?, penerbit=? WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("sssiiss", $id, $kategori, $nama_buku, $harga, $stok, $penerbit, $original_id);
            
            if ($stmt->execute()) {
                $_SESSION['message_buku'] = "Data Berhasil Diperbarui.";
                header("Location: ../admin.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else { // Mode tambah buku
        // Cek apakah ID sudah ada
        $stmt = $db->prepare("SELECT id FROM buku WHERE id=?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $_SESSION['error_message_buku'] = "ID sudah digunakan, harap menggunakan ID yang berbeda.";
            header("Location: ../admin.php");
            exit();
        } else {
            $sql = "INSERT INTO buku (id, kategori, nama_buku, harga, stok, penerbit) VALUES (?,?,?,?,?,?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("sssiis", $id, $kategori, $nama_buku, $harga, $stok, $penerbit);
            
            if ($stmt->execute()) {
                $_SESSION['message_buku'] = "Data Berhasil Disimpan.";
                header("Location: ../admin.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

?>