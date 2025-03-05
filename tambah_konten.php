<?php
session_start();

// Cek apakah pengguna sudah login dan memiliki role pengajar
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'pengajar' || !isset($_SESSION['id'])) {
    header('Location: login.php');  // Arahkan ke halaman login jika belum login atau bukan pengajar
    exit();
}

include 'controller.php';  // Pastikan file controller.php yang berisi fungsi addMateri() sudah ada

// Mendapatkan materi_id dari URL
$materi_id = isset($_GET['materi_id']) ? $_GET['materi_id'] : 0;

// Proses jika formulir ditambahkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type']; // Deskripsi atau Video
    $content = '';

    if ($type == 'description') {
        // Konten berupa deskripsi teks
        $content = $_POST['content'];
    } elseif ($type == 'video' && isset($_FILES['video'])) {
        // Konten berupa video
        $video_tmp_name = $_FILES['video']['tmp_name'];
        $video_name = $_FILES['video']['name'];
        $video_ext = pathinfo($video_name, PATHINFO_EXTENSION);

        // Validasi ekstensi video (misalnya hanya mp4, avi)
        $allowed_extensions = ['mp4', 'avi', 'mov', 'mkv'];
        if (in_array($video_ext, $allowed_extensions)) {
            // Tentukan lokasi upload video
            $upload_dir = 'uploads/videos/';
            $new_video_name = time() . '.' . $video_ext;
            $video_path = $upload_dir . $new_video_name;

            // Pindahkan file video ke folder upload
            if (move_uploaded_file($video_tmp_name, $video_path)) {
                $content = $video_path; // Menyimpan path video ke database
            } else {
                echo "Gagal mengunggah video.";
                exit();
            }
        } else {
            echo "Ekstensi video tidak valid. Hanya mp4, avi, dan mov yang diperbolehkan.";
            exit();
        }
    }

    // Menambahkan konten menggunakan fungsi addKonten
    if (addKonten($materi_id, $type, $content)) {
        // Set session flash message untuk Sweet Alert
        $_SESSION['success_message'] = 'Konten berhasil ditambahkan!';
        header("Location: tambah_konten.php?materi_id=$materi_id&success=true");
        exit();
    } else {
        // Set session flash message untuk error
        $_SESSION['error_message'] = 'Gagal menambahkan konten. Coba lagi.';
        header("Location: tambah_konten.php?materi_id=$materi_id&error=true");
        exit();
    }
}
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

        <!-- Sweet Alert 2 Stylesheet -->
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">

        <!-- Custom Stylesheet for Tambah Konten -->
        <link href="css/tambah-konten.css" rel="stylesheet">
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
                    <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius">Tambah Konten</h4>
                    <h1 class="mb-5 display-3">Mari Tambahkan Konten</h1>
                </div>
                <div class="row g-5 justify-content-center">
                    <form action="tambah_konten.php?materi_id=<?php echo $materi_id; ?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="type">Tipe Konten</label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="description">Deskripsi</option>
                                <option value="video">Video</option>
                            </select>
                        </div>

                        <div class="form-group mt-3" id="textContent">
                            <label for="content">Konten (Deskripsi)</label>
                            <textarea id="content" name="content" class="form-control" rows="5"></textarea>
                        </div>

                        <div class="form-group mt-3" id="videoContent" style="display:none;">
                            <label for="video">Upload Video</label>
                            <input type="file" id="video" name="video" class="form-control" accept="video/*">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Tambah Konten</button>
                    </form>
                    <div class="d-inline-block text-center wow fadeIn" data-wow-delay="0.1s">
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

        <!-- Sweet Alert 2 Script -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>

        <!-- Custom JavaScript for Tambah Konten -->
        <script src="js/tambah-konten.js"></script>
    </body>
</html>