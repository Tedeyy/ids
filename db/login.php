<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST['uname'];
    $pass = $_POST['pass'];

    // Check in admin table first
    $stmt = $conn->prepare("SELECT admin_id, pass, 'admin' AS role FROM admin WHERE uname = ? 
                            UNION 
                            SELECT user_id, pass, 'user' AS role FROM users WHERE uname = ?");
    $stmt->bind_param("ss", $uname, $uname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($pass, $user['pass'])) {
            $_SESSION['user_id'] = $user['admin_id'] ?? $user['user_id'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
}
?>
