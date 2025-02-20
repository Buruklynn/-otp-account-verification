<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password is empty
$dbname = "lainishasacco"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_number = $_POST['account_number'];
    $pin = $_POST['pin'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE account_number = ? AND pin = ?");
    $stmt->bind_param("ss", $account_number, $pin);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        // User found, redirect to after.html
        header("Location: after.html");
        exit();
    } else {
        // User not found, redirect back with an error message
        header("Location: login.html?error=" . urlencode("No account found. Please check your details."));
        exit();
    }

    $stmt->close();
}

$conn->close();
?>