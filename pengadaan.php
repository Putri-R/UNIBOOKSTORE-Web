<?php
include "config/database.php";

$no = 1;

// Mengambil stok terendah
$stmt_min_stok = $db->query("SELECT MIN(stok) AS min_stok FROM buku");
$row_min = $stmt_min_stok->fetch_assoc();
$min_stok = $row_min['min_stok']; 

// Ambil semua buku dengan stok terendah
$stmt_stok = $db->query("SELECT buku.nama_buku, penerbit.nama_penerbit, buku.stok FROM buku
                         LEFT JOIN penerbit ON buku.penerbit = penerbit.id
                         WHERE buku.stok = $min_stok");

// urut berdasarkan stok terendah atau id terkecil
$stmt_buku = $db->query("SELECT buku.*, penerbit.nama_penerbit 
                          FROM buku 
                          LEFT JOIN penerbit ON buku.penerbit = penerbit.id 
                          ORDER BY stok ASC, CAST(SUBSTR(buku.id, 2) AS UNSIGNED) ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="asset/style.css">
    <title>Pengadaan</title>
</head>
<body>
    <?php include 'asset/header.html' ?>

    <div class="container">
        <h2 class="pengadaan">Pengadaan</h2>
        <div class="row">
            <!-- Daftar Produk -->
            <div class="mb-3">
                
                <p>Di bawah ini adalah daftar buku dengan stok paling sedikit :</p>
                <?php while ($row = $stmt_stok->fetch_assoc()): ?>
                    <p>- <span>Buku <b><?php echo htmlspecialchars($row['nama_buku']); ?></b> dari penerbit <b><?php echo htmlspecialchars($row['nama_penerbit']); ?></b> (Stok: <?php echo $row['stok']; ?>)</span></p>
                <?php endwhile; ?>
                
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Daftar Buku</h4>
                        <hr>

                        <!-- Tabel Daftar Produk -->
                        <table class="table align-middle">
                            <colgroup>
                                <col class="w-10">
                                <col class="w-30">
                                <col class="w-20">
                                <col class="w-10">
                                <col class="w-10">
                                <col class="w-15">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>ID</th>
                                    <th>Nama Buku</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Penerbit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $stmt_buku->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['nama_buku']; ?></td>
                                    <td><?php echo $row['kategori']; ?></td>
                                    <td><?php echo $row['stok']; ?></td>
                                    <td><?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo $row['nama_penerbit']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>