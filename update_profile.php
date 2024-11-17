<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_signup.php");
    exit;
}
include 'db.php';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $query = $conn->prepare("UPDATE users SET firstname = ?,lastname = ?, email = ?, phone = ? WHERE id = ?");
    $query->bind_param("ssssi", $firstname, $lastname, $email, $phone, $user_id);
    if ($query->execute()) {
        header("Location: profile.php?update=success");
    } else {
        header("Location: profile.php?update=error");
    }
    $query->close();
}
