<?php
session_start(); // Memulai sesi
include 'config.php';
include 'controller.php';

// Mengambil semua data materi
$materi_result = getMateriUser();
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
                            <a href="index.php" class="nav-item nav-link active">Home</a>
                            <a href="#about-section" class="nav-item nav-link">About</a>
                            <a href="#konten-section" class="nav-item nav-link">Programs</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                    <a href="#team-section" class="dropdown-item">Our Team</a>
                                </div>
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
                <a href="parents.php" class="btn btn-primary px-4 py-3 px-md-5 btn-border-radius">Parents</a>
                <a href="teacher.php" class="btn btn-secondary px-4 py-3 px-md-5 btn-border-radius">Teacher</a>
            </div>
        </div>
    </div>
</div>
<!-- Hero End -->

<!-- About Start -->
<div class="container-fluid py-5 about bg-light text-center">
    <div class="container py-5">
        <div class="row justify-content-center"  id="about-section">
            <div class="col-lg-8 wow fadeIn" data-wow-delay="0.3s">
                <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius">About PlayGrow</h4>
                <h1 class="text-dark mb-4 display-5">Learning Through Play, Growing Every Day!</h1>
                <p class="text-dark mb-4">
                    PlayGrow adalah tempat di mana anak-anak belajar dengan cara yang menyenangkan dan interaktif.
                    Kami percaya bahwa pembelajaran terbaik terjadi melalui permainan yang kreatif dan edukatif.
                    Dengan pendekatan inovatif, kami membantu anak-anak mengembangkan keterampilan sosial,
                    kognitif, dan motorik mereka dalam lingkungan yang aman dan penuh kasih.
                </p>
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
                <div class="row g-5 justify-content-center" id="konten-section">
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
                                            <a href="login.php" class="h4"><?php echo htmlspecialchars($materi['title']); ?></a> 
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
                </div> 
            </div>
        </div>
        <!-- Program End -->

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
                            <div class="team-content text-center py-3" id="testimonial-section">
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

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>

</html>