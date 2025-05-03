<?php
include 'dbkoneksi.php'; // Pastikan file koneksi database sudah benar

// Ambil data kelurahan untuk dropdown
$query = "SELECT * FROM kelurahan"; 
$stmt = $dbh->query($query);
$kelurahan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Proses penyimpanan data pasien
if (isset($_POST['submit'])) {
    // Tangkap data yang dikirim melalui POST
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $tmp_lahir = $_POST['tmp_lahir'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $gender = $_POST['gender'];
    $kelurahan_id = $_POST['kelurahan']; // ID kelurahan yang dipilih
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];

    // Query untuk memasukkan data ke tabel pasien
    $query_insert = "INSERT INTO pasien (kode, nama, tmp_lahir, tgl_lahir, gender, kelurahan_id, email, alamat)
                     VALUES (:kode, :nama, :tmp_lahir, :tgl_lahir, :gender, :kelurahan_id, :email, :alamat)";
    
    // Persiapkan query
    $stmt_insert = $dbh->prepare($query_insert);
    
    // Binding parameter
    $stmt_insert->bindParam(':kode', $kode);
    $stmt_insert->bindParam(':nama', $nama);
    $stmt_insert->bindParam(':tmp_lahir', $tmp_lahir);
    $stmt_insert->bindParam(':tgl_lahir', $tgl_lahir);
    $stmt_insert->bindParam(':gender', $gender);
    $stmt_insert->bindParam(':kelurahan_id', $kelurahan_id);
    $stmt_insert->bindParam(':email', $email);
    $stmt_insert->bindParam(':alamat', $alamat);

    // Eksekusi query
    if ($stmt_insert->execute()) {
        echo "Data pasien berhasil disimpan.";
        // Redirect ke halaman pasien atau tampilan lain setelah penyimpanan sukses
        header('Location: form_pasien.php'); // Ganti dengan halaman yang sesuai
        exit;
    } else {
        echo "Gagal menyimpan data pasien.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Form Pasien</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">Dashboard Klinik</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="form_pasien.php" class="nav-link active">
                            <i class="nav-icon fas fa-user-plus"></i>
                            <p>Form Pasien</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="kelurahan.php" class="nav-link">
                            <i class="nav-icon fas fa-map-marker-alt"></i>
                            <p>Kelurahan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="paramedis.php" class="nav-link">
                            <i class="nav-icon fas fa-user-md"></i>
                            <p>Paramedis</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pasien.php" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Pasien</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="periksa.php" class="nav-link">
                            <i class="nav-icon fas fa-stethoscope"></i>
                            <p>Periksa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="unit_kerja.php" class="nav-link">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Unit Kerja</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1 class="ml-3 mt-3">Form Data Pasien</h1>
        </section>
        <section class="content">
            <div class="card m-3">
                <div class="card-body">
                    <form action="form_pasien.php" method="POST">
                        <div class="form-group">
                            <label for="kode">Kode Pasien</label>
                            <input type="text" class="form-control" id="kode" name="kode" required>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Pasien</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="tmp_lahir">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tmp_lahir" name="tmp_lahir" required>
                        </div>
                        <div class="form-group">
                            <label for="tgl_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Jenis Kelamin</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="">Pilih</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kelurahan">Kelurahan</label>
                            <select class="form-control" id="kelurahan" name="id" required>
                                <option value="">Pilih Kelurahan</option>
                                <?php foreach ($kelurahan as $row): ?>
                                    <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['id']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Pasien</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- JS AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
