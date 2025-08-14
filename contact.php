<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit;
}

if (isset($_POST['send'])) {
   $name = $_POST['name'];
   $email = $_POST['email'];
   $number = $_POST['number'];
   $msg = $_POST['message'];

   $stmt_select = $conn->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $stmt_select->bind_param("ssss", $name, $email, $number, $msg);
   $stmt_select->execute();
   $select_message = $stmt_select->get_result();

   if ($select_message->num_rows > 0) {
      $message[] = 'Message has already been sent!';
   } else {
      $stmt_insert = $conn->prepare("INSERT INTO `message` (user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)");
      $stmt_insert->bind_param("issss", $user_id, $name, $email, $number, $msg);
      $stmt_insert->execute();
      $stmt_insert->close();
      $message[] = 'Message sent successfully!';
   }
   $stmt_select->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Contact Us</h3>
   <p><a href="index.php">Home</a> / Contact</p>
</div>

<section class="contact">
   <form action="" method="post">
      <h3>Send Us a Message</h3>
      <input type="text" name="name" required placeholder="Enter your name" class="box">
      <input type="email" name="email" required placeholder="Enter your email" class="box">
      <input type="number" name="number" required placeholder="Enter your phone number" class="box">
      <textarea name="message" class="box" placeholder="Enter your message" cols="30" rows="10" required></textarea>
      <input type="submit" value="Send Message" name="send" class="btn">
   </form>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
