<?php
include '../config.php';

// Cek jika formulir telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $id_pelanggan = $_POST['id_pelanggan'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $meter_awal = $_POST['meter_awal'];
    $meter_ahir = $_POST['meter_ahir'];

    // Query untuk menyimpan data ke tabel penggunaan
    $sql = "INSERT INTO penggunaan (id_pelanggan, bulan, tahun, meter_awal, meter_ahir) VALUES ('$id_pelanggan', '$bulan', '$tahun', '$meter_awal', '$meter_ahir')";

    // Eksekusi query dan berikan pesan jika terjadi error
    if ($conn->query($sql) === TRUE) {
        header("location: read.php"); // Redirect ke halaman read.php setelah berhasil
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil semua ID pelanggan dari tabel pelanggan
$sql_idpelanggan = "SELECT id_pelanggan FROM pelanggan";
$result_idpelanggan = $conn->query($sql_idpelanggan);

// Simpan ID pelanggan dalam array untuk digunakan dalam formulir (misalnya, sebagai pilihan dropdown)
$id_pelanggan_options = [];
if ($result_idpelanggan->num_rows > 0) {
    while($row = $result_idpelanggan->fetch_assoc()) {
        $id_pelanggan_options[] = $row['id_pelanggan'];
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Buat Penggunaan Baru</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="../index.html" class="text-nowrap logo-img">
                        <img src="../assets/images/logos/dark-logo.svg" width="180" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Dashboard</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../index.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-dashboard"></i>
                                </span>
                                <span class="hide-menu">Beranda</span>
                            </a>
                        </li>
                        <?php if ($_SESSION['id_level'] == 1) { ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../pelanggan/read.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-article"></i>
                                </span>
                                <span class="hide-menu">Manage Pelanggan</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../penggunaan/read.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-alert-circle"></i>
                                </span>
                                <span class="hide-menu">Manage Penggunaan</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../tarif/read.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-cards"></i>
                                </span>
                                <span class="hide-menu">Manage Tarif</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../users/read.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-file-description"></i>
                                </span>
                                <span class="hide-menu">Manage Users</span>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../tagihan.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-typography"></i>
                                </span>
                                <span class="hide-menu">Lihat Tagihan</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../logout.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-login"></i>
                                </span>
                                <span class="hide-menu">Keluar</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <div class="container-fluid">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-4">Form Buat Penggunaan Baru</h5>
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label for="id_pelanggan" class="form-label">ID Pelanggan</label>
                                            <select class="form-control" name="id_pelanggan" id="id_pelanggan" required>
                                                <?php foreach($id_pelanggan_options as $option) { ?>
                                                <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="bulan" class="form-label">Bulan</label>
                                            <select class="form-control" name="bulan" id="bulan" required>
                                                <option>Januari</option>
                                                <option>Februari</option>
                                                <option>Maret</option>
                                                <option>April</option>
                                                <option>Mei</option>
                                                <option>Juni</option>
                                                <option>Juli</option>
                                                <option>Agustus</option>
                                                <option>September</option>
                                                <option>Oktober</option>
                                                <option>November</option>
                                                <option>Desember</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tahun" class="form-label">Tahun</label>
                                            <input type="text" class="form-control" id="tahun" name="tahun" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="meter_awal" class="form-label">Meter Awal</label>
                                            <input type="text" class="form-control" id="meter_awal" name="meter_awal"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="meter_ahir" class="form-label">Meter Akhir</label>
                                            <input type="text" class="form-control" id="meter_ahir" name="meter_ahir"
                                                required>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Buat Penggunaan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>

</html>