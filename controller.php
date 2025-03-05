<?php
include 'config.php';

// Fungsi untuk login
function login($email, $password) {
    global $conn;

    // Menggunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);  // 's' menunjukkan parameter string
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Ambil data pengguna dari hasil query
        $user = $result->fetch_assoc();
        
        // Verifikasi password dengan password yang di-hash
        if (password_verify($password, $user['password'])) {
            // Login berhasil, set session untuk pengguna
            session_start();
            $_SESSION['email'] = $user['email'];  // Menyimpan email dalam sesi
            $_SESSION['id'] = $user['id'];    // Menyimpan id dalam sesi
            $_SESSION['role'] = $user['role'];    // Menyimpan role dalam sesi

            // Cek role dan arahkan ke dashboard yang sesuai
            if ($user['role'] == 'parent') {
                // Redirect ke dashboard parent
                header('Location: dashboard_parent.php');
            } elseif ($user['role'] == 'pengajar') {
                // Redirect ke dashboard pengajar
                header('Location: dashboard_pengajar.php');
            } else {
                // Redirect ke dashboard umum atau default
                header('Location: dashboard.php');
            }
            exit();  // Hentikan eksekusi setelah redirect
        } else {
            // Password salah
            return false;
        }
    } else {
        // Email tidak ditemukan
        return false;
    }
}

function logout() {
    session_start();  // Mulai sesi
    session_unset();  // Menghapus semua data sesi
    session_destroy();  // Menghancurkan sesi
    
    // Redirect ke halaman login setelah logout
    header('Location: login.php');
    exit();  // Pastikan tidak ada proses lebih lanjut setelah redirect
}


function register($username, $email, $password) {
    global $conn;
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Role default 'user'
    $role = 'parent';
    
    // Prepared statement untuk menghindari SQL Injection
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
    
    // Eksekusi statement
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


//Menambahkan Materi
function addMateri($title, $description, $created_by) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO materi (title, description, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $created_by);  // 'ssi' -> string, string, integer

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Fungsi untuk mengambil materi berdasarkan pengajar
function getMateri($created_by) {
    global $conn;

    // Menyiapkan query untuk mengambil data materi dan nama pengajar (dari tabel users)
    $stmt = $conn->prepare("SELECT m.id, m.title, m.description, u.username AS created_by_name, u.email AS created_by_email
                            FROM materi m
                            JOIN users u ON m.created_by = u.id
                            WHERE m.created_by = ?");
    $stmt->bind_param("i", $created_by);  // 'i' -> integer untuk ID pengajar
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;  // Mengembalikan hasil query
}

function getMateriUser() {
    global $conn;

    // Query untuk mengambil semua data materi
    $stmt = $conn->prepare("
        SELECT materi.*, users.username AS created_by_name, users.email AS created_by_email
        FROM materi
        LEFT JOIN users ON materi.created_by = users.id
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

//Menambahkan Materi
function addKonten($materi_id, $type, $content) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO konten (materi_id, type, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $materi_id, $type, $content);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Fungsi untuk mengambil semua konten berdasarkan materi_id
function getKontenByMateriId($materi_id) {
    global $conn;

    // Query untuk mengambil semua konten yang terkait dengan materi_id
    $stmt = $conn->prepare("SELECT * FROM konten WHERE materi_id = ?");
    $stmt->bind_param("i", $materi_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}


// Fungsi untuk mengedit konten
function editKonten($konten_id, $materi_id, $type, $content) {
    global $conn;
    error_log("Edit Konten - Params: konten_id=$konten_id, materi_id=$materi_id, type=$type, content=$content");
    // Validasi input
    $konten_id = intval($konten_id);
    $materi_id = intval($materi_id);
    $type = filter_var($type, FILTER_SANITIZE_STRING);
    
    // Persiapkan query untuk update
    $query = $conn->prepare("UPDATE konten SET type = ?, content = ? WHERE id = ? AND materi_id = ?");
    $query->bind_param("ssii", $type, $content, $konten_id, $materi_id);
    
    // Eksekusi query
    $result = $query->execute();
    
    // Tutup statement
    $query->close();
    
    return $result;
}

function hapusKonten($konten_id, $materi_id) {
    global $conn;

    // Ambil data konten berdasarkan konten_id dan materi_id untuk memeriksa apakah ada video yang perlu dihapus
    $stmt = $conn->prepare("SELECT content, type FROM konten WHERE id = ? AND materi_id = ?");
    $stmt->bind_param("ii", $konten_id, $materi_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek jika konten ditemukan
    if ($result->num_rows > 0) {
        $konten = $result->fetch_assoc();

        // Jika konten tipe video dan ada file video, hapus file video
        if ($konten['type'] == 'video' && file_exists($konten['content'])) {
            unlink($konten['content']);  // Hapus file video
        }

        // Hapus konten dari database
        $stmt_delete = $conn->prepare("DELETE FROM konten WHERE id = ? AND materi_id = ?");
        $stmt_delete->bind_param("ii", $konten_id, $materi_id);
        if ($stmt_delete->execute()) {
            return true;  // Konten berhasil dihapus
        } else {
            return false;  // Gagal menghapus konten
        }
    } else {
        return false;  // Konten tidak ditemukan
    }
}

// Fungsi untuk mengedit materi
function editMateri($materi_id, $title, $description) {
    global $conn;

    // Query untuk memperbarui materi
    $stmt = $conn->prepare("UPDATE materi SET title = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $description, $materi_id);

    return $stmt->execute();
}

// Fungsi untuk menghapus materi
function hapusMateri($materi_id) {
    global $conn;

    // Menghapus materi berdasarkan materi_id
    $stmt = $conn->prepare("DELETE FROM materi WHERE id = ?");
    $stmt->bind_param("i", $materi_id);

    return $stmt->execute();
}

// Fungsi untuk menyimpan data kunjungan pengguna
function saveVisit($user_id) {
    // Ambil waktu mulai kunjungan (timestamp dalam detik)
    $visit_time_start = date('Y-m-d H:i:s', time());  // Waktu mulai dalam format yang benar
    $visit_time_end = NULL;  // Waktu berakhir diset NULL untuk awal

    // Hitung durasi (dengan nilai sementara untuk saat ini)
    $duration = 0;  // Durasi di set ke 0 karena kunjungan baru dimulai

    // Simpan data ke database
    storeVisitData($user_id, $visit_time_start, $visit_time_end, $duration);
}


// Fungsi untuk menyimpan data kunjungan ke database
function storeVisitData($user_id, $visit_time_start, $visit_time_end, $duration) {
    // Menggunakan koneksi yang sudah ada (global $conn)
    global $conn;

    try {
        // Query untuk menyimpan data kunjungan
        $query = "INSERT INTO user_visits (user_id, visit_time_start, visit_time_end, duration) 
                  VALUES (?, ?, ?, ?)";

        // Menyiapkan statement
        $stmt = $conn->prepare($query);

        // Bind parameter ke statement
        // Gunakan 's' untuk string (waktu), 'i' untuk integer (duration)
        $stmt->bind_param("issi", $user_id, $visit_time_start, $visit_time_end, $duration);

        // Eksekusi query
        $stmt->execute();
    } catch (Exception $e) {
        // Menangani error jika terjadi masalah dengan query atau koneksi database
        echo "Error: " . $e->getMessage();
    }
}

// Fungsi untuk memperbarui waktu akhir kunjungan dan durasi
function endVisit($user_id) {
    global $conn;

    // Perbarui waktu logout dan hitung durasi
    $query = "UPDATE user_visits 
              SET visit_time_end = NOW(), 
                  duration = TIMESTAMPDIFF(SECOND, visit_time_start, NOW()) 
              WHERE user_id = ? AND visit_time_end IS NULL";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}


// Fungsi untuk mengambil data durasi kunjungan per hari
function getVisitData() {
    global $conn;
    // Query untuk mengambil data kunjungan berdasarkan user_id
    $query = "SELECT visit_time_start, visit_time_end, duration, DATE(visit_time_start) AS visit_date, SUM(duration) AS total_duration
              FROM user_visits 
              WHERE user_id = ? AND visit_time_start IS NOT NULL
              GROUP BY visit_date 
              ORDER BY visit_date DESC";  // Bisa disesuaikan dengan urutan yang Anda inginkan
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['id']); // Menggunakan session user_id
    $stmt->execute();
    
    return $stmt->get_result();
}

function getAllVisitData() {
    global $conn;
    $query = "SELECT users.id, users.username, SUM(user_visits.duration) AS total_duration
              FROM user_visits
              JOIN users ON user_visits.user_id = users.id
              WHERE user_visits.visit_time_end IS NOT NULL
              GROUP BY users.id, users.username
              ORDER BY total_duration DESC";
    return $conn->query($query);
}

function getVisitDataByUser($user_id) {
    global $conn;
    $query = "SELECT DATE(visit_time_start) AS visit_date, SUM(duration) AS total_duration
              FROM user_visits
              WHERE visit_time_end IS NOT NULL AND user_id = ?
              GROUP BY DATE(visit_time_start)
              ORDER BY visit_time_start ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}


function getYouTubeVideos($channelId, $maxResults = 6) {
    $apiKey = "AIzaSyDj3boPJB-d8cVg3BUPn03GVk9dN6RqWUs";
    $url = "https://www.googleapis.com/youtube/v3/search?key=$apiKey&channelId=$channelId&part=id,snippet&order=date&maxResults=$maxResults&type=video";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>
    </body>

</html>