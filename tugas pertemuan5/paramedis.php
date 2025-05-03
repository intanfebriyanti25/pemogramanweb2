<?php
include 'dbkoneksi.php';

// Ambil data untuk ditampilkan jika edit
$id = $_GET['id'] ?? '';
$paramedis = [];
if ($id) {
    $stmt = $dbh->prepare("SELECT * FROM paramedis WHERE id = ?");
    $stmt->execute([$id]);
    $paramedis = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Proses simpan dan update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['simpan'])) {
        $sql = "INSERT INTO paramedis (nama, gender, tmp_lahir, tgl_lahir, kategori, telpon, alamat, unitkerja_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            $_POST['nama'], $_POST['gender'], $_POST['tmp_lahir'], $_POST['tgl_lahir'],
            $_POST['kategori'], $_POST['telpon'], $_POST['alamat'], $_POST['unitkerja_id']
        ]);
        header('Location: paramedis.php');
        exit;
    }

    if (isset($_POST['update'])) {
        $sql = "UPDATE paramedis SET 
                nama=?, gender=?, tmp_lahir=?, tgl_lahir=?, kategori=?, telpon=?, alamat=?, unitkerja_id=? 
                WHERE id=?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            $_POST['nama'], $_POST['gender'], $_POST['tmp_lahir'], $_POST['tgl_lahir'],
            $_POST['kategori'], $_POST['telpon'], $_POST['alamat'], $_POST['unitkerja_id'], $_POST['id']
        ]);
        header('Location: paramedis.php');
        exit;
    }
}

if (isset($_GET['delete']) && isset($_GET['id'])) {
    $stmt = $dbh->prepare("DELETE FROM paramedis WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: paramedis.php');
    exit;
}

$query = "SELECT * FROM paramedis";
$stmt = $dbh->query($query);
$paramedisList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Paramedis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
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
                    <li class="nav-item"><a href="form_pasien.php" class="nav-link"><i class="nav-icon fas fa-user-plus"></i> <p>Form Pasien</p></a></li>
                    <li class="nav-item"><a href="kelurahan.php" class="nav-link"><i class="nav-icon fas fa-map-marker-alt"></i> <p>Kelurahan</p></a></li>
                    <li class="nav-item"><a href="paramedis.php" class="nav-link"><i class="nav-icon fas fa-user-md"></i> <p>Paramedis</p></a></li>
                    <li class="nav-item"><a href="pasien.php" class="nav-link"><i class="nav-icon fas fa-users"></i> <p>Pasien</p></a></li>
                    <li class="nav-item"><a href="periksa.php" class="nav-link"><i class="nav-icon fas fa-stethoscope"></i> <p>Periksa</p></a></li>
                    <li class="nav-item"><a href="unit_kerja.php" class="nav-link"><i class="nav-icon fas fa-building"></i> <p>Unit Kerja</p></a></li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1 class="ml-3 mt-3">Data Paramedis</h1>
        </section>
        <section class="content">
            <div class="card m-3">
                <div class="card-body">
                    <!-- Tombol tambah -->
                    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalParamedis">Tambah Paramedis</button>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Gender</th>
                                <th>Tmp Lahir</th>
                                <th>Tgl Lahir</th>
                                <th>Kategori</th>
                                <th>Telpon</th>
                                <th>Alamat</th>
                                <th>Unit Kerja</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($paramedisList)): ?>
                                <?php foreach($paramedisList as $row): ?>
                                <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td><?= $row['nama']; ?></td>
                                    <td><?= $row['gender']; ?></td>
                                    <td><?= $row['tmp_lahir']; ?></td>
                                    <td><?= $row['tgl_lahir']; ?></td>
                                    <td><?= $row['kategori']; ?></td>
                                    <td><?= $row['telpon']; ?></td>
                                    <td><?= $row['alamat']; ?></td>
                                    <td><?= $row['unitkerja_id']; ?></td>
                                    <td>
                                        <a href="paramedis.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="paramedis.php?delete&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="10" class="text-center">Tidak ada data paramedis.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalParamedis" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="paramedis.php">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel"><?= $id ? 'Edit' : 'Tambah' ?> Paramedis</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="<?= $paramedis['id'] ?? '' ?>">
          <div class="form-group"><label>Nama</label><input type="text" name="nama" class="form-control" value="<?= $paramedis['nama'] ?? '' ?>" required></div>
          <div class="form-group"><label>Gender</label>
            <select name="gender" class="form-control" required>
              <option value="L" <?= (isset($paramedis['gender']) && $paramedis['gender'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
              <option value="P" <?= (isset($paramedis['gender']) && $paramedis['gender'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
            </select>
          </div>
          <div class="form-group"><label>Tempat Lahir</label><input type="text" name="tmp_lahir" class="form-control" value="<?= $paramedis['tmp_lahir'] ?? '' ?>" required></div>
          <div class="form-group"><label>Tanggal Lahir</label><input type="date" name="tgl_lahir" class="form-control" value="<?= $paramedis['tgl_lahir'] ?? '' ?>" required></div>
          <div class="form-group"><label>Kategori</label><input type="text" name="kategori" class="form-control" value="<?= $paramedis['kategori'] ?? '' ?>" required></div>
          <div class="form-group"><label>Telpon</label><input type="text" name="telpon" class="form-control" value="<?= $paramedis['telpon'] ?? '' ?>" required></div>
          <div class="form-group"><label>Alamat</label><textarea name="alamat" class="form-control" required><?= $paramedis['alamat'] ?? '' ?></textarea></div>
          <div class="form-group"><label>Unit Kerja ID</label><input type="number" name="unitkerja_id" class="form-control" value="<?= $paramedis['unitkerja_id'] ?? '' ?>" required></div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="<?= $id ? 'update' : 'simpan' ?>" class="btn btn-success"><?= $id ? 'Update' : 'Simpan' ?></button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<?php if ($id): ?>
<script>
    $(document).ready(function() {
        $('#modalParamedis').modal('show');
    });
</script>
<?php endif; ?>
</body>
</html>
