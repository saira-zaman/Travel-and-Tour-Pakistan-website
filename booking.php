<?php
include_once "db.php"; // Database connection

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Receive form data safely
    $name        = trim($_POST['name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $date        = trim($_POST['date'] ?? '');
    $notes       = trim($_POST['notes'] ?? '');

    // Validate required fields
    if(empty($name) || empty($email) || empty($phone) || empty($destination) || empty($date)) {
        echo json_encode(["status"=>"error","message"=>"Please fill all required fields"]);
        exit;
    }

    // Validate email format
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status"=>"error","message"=>"Invalid email address"]);
        exit;
    }

    // Optional: XSS protection for notes
    $notes = htmlspecialchars($notes, ENT_QUOTES, 'UTF-8');

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO bookings (name,email,phone,destination,date,notes) VALUES (?,?,?,?,?,?)");

    if($stmt === false){
        echo json_encode(["status"=>"error","message"=>"Prepare failed: ".$conn->error]);
        exit;
    }

    $stmt->bind_param("ssssss", $name, $email, $phone, $destination, $date, $notes);

    // Execute statement and return JSON response
    if ($stmt->execute()) {
        echo json_encode(["status"=>"success","message"=>"Booking saved successfully!"]);
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
