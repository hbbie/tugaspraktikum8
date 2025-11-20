# tugaspraktikum8
tugas  pemrograman web pertemuan 10

# Nama : Dhani Naufal Habibie
# NIM  : 312410300
# Kelas: TI.24.A4

# Lab8Web - Praktikum 8: PHP dan Database MySQL  
### Aplikasi CRUD Sederhana Data Barang (PHP Native + MySQL)

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.0%2B-blue?style=for-the-badge&logo=php"/>
  <img src="https://img.shields.io/badge/MySQL-8.0-blue?style=for-the-badge&logo=mysql"/>
  <img src="https://img.shields.io/badge/XAMPP-v8.2-orange?style=for-the-badge"/>
  <img src="https://img.shields.io/badge/Status-Selesai-brightgreen?style=for-the-badge"/>
</p>

**Mata Kuliah**    : Pemrograman Web  
**Dosen Pengampu** : Agung Nugroho, S.Kom., M.Kom.  
**Email Dosen**    : agung@pelitabangsa.ac.id  
**Universitas**    : Universitas Pelita Bangsa  
**Repository**     : Lab8Web (Praktikum 8)

---

## Tujuan Praktikum
1. Mahasiswa memahami konsep dasar Database dan MySQL
2. Mahasiswa memahami konsep dasar operasi CRUD menggunakan PHP
3. Mahasiswa mampu membuat program CRUD sederhana dengan PHP native

## Studi Kasus
Aplikasi manajemen **Data Barang** (tabel `data_barang`) dengan fitur:
- Create (Tambah barang + upload gambar)
- Read (Tampilkan semua data)
- Update (Ubah data barang)
- Delete (Hapus barang)

## Struktur Folder Project

lab8_php_database/
├── index.php        -> Halaman utama (Read - Daftar Barang)
├── tambah.php       -> Form tambah barang baru (Create)
├── ubah.php         -> Form ubah data barang (Update)
├── hapus.php        -> Proses hapus barang (Delete)
├── koneksi.php      -> Koneksi ke database MySQL
├── style.css        -> Styling tampilan
├── gambar/          -> Folder penyimpanan gambar barang yang di-upload
└── db_latihan1.sql  -> Script SQL database + tabel + data awal


## Langkah-langkah Praktikum & Penjelasan

## Konsep Dasar

Pada praktikum ini kita mempelajari implementasi CRUD menggunakan PHP native dan database MySQL.

CRUD adalah singkatan dari:

Operasi	Fungsi	Penjelasan
Create	Tambah data	Menyimpan data baru ke database
Read	Baca data	Menampilkan data dari tabel
Update	Edit data	Mengubah data yang sudah ada
Delete	Hapus data	Menghapus data dari database

### 1. Persiapan Environment
- Text editor: Visual Studio Code
- Web server: XAMPP
- Buat folder `lab8_php_database` di `htdocs`

### 2. Menjalankan MySQL Server & phpMyAdmin
- Start Apache dan MySQL dari XAMPP Control Panel  
- Akses: http://localhost/phpmyadmin

### 3. Membuat Database, Tabel, Menambah Data

### A. Membuat Database
```
CREATE DATABASE latihan1;
```

### B. Membuat Tabel
```
CREATE TABLE data_barang (
 id_barang int(10) auto_increment Primary Key,
 kategori varchar(30),
 nama varchar(30),
 gambar varchar(100),
 harga_beli decimal(10,0),
 harga_jual decimal(10,0),
 stok int(4)
);
```

# C. Menambah Data
```
INSERT INTO data_barang (kategori, nama, gambar, harga_beli, harga_jual, stok)
VALUES ('Elektronik', 'HP Samsung Android', 'hp_samsung.jpg', 2000000, 2400000, 5),
('Elektronik', 'HP Xiaomi Android', 'hp_xiaomi.jpg', 1000000, 1400000, 5),
('Elektronik', 'HP OPPO Android', 'hp_oppo.jpg', 1800000, 2300000, 5);
```

### 4. Membuat file koneksi database
```
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "latihan1";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    echo "Koneksi ke server gagal.";
    die();
} else {
    echo "Koneksi berhasil!";
}
?>
```

### 5. Membuat file index untuk menampilkan data (Read)
```
<?php
include("koneksi.php");

// query untuk menampilkan data
$sql = "SELECT * FROM data_barang";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Data Barang</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <h1>Data Barang</h1>
    <a href="tambah.php">+ Tambah Barang</a>
    <br><br>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Gambar</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Harga Jual</th>
            <th>Harga Beli</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php if($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <?php if($row['gambar']): ?>
                            <img src="<?= $row['gambar'];?>" width="80" alt="<?= $row['nama'];?>">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?= $row['nama'];?></td>
                    <td><?= $row['kategori'];?></td>
                    <td><?= $row['harga_jual'];?></td>
                    <td><?= $row['harga_beli'];?></td>
                    <td><?= $row['stok'];?></td>
                    <td>
                        <a href="ubah.php?id=<?= $row['id_barang'];?>">Ubah</a> | 
                        <a href="hapus.php?id=<?= $row['id_barang'];?>" onclick="return confirm('Yakin ingin dihapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Belum ada data</td>
            </tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
```

### 6. Menambah Data (Create)
```
<?php
include_once 'koneksi.php';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga_jual = $_POST['harga_jual'];
    $harga_beli = $_POST['harga_beli'];
    $stok = $_POST['stok'];

    $gambar = null;
    if (isset($_FILES['file_gambar']) && $_FILES['file_gambar']['error'] == 0) {
        $filename = str_replace(' ', '_', $_FILES['file_gambar']['name']);
        $destination = 'gambar/' . $filename;
        if (move_uploaded_file($_FILES['file_gambar']['tmp_name'], $destination)) {
            $gambar = $destination;
        }
    }

    $sql = "INSERT INTO data_barang (nama, kategori, harga_jual, harga_beli, stok, gambar)
            VALUES ('$nama', '$kategori', '$harga_jual', '$harga_beli', '$stok', '$gambar')";
    mysqli_query($conn, $sql);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tambah Barang</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="container">
    <h1>Tambah Barang</h1>
    <form method="post" action="" enctype="multipart/form-data">
        <label>Nama Barang</label><br>
        <input type="text" name="nama" required><br><br>

        <label>Kategori</label><br>
        <select name="kategori" required>
            <option value="Komputer">Komputer</option>
            <option value="Elektronik">Elektronik</option>
            <option value="Hand Phone">Hand Phone</option>
        </select><br><br>

        <label>Harga Jual</label><br>
        <input type="number" name="harga_jual" required><br><br>

        <label>Harga Beli</label><br>
        <input type="number" name="harga_beli" required><br><br>

        <label>Stok</label><br>
        <input type="number" name="stok" required><br><br>

        <label>File Gambar</label><br>
        <input type="file" name="file_gambar"><br><br>

        <input type="submit" name="submit" value="Simpan">
    </form>
</div>
</body>
</html>
```

### 7. Mengubah Data (Update)
```
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
```

### 8. Menghapus Data (Delete)
```
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Error: ID tidak ditemukan di URL");
}

// Cek apakah data dengan ID tersebut ada
$cek = mysqli_query($conn, "SELECT * FROM data_barang WHERE id_barang='$id'");
if (!$cek) {
    die("Error query cek data: " . mysqli_error($conn));
}
if (mysqli_num_rows($cek) == 0) {
    die("Error: Data dengan ID $id tidak ditemukan");
}

// Hapus data
$sql = "DELETE FROM data_barang WHERE id_barang='$id'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error menghapus data: " . mysqli_error($conn));
}

// Jika berhasil, redirect ke index.php
header('Location: index.php');
exit;
?>
```
