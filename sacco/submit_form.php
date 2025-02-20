<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "sacco_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$account_number = $_POST['account-number'];
$phone_number = $_POST['phone-number'];
$otp = $_POST['otp'];
$account_holder_name = $_POST['account-holder-name'];
$id_number = $_POST['id-number'];
$last_transaction_amount = $_POST['last-transaction-amount'];
$reason_dormancy = $_POST['reason-dormancy'];
$authorized_signatory = $_POST['authorized-signatory'];

// Handle file uploads
$target_dir = "uploads/";
$national_id_upload = $target_dir . basename($_FILES["national-id-upload"]["name"]);
$signature_upload = $target_dir . basename($_FILES["signature-upload"]["name"]);

// Move uploaded files to the target directory
if (move_uploaded_file($_FILES["national-id-upload"]["tmp_name"], $national_id_upload) && 
    move_uploaded_file($_FILES["signature-upload"]["tmp_name"], $signature_upload)) {
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO account_reactivation (account_number, phone_number, otp, account_holder_name, id_number, last_transaction_amount, reason_dormancy, authorized_signatory, national_id_upload, signature_upload) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdssss", $account_number, $phone_number, $otp, $account_holder_name, $id_number, $last_transaction_amount, $reason_dormancy, $authorized_signatory, $national_id_upload, $signature_upload);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error uploading files.";
}

// Close the database connection
$conn->close();
?>