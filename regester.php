<?php
// Debugging POST input (optional during development)
var_dump($_POST);

// Get values from POST
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$repeatPassword = $_POST['repeatpassword'];

// Check if passwords match
if ($password !== $repeatPassword) {
    // Redirect back with error
    header("Location: register_form.php?error=Passwords+do+not+match");
    exit;
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'users');

if ($conn->connect_error) {
    die('Connection error: ' . $conn->connect_error);
} else {
    // Optional: Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Success message
        header("Location: index.html?success=Registration+successful");
    } else {
        // Handle DB errors (e.g., duplicate email)
        header("Location: register_form.php?error=Database+error");
    }

    $stmt->close();
    $conn->close();
}
?>