<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$modul_id = $_POST['modul_id'] ?? null;
if (!$modul_id || !isset($_FILES['file_laporan'])) {
    header('Location: dashboard.php?msg=Modul tidak valid');
    exit;
}
// Validasi file
$allowed = ['pdf','docx'];
$filename = $_FILES['file_laporan']['name'];
$tmp = $_FILES['file_laporan']['tmp_name'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
if (!in_array($ext, $allowed)) {
    header('Location: praktikum_detail.php?id=' . $modul_id . '&msg=File harus PDF/DOCX');
    exit;
}
$upload_dir = '../uploads/laporan/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
$newname = 'laporan_' . $user_id . '_' . $modul_id . '_' . time() . '.' . $ext;
move_uploaded_file($tmp, $upload_dir . $newname);
// Cek sudah pernah upload?
global $conn;
$cek = mysqli_query($conn, "SELECT * FROM laporan WHERE modul_id='$modul_id' AND user_id='$user_id'");
if (mysqli_num_rows($cek) > 0) {
    mysqli_query($conn, "UPDATE laporan SET file_laporan='$newname', status='Dikirim', tanggal_kumpul=NOW() WHERE modul_id='$modul_id' AND user_id='$user_id'");
} else {
    mysqli_query($conn, "INSERT INTO laporan (modul_id, user_id, file_laporan) VALUES ('$modul_id', '$user_id', '$newname')");
}
// Redirect kembali ke detail praktikum
$praktikum_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT praktikum_id FROM modul WHERE id='$modul_id'"))['praktikum_id'];
header('Location: praktikum_detail.php?id=' . $praktikum_id . '&msg=Upload berhasil');
exit; 