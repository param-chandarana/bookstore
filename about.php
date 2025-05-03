<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
   header('Location: login.php');
   exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>About Us</h3>
   <p><a href="home.php">Home</a> / About</p>
</div>

<section class="about">
   <div class="flex">
      <div class="image">
         <img src="images/about-img.jpg" alt="Our Company">
      </div>
      <div class="content">
         <h3>Why Choose Us?</h3>
         <p>We offer a wide range of quality products at competitive prices. Our team is committed to providing exceptional customer service and ensuring a smooth shopping experience.</p>
         <p>With a secure checkout process, fast delivery, and easy returns, we are your go-to destination for all your shopping needs.</p>
         <a href="contact.php" class="btn">Contact Us</a>
      </div>
   </div>
</section>

<section class="reviews">
   <h1 class="title">Client Reviews</h1>
   <div class="box-container">
      <?php
      $clients = [
         ["img" => "images/pic-1.png", "name" => "Sarah Smith"],
         ["img" => "images/pic-2.png", "name" => "Michael Brown"],
         ["img" => "images/pic-3.png", "name" => "Emily Davis"],
         ["img" => "images/pic-4.png", "name" => "James Wilson"],
         ["img" => "images/pic-5.png", "name" => "Laura Johnson"],
         ["img" => "images/pic-6.png", "name" => "Robert Miller"]
      ];

      foreach ($clients as $client) {
         echo '
         <div class="box">
            <img src="'.$client["img"].'" alt="Review by '.$client["name"].'">
            <p>Excellent service and top-quality products. I had a great experience shopping here and will definitely return.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>'.$client["name"].'</h3>
         </div>';
      }
      ?>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
