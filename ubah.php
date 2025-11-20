<?php
include_once 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID tidak ditemukan");
}

// ambil data lama
$sql = "SELECT * FROM data_barang WHERE id_barang='$id'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) die("Data tidak tersedia");

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga_jual = $_POST['harga_jual'];
    $harga_beli = $_POST['harga_beli'];
    $stok = $_POST['stok'];

    $gambar = $data['gambar'];
    if (isset($_FILES['file_gambar']) && $_FILES['file_gambar']['error'] == 0) {
        $filename = str_replace(' ', '_', $_FILES['file_gambar']['name']);
        $destination = 'gambar/' . $filename;
        if (move_uploaded_file($_FILES['file_gambar']['tmp_name'], $destination)) {
            $gambar = $destination;
        }
    }

    $sql_update = "UPDATE data_barang SET 
                    nama='$nama', 
                    kategori='$kategori', 
                    harga_jual='$harga_jual', 
                    harga_beli='$harga_beli', 
                    stok='$stok', 
                    gambar='$gambar'
                   WHERE id_barang='$id'";
    mysqli_query($conn, $sql_update);
    header('Location: index.php');
    exit;
}

// fungsi untuk selected option
function is_select($var, $val) {
    return $var == $val ? 'selected' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Ubah Barang</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <h1>Ubah Barang</h1>
    <form method="post" action="" enctype="multipart/form-data">
        <label>Nama Barang</label><br>
        <input type="text" name="nama" value="<?= $data['nama']; ?>" required><br><br>

        <label>Kategori</label><br>
        <select name="kategori" required>
            <option value="Komputer" <?= is_select($data['kategori'], 'Komputer'); ?>>Komputer</option>
            <option value="Elektronik" <?= is_select($data['kategori'], 'Elektronik'); ?>>Elektronik</option>
            <option value="Hand Phone" <?= is_select($data['kategori'], 'Hand Phone'); ?>>Hand Phone</option>
        </select><br><br>

        <label>Harga Jual</label><br>
        <input type="number" name="harga_jual" value="<?= $data['harga_jual']; ?>" required><br><br>

        <label>Harga Beli</label><br>
        <input type="number" name="harga_beli" value="<?= $data['harga_beli']; ?>" required><br><br>

        <label>Stok</label><br>
        <input type="number" name="stok" value="<?= $data['stok']; ?>" required><br><br>

        <label>File Gambar</label><br>
        <input type="file" name="file_gambar"><br>
        <?php if($data['gambar']): ?>
            <img src="<?= $data['gambar']; ?>" width="100" alt="<?= $data['nama']; ?>">
        <?php endif; ?><br><br>

        <input type="submit" name="submit" value="Simpan">
    </form>
</div>
</body>
</html>
