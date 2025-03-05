<?php
session_start();
include 'controller.php';

// Cek apakah pengajar sudah login
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'pengajar') {
    header('Location: login.php');
    exit();
}

// Mendapatkan materi_id dari URL
$materi_id = isset($_GET['materi_id']) ? $_GET['materi_id'] : 0;

// Mengambil data materi berdasarkan materi_id
$materi_query = $conn->prepare("SELECT * FROM materi WHERE id = ?");
$materi_query->bind_param("i", $materi_id);
$materi_query->execute();
$materi_result = $materi_query->get_result();

if ($materi_result->num_rows == 0) {
    echo "<p>Materi tidak ditemukan.</p>";
    exit();
}

$materi = $materi_result->fetch_assoc();

// Proses pengeditan jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (editMateri($materi_id, $title, $description)) {
        $_SESSION['update_success'] = true;
        header('Location: edit_materi.php?materi_id=' . $materi_id);
        exit();
    } else {
        echo "<p>Gagal memperbarui materi. Coba lagi.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PlayGrow - Edit Materi</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Montserrat:wght@200;400;600&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom CSS for Edit Materi -->
    <link href="css/edit-materi.css" rel="stylesheet">
</head>
<body>

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
                            </div>
                        </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->

    <div class="container-fluid program py-5">
        <div class="container py-5">
            <div class="mx-auto text-center wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px;">
                <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius">Edit Materi</h4>
                <h1 class="mb-5 display-3">Edit Materi</h1>
            </div>
            <div class="row g-5 justify-content-center">
                <form action="edit_materi.php?materi_id=<?php echo $materi_id; ?>" method="POST">
                    <div class="form-group">
                        <label for="title">Judul Materi</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($materi['title']); ?>" required>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="description">Deskripsi Materi</label>
                        <textarea id="description" name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($materi['description']); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                </form>
                <div class="d-inline-block text-center wow fadeIn" data-wow-delay="0.1s">
                    <a href="dashboard_pengajar.php" class="btn btn-primary px-5 py-3 text-white btn-border-radius">Kembali ke Dashboard</a>
                </div>
            </div> 
        </div>
    </div>

    <?php
    if (isset($_SESSION['update_success']) && $_SESSION['update_success'] === true) {
    ?>
    <!-- Modal Konfirmasi Berhasil -->
    <div class="modal fade show success-modal" id="successModal" tabindex="-1" style="display: block;" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content success-content p-4 text-center">
                <div class="modal-body">
                    <i class="fas fa-check-circle success-icon mb-3"></i>
                    <h2 class="mb-4">Berhasil!</h2>
                    <p class="mb-4">Materi berhasil diperbarui dengan sukses.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="dashboard_pengajar.php" class="btn btn-primary">Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    unset($_SESSION['update_success']);
    }
    ?>

            <!-- Copyright Start -->
            <div class="container-fluid copyright bg-dark py-4">
            <div class="container">
                <div class="row">
                </div>
            </div>
        </div>
        <!-- Copyright End -->

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript for Edit Materi -->
    <script src="js/edit-materi.js"></script>
</body>
</html>