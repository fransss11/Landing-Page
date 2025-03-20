<?php
include 'database.php';
// Fetch email from the 'info' table
$sql = "SELECT gmail FROM info ORDER BY id_info DESC LIMIT 1";
$result = $conn->query($sql);
$info = $result->fetch_assoc();
$to = $info['gmail'];
// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$project = $_POST['project'];
$subject = $_POST['subject'];
$message = $_POST['message'];
// Prepare email
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$body = "<h2>Contact Request</h2>
         <p><strong>Name:</strong> $name</p>
         <p><strong>Email:</strong> $email</p>
         <p><strong>Phone:</strong> $phone</p>
         <p><strong>Project:</strong> $project</p>
         <p><strong>Subject:</strong> $subject</p>
         <p><strong>Message:</strong><br>$message</p>";
// Send email
if (mail($to, $subject, $body, $headers)) {
    echo "<script>alert('Message sent successfully!'); window.location.href='contact.php';</script>";
} else {
    echo "<script>alert('Failed to send message.'); window.location.href='contact.php';</script>";
}
?>