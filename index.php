<?php
include 'koneksi.php';
$pesan = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $pesan = 'Data berhasil disimpan.';
    } elseif ($_GET['status'] === 'empty') {
        $pesan = 'Semua data wajib diisi.';
    } elseif ($_GET['status'] === 'error') {
        $pesan = 'Data gagal disimpan. Pastikan struktur tabel users sudah sesuai.';
    }
}

// Proses submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $prodi = $_POST['prodi'] ?? '';

    if ($nama === '' || $alamat === '' || $jenis_kelamin === '' || $prodi === '') {
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=empty");
        exit();
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            'INSERT INTO users (nama, alamat, jenis_kelamin, prodi) VALUES (?, ?, ?, ?)'
        );
        mysqli_stmt_bind_param($stmt, 'ssss', $nama, $alamat, $jenis_kelamin, $prodi);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            // Redirect ke halaman sendiri untuk menghapus payload POST
            header("Location: " . $_SERVER['PHP_SELF'] . "?status=success");
            exit();
        } else {
            mysqli_stmt_close($stmt);
            header("Location: " . $_SERVER['PHP_SELF'] . "?status=error");
            exit();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran</title>
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Arial, Helvetica, sans-serif;
    }

    /* Mengatur halaman agar scroll rapi dari atas */
    body {
        background-color: #f0f2f5;
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Mengubah dari center ke flex-start agar tidak bertabrakan saat scroll */
        min-height: 100vh;
        padding: 40px 20px;
    }

    /* Pembungkus Utama untuk Memisahkan Kedua Kartu */
    .main-wrapper {
        width: 100%;
        max-width: 600px; /* Lebar yang ideal agar tabel tidak kesempitan */
        display: flex;
        flex-direction: column;
        gap: 25px; /* JARAK PEMISAH UTAMA ANTA KARTU */
    }

    /* Desain Kartu (Card Container) */
    .card {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .card h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333333;
    }

    /* Styling Form & Margin Tiap Baris */
    .b1, .b2, .b3, .b4 {
        margin-bottom: 18px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
        color: #444444;
        font-size: 14px;
    }

    /* Input Text & Select */
    input[type="text"],
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #cccccc;
        border-radius: 5px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s ease;
    }

    input[type="text"]:focus,
    select:focus {
        border-color: #007bff;
    }

    /* Radio Button Styling */
    .radio-group {
        display: flex;
        gap: 15px;
        align-items: center;
        margin-top: 5px;
        font-size: 14px;
        color: #555555;
    }

    .radio-group label {
        font-weight: normal;
        margin-bottom: 0;
        cursor: pointer;
    }

    .radio-group input[type="radio"] {
        cursor: pointer;
    }

    /* Button Submit */
    .btn-submit {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 15px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 10px;
        transition: background-color 0.2s ease;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }

    /* Agar Tabel Tidak Pecah di Layar Kecil */
    .table-container {
        overflow-x: auto;
    }
</style>
    
</head>
<body>
    <div class="main-wrapper">
        
        <!-- KARTU 1: FORMULIR PENDAFTARAN -->
        <div class="card">
            <h2>Formulir Pendaftaran</h2>

            <?php if ($pesan !== ''): ?>
                <p role="status" style="margin-bottom: 16px; text-align: center;">
                    <?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <form method="post" action="">
                <div class="b1">
                    <label for="nama">Nama:</label>
                    <input type="text" name="nama" id="nama" required>
                </div>

                <div class="b2">
                    <label for="alamat">Alamat:</label>
                    <input type="text" name="alamat" id="alamat" required>
                </div> 

                <div class="b3">
                    <label>Jenis Kelamin:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="jenis_kelamin" value="Laki-laki" required> Laki-laki</label>
                        <label><input type="radio" name="jenis_kelamin" value="Perempuan"> Perempuan</label>
                    </div>
                </div>

                <div class="b4">
                    <label for="prodi">Prodi</label>
                    <select name="prodi" id="prodi" required>
                        <option value="" selected disabled hidden>pilih Prodi</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Manajemen Informatika">Manajemen Informatika</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Submit</button>
            </form>
        </div> <!-- TERTUTUP RAPI: KARTU 1 SELESAI -->

        <!-- KARTU 2: DATA MAHASISWA -->
        <div class="card">
            <h2>Data Mahasiswa</h2>
            <div style="overflow-x: auto;">
                <?php
                $result = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id DESC");
                if ($result && mysqli_num_rows($result) > 0) {
                    echo '<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
                    echo '<tr><th>ID</th><th>Nama</th><th>Alamat</th><th>Jenis Kelamin</th><th>Prodi</th></tr>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '<td>' . htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '<td>' . htmlspecialchars($row['alamat'], ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '<td>' . htmlspecialchars($row['jenis_kelamin'], ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '<td>' . htmlspecialchars($row['prodi'], ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p style="text-align: center;">Tidak ada data mahasiswa.</p>';
                }
                ?>
            </div>
        </div> <!-- TERTUTUP RAPI: KARTU 2 SELESAI -->

    </div>
</body>
</html>