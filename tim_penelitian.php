<?php
include 'config/database.php';

// Tambah data tim penelitian
if (isset($_POST['tambah'])) {
    $penelitian_id = $_POST['penelitian_id'];
    $dosen_id = $_POST['dosen_id'];
    $peran = $_POST['peran'];
    mysqli_query($conn, "INSERT INTO tim_penelitian (penelitian_id, dosen_id, peran) VALUES ('$penelitian_id', '$dosen_id', '$peran')");
}

// Hapus relasi tim
if (isset($_GET['hapus'])) {
    $penelitian_id = $_GET['penelitian_id'];
    $dosen_id = $_GET['dosen_id'];
    mysqli_query($conn, "DELETE FROM tim_penelitian WHERE penelitian_id=$penelitian_id AND dosen_id=$dosen_id");
    header("Location: tim_penelitian.php");
}

// Ambil data tim dengan relasi dosen dan penelitian
$data = mysqli_query($conn, "SELECT tp.*, d.nama AS nama_dosen, p.judul AS judul_penelitian
                             FROM tim_penelitian tp
                             LEFT JOIN dosen d ON tp.dosen_id = d.id
                             LEFT JOIN penelitian p ON tp.penelitian_id = p.id");

// Ambil data dosen dan penelitian untuk dropdown
$dosen = mysqli_query($conn, "SELECT * FROM dosen");
$penelitian = mysqli_query($conn, "SELECT * FROM penelitian");

// Tambahan: Logika untuk Update
if (isset($_POST['update'])) {
    $old_penelitian_id = $_POST['old_penelitian_id'];
    $old_dosen_id = $_POST['old_dosen_id'];
    $penelitian_id = $_POST['penelitian_id'];
    $dosen_id = $_POST['dosen_id'];
    $peran = $_POST['peran'];
    $stmt = $conn->prepare("UPDATE tim_penelitian SET penelitian_id=?, dosen_id=?, peran=? WHERE penelitian_id=? AND dosen_id=?");
    $stmt->bind_param("iissi", $penelitian_id, $dosen_id, $peran, $old_penelitian_id, $old_dosen_id);
    $stmt->execute();
    $stmt->close();
    header("Location: tim_penelitian.php");
}

// Tambahan: Ambil data untuk edit
$edit_row = null;
if (isset($_GET['edit'])) {
    $penelitian_id = $_GET['penelitian_id'];
    $dosen_id = $_GET['dosen_id'];
    $stmt = $conn->prepare("SELECT tp.*, d.nama AS nama_dosen, p.judul AS judul_penelitian
                            FROM tim_penelitian tp
                            LEFT JOIN dosen d ON tp.dosen_id = d.id
                            LEFT JOIN penelitian p ON tp.penelitian_id = p.id
                            WHERE tp.penelitian_id=? AND tp.dosen_id=?");
    $stmt->bind_param("ii", $penelitian_id, $dosen_id);
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
        <h2>Tim Penelitian</h2>

        <!-- Form tambah tim penelitian -->
        <form method="POST" class="mb-4">
            <select name="penelitian_id" required>
                <option value="">Pilih Judul Penelitian</option>
                <?php while ($p = mysqli_fetch_assoc($penelitian)) { ?>
                    <option value="<?= $p['id'] ?>"><?= $p['judul'] ?></option>
                <?php } ?>
            </select>

            <select name="dosen_id" required>
                <option value="">Pilih Dosen</option>
                <?php while ($d = mysqli_fetch_assoc($dosen)) { ?>
                    <option value="<?= $d['id'] ?>"><?= $d['nama'] ?></option>
                <?php } ?>
            </select>
            <label>Peran sebagai :</label>
            <input type="text" name="peran" placeholder="(contoh: Ketua, Anggota)" required>
            <button name="tambah">Tambah</button>
        </form>

        <!-- Tampilan data tim penelitian (diubah ke tabel) -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dosen</th>
                        <th>Judul Penelitian</th>
                        <th>Peran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($data)) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_dosen']) ?></td>
                        <td><?= htmlspecialchars($row['judul_penelitian']) ?></td>
                        <td><?= htmlspecialchars($row['peran']) ?></td>
                        <td>
                            <a href="?edit=1&penelitian_id=<?= $row['penelitian_id'] ?>&dosen_id=<?= $row['dosen_id'] ?>" 
                               class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['penelitian_id'] . '_' . $row['dosen_id'] ?>">Edit</a>
                            <a href="?hapus=1&penelitian_id=<?= $row['penelitian_id'] ?>&dosen_id=<?= $row['dosen_id'] ?>" 
                               class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
                        </td>
                    </tr>
                    <!-- Modal untuk Edit -->
                    <div class="modal fade" id="editModal<?= $row['penelitian_id'] . '_' . $row['dosen_id'] ?>" tabindex="-1" 
                         aria-labelledby="editModalLabel<?= $row['penelitian_id'] . '_' . $row['dosen_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?= $row['penelitian_id'] . '_' . $row['dosen_id'] ?>">Edit Data Tim Penelitian</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="old_penelitian_id" value="<?= $row['penelitian_id'] ?>">
                                        <input type="hidden" name="old_dosen_id" value="<?= $row['dosen_id'] ?>">
                                        <div class="mb-3">
                                            <label for="penelitian_id<?= $row['penelitian_id'] . '_' . $row['dosen_id'] ?>" class="form-label">Judul Penelitian</label>
                                            <select class="form-control" id="penelitian_id<?= $row['penelitian_id'] . '_' . $row['dosen_id'] ?>" name="penelitian_id" required>
                                                <?php 
                                                $penelitian = mysqli_query($conn, "SELECT * FROM penelitian");
                                                while ($p = mysqli_fetch_assoc($penelitian)) { ?>
                                                    <option value="<?= $p['id'] ?>" <?= $row['penelitian_id'] == $p['id'] ? 'selected' : '' ?>>
                                                        <?= $p['judul'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="dosen_id<?= $row['penelitian_id'] . '_' . $row['dosen_id'] ?>" class="form-label">Nama Dosen</label>
                                            <select class="form-control" id="dosen_id<?= $row['penelitian_id'] . '_' . $row['dosen_id'] ?>" name="dosen_id" required>
                                                <?php 
                                                $dosen = mysqli_query($conn, "SELECT * FROM dosen");
                                                while ($d = mysqli_fetch_assoc($dosen)) { ?>
                                                    <option value="<?= $d['id'] ?>" <?= $row['dosen_id'] == $d['id'] ? 'selected' : '' ?>>
                                                        <?= $d['nama'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="peran<?= $row['penelitian_id'] . '_' . $row['dosen_id'] ?>" class="form-label">Peran</label>
                                            <input type="text" class="form-control" id="peran<?= $row['penelitian_id'] . '_' . $row['dosen_id'] ?>" name="peran" value="<?= htmlspecialchars($row['peran']) ?>" required>
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