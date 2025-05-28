<?php
session_start();
include 'db_connect.php';  // Adjust the path if necessary

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        echo "<script>alert('Session expired. Please log in again.'); window.location.href = '../login.php';</script>";
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $new_username = htmlspecialchars(trim($_POST['new_username']), ENT_QUOTES, 'UTF-8');
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Kunin ang kasalukuyang password mula sa database
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "<script>alert('User not found!'); window.history.back();</script>";
        exit();
    }

    // I-verify ang kasalukuyang password
    if (!password_verify($current_password, $user['password'])) {
        echo "<script>alert('Wrong current password!'); window.history.back();</script>";
        exit();
    }

    // I-validate kung pareho ang new password at confirm password
    if (!empty($new_password) && $new_password !== $confirm_password) {
        echo "<script>alert('Password not match!'); window.history.back();</script>";
        exit();
    }

    // I-update ang username lang kung walang new password
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_query = "UPDATE users SET username = ?, password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssi", $new_username, $hashed_password, $user_id);
    } else {
        $update_query = "UPDATE users SET username = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $new_username, $user_id);
    }

    if ($update_stmt->execute()) {
        echo "<script>alert('Profile successfully updated!'); window.location.href = '../pages/dashboard.php';</script>";
    } else {
        echo "<script>alert('Error on updating profile.'); window.history.back();</script>";
    }
}
?>
