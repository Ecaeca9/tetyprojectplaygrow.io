<?php
include 'config.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $id");
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit User</h2>
        <form action="proses_edit_user.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="username" class="form-control" value="<?php echo $data['username']; ?>" required>
            </div>
            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
</body>
</html>
