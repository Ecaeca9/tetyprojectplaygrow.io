<?php
session_start(); // Memulai sesi
include 'controller.php';

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION['email'])) {
    // Jika belum login, arahkan ke halaman login
    header('Location: login.php');
    exit();
}

// Mendapatkan email pengguna yang login
$email = $_SESSION ['email'];

$user_id = $_SESSION['id'];  // Mendapatkan user_id dari session
saveVisit($user_id);  // Menyimpan data kunjungan

// Mengambil semua data materi
$materi_result = getMateriUser();

// Ambil data kunjungan
$visit_data = getVisitData();

// Ubah mysqli_result menjadi array
$visit_data_array = [];
while ($row = $visit_data->fetch_assoc()) {
    $visit_data_array[] = $row;
}

// Filter data yang valid
$valid_visit_data = array_filter($visit_data_array, function($row) {
    return !is_null($row['visit_date']) && $row['total_duration'] > 0;
});

// Siapkan data untuk chart
$dates = [];
$durations = [];

foreach ($valid_visit_data as $row) {
    $dates[] = $row['visit_date'];
    $durations[] = $row['total_duration'];
}

// Jika pengguna logout atau sesi berakhir, panggil fungsi endVisit untuk memperbarui data
if (isset($_GET['logout'])) {
    endVisit($user_id);  // Memperbarui visit_time_end dan durasi
    session_destroy();  // Menghancurkan sesi setelah logout
    header('Location: login.php');  // Arahkan ke halaman login setelah logout
    exit();
}

$channelId = "UCdd9POu1PHwW7k8oiuWPweQ"; // Ganti dengan ID Channel YouTube Anda
$videos = getYouTubeVideos($channelId);
$cacheFile = "cache_videos.json";
$cacheTime = 3600; // 1 jam

if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    $videos = json_decode(file_get_contents($cacheFile), true);
} else {
    $videos = getYouTubeVideos($channelId, 6);
    file_put_contents($cacheFile, json_encode($videos));
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

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
        <link href="css/custom-video.css" rel="stylesheet">
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
                            <a href="dashboard_parent.php" class="nav-item nav-link active">Home</a>
                            <a href="#materi-section" class="nav-item nav-link">Programs</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                    <a href="#aktivitas-section" class="dropdown-item">Aktivitas</a>
                                    <a href="logout.php" class="dropdown-item" onclick="event.preventDefault(); window.location.href='logout.php';">Logout</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex me-4">
                            <div id="phone-tada" class="d-flex align-items-center justify-content-center">
                            <a href="https://wa.me/6285251742246" target="_blank" class="position-relative wow tada" data-wow-delay=".9s">
                            <i class="fa fa-phone-alt text-primary fa-2x me-4"></i>
                            <div class="position-absolute" style="top: -7px; left: 20px;">
                                <span><i class="fa fa-comment-dots text-secondary"></i></span>
                            </div>
                            </a>
                        </div>
                    </div>
                            <div class="d-flex flex-column pe-3 border-end border-primary">
                                <span class="text-primary">Have any questions?</span>
                                <a href="#"><span class="text-secondary">WhatsApp: +6285251742246</span></a>
                            </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->

        <!-- Hero Start -->
        <div class="container-fluid py-5 hero-header wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-7 col-md-12">
                        <h1 class="mb-3 text-primary">We Care Your Baby</h1>
                        <h1 class="mb-5 display-1 text-white">The Best Play Area For Your Kids</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero End -->

        <!-- Modal Video -->
        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Youtube Video</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- 16:9 aspect ratio -->
                        <div class="ratio ratio-16x9">
                            <iframe class="embed-responsive-item" src="" id="video" allowfullscreen allowscriptaccess="always"
                                allow="autoplay"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->

        <!-- Programs Start -->
        <div class="container-fluid program  py-5">
            <div class="container py-5">
                <div class="mx-auto text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px;">
                    <h1 class="mb-5 display-3">We Offer An Exclusive Program For Kids</h1>
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
                                            <a href="materi_detail_user.php?materi_id=<?php echo $materi['id']; ?>" class="h4"><?php echo htmlspecialchars($materi['title']); ?></a> 
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
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Belum ada materi yang dibuat.</p>
                    <?php endif; ?>
                    <div class="d-inline-block text-center wow fadeIn" data-wow-delay="0.1s">
                    <a href="#materi-section" class="btn btn-primary px-5 py-3 text-white btn-border-radius">View All Contents</a>
                    </div>
                </div> 
            </div>
        </div>
        <!-- Program End -->

<h2>Channel Youtube PlayGrow</h2>
<div class="video-container">
    <?php
    if (!empty($videos['items'])) {
        foreach ($videos['items'] as $video) {
            if (isset($video['id']['videoId'])) {
                $videoId = $video['id']['videoId'];
                $title = $video['snippet']['title'];
                echo "<div class='video-box'>";
                echo "<h3 class='video-title'>$title</h3>";
                echo "<div class='video-frame'>";
                echo "<iframe class='video-iframe' src='https://www.youtube.com/embed/$videoId?enablejsapi=1' frameborder='0' allowfullscreen></iframe>";
                echo "</div>";
                echo "</div>";
            }
        }
    } else {
        echo "<p class='no-video'>Tidak ada video tersedia.</p>";
    }
    ?>
</div>

         <!-- Grafik Manajemen Waktu -->
         <div class="container-fluid blog py-5">
            <div class="container py-5">
                <div class="mx-auto text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 600px;" id=aktivitas-section>
                    <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius">Waktu Dihabiskan</h4>
                </div>
                <div class="row g-5 justify-content-center">
                    <canvas id="visitChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <!-- Grafik Manajemen Waktu -->


        <!-- Team Start-->
        <div class="container-fluid team py-5">
            <div class="container py-5">
                <div class="mx-auto text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius">Our Team</h4>
                    <h1 class="mb-5 display-3">Meet With Our Expert Teacher</h1>
                </div>
                <div class="row g-5 justify-content-center" id="team-section">
                    <div class="col-md-6 col-lg-4 col-xl-3 wow fadeIn" data-wow-delay="0.1s">
                        <div class="team-item border border-primary img-border-radius overflow-hidden">
                            <img src="img/team-1.jpg" class="img-fluid w-100" alt="">
                            <div class="team-icon d-flex align-items-center justify-content-center">
                                <a class="share btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fas fa-share-alt"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fab fa-twitter"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                            <div class="team-content text-center py-3">
                                <h4 class="text-primary">Linda Carlson</h4>
                                <p class="text-muted mb-2">English Teacher</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 wow fadeIn" data-wow-delay="0.3s">
                        <div class="team-item border border-primary img-border-radius overflow-hidden">
                            <img src="img/team-2.jpg" class="img-fluid w-100" alt="">
                            <div class="team-icon d-flex align-items-center justify-content-center">
                                <a class="share btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fas fa-share-alt"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fab fa-twitter"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                            <div class="team-content text-center py-3">
                                <h4 class="text-primary">Linda Carlson</h4>
                                <p class="text-muted mb-2">English Teacher</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 wow fadeIn" data-wow-delay="0.5s">
                        <div class="team-item border border-primary img-border-radius overflow-hidden">
                            <img src="img/team-3.jpg" class="img-fluid w-100" alt="">
                            <div class="team-icon d-flex align-items-center justify-content-center">
                                <a class="share btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fas fa-share-alt"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fab fa-twitter"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                            <div class="team-content text-center py-3">
                                <h4 class="text-primary">Linda Carlson</h4>
                                <p class="text-muted mb-2">English Teacher</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 wow fadeIn" data-wow-delay="0.7s">
                        <div class="team-item border border-primary img-border-radius overflow-hidden">
                            <img src="img/team-4.jpg" class="img-fluid w-100" alt="">
                            <div class="team-icon d-flex align-items-center justify-content-center">
                                <a class="share btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fas fa-share-alt"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle me-3" href=""><i class="fab fa-twitter"></i></a>
                                <a class="share-link btn btn-primary btn-md-square text-white rounded-circle" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                            <div class="team-content text-center py-3">
                                <h4 class="text-primary">Linda Carlson</h4>
                                <p class="text-muted mb-2">English Teacher</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Team End-->
        
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="js/youtube-control.js" defer></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="js/visit-chart.js"></script>
    <script>
        var visitDates = <?php echo json_encode($dates); ?>;
        var visitDurations = <?php echo json_encode($durations); ?>;
    </script>
    </body>
</html>
