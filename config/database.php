<?php
$hostname = "localhost";
$username = "root";
$password = "";
$db_name = "data";

// Koneksi ke MySQL
$db = new mysqli($hostname, $username, $password);

// Periksa koneksi
if ($db->connect_error) {
    die("Koneksi ke database gagal: " . $db->connect_error);
}

// Cek apakah database sudah ada
$db->query("CREATE DATABASE IF NOT EXISTS $db_name");

// Pilih database
$db->select_db($db_name);

// Periksa apakah tabel sudah ada dalam database
$check_tables = $db->query("SHOW TABLES");
if ($check_tables->num_rows == 0) {
    // Jika tabel belum ada, import file SQL
    $sqlFile = __DIR__ . '/../sql/data.sql';
    
    if (file_exists($sqlFile)) {
        $queries = file_get_contents($sqlFile);
        
        if ($queries !== false && !empty($queries)) {
            // Eksekusi query SQL baris per baris
            $queryList = explode(";", $queries);
            
            foreach ($queryList as $query) {
                $trimmedQuery = trim($query);
                if (!empty($trimmedQuery)) {
                    $db->query($trimmedQuery);
                }
            }
        }
    }
}

// Database siap digunakan
$database = $db;
?>
