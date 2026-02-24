<?php
include_once "db.php"; // Database connection

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Receive form data safely
    $cname  = trim($_POST['cname'] ?? '');
    $cemail = trim($_POST['cemail'] ?? '');
    $cmsg   = trim($_POST['cmsg'] ?? '');

    // Validate required fields
    if(empty($cname) || empty($cemail) || empty($cmsg)) {
        echo json_encode(["status"=>"error","message"=>"Please fill all required fields"]);
        exit;
    }

    // Validate email format
    if(!filter_var($cemail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status"=>"error","message"=>"Invalid email address"]);
        exit;
    }

    // Optional: XSS protection
    $cname  = htmlspecialchars($cname, ENT_QUOTES, 'UTF-8');
    $cmsg   = htmlspecialchars($cmsg, ENT_QUOTES, 'UTF-8');

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO contacts (cname, cemail, cmsg) VALUES (?,?,?)");

    if($stmt === false){
        echo json_encode(["status"=>"error","message"=>"Prepare failed: ".$conn->error]);
        exit;
    }

    $stmt->bind_param("sss", $cname, $cemail, $cmsg);

    // Execute statement and return JSON response
    if ($stmt->execute()) {
        echo json_encode(["status"=>"success","message"=>"Message sent successfully!"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Error: ".$stmt->error]);
    }

    // Close connections
    $stmt->close();
    $conn->close();

} else {
    echo json_encode(["status"=>"error","message"=>"Invalid request method"]);
}
?>
