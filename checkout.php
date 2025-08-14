<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['order_btn'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products = [];

   $stmt_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $stmt_cart->bind_param("i", $user_id);
   $stmt_cart->execute();
   $cart_query = $stmt_cart->get_result();
   if ($cart_query->num_rows > 0) {
      while ($cart_item = $cart_query->fetch_assoc()) {
         $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(', ', $cart_products);

   $stmt_order = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
   $stmt_order->bind_param("ssssssi", $name, $number, $email, $method, $address, $total_products, $cart_total);
   $stmt_order->execute();
   $order_query = $stmt_order->get_result();

   if ($cart_total == 0) {
      $message[] = 'Your cart is empty.';
   } else {
      if ($order_query->num_rows > 0) {
         $message[] = 'Order already placed!';
      } else {
         $stmt_insert = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
         $stmt_insert->bind_param("issssssss", $user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on);
         $stmt_insert->execute();
         $stmt_insert->close();
         $message[] = 'Order placed successfully!';
         $stmt_delete = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $stmt_delete->bind_param("i", $user_id);
         $stmt_delete->execute();
         $stmt_delete->close();
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Checkout</h3>
   <p><a href="index.php">Home</a> / Checkout</p>
</div>

<section class="display-order">
   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
      if (mysqli_num_rows($select_cart) > 0) {
         while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
            $grand_total += $total_price;
   ?>
   <p><?php echo $fetch_cart['name']; ?> <span>(<?php echo '$' . $fetch_cart['price'] . '  × ' . $fetch_cart['quantity']; ?>)</span></p>
   <?php
         }
      } else {
         echo '<p class="empty">Your cart is empty.</p>';
      }
   ?>
   <div class="grand-total">Grand Total: <span>₹<?php echo $grand_total; ?></span></div>
</section>

<section class="checkout">
   <form action="" method="post">
      <h3>Place Your Order</h3>
      <div class="flex">
         <div class="inputBox">
            <span>Your Name:</span>
            <input type="text" name="name" required placeholder="Enter your name">
         </div>
         <div class="inputBox">
            <span>Your Phone Number:</span>
            <input type="number" name="number" required placeholder="Enter your phone number">
         </div>
         <div class="inputBox">
            <span>Your Email:</span>
            <input type="email" name="email" required placeholder="Enter your email">
         </div>
         <div class="inputBox">
            <span>Payment Method:</span>
            <select name="method">
               <option value="cash on delivery">Cash on Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="paypal">PayPal</option>
               <option value="paytm">Paytm</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Address Line 01:</span>
            <input type="text" name="flat" required placeholder="e.g., Flat No. 101">
         </div>
         <div class="inputBox">
            <span>Address Line 02:</span>
            <input type="text" name="street" required placeholder="e.g., MG Road">
         </div>
         <div class="inputBox">
            <span>City:</span>
            <input type="text" name="city" required placeholder="e.g., Mumbai">
         </div>
         <div class="inputBox">
            <span>State:</span>
            <input type="text" name="state" required placeholder="e.g., Maharashtra">
         </div>
         <div class="inputBox">
            <span>Country:</span>
            <input type="text" name="country" required placeholder="e.g., India">
         </div>
         <div class="inputBox">
            <span>PIN Code:</span>
            <input type="number" min="0" name="pin_code" required placeholder="e.g., 400001">
         </div>
      </div>
      <input type="submit" value="Order Now" class="btn" name="order_btn">
   </form>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
