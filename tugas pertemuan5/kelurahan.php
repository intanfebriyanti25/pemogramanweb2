<?php
include 'dbkoneksi.php';

// Inisialisasi variabel
$id = '';
$nama_kelurahan = '';

// Jika ada parameter edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $dbh->prepare("SELECT * FROM kelurahan WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $nama_kelurahan = $row['nama_kelurahan'];
    }
}

// Proses simpan (insert atau update)
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama_kelurahan = $_POST['nama_kelurahan'];

    if ($id) {
        // Update data
        $stmt = $dbh->prepare("UPDATE kelurahan SET nama_kelurahan = ? WHERE id = ?");
        $stmt->execute([$nama_kelurahan, $id]);
    } else {
        // Insert data baru
        $stmt = $dbh->prepare("INSERT INTO kelurahan (nama_kelurahan) VALUES (?)");
        $stmt->execute([$nama_kelurahan]);
    }

    header("Location: kelurahan.php");
    exit;
}

// Proses delete
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $stmt = $dbh->prepare("DELETE FROM kelurahan WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    header("Location: kelurahan.php");
    exit;
}

// Ambil data kelurahan untuk ditampilkan pada halaman utama
$query = "SELECT * FROM kelurahan";
$stmt = $dbh->query($query);
$kelurahan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Kelurahan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <!-- Tambahkan Bootstrap CSS untuk Modal -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="kelurahan.php" class="brand-link">
        <span class="brand-text font-weight-light">Dashboard Puskesmas</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item">
                    <a href="form_pasien.php" class="nav-link"><i class="nav-icon fas fa-user-plus"></i> <p>Form Pasien</p></a>
                </li>
                <li class="nav-item">
                    <a href="kelurahan.php" class="nav-link"><i class="nav-icon fas fa-map-marker-alt"></i> <p>Kelurahan</p></a>
                </li>
                <li class="nav-item">
                    <a href="paramedis.php" class="nav-link"><i class="nav-icon fas fa-user-md"></i> <p>Paramedis</p></a>
                </li>
                <li class="nav-item">
                    <a href="pasien.php" class="nav-link"><i class="nav-icon fas fa-users"></i> <p>Pasien</p></a>
                </li>
                <li class="nav-item">
                    <a href="periksa.php" class="nav-link"><i class="nav-icon fas fa-stethoscope"></i> <p>Periksa</p></a>
                </li>
                <li class="nav-item">
                    <a href="unit_kerja.php" class="nav-link"><i class="nav-icon fas fa-building"></i> <p>Unit Kerja</p></a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1 class="ml-3 mt-3">Data Kelurahan</h1>
        </section>
        <section class="content">
            <div class="card m-3">
                <div class="card-body">
                    <!-- Button to Open Modal -->
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#kelurahanModal">
                        Tambah Kelurahan
                    </button>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kelurahan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($kelurahan)): ?>
                                <?php foreach($kelurahan as $row): ?>
                                <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td><?= $row['nama_kelurahan']; ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#kelurahanModal" onclick="editKelurahan(<?= $row['id']; ?>, '<?= $row['nama_kelurahan']; ?>')">Edit</button>
                                        
                                        <!-- Delete Button -->
                                        <a href="kelurahan.php?delete&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center">Tidak ada data kelurahan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

</div>

<!-- Modal Form Tambah/Edit Kelurahan -->
<div class="modal fade" id="kelurahanModal" tabindex="-1" aria-labelledby="kelurahanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kelurahanModalLabel">Tambah Kelurahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="kelurahan.php" method="POST">
                    <input type="hidden" name="id" id="kelurahanId" value="">

                    <div class="form-group">
                        <label for="nama_kelurahan">Nama Kelurahan</label>
                        <input type="text" name="nama_kelurahan" id="nama_kelurahan" class="form-control" required>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan Bootstrap JS dan Popper.js untuk Modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Fungsi untuk mengisi form dengan data kelurahan yang akan diedit
    function editKelurahan(id, nama_kelurahan) {
        document.getElementById('kelurahanId').value = id;
        document.getElementById('nama_kelurahan').value = nama_kelurahan;
        document.getElementById('kelurahanModalLabel').innerText = 'Edit Kelurahan';
    }
</script>

</body>
</html>
