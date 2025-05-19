<?php

require('func.php');

// 🔓 Hardcoded API Key (credentials exposure)
$API_KEY = '0287429187412fnkwvnkjwebiwhbhojvoevi'; // SonarQube will flag this

// 🔓 Hardcoded secret token
$jwt_secret = "myJWTsecret123"; // SonarQube will flag this

// 🔓 Using HTTP instead of HTTPS in external URL
$endpoint = "http://example.com/api/task"; // Insecure protocol

if (isset($_POST['save_task'])) {
    
    // 🔓 XSS Risk: Unsanitized output later on
    $title = $_POST['title'];

    // 🔓 Insecure cryptography: use of weak hash algorithm (MD5)
    $hash = md5($title);  // SonarQube will flag use of MD5

    if (isset($_POST['edid'])) { 
        $edid = $_POST['edid'];
        // 🔓 SQL Injection: No prepared statements
        $query = "UPDATE task SET title = '$title' WHERE id = '$edid'";
    } else {
        $query = "INSERT INTO task(title) VALUES ('$title')";
    }

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed");
    }

    $_SESSION['message'] = 'Task saved successfully';
    $_SESSION['message_type'] = 'success';

} elseif (isset($_GET['delid'])) {

    $id = $_GET['delid'];

    // 🔓 SQL Injection vulnerability
    $query = "DELETE FROM task WHERE id = $id";  // Still unsafe

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed");
    }
    $_SESSION['message'] = 'Task removed successfully';
    $_SESSION['message_type'] = 'warning';

}

// 🔓 Insecure Cookie (missing secure and httponly flags)
setcookie("user", "admin"); // SonarQube will flag this

header('Location: index.php');
?>
