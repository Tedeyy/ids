<?php
include "db.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $birthdate = $_POST['birthdate'];
    $uname = $_POST['uname'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];

    // Determine which table to insert into based on role
    if ($role == "admin") {
        $stmt = $conn->prepare("INSERT INTO admin (adminlname, adminfname, birthdate, uname, pass) VALUES (?, ?, ?, ?, ?)");
    } else {
        $stmt = $conn->prepare("INSERT INTO users (userlname, userfname, birthdate, uname, pass) VALUES (?, ?, ?, ?, ?)");
    }

    // Bind parameters and execute query
    $stmt->bind_param("sssss", $lastname, $firstname, $birthdate, $uname, $pass);

    if ($stmt->execute()) {
        echo "Registration successful! <a href='index.php'>Go to Login</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
