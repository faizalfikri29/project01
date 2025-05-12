<?php
include 'config/database.php';

// Tambah data penelitian
if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $mulai = $_POST['mulai'];
    $akhir = $_POST['akhir'];
    $tahun_ajaran = $_POST['tahun_ajaran'];
    $bidang_ilmu_id = $_POST['bidang_ilmu_id'];

    mysqli_query($conn, "INSERT INTO penelitian (judul, mulai, akhir, tahun_ajaran, bidang_ilmu_id)
                         VALUES ('$judul', '$mulai', '$akhir', '$tahun_ajaran', '$bidang_ilmu_id')");
}

// Hapus data penelitian
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM penelitian WHERE id=$id");
    header("Location: penelitian.php");
}

// Ambil data penelitian dengan nama bidang ilmu
$data = mysqli_query($conn, "SELECT p.*, b.nama AS nama_bidang 
                             FROM penelitian p 
                             LEFT JOIN bidang_ilmu b ON p.bidang_ilmu_id = b.id");

// Ambil data bidang ilmu untuk dropdown
$bidang_ilmu = mysqli_query($conn, "SELECT * FROM bidang_ilmu");

// Tambahan: Logika untuk Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $mulai = $_POST['mulai'];
    $akhir = $_POST['akhir'];
    $tahun_ajaran = $_POST['tahun_ajaran'];
    $bidang_ilmu_id = $_POST['bidang_ilmu_id'];
    $stmt = $conn->prepare("UPDATE penelitian SET judul=?, mulai=?, akhir=?, tahun_ajaran=?, bidang_ilmu_id=? WHERE id=?");
    $stmt->bind_param("sssssi", $judul, $mulai, $akhir, $tahun_ajaran, $bidang_ilmu_id, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: penelitian.php");
}

// Tambahan: Ambil data untuk edit
$edit_row = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT p.*, b.nama AS nama_bidang 
                            FROM penelitian p 
                            LEFT JOIN bidang_ilmu b ON p.bidang_ilmu_id = b.id 
                            WHERE p.id=?");
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
        <h2>Data Penelitian</h2>

        <!-- Form tambah penelitian -->
        <form method="POST" class="mb-4">
            <h2>Judul</h2>
            <textarea name="judul" placeholder="Judul Penelitian" required></textarea><br>
            <label>Tanggal Mulai</label>
            <input type="date" name="mulai" required>
            <label>Tanggal Selesai</label>
            <input type="date" name="akhir" required>
            <label>Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" placeholder="(contoh: 2022/2023)" required>
            <select name="bidang_ilmu_id" required>
                <option value="">Pilih Bidang Ilmu</option>
                <?php while ($b = mysqli_fetch_assoc($bidang_ilmu)) { ?>
                    <option value="<?= $b['id'] ?>"><?= $b['nama'] ?></option>
                <?php } ?>
            </select>
            <button name="tambah">Tambah</button>
        </form>

        <!-- Tampilan data penelitian (diubah ke tabel) -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Tahun Ajaran</th>
                        <th>Bidang Ilmu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($data)) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['judul']) ?></td>
                        <td><?= htmlspecialchars($row['mulai']) ?></td>
                        <td><?= htmlspecialchars($row['akhir']) ?></td>
                        <td><?= htmlspecialchars($row['tahun_ajaran']) ?></td>
                        <td><?= htmlspecialchars($row['nama_bidang']) ?></td>
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
                                    <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Data Penelitian</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="mb-3">
                                            <label for="judul<?= $row['id'] ?>" class="form-label">Judul Penelitian</label>
                                            <textarea class="form-control" id="judul<?= $row['id'] ?>" name="judul" required><?= htmlspecialchars($row['judul']) ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mulai<?= $row['id'] ?>" class="form-label">Tanggal Mulai</label>
                                            <input type="date" class="form-control" id="mulai<?= $row['id'] ?>" name="mulai" value="<?= htmlspecialchars($row['mulai']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="akhir<?= $row['id'] ?>" class="form-label">Tanggal Selesai</label>
                                            <input type="date" class="form-control" id="akhir<?= $row['id'] ?>" name="akhir" value="<?= htmlspecialchars($row['akhir']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tahun_ajaran<?= $row['id'] ?>" class="form-label">Tahun Ajaran</label>
                                            <input type="text" class="form-control" id="tahun_ajaran<?= $row['id'] ?>" name="tahun_ajaran" value="<?= htmlspecialchars($row['tahun_ajaran']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="bidang_ilmu_id<?= $row['id'] ?>" class="form-label">Bidang Ilmu</label>
                                            <select class="form-control" id="bidang_ilmu_id<?= $row['id'] ?>" name="bidang_ilmu_id" required>
                                                <?php 
                                                $bidang_ilmu = mysqli_query($conn, "SELECT * FROM bidang_ilmu");
                                                while ($b = mysqli_fetch_assoc($bidang_ilmu)) { ?>
                                                    <option value="<?= $b['id'] ?>" <?= $row['bidang_ilmu_id'] == $b['id'] ? 'selected' : '' ?>><?= $b['nama'] ?></option>
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