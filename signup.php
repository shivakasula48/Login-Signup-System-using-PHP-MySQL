<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default MySQL username
$password = "";     // Default MySQL password (leave blank for XAMPP)
$dbname = "user_auth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required!";
    } elseif ($password !== $confirm_password) {
        echo "Passwords do not match!";
    } else {
        // Check for duplicate email or username
        $check_user = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $check_user->bind_param("ss", $username, $email);
        $check_user->execute();
        $result = $check_user->get_result();

        if ($result->num_rows > 0) {
            echo "Username or Email already exists!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "Signup successful! You can now login.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $check_user->close();
    }
}

$conn->close();
?>
