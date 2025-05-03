<?php
include 'dbkoneksi.php';

// Cek apakah ada aksi (tambah, edit, hapus)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $tmp_lahir = $_POST['tmp_lahir'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $kelurahan_id = $_POST['kelurahan_id'];

    if (isset($_POST['id'])) {
        // Update pasien
        $id = $_POST['id'];
        $query = "UPDATE pasien SET kode = ?, nama = ?, tmp_lahir = ?, tgl_lahir = ?, gender = ?, email = ?, alamat = ?, kelurahan_id = ? WHERE id = ?";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$kode, $nama, $tmp_lahir, $tgl_lahir, $gender, $email, $alamat, $kelurahan_id, $id]);
        header('Location: pasien.php');
    } else {
        // Tambah pasien
        $query = "INSERT INTO pasien (kode, nama, tmp_lahir, tgl_lahir, gender, email, alamat, kelurahan_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$kode, $nama, $tmp_lahir, $tgl_lahir, $gender, $email, $alamat, $kelurahan_id]);
        header('Location: pasien.php');
    }
}

// Proses hapus pasien
if (isset($_GET['delete'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM pasien WHERE id = :id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header('Location: pasien.php');
}

// Ambil data pasien untuk ditampilkan
$query = "SELECT * FROM pasien";
$stmt = $dbh->query($query);
$pasien = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cek apakah ada ID untuk edit data
$id = isset($_GET['id']) ? $_GET['id'] : null;
$data = null;

if ($id) {
    // Ambil data pasien berdasarkan ID untuk edit
    $query = "SELECT * FROM pasien WHERE id = :id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
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
                        <a href="unit_kerja/unit_kerja.php" class="nav-link"><i class="nav-icon fas fa-building"></i> <p>Unit Kerja</p></a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1 class="ml-3 mt-3"><?= $id ? 'Edit' : 'Tambah' ?> Pasien</h1>
        </section>
        <section class="content">
            <div class="card m-3">
                <div class="card-body">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPasien">
                        <?= $id ? 'Edit' : 'Tambah' ?> Pasien
                    </button>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card m-3">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Tmp Lahir</th>
                                <th>Tgl Lahir</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Kelurahan ID</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pasien)): ?>
                                <?php foreach($pasien as $row): ?>
                                <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td><?= $row['kode']; ?></td>
                                    <td><?= $row['nama']; ?></td>
                                    <td><?= $row['tmp_lahir']; ?></td>
                                    <td><?= $row['tgl_lahir']; ?></td>
                                    <td><?= $row['gender']; ?></td>
                                    <td><?= $row['email']; ?></td>
                                    <td><?= $row['alamat']; ?></td>
                                    <td><?= $row['kelurahan_id']; ?></td>
                                    <td>
                                        <a href="pasien.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="pasien.php?delete&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="10" class="text-center">Tidak ada data pasien.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

</div>

<!-- Modal Form Pasien -->
<div class="modal fade" id="modalPasien" tabindex="-1" role="dialog" aria-labelledby="modalPasienLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPasienLabel"><?= $id ? 'Edit' : 'Tambah' ?> Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="pasien.php" method="POST">
                    <?php if ($id): ?>
                        <input type="hidden" name="id" value="<?= $data['id']; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="kode">Kode</label>
                        <input type="text" name="kode" id="kode" class="form-control" value="<?= $data['kode'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= $data['nama'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tmp_lahir">Tempat Lahir</label>
                        <input type="text" name="tmp_lahir" id="tmp_lahir" class="form-control" value="<?= $data['tmp_lahir'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" value="<?= $data['tgl_lahir'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="L" <?= isset($data['gender']) && $data['gender'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= isset($data['gender']) && $data['gender'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= $data['email'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" required><?= $data['alamat'] ?? ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="kelurahan_id">Kelurahan ID</label>
                        <input type="text" name="kelurahan_id" id="kelurahan_id" class="form-control" value="<?= $data['kelurahan_id'] ?? ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= $id ? 'Update' : 'Tambah' ?> Pasien</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
