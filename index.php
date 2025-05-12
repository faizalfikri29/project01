
<?php
include 'config/database.php';


// Ambil jumlah data dari masing-masing tabel
$jml_dosen = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM dosen"));
$jml_prodi = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM prodi"));
$jml_kegiatan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kegiatan"));
$jml_penelitian = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM penelitian"));
$jml_bidang_ilmu = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bidang_ilmu"));
$jml_jenis_kegiatan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM jenis_kegiatan"));

// Query jumlah dosen berdasarkan tahun masuk
$tahunData = [];
$jumlahDosen = [];
$result = mysqli_query($conn, "SELECT tahun_masuk, COUNT(*) as total FROM dosen GROUP BY tahun_masuk ORDER BY tahun_masuk");
while ($row = mysqli_fetch_assoc($result)) {
    $tahunData[] = $row['tahun_masuk'];
    $jumlahDosen[] = $row['total'];
}
?>



<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>


<link href="css/table.css" rel="stylesheet" />
<!-- Konten halaman di sini -->
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                <div class="card-body"><?= $jml_dosen ?> Dosen</div>

                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                <div class="card-body"><?= $jml_prodi ?> Program Studi</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                <div class="card-body"><?= $jml_kegiatan ?> Kegiatan</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                <div class="card-body"><?= $jml_penelitian ?> Penelitian</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Dosen Masuk per Tahun
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Bar: Dosen Masuk per Tahun
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Data
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIDN</th>
                    <th>Email</th>
                    <th>Prodi</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat, Tgl Lahir</th>
                    <th>Tahun Masuk</th>
                    <th>Jumlah Kegiatan</th>
                    <th>Jumlah Penelitian</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "
                    SELECT 
                        d.id,
                        d.nama,
                        d.nidn,
                        d.email,
                        p.nama AS prodi,
                        d.jenis_kelamin,
                        d.tempat_lahir,
                        DATE_FORMAT(d.tanggal_lahir, '%d-%m-%Y') AS tgl_lahir,
                        d.tahun_masuk,
                        (SELECT COUNT(*) FROM dosen_kegiatan dk WHERE dk.dosen_id = d.id) AS jumlah_kegiatan,
                        (SELECT COUNT(*) FROM tim_penelitian tp WHERE tp.dosen_id = d.id) AS jumlah_penelitian
                    FROM dosen d
                    LEFT JOIN prodi p ON d.prodi_id = p.id
                ";
                $res = mysqli_query($conn, $query);
                while ($d = mysqli_fetch_assoc($res)) {
                    $ttl = $d['tempat_lahir'] . ', ' . $d['tgl_lahir'];
                    echo "<tr>
                        <td>{$d['nama']}</td>
                        <td>{$d['nidn']}</td>
                        <td>{$d['email']}</td>
                        <td>{$d['prodi']}</td>
                        <td>{$d['jenis_kelamin']}</td>
                        <td>{$ttl}</td>
                        <td>{$d['tahun_masuk']}</td>
                        <td>{$d['jumlah_kegiatan']}</td>
                        <td>{$d['jumlah_penelitian']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const tahunLabels = <?= json_encode($tahunData); ?>;
    const jumlahData = <?= json_encode($jumlahDosen); ?>;

    // Area Chart
    new Chart(document.getElementById("myAreaChart"), {
        type: 'line',
        data: {
            labels: tahunLabels,
            datasets: [{
                label: "Jumlah Dosen",
                data: jumlahData,
                fill: true,
                borderColor: "rgba(75,192,192,1)",
                backgroundColor: "rgba(75,192,192,0.2)",
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Bar Chart
    new Chart(document.getElementById("myBarChart"), {
        type: 'bar',
        data: {
            labels: tahunLabels,
            datasets: [{
                label: "Jumlah Dosen",
                data: jumlahData,
                backgroundColor: "rgba(54, 162, 235, 0.7)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>


