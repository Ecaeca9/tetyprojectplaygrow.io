<?php
session_start(); // Memulai sesi
include 'config.php';

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION['email']))
    // Jika belum login, arahkan ke halaman login
    header('Location: login.php');

include 'controller.php';
// Mendapatkan email pengguna yang login
$email = $_SESSION['email'];

// Mendapatkan ID pengajar yang sedang login
$pengajar_id = $_SESSION['id'];

// Mengambil data materi yang dibuat oleh pengajar
$materi_result = getMateri($pengajar_id);

// Konfigurasi Pagination
$records_per_page = 5; // Jumlah record per halaman
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Query untuk mengambil data users (tanpa role 'pengajar') dengan LIMIT
$query = "SELECT * FROM users WHERE role NOT IN ('pengajar') LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $offset, $records_per_page);
$stmt->execute();
$result = $stmt->get_result();

// Query untuk menghitung total records
$total_query = "SELECT COUNT(*) AS total FROM users WHERE role NOT IN ('pengajar')";
$total_result = $conn->query($total_query);
$total_records = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

$user_id = $_SESSION['id'];  // Mendapatkan user_id dari session
saveVisit($user_id);  // Menyimpan data kunjungan
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PlayGrow - Belajar Sambil Bermain</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Montserrat:wght@200;400;600&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/animate/animate.min.css" rel="stylesheet">
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
        <link href="css/pagination.css" rel="stylesheet">

    </head>
    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar start -->
        <div class="container-fluid border-bottom bg-light wow fadeIn" data-wow-delay="0.1s">
            <div class="container topbar bg-primary d-none d-lg-block py-2" style="border-radius: 0 40px">
                <div class="d-flex justify-content-between">
                    <div class="top-info ps-2">
                        </div>
                    <div class="top-link pe-2">
                    </div>
                </div>
            </div>
            <div class="container px-0">
                <nav class="navbar navbar-light navbar-expand-xl py-3">
                    <a href="index.html" class="navbar-brand"><h1 class="text-primary display-6">Play<span class="text-secondary">Grow</span></h1></a>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarCollapse">
                    <div class="navbar-nav">
                            <a href="dashboard_pengajar.php" class="nav-item nav-link">Home</a>
                            <a href="#materi-section" class="nav-item nav-link">Programs</a>
                            <a href="#tabel-section" class="nav-item nav-link">User Management</a>
                            <a href="logout.php" class="nav-item nav-link">Logout</a>
                                </div>                        
                            </div>
                        </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->

        <!-- Page Header Start -->
        <div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container text-center py-5">
                <h1 class="display-2 text-white mb-4">Welcome To Your Dashboard</h1>
                </ol>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Programs Start -->
        <div class="container-fluid program  py-5">
            <div class="container py-5">
                <div class="mx-auto text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px;">
                    <h1 class="mb-5 display-3">All Contents</h1>
                </div>
                <div class="row g-5 justify-content-center" id="materi-section">
                    <?php if ($materi_result && $materi_result->num_rows > 0): ?>
                        <?php while ($materi = $materi_result->fetch_assoc()): ?>
                            <div class="col-md-6 col-lg-6 col-xl-4 wow fadeIn" data-wow-delay="0.1s">
                                <div class="program-item rounded">
                                    <div class="program-img position-relative">
                                        <div class="overflow-hidden img-border-radius">
                                            <img src="img/program-1.jpg" class="img-fluid w-100" alt="Image">
                                        </div>
                                    </div>
                                    <div class="program-text bg-white px-4 pb-3">
                                        <div class="program-text-inner">
                                            <a href="materi_detail.php?materi_id=<?php echo $materi['id']; ?>" class="h4"><?php echo htmlspecialchars($materi['title']); ?></a> 
                                            <p class="mt-3 mb-0"><?php echo htmlspecialchars($materi['description']); ?></p>
                                        </div>
                                    </div>
                                    <div class="program-teacher d-flex align-items-center border-top border-primary bg-white px-4 py-3">
                                        <img src="img/program-teacher.jpg" class="img-fluid rounded-circle p-2 border border-primary bg-white" alt="Image" style="width: 70px; height: 70px;">
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-primary"><?php echo htmlspecialchars($materi['created_by_name']); ?></h6>
                                            <small><?php echo htmlspecialchars($materi['created_by_email']); ?></small>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between px-4 py-2 bg-primary rounded-bottom">
                                        <small class="text-white">
                                            <!-- Tombol Edit -->
                                            <a href="edit_materi.php?materi_id=<?php echo $materi['id']; ?>" 
                                            class="btn btn-warning btn-sm me-2" 
                                            title="Edit Materi">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </small>
                                        <small class="text-white">
                                            <a href="javascript:void(0);" 
                                            class="btn btn-danger btn-sm" 
                                            title="Hapus Materi" 
                                            onclick="confirmDelete(<?php echo $materi['id']; ?>)">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Belum ada materi yang dibuat.</p>
                    <?php endif; ?>
                    <div class="d-inline-block text-center wow fadeIn" data-wow-delay="0.1s">
                    <a href="#materi-section" class="btn btn-primary px-5 py-3 text-white btn-border-radius">View All Contents</a>
                    <a href="tambah_materi.php" class="btn btn-primary px-5 py-3 text-white btn-border-radius">Tambah Materi</a>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <!-- Program End -->

    <!-- Manajemen Pengguna -->
    <div class="container mt-5" id="tabel-section">
        <h2>Table Management Users</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($row['role'])); ?></td>
                            <td>
                                <?php if ($_SESSION['role'] === 'pengajar'): ?>
                                <a href="javascript:void(0);" 
                                class="btn btn-danger btn-sm" 
                                onclick="konfirmasiHapus(<?php echo $row['id']; ?>)">
                                Hapus
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    <!-- Pagination for User Management -->
    <?php if ($total_records > $records_per_page): ?>
    <ul class="pagination">
        <!-- First & Prev Buttons -->
        <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=1">&laquo; First</a>
        </li>
        <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>">&lsaquo; Prev</a>
        </li>

            <?php
            // Logika untuk menampilkan halaman di sekitar halaman aktif
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);
            
            if ($start_page > 1) {
                echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                if ($start_page > 2) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }
            
            for ($i = $start_page; $i <= $end_page; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor;
            
            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '">' . $total_pages . '</a></li>';
            }
            ?>

         <!-- Next & Last Buttons -->
         <li class="page-item <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo min($total_pages, $page + 1); ?>">Next &rsaquo;</a>
        </li>
        <li class="page-item <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $total_pages; ?>">Last &raquo;</a>
        </li>
    </ul>
    <?php endif; ?>
        </div>
<!-- End Pagination -->

    <!-- Fungsi Manajemen Pengguna -->
    <?php
    // **Proses Hapus User**
    if (isset($_GET['hapus_id'])) {
        $id = intval($_GET['hapus_id']); // Pastikan ID valid

        // Gunakan prepared statement untuk keamanan
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) { 
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    title: 'User Terhapus!',
                    text: 'User telah berhasil dihapus dari sistem.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location='dashboard_pengajar.php';
                });
            </script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat menghapus user!');</script>";
        }
    }        
    ?>
    <!-- Fungsi Manajemen Pengguna -->

        <!-- Copyright Start -->
        <div class="container-fluid copyright bg-dark py-4">
            <div class="container">
                <div class="row">
                </div>
            </div>
        </div>
        <!-- Copyright End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/konfirmasiHapus.js"></script> 
    <script src="js/confirmDelete.js"></script> 

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>
</html>