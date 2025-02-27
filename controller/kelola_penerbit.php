<?php
include "../config/database.php";

session_start(); //memulai sesi

// Inisialisasi variabel
$edit_penerbit = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id = $_POST['id'];
    $nama_penerbit = $_POST['nama_penerbit'];
    $alamat = $_POST['alamat'];
    $kota = $_POST['kota'];
    $telepon = $_POST['telepon'];
    
    // Cek apakah ini mode edit dengan memeriksa input hidden 'original_id'
    $is_editing = !empty($_POST['original_id']);
    
    if ($is_editing) { // Mode edit penerbit
        $original_id = $_POST['original_id'];
        
        // Jika ID telah diubah, lakukan pengecekan duplikasi
        if ($id !== $original_id) {
            $stmt = $db->prepare("SELECT id FROM penerbit WHERE id=?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error_message_penerbit = "ID sudah digunakan, harap menggunakan ID yang berbeda.";
            }
            $stmt->close();
        }
        
        // Jika tidak ada error, update data
        if (!isset($error_message_penerbit)) {
            // Jika ID diubah, update dengan mengganti key (id)
            $sql = "UPDATE penerbit SET id=?, nama_penerbit=?, alamat=?, kota=?, telepon=? WHERE id=?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ssssis", $id, $nama_penerbit, $alamat, $kota, $telepon, $original_id);
            
            if ($stmt->execute()) {
                $_SESSION['message_penerbit'] = "Data Berhasil Diperbarui.";
                header("Location: ../admin.php#form-penerbit");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else { // Mode tambah penerbit
        // Cek apakah ID sudah ada
        $stmt = $db->prepare("SELECT id FROM penerbit WHERE id=?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $_SESSION['error_message_penerbit'] = "ID sudah digunakan, harap menggunakan ID yang berbeda.";
            header("Location: ../admin.php#form-penerbit");
            exit();
        } else {
            $sql = "INSERT INTO penerbit (id, nama_penerbit, alamat, kota, telepon) VALUES (?,?,?,?,?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ssssi", $id, $nama_penerbit, $alamat, $kota, $telepon);
            
            if ($stmt->execute()) {
                $_SESSION['message_penerbit'] = "Data Berhasil Disimpan.";
                header("Location: ../admin.php#form-penerbit");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

?>