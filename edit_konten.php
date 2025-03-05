<?php
session_start();
include 'controller.php';  

// Cek apakah pengajar sudah login
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'pengajar') {
    header('Location: login.php');
    exit();
}

// Mendapatkan konten_id dan materi_id dari URL
$konten_id = isset($_GET['konten_id']) ? intval($_GET['konten_id']) : 0;
$materi_id = isset($_GET['materi_id']) ? intval($_GET['materi_id']) : 0;

// Mengambil data konten berdasarkan konten_id
$konten_query = $conn->prepare("SELECT * FROM konten WHERE id = ?");
$konten_query->bind_param("i", $konten_id);
$konten_query->execute();
$konten_result = $konten_query->get_result();

if ($konten_result->num_rows == 0) {
    $_SESSION['error_message'] = "Konten tidak ditemukan.";
    header('Location: dashboard_pengajar.php');
    exit();
}

$konten = $konten_result->fetch_assoc();

// Proses pengeditan jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $content = '';

    // Validasi input tambahan
    if (empty($type)) {
        $_SESSION['error_message'] = "Tipe konten tidak valid.";
        header("Location: edit_konten.php?konten_id=$konten_id&materi_id=$materi_id");
        exit();
    }

    if ($type == 'description') {
        // Konten berupa deskripsi teks
        $content = trim($_POST['content']);
        if (empty($content)) {
            $_SESSION['error_message'] = "Deskripsi tidak boleh kosong.";
            header("Location: edit_konten.php?konten_id=$konten_id&materi_id=$materi_id");
            exit();
        }
    } elseif ($type == 'video' && isset($_FILES['video'])) {
        // Konten berupa video
        $video_tmp_name = $_FILES['video']['tmp_name'];
        $video_name = $_FILES['video']['name'];
        $video_size = $_FILES['video']['size'];
        $video_ext = strtolower(pathinfo($video_name, PATHINFO_EXTENSION));

        // Validasi ekstensi video
        $allowed_extensions = ['mp4', 'avi', 'mov', 'mkv'];
        $max_file_size = 50 * 1024 * 1024; // 50MB

        if (!in_array($video_ext, $allowed_extensions)) {
            $_SESSION['error_message'] = "Ekstensi video tidak valid. Hanya mp4, avi, mov, dan mkv yang diperbolehkan.";
            header("Location: edit_konten.php?konten_id=$konten_id&materi_id=$materi_id");
            exit();
        }

        if ($video_size > $max_file_size) {
            $_SESSION['error_message'] = "Ukuran video terlalu besar. Maks 50MB.";
            header("Location: edit_konten.php?konten_id=$konten_id&materi_id=$materi_id");
            exit();
        }

        // Tentukan lokasi upload video
        $upload_dir = 'uploads/videos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $new_video_name = time() . '_' . uniqid() . '.' . $video_ext;
        $video_path = $upload_dir . $new_video_name;

        // Pindahkan file video ke folder upload
        if (move_uploaded_file($video_tmp_name, $video_path)) {
            // Hapus video lama jika ada
            if (!empty($konten['content']) && $konten['type'] == 'video') {
                @unlink($konten['content']);
            }
            $content = $video_path;
        } else {
            $_SESSION['error_message'] = "Gagal mengunggah video.";
            header("Location: edit_konten.php?konten_id=$konten_id&materi_id=$materi_id");
            exit();
        }
    }

    // Menambahkan konten menggunakan fungsi editKonten
    if (editKonten($konten_id, $materi_id, $type, $content)) {
        $_SESSION['success_message'] = "Konten berhasil diperbarui!";
        header("Location: edit_konten.php?konten_id=$konten_id&materi_id=$materi_id");
        exit();
    } else {
        $_SESSION['error_message'] = "Gagal memperbarui konten. Coba lagi.";
        header("Location: edit_konten.php?konten_id=$konten_id&materi_id=$materi_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PlayGrow - Edit Konten</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Hidden inputs for session messages -->
    <?php if(isset($_SESSION['success_message'])): ?>
        <input type="hidden" id="success-message" value="<?php echo htmlspecialchars($_SESSION['success_message']); ?>">
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error_message'])): ?>
        <input type="hidden" id="error-message" value="<?php echo htmlspecialchars($_SESSION['error_message']); ?>">
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Navbar start -->
    <div class="container-fluid border-bottom bg-light wow fadeIn" data-wow-delay="0.1s">
        <div class="container topbar bg-primary d-none d-lg-block py-2" style="border-radius: 0 40px">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2"></div>
                <div class="top-link pe-2"></div>
            </div>
        </div>
        <div class="container px-0">
            <nav class="navbar navbar-light navbar-expand-xl py-3">
                <a href="index.html" class="navbar-brand">
                    <h1 class="text-primary display-6">Play<span class="text-secondary">Grow</span></h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Programs Start -->
    <div class="container-fluid program py-5">
        <div class="container py-5">
            <div class="mx-auto text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px;">
                <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius">Edit Konten</h4>
                <h1 class="mb-5 display-3">Edit Konten</h1>
            </div>
            <div class="row g-5 justify-content-center">
                <form 
                    action="edit_konten.php?konten_id=<?php echo $konten_id; ?>&materi_id=<?php echo $materi_id; ?>" 
                    method="POST" 
                    enctype="multipart/form-data"
                    id="edit-konten-form"
                >
                    <div class="form-group">
                        <label for="type">Tipe Konten</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="description" <?php echo $konten['type'] == 'description' ? 'selected' : ''; ?>>Deskripsi</option>
                            <option value="video" <?php echo $konten['type'] == 'video' ? 'selected' : ''; ?>>Video</option>
                        </select>
                    </div>

                    <?php if ($konten['type'] == 'video' && !empty($konten['content'])): ?>
                        <div class="form-group mt-3">
                            <label>Video Saat Ini</label>
                            <video controls class="img-fluid">
                                <source src="<?php echo htmlspecialchars($konten['content']); ?>" type="video/mp4">
                                Browser Anda tidak mendukung tag video.
                            </video>
                        </div>
                    <?php endif; ?>

                    <div class="form-group mt-3" id="textContent">
                        <label for="content">Konten (Deskripsi)</label>
                        <textarea id="content" name="content" class="form-control" rows="5"><?php 
                            echo $konten['type'] == 'description' ? htmlspecialchars($konten['content']) : ''; 
                        ?></textarea>
                    </div>

                    <div class="form-group mt-3" id="videoContent" style="display:none;">
                        <label for="video">Upload Video</label>
                        <input type="file" id="video" name="video" class="form-control" accept="video/mp4,video/avi,video/mov,video/mkv">
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-3">Perbarui Konten</button>
                </form>
                
                <div class="d-inline-block text-center wow fadeIn mt-3" data-wow-delay="0.1s">
                <a href="dashboard_pengajar.php" class="btn btn-primary px-5 py-3 text-white btn-border-radius">Kembali ke Dashboard</a>                </div>
            </div> 
        </div>
    </div>
    <!-- Program End -->

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="js/edit-konten.js"></script>
    <script src="js/main.js"></script>
</body>
</html>