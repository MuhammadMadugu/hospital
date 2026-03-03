<?php 
include '../functions.php';
if (!isLoggedIn() || ($_SESSION['type'] != 0 AND $_SESSION['type']!=5)) {
    $_SESSION['error'] = 'Login To Continue';
    header('Location: ../login/index.php');
    exit;
}

if(empty($_GET['id'])){
    $_SESSION['error'] = "Invalid request";
    echo "<script>window.history.back()</script>";
    exit;
}


$patient = sanitize($_GET['id']);

$sql = "UPDATE users SET status = -1 WHERE id = '$patient' AND type = 1";
$run = $db->query($sql);
if(!$run){
  $_SESSION['error'] = "Invalid request";
    echo "<script>window.history.back()</script>";
    exit;
}

<<<<<<< HEAD
    $_SESSION['success'] = "Patient deleted successfully!";
=======
    $_SESSION['success'] = "Staff deleted successfully!";
>>>>>>> ebc253a72e4a128f805e4199017270518a535eb5
    header('location:view.php');
    exit;
