<?php
// Include file konfigurasi database
include '../config.php';

// Ambil ID pengguna dari parameter URL
$id = $_GET['id'];

// Ambil data pengguna berdasarkan ID
$sql = "SELECT * FROM users WHERE id_user=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Cek jika formulir telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama_admin = $_POST['nama_admin'];
    $id_level = $_POST['id_level'];

    // Update data pengguna
    $sql = "UPDATE users SET username='$username', password='$password', nama_admin='$nama_admin', id_level='$id_level' WHERE id_user=$id";

    // Cek hasil update
    if ($conn->query($sql) === TRUE) {
        // Redirect ke halaman daftar pengguna
        header("location: read.php");
    } else {
        // Tampilkan pesan error jika gagal
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Opsi level pengguna untuk formulir
$level_options = [
    1 => 'Administrator',
    2 => 'Pelanggan',
    // Tambahkan level lain jika diperlukan
];
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Update Akun</title>
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
                            <h5 class="card-title fw-semibold mb-4">Form Update Akun</h5>
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                value="<?php echo $row['username']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                value="<?php echo $row['password']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama_admin" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="nama_admin" name="nama_admin"
                                                value="<?php echo $row['nama_admin']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_level" class="form-label">Level</label>
                                            <select class="form-control" name="id_level" id="id_level" required>
                                                <?php foreach ($level_options as $value => $label) { ?>
                                                <option value="<?php echo $value; ?>"
                                                    <?php echo ($row['id_level'] == $value) ? 'selected' : ''; ?>>
                                                    <?php echo $label; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Akun</button>
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