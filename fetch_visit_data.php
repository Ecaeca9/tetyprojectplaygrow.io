<?php
session_start();
include 'controller.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'pengajar') {
    die(json_encode(["error" => "Anda tidak memiliki akses"]));
}

if (!isset($_GET['parent_id'])) {
    die(json_encode(["error" => "User ID tidak valid"]));
}

$parent_id = intval($_GET['parent_id']);
$visit_data = getVisitDataByUser($parent_id);
$visit_data_array = [];

while ($row = $visit_data->fetch_assoc()) {
    $visit_data_array[] = [
        "label" => $row['visit_date'],
        "y" => $row['total_duration']
    ];
}

header('Content-Type: application/json');
echo json_encode($visit_data_array);
?>
