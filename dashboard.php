<?php
// ✅ Turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ Start session
session_start();

// ✅ Check login session
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// ✅ Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "user_auth";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Get user details using username
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Welcome <?php echo htmlspecialchars($user['username']); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            margin: 0;
            padding: 0;
        }

        .dashboard {
            max-width: 500px;
            margin: 7% auto;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        .dashboard h1 {
            color: #2c3e50;
        }

        .dashboard p {
            font-size: 1.1rem;
            color: #555;
            margin: 0.5rem 0;
        }

        .logout-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        @media (max-width: 600px) {
            .dashboard {
                margin: 15% 1rem;
            }
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?> 👋</h1>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p>You have successfully logged in to your secure dashboard.</p>
    <a class="logout-btn" href="logout.php">Logout</a>
</div>

</body>
</html>
