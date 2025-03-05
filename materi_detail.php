<?php
session_start();
include 'controller.php';  // Pastikan fungsi sudah diimpor

// Cek apakah pengajar sudah login
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'pengajar') {
    header('Location: login.php');
    exit();
}

// Mendapatkan materi_id dari URL
$materi_id = isset($_GET['materi_id']) ? $_GET['materi_id'] : 0;

// Ambil detail materi berdasarkan materi_id
$materi_query = $conn->prepare("SELECT * FROM materi WHERE id = ?");
$materi_query->bind_param("i", $materi_id);
$materi_query->execute();
$materi_result = $materi_query->get_result();

if ($materi_result->num_rows == 0) {
    echo "<p>Materi tidak ditemukan.</p>";
    exit();
}

$materi = $materi_result->fetch_assoc();

// Mengambil konten materi berdasarkan materi_id
$konten_query = $conn->prepare("SELECT * FROM konten WHERE materi_id = ?");
$konten_query->bind_param("i", $materi_id);
$konten_query->execute();
$konten_result = $konten_query->get_result();
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

            <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        
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
                            <a href="#materidetail-section" class="nav-item nav-link active">Programs</a>                      
                            </div>
                        </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->

        <!-- Programs Start -->
        <div class="container-fluid program py-5">
            <div class="container py-5">
                <div class="mx-auto text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px;">
                    <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius"><?php echo htmlspecialchars($materi['title']); ?></h4>
                    <h1 class="mb-5 display-3">Konten Yang Tersedia</h1>
                </div>
                <div class="row g-5 justify-content-center" id="materidetail-section">
                    <?php if ($konten_result->num_rows > 0): ?>
                        <?php while ($konten = $konten_result->fetch_assoc()): ?>
                            <div class="content-item">
                                <h5><?php echo htmlspecialchars($konten['type']); ?></h5>
                                <?php if ($konten['type'] == 'video'): ?>
                                    <!-- Menampilkan video jika tipe konten adalah video -->
                                    <video width="100%" controls>
                                        <source src="<?php echo htmlspecialchars($konten['content']); ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php else: ?>
                                    <p><?php echo htmlspecialchars($konten['content']); ?></p>
                                <?php endif; ?>

                                <!-- Ikon Edit dan Hapus -->
                                <div class="d-flex justify-content-end mt-2">
                                    <!-- Edit Icon -->
                                    <a href="edit_konten.php?konten_id=<?php echo $konten['id']; ?>&materi_id=<?php echo $materi_id; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete Icon -->
                                    <a href="javascript:void(0);" 
                                    class="btn btn-danger btn-sm ms-2 hapus-konten" 
                                        data-konten-id="<?php echo $konten['id']; ?>" 
                                        data-materi-id="<?php echo $materi_id; ?>">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Belum ada konten untuk materi ini.</p>
                    <?php endif; ?>
                    <div class="d-inline-block text-center wow fadeIn" data-wow-delay="0.1s">
                        <a href="tambah_konten.php?materi_id=<?php echo $materi_id; ?>" class="btn btn-primary px-5 py-3 text-white btn-border-radius">Tambah Konten</a>
                        <a href="dashboard_pengajar.php" class="btn btn-primary px-5 py-3 text-white btn-border-radius">Kembali ke Dashboard</a>
                    </div>
                </div> 
            </div>
        </div>
        <!-- Program End -->

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
    <script src="js/hapus-konten.js"></script> 


    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>

</html>