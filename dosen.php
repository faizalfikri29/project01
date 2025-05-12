<?php
include 'config/database.php';

// Tambah data
if (isset($_POST['tambah'])) {
    $nidn = $_POST['nidn'];
    $nama = $_POST['nama'];
    $gelar_belakang = $_POST['gelar_belakang'];
    $gelar_depan = $_POST['gelar_depan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $tahun_masuk = $_POST['tahun_masuk'];
    $prodi_id = $_POST['prodi_id'];

    $stmt = $conn->prepare("INSERT INTO dosen (nidn, nama, gelar_belakang, gelar_depan, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, email, tahun_masuk, prodi_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssi", $nidn, $nama, $gelar_belakang, $gelar_depan, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $alamat, $email, $tahun_masuk, $prodi_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dosen.php");
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM dosen WHERE id=$id");
    header("Location: dosen.php");
}

// Ambil data dosen
$data = mysqli_query($conn, "SELECT dosen.*, prodi.nama as nama_prodi FROM dosen 
                             LEFT JOIN prodi ON dosen.prodi_id = prodi.id");

// Ambil data prodi untuk dropdown
$prodi = mysqli_query($conn, "SELECT * FROM prodi");

// Tambahan: Logika untuk Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nidn = $_POST['nidn'];
    $nama = $_POST['nama'];
    $gelar_belakang = $_POST['gelar_belakang'];
    $gelar_depan = $_POST['gelar_depan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $tahun_masuk = $_POST['tahun_masuk'];
    $prodi_id = $_POST['prodi_id'];
    $stmt = $conn->prepare("UPDATE dosen SET nidn=?, nama=?, gelar_belakang=?, gelar_depan=?, jenis_kelamin=?, tempat_lahir=?, tanggal_lahir=?, alamat=?, email=?, tahun_masuk=?, prodi_id=? WHERE id=?");
    $stmt->bind_param("ssssssssssii", $nidn, $nama, $gelar_belakang, $gelar_depan, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $alamat, $email, $tahun_masuk, $prodi_id, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dosen.php");
}

// Tambahan: Ambil data untuk edit
$edit_row = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM dosen WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div id="layoutSidenav_content">
<div class="container">
    <link href="css/styles2.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />

    <div class="container-fluid px-4">
        <h2>Data Dosen</h2>

      <!-- Tombol untuk membuka modal tambah -->
<button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Dosen</button>

<!-- Modal untuk Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="nidn" class="form-label">NIDN</label>
                        <input type="text" class="form-control" id="nidn" name="nidn" placeholder="NIDN" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="gelar_depan" class="form-label">Gelar Depan</label>
                        <input type="text" class="form-control" id="gelar_depan" name="gelar_depan" placeholder="Gelar Depan">
                    </div>
                    <div class="mb-3">
                        <label for="gelar_belakang" class="form-label">Gelar Belakang</label>
                        <input type="text" class="form-control" id="gelar_belakang" name="gelar_belakang" placeholder="Gelar Belakang">
                    </div>
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="Tanggal Lahir">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="mb-3">
                        <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                        <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" placeholder="Tahun Masuk">
                    </div>
                    <div class="mb-3">
                        <label for="prodi_id" class="form-label">Prodi</label>
                        <select class="form-control" id="prodi_id" name="prodi_id" required>
                            <option value="">Pilih Prodi</option>
                            <?php 
                            $prodi = mysqli_query($conn, "SELECT * FROM prodi");
                            while ($p = mysqli_fetch_assoc($prodi)) { ?>
                                <option value="<?= $p['id'] ?>"><?= $p['nama'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

        <!-- Tampilan data dosen (diubah ke tabel) -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIDN</th>
                        <th>Nama Lengkap</th>
                        <th>Gelar Depan</th>
                        <th>Gelar Belakang</th>
                        <th>Jenis Kelamin</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Alamat</th>
                        <th>Email</th>
                        <th>Tahun Masuk</th>
                        <th>Prodi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($data)) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nidn']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['gelar_depan']) ?></td>
                        <td><?= htmlspecialchars($row['gelar_belakang']) ?></td>
                        <td><?= htmlspecialchars($row['jenis_kelamin']) == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                        <td><?= htmlspecialchars($row['tempat_lahir']) ?></td>
                        <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                        <td><?= htmlspecialchars($row['alamat']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['tahun_masuk']) ?></td>
                        <td><?= htmlspecialchars($row['nama_prodi']) ?></td>
                        <td>
                            <a href="?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</a>
                            <a href="?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
                        </td>
                    </tr>
                    <!-- Modal untuk Edit -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Data Dosen</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="mb-3">
                                            <label for="nidn<?= $row['id'] ?>" class="form-label">NIDN</label>
                                            <input type="text" class="form-control" id="nidn<?= $row['id'] ?>" name="nidn" value="<?= htmlspecialchars($row['nidn']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama<?= $row['id'] ?>" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="nama<?= $row['id'] ?>" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="gelar_depan<?= $row['id'] ?>" class="form-label">Gelar Depan</label>
                                            <input type="text" class="form-control" id="gelar_depan<?= $row['id'] ?>" name="gelar_depan" value="<?= htmlspecialchars($row['gelar_depan']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="gelar_belakang<?= $row['id'] ?>" class="form-label">Gelar Belakang</label>
                                            <input type="text" class="form-control" id="gelar_belakang<?= $row['id'] ?>" name="gelar_belakang" value="<?= htmlspecialchars($row['gelar_belakang']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="jenis_kelamin<?= $row['id'] ?>" class="form-label">Jenis Kelamin</label>
                                            <select class="form-control" id="jenis_kelamin<?= $row['id'] ?>" name="jenis_kelamin" required>
                                                <option value="L" <?= $row['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                                <option value="P" <?= $row['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tempat_lahir<?= $row['id'] ?>" class="form-label">Tempat Lahir</label>
                                            <input type="text" class="form-control" id="tempat_lahir<?= $row['id'] ?>" name="tempat_lahir" value="<?= htmlspecialchars($row['tempat_lahir']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal_lahir<?= $row['id'] ?>" class="form-label">Tanggal Lahir</label>
                                            <input type="date" class="form-control" id="tanggal_lahir<?= $row['id'] ?>" name="tanggal_lahir" value="<?= htmlspecialchars($row['tanggal_lahir']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="alamat<?= $row['id'] ?>" class="form-label">Alamat</label>
                                            <input type="text" class="form-control" id="alamat<?= $row['id'] ?>" name="alamat" value="<?= htmlspecialchars($row['alamat']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email<?= $row['id'] ?>" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email<?= $row['id'] ?>" name="email" value="<?= htmlspecialchars($row['email']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="tahun_masuk<?= $row['id'] ?>" class="form-label">Tahun Masuk</label>
                                            <input type="number" class="form-control" id="tahun_masuk<?= $row['id'] ?>" name="tahun_masuk" value="<?= htmlspecialchars($row['tahun_masuk']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="prodi_id<?= $row['id'] ?>" class="form-label">Prodi</label>
                                            <select class="form-control" id="prodi_id<?= $row['id'] ?>" name="prodi_id" required>
                                                <?php 
                                                $prodi = mysqli_query($conn, "SELECT * FROM prodi");
                                                while ($p = mysqli_fetch_assoc($prodi)) { ?>
                                                    <option value="<?= $p['id'] ?>" <?= $row['prodi_id'] == $p['id'] ? 'selected' : '' ?>><?= $p['nama'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <button type="submit" name="update" class="btn btn-primary">Edit</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>