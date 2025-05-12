<?php
include 'config/database.php';

// Tambah data kegiatan
if (isset($_POST['tambah'])) {
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $tempat = $_POST['tempat'];
    $deskripsi = $_POST['deskripsi'];
    $jenis_kegiatan_id = $_POST['jenis_kegiatan_id'];

    $stmt = $conn->prepare("INSERT INTO kegiatan (tanggal_mulai, tanggal_selesai, tempat, deskripsi, jenis_kegiatan_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $tanggal_mulai, $tanggal_selesai, $tempat, $deskripsi, $jenis_kegiatan_id);
    $stmt->execute();
    $stmt->close();
    header("Location: kegiatan.php");
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kegiatan WHERE id=$id");
    header("Location: kegiatan.php");
}

// Ambil data kegiatan dan jenis_kegiatan
$data = mysqli_query($conn, "SELECT kegiatan.*, jenis_kegiatan.nama AS jenis_nama FROM kegiatan 
                             LEFT JOIN jenis_kegiatan ON kegiatan.jenis_kegiatan_id = jenis_kegiatan.id");

// Ambil data jenis kegiatan untuk dropdown
$jenis = mysqli_query($conn, "SELECT * FROM jenis_kegiatan");

// Tambahan: Logika untuk Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $tempat = $_POST['tempat'];
    $deskripsi = $_POST['deskripsi'];
    $jenis_kegiatan_id = $_POST['jenis_kegiatan_id'];
    $stmt = $conn->prepare("UPDATE kegiatan SET tanggal_mulai=?, tanggal_selesai=?, tempat=?, deskripsi=?, jenis_kegiatan_id=? WHERE id=?");
    $stmt->bind_param("sssssi", $tanggal_mulai, $tanggal_selesai, $tempat, $deskripsi, $jenis_kegiatan_id, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: kegiatan.php");
}

// Tambahan: Ambil data untuk edit
$edit_row = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM kegiatan WHERE id=?");
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
        <h2>Data Kegiatan</h2>

<!-- Tombol untuk membuka modal tambah -->
<button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Kegiatan</button>

        <!-- Modal untuk Tambah -->
        <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahModalLabel">Tambah Data Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                            </div>
                            <div class="mb-3">
                                <label for="tempat" class="form-label">Tempat</label>
                                <input type="text" class="form-control" id="tempat" name="tempat" placeholder="Tempat" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_kegiatan_id" class="form-label">Jenis Kegiatan</label>
                                <select class="form-control" id="jenis_kegiatan_id" name="jenis_kegiatan_id" required>
                                    <option value="">Pilih Jenis Kegiatan</option>
                                    <?php 
                                    $jenis = mysqli_query($conn, "SELECT * FROM jenis_kegiatan");
                                    while ($j = mysqli_fetch_assoc($jenis)) { ?>
                                        <option value="<?= $j['id'] ?>"><?= $j['nama'] ?></option>
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

        <!-- Tampilan data kegiatan (diubah ke tabel) -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Tempat</th>
                        <th>Deskripsi</th>
                        <th>Jenis Kegiatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($data)) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['tanggal_mulai']) ?></td>
                        <td><?= htmlspecialchars($row['tanggal_selesai']) ?></td>
                        <td><?= htmlspecialchars($row['tempat']) ?></td>
                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td><?= htmlspecialchars($row['jenis_nama']) ?></td>
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
                                    <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Data Kegiatan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="mb-3">
                                            <label for="tanggal_mulai<?= $row['id'] ?>" class="form-label">Tanggal Mulai</label>
                                            <input type="date" class="form-control" id="tanggal_mulai<?= $row['id'] ?>" name="tanggal_mulai" value="<?= htmlspecialchars($row['tanggal_mulai']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal_selesai<?= $row['id'] ?>" class="form-label">Tanggal Selesai</label>
                                            <input type="date" class="form-control" id="tanggal_selesai<?= $row['id'] ?>" name="tanggal_selesai" value="<?= htmlspecialchars($row['tanggal_selesai']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tempat<?= $row['id'] ?>" class="form-label">Tempat</label>
                                            <input type="text" class="form-control" id="tempat<?= $row['id'] ?>" name="tempat" value="<?= htmlspecialchars($row['tempat']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="deskripsi<?= $row['id'] ?>" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi<?= $row['id'] ?>" name="deskripsi" required><?= htmlspecialchars($row['deskripsi']) ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jenis_kegiatan_id<?= $row['id'] ?>" class="form-label">Jenis Kegiatan</label>
                                            <select class="form-control" id="jenis_kegiatan_id<?= $row['id'] ?>" name="jenis_kegiatan_id" required>
                                                <?php 
                                                $jenis = mysqli_query($conn, "SELECT * FROM jenis_kegiatan");
                                                while ($j = mysqli_fetch_assoc($jenis)) { ?>
                                                    <option value="<?= $j['id'] ?>" <?= $row['jenis_kegiatan_id'] == $j['id'] ? 'selected' : '' ?>><?= $j['nama'] ?></option>
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