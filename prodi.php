<?php
include 'config/database.php';

// Tambah data
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telpon = $_POST['telpon'];
    $ketua = $_POST['ketua'];
    $stmt = $conn->prepare("INSERT INTO prodi (kode, nama, alamat, telpon, ketua) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $kode, $nama, $alamat, $telpon, $ketua);
    $stmt->execute();
    $stmt->close();
    header("Location: prodi.php");
    exit;
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM prodi WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: prodi.php");
    exit;
}

// Ambil data
$data = mysqli_query($conn, "SELECT * FROM prodi");

// Update data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telpon = $_POST['telpon'];
    $ketua = $_POST['ketua'];
    $stmt = $conn->prepare("UPDATE prodi SET kode=?, nama=?, alamat=?, telpon=?, ketua=? WHERE id=?");
    $stmt->bind_param("sssssi", $kode, $nama, $alamat, $telpon, $ketua, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: prodi.php");
    exit;
}

// Ambil data untuk edit
$edit_row = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM prodi WHERE id=?");
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

        <div class="container-fluid px-4">
            <h2>Data Prodi</h2>

            <!-- Tombol untuk membuka modal tambah -->
            <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Akun</button>

            <!-- Konten Prodi (tabel) -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telpon</th>
                            <th>Ketua</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($data)) { ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['kode']) ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td><?= htmlspecialchars($row['telpon']) ?></td>
                                <td><?= htmlspecialchars($row['ketua']) ?></td>
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
                                            <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Data</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <div class="mb-3">
                                                    <label for="kode<?= $row['id'] ?>" class="form-label">Kode</label>
                                                    <input type="text" class="form-control" id="kode<?= $row['id'] ?>" name="kode" value="<?= htmlspecialchars($row['kode']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nama<?= $row['id'] ?>" class="form-label">Nama Prodi</label>
                                                    <input type="text" class="form-control" id="nama<?= $row['id'] ?>" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="alamat<?= $row['id'] ?>" class="form-label">Alamat</label>
                                                    <input type="text" class="form-control" id="alamat<?= $row['id'] ?>" name="alamat" value="<?= htmlspecialchars($row['alamat']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="telpon<?= $row['id'] ?>" class="form-label">Telpon</label>
                                                    <input type="text" class="form-control" id="telpon<?= $row['id'] ?>" name="telpon" value="<?= htmlspecialchars($row['telpon']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ketua<?= $row['id'] ?>" class="form-label">Ketua Prodi</label>
                                                    <input type="text" class="form-control" id="ketua<?= $row['id'] ?>" name="ketua" value="<?= htmlspecialchars($row['ketua']) ?>" required>
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
            <!-- Modal untuk Tambah -->
            <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahModalLabel">Tambah Data Prodi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="kode" class="form-label">Kode</label>
                                    <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Prodi</label>
                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Prodi" required>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="telpon" class="form-label">Telpon</label>
                                    <input type="text" class="form-control" id="telpon" name="telpon" placeholder="Telpon" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ketua" class="form-label">Ketua Prodi</label>
                                    <input type="text" class="form-control" id="ketua" name="ketua" placeholder="Ketua Prodi" required>
                                </div>
                                <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'layout/footer.php'; ?>