<?php
include "config/database.php";

$no = 1;

$search = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search)) {
    $stmt = $db->prepare("SELECT buku.*, penerbit.nama_penerbit FROM buku 
                          LEFT JOIN penerbit ON buku.penerbit = penerbit.id 
                          WHERE buku.nama_buku LIKE ?");
    $search_param = "%" . $search . "%";
    $stmt->bind_param("s", $search_param); // Pencarian hanya untuk nama buku
    $stmt->execute();
    $stmt_buku = $stmt->get_result();
} else {
    // Menampilkan seluruh buku
    $stmt_buku = $db->query("SELECT buku.*, penerbit.nama_penerbit FROM buku 
                              LEFT JOIN penerbit ON buku.penerbit = penerbit.id 
                              ORDER BY nama_buku ASC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="asset/style.css">
    <title>Home</title>
</head>
<body>
    <?php include 'asset/header.html' ?>

    <div class="container">
        <h2 class="home">Home</h2>
        <div class="row">
            <!-- Daftar Produk -->
            <div class="mb-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Daftar Buku</h4>
                        <form class="d-flex" method="GET">
                            <input class="form-control me-2" type="search" name="search" placeholder="Cari Nama Buku" 
                                aria-label="Search" value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn btn-primary me-2" type="submit">Search</button>
                            <a href="index.php" class="btn btn-danger">Batal</a> <!-- Tombol reset -->
                        </form>
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
                                <?php if ($stmt_buku->num_rows > 0): ?>
                                    <?php while ($row = $stmt_buku->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_buku']); ?></td>
                                            <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                            <td><?php echo htmlspecialchars($row['stok']); ?></td>
                                            <td><?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_penerbit']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="7">Tidak ada data buku ditemukan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>