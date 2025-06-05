<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
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
    $username_email = $_POST['username_email'];
    $password = $_POST['password'];

    // Validate fields
    if (empty($username_email) || empty($password)) {
        echo "Both fields are required!";
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username_email, $username_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
    // Start session
    session_start();
    $_SESSION['username'] = $user['username'];
    header("Location: dashboard.php");
    exit(); // Ensures no further code is executed
}
else {
                echo "Incorrect password!";
            }
        } else {
            echo "User does not exist!";
        }

        $stmt->close();
    }
}

$conn->close();
?>
