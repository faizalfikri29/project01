<?php
include 'config/database.php';

// Fungsi untuk memotong teks agar tidak terlalu panjang
function shorten_text($text, $max_length = 30) {
    if (strlen($text) > $max_length) {
        return substr($text, 0, $max_length) . '...';
    }
    return $text;
}

// Tambah relasi dosen-kegiatan
if (isset($_POST['tambah'])) {
    $dosen_id = $_POST['dosen_id'];
    $kegiatan_id = $_POST['kegiatan_id'];
    $stmt = $conn->prepare("INSERT INTO dosen_kegiatan (dosen_id, kegiatan_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $dosen_id, $kegiatan_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dosen_kegiatan.php");
    exit;
}

// Hapus relasi
if (isset($_GET['hapus'])) {
    $dosen_id = $_GET['dosen_id'];
    $kegiatan_id = $_GET['kegiatan_id'];
    $stmt = $conn->prepare("DELETE FROM dosen_kegiatan WHERE dosen_id = ? AND kegiatan_id = ?");
    $stmt->bind_param("ii", $dosen_id, $kegiatan_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dosen_kegiatan.php");
    exit;
}

// Ambil data relasi
$data = mysqli_query($conn, "SELECT dk.dosen_id, dk.kegiatan_id, d.nama AS nama_dosen, jk.nama AS nama_kegiatan 
                             FROM dosen_kegiatan dk
                             JOIN dosen d ON dk.dosen_id = d.id
                             JOIN kegiatan k ON dk.kegiatan_id = k.id
                             JOIN jenis_kegiatan jk ON k.jenis_kegiatan_id = jk.id");

// Ambil data untuk dropdown
$dosen = mysqli_query($conn, "SELECT * FROM dosen");
$kegiatan = mysqli_query($conn, "SELECT k.id, jk.nama AS nama_kegiatan 
                                FROM kegiatan k 
                                JOIN jenis_kegiatan jk ON k.jenis_kegiatan_id = jk.id");

// Tambahan: Logika untuk Update
if (isset($_POST['update'])) {
    $old_dosen_id = $_POST['old_dosen_id'];
    $old_kegiatan_id = $_POST['old_kegiatan_id'];
    $dosen_id = $_POST['dosen_id'];
    $kegiatan_id = $_POST['kegiatan_id'];
    $stmt = $conn->prepare("UPDATE dosen_kegiatan SET dosen_id = ?, kegiatan_id = ? WHERE dosen_id = ? AND kegiatan_id = ?");
    $stmt->bind_param("iiii", $dosen_id, $kegiatan_id, $old_dosen_id, $old_kegiatan_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dosen_kegiatan.php");
    exit;
}

// Tambahan: Ambil data untuk edit
$edit_row = null;
if (isset($_GET['edit'])) {
    $dosen_id = $_GET['dosen_id'];
    $kegiatan_id = $_GET['kegiatan_id'];
    $stmt = $conn->prepare("SELECT dk.dosen_id, dk.kegiatan_id, d.nama AS nama_dosen, jk.nama AS nama_kegiatan 
                            FROM dosen_kegiatan dk
                            JOIN dosen d ON dk.dosen_id = d.id
                            JOIN kegiatan k ON dk.kegiatan_id = k.id
                            JOIN jenis_kegiatan jk ON k.jenis_kegiatan_id = jk.id
                            WHERE dk.dosen_id = ? AND dk.kegiatan_id = ?");
    $stmt->bind_param("ii", $dosen_id, $kegiatan_id);
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

    <style>
        /* CSS untuk membatasi lebar dropdown dan menampilkan elipsis */
        select.form-control {
            max-width: 300px; /* Atur lebar maksimum dropdown */
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
    </style>

    <div class="container-fluid px-4">
        <h2>Relasi Dosen dan Kegiatan</h2>

        <!-- Form tambah relasi -->
        <form method="POST" class="mb-4">
            <select name="dosen_id" required class="form-control">
                <option value="">Pilih Dosen</option>
                <?php while ($d = mysqli_fetch_assoc($dosen)) { ?>
                    <option value="<?= $d['id'] ?>"><?= htmlspecialchars(shorten_text($d['nama'])) ?></option>
                <?php } ?>
            </select>

            <select name="kegiatan_id" required class="form-control">
                <option value="">Pilih Kegiatan</option>
                <?php while ($k = mysqli_fetch_assoc($kegiatan)) { ?>
                    <option value="<?= $k['id'] ?>"><?= htmlspecialchars(shorten_text($k['nama_kegiatan'])) ?></option>
                <?php } ?>
            </select>

            <button name="tambah" class="btn btn-primary">Tambah</button>
        </form>

        <!-- Tampilan data relasi -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dosen</th>
                        <th>Kegiatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($data)) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars(shorten_text($row['nama_dosen'])) ?></td>
                        <td><?= htmlspecialchars(shorten_text($row['nama_kegiatan'])) ?></td>
                        <td>
                            <a href="?hapus=1&dosen_id=<?= $row['dosen_id'] ?>&kegiatan_id=<?= $row['kegiatan_id'] ?>" 
                               class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
                        </td>
                    </tr>
                    <!-- Modal untuk Edit -->
                    <div class="modal fade" id="editModal<?= $row['dosen_id'] . '_' . $row['kegiatan_id'] ?>" tabindex="-1" 
                         aria-labelledby="editModalLabel<?= $row['dosen_id'] . '_' . $row['kegiatan_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?= $row['dosen_id'] . '_' . $row['kegiatan_id'] ?>">Edit Relasi Dosen dan Kegiatan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="old_dosen_id" value="<?= $row['dosen_id'] ?>">
                                        <input type="hidden" name="old_kegiatan_id" value="<?= $row['kegiatan_id'] ?>">
                                        <div class="mb-3">
                                            <label for="dosen_id<?= $row['dosen_id'] . '_' . $row['kegiatan_id'] ?>" class="form-label">Nama Dosen</label>
                                            <select class="form-control" id="dosen_id<?= $row['dosen_id'] . '_' . $row['kegiatan_id'] ?>" name="dosen_id" required>
                                                <?php 
                                                $dosen = mysqli_query($conn, "SELECT * FROM dosen");
                                                while ($d = mysqli_fetch_assoc($dosen)) { ?>
                                                    <option value="<?= $d['id'] ?>" <?= $row['dosen_id'] == $d['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars(shorten_text($d['nama'])) ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kegiatan_id<?= $row['dosen_id'] . '_' . $row['kegiatan_id'] ?>" class="form-label">Kegiatan</label>
                                            <select class="form-control" id="kegiatan_id<?= $row['dosen_id'] . '_' . $row['kegiatan_id'] ?>" name="kegiatan_id" required>
                                                <?php 
                                                $kegiatan = mysqli_query($conn, "SELECT k.id, jk.nama AS nama_kegiatan 
                                                                                FROM kegiatan k 
                                                                                JOIN jenis_kegiatan jk ON k.jenis_kegiatan_id = jk.id");
                                                while ($k = mysqli_fetch_assoc($kegiatan)) { ?>
                                                    <option value="<?= $k['id'] ?>" <?= $row['kegiatan_id'] == $k['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars(shorten_text($k['nama_kegiatan'])) ?>
                                                    </option>
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