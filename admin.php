<?php
include "config/database.php";

session_start(); // Memulai sesi

$edit_buku = null;
$edit_penerbit = null;

// Menampilkan penerbit di select option Tambah Buku
$penerbit_array = [];
$stmt_penerbit = $db->query("SELECT * FROM penerbit ORDER BY nama_penerbit ASC");
while ($row = $stmt_penerbit->fetch_assoc()) {
    $penerbit_array[] = $row; // Menyimpan data dari baris
}

// Cek apakah ada parameter 'edit_data_buku' di URL
if (isset($_GET['edit_data_buku'])) {
    $id = $_GET['edit_data_buku'];
    $stmt = $db->prepare("SELECT * FROM buku WHERE id=?");
    $stmt->bind_param("s", $id); // Menggunakan 's' untuk string
    $stmt->execute();

    $result = $stmt->get_result();
    $edit_buku = $result->fetch_assoc();
}

// Cek apakah ada parameter 'edit_data_penerbit' di URL
if (isset($_GET['edit_data_penerbit'])) {
    $id = $_GET['edit_data_penerbit'];
    $stmt = $db->prepare("SELECT * FROM penerbit WHERE id=?");
    $stmt->bind_param("s", $id); // Menggunakan 's' untuk string
    $stmt->execute();

    $result = $stmt->get_result();
    $edit_penerbit = $result->fetch_assoc();
}

$stmt_buku = $db->query("SELECT buku.*, penerbit.nama_penerbit FROM buku 
                          LEFT JOIN penerbit ON buku.penerbit = penerbit.id 
                          ORDER BY CAST(SUBSTR(buku.id, 2) AS UNSIGNED) ASC");

$stmt_penerbit = $db->query("SELECT * FROM penerbit ORDER BY CAST(SUBSTR(id, 2) AS UNSIGNED) ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="asset/style.css">
    <title>Admin</title>
</head>
<body>
    <!-- Menambahkan header -->
    <?php include 'asset/header.html' ?> 

    <section class="section-buku">
        <div class="container">
            <h2 class="admin">Admin</h2>
            <div class="row">
                <!-- Daftar Produk -->
                <div class="col-md-8 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Daftar Buku</h4>
                            <hr>

                            <!-- menampilkan pesan setelah melakukan edit/add buku -->
                            <?php if (isset($_SESSION['message_buku'])): ?>
                                <div class="alert alert-success">
                                    <?php
                                    echo $_SESSION['message_buku'];
                                    unset($_SESSION['message_buku']); // Hapus pesan setelah ditampilkan
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['error_message_buku'])): ?>
                                <div class="alert alert-danger">
                                    <?php
                                    echo $_SESSION['error_message_buku'];
                                    unset($_SESSION['error_message_buku']); // Hapus pesan setelah ditampilkan
                                    ?>
                                </div>
                            <?php endif; ?>

                            <!-- Tabel Daftar Produk -->
                            <table class="table align-middle">
                                <colgroup>
                                    <col class="w-10">
                                    <col class="w-25">
                                    <col class="w-20">
                                    <col class="w-10">
                                    <col class="w-10">
                                    <col class="w-15">
                                    <col class="w-10">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Buku</th>
                                        <th>Kategori</th>
                                        <th>Stok</th>
                                        <th>Harga</th>
                                        <th>Penerbit</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $stmt_buku->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['nama_buku']; ?></td>
                                        <td><?php echo $row['kategori']; ?></td>
                                        <td><?php echo $row['stok']; ?></td>
                                        <td><?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                        <td><?php echo $row['nama_penerbit']; ?></td>
                                        <td>
                                            <a href="admin.php?edit_data_buku=<?php echo $row['id']; ?>"><i class="fa-solid fa-pen" style="color: #00a832; margin-right: 20px;"></i></a>
                                            <a href="controller/delete_data.php?id=<?php echo $row['id']; ?>&table=buku" onclick="return confirm('Apakah kamu yakin ingin menghapus data ini? Data yang telah dihapus tidak dapat dikembalikan.');">
                                                <i class="fa-solid fa-trash" style="color: #cc0000;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Form Tambah/Edit Buku -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $edit_buku ? 'Edit Buku' : 'Tambah Buku'; ?></h4>
                            <hr>
                            <form action="controller/kelola_buku.php" method="POST">
                                <?php if (isset($edit_buku)): ?>
                                    <input type="hidden" name="original_id" value="<?php echo htmlspecialchars($edit_buku['id']); ?>">
                                <?php endif; ?>
                                <div class="mb-3">
                                    <label for="id" class="form-label">ID Buku</label>
                                    <input type="text" class="form-control" name="id" id="id" placeholder="Masukkan id buku" value="<?php echo isset($edit_buku['id']) ? htmlspecialchars($edit_buku['id']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kategori" class="form-label">Nama Buku</label>
                                    <input type="text" class="form-control" name="kategori" id="kategori" placeholder="Masukkan kategori buku" value="<?php echo isset($edit_buku['nama_buku']) ? htmlspecialchars($edit_buku['kategori']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_buku" class="form-label">Nama Buku</label>
                                    <input type="text" class="form-control" name="nama_buku" id="nama_buku" placeholder="Masukkan nama buku" value="<?php echo isset($edit_buku['nama_buku']) ? htmlspecialchars($edit_buku['nama_buku']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga Buku</label>
                                    <input type="number" class="form-control" name="harga" id="harga" placeholder="Masukkan harga buku" value="<?php echo isset($edit_buku['harga']) ? htmlspecialchars($edit_buku['harga']) : ''; ?>" required min="0" step="1">
                                </div>
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok Buku</label>
                                    <input type="number" class="form-control" name="stok" id="stok" placeholder="Masukkan stok buku" value="<?php echo isset($edit_buku['stok']) ? htmlspecialchars($edit_buku['stok']) : ''; ?>" required min="0" step="1">
                                </div>
                                <div class="mb-3">
                                    <label for="penerbit" class="form-label">Penerbit</label>
                                    <select class="form-control" name="penerbit" id="penerbit" required>
                                        <option value="">-- Pilih Penerbit --</option>
                                        <?php foreach ($penerbit_array as $penerbitItem): ?>
                                            <option value="<?php echo htmlspecialchars($penerbitItem['id']); ?>"
                                                <?php 
                                                    if (isset($edit_buku['penerbit']) && $edit_buku['penerbit'] === $penerbitItem['id']) {
                                                        echo 'selected';
                                                    }
                                                ?>>
                                                <?php echo htmlspecialchars($penerbitItem['nama_penerbit']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <!-- <input type="text" class="form-control" name="penerbit" id="penerbit" placeholder="Masukkan nama buku" value="<?php echo isset($edit_buku['penerbit']) ? htmlspecialchars($edit_buku['penerbit']) : ''; ?>" required> -->
                                </div>
                                
                                <button type="submit" class="btn btn-primary"><?php echo isset($edit_buku) ? 'Update' : 'Tambah'; ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-penerbit" style="margin-bottom: 40px;">
        <div class="container">
            <div class="row">
                <!-- Daftar Penerbit -->
                <div class="col-md-8 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Daftar Penerbit</h4>
                            <hr>

                            <!-- menampilkan pesan setelah melakukan edit/add buku -->
                            <?php if (isset($_SESSION['message_penerbit'])): ?>
                                <div class="alert alert-success">
                                    <?php
                                    echo $_SESSION['message_penerbit'];
                                    unset($_SESSION['message_penerbit']); // Hapus pesan setelah ditampilkan
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['error_message_penerbit'])): ?>
                                <div class="alert alert-danger">
                                    <?php
                                    echo $_SESSION['error_message_penerbit'];
                                    unset($_SESSION['error_message_penerbit']); // Hapus pesan setelah ditampilkan
                                    ?>
                                </div>
                            <?php endif; ?>

                            <!-- Tabel Daftar Produk -->
                            <table class="table align-middle">
                                <colgroup>
                                    <col class="w-10">
                                    <col class="w-25">
                                    <col class="w-25">
                                    <col class="w-10">
                                    <col class="w-10">
                                    <col class="w-10">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Penerbit</th>
                                        <th>Alamat</th>
                                        <th>Kota</th>
                                        <th>Telepon</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $stmt_penerbit->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['nama_penerbit']; ?></td>
                                        <td><?php echo $row['alamat']; ?></td>
                                        <td><?php echo $row['kota']; ?></td>
                                        <td><?php echo $row['telepon']; ?></td>
                                        <td>
                                            <a href="admin.php?edit_data_penerbit=<?php echo $row['id']; ?>#form-penerbit"><i class="fa-solid fa-pen" style="color: #00a832; margin-right: 20px;"></i></a>
                                            <a href="controller/delete_data.php?id=<?php echo $row['id']; ?>&table=penerbit#form-penerbit" onclick="return confirm('Apakah kamu yakin ingin menghapus data ini? Data yang telah dihapus tidak dapat dikembalikan.');">
                                                <i class="fa-solid fa-trash" style="color: #cc0000;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Form Tambah/Edit Penerbit -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $edit_penerbit ? 'Edit Penerbit' : 'Tambah Penerbit'; ?></h4>
                            <hr>
                            <form id="form-penerbit" action="controller/kelola_penerbit.php" method="POST">
                            <?php if (isset($edit_penerbit)): ?>
                                <input type="hidden" name="original_id" value="<?php echo htmlspecialchars($edit_penerbit['id']); ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                    <label for="id" class="form-label">ID Penerbit</label>
                                    <input type="text" class="form-control" name="id" id="id" placeholder="Masukkan ID penerbit" 
                                        value="<?php echo isset($edit_penerbit['id']) ? htmlspecialchars($edit_penerbit['id']) : ''; ?>" 
                                        required <?php echo isset($edit_penerbit['id']) ? 'readonly' : ''; ?>>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_penerbit" class="form-label">Nama Penerbit</label>
                                    <input type="text" class="form-control" name="nama_penerbit" id="nama_penerbit" placeholder="Masukkan nama penerbit" value="<?php echo isset($edit_penerbit['nama_penerbit']) ? htmlspecialchars($edit_penerbit['nama_penerbit']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat Penerbit</label>
                                    <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Masukkan alamat penerbit" value="<?php echo isset($edit_penerbit['alamat']) ? htmlspecialchars($edit_penerbit['alamat']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kota" class="form-label">Kota Penerbit</label>
                                    <input type="text" class="form-control" name="kota" id="kota" placeholder="Masukkan kota penerbit" value="<?php echo isset($edit_penerbit['kota']) ? htmlspecialchars($edit_penerbit['kota']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon Penerbit</label>
                                    <input type="number" class="form-control" name="telepon" id="telepon" placeholder="Masukkan nomor telepon" value="<?php echo isset($edit_penerbit['telepon']) ? htmlspecialchars($edit_penerbit['telepon']) : ''; ?>" required min="0" step="1">
                                </div>
                                
                                <button type="submit" class="btn btn-primary"><?php echo isset($edit_penerbit) ? 'Update' : 'Tambah'; ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>