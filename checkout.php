<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['order_btn'])) {
   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $address = $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
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
   <title>Checkout - BookHaven | Complete Your Order</title>

   <!-- Tailwind CSS -->
   <script src="https://cdn.tailwindcss.com"></script>
   
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

   <script>
      tailwind.config = {
         theme: {
            extend: {
               colors: {
                  primary: {
                     50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc', 400: '#38bdf8', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e',
                  },
                  accent: {
                     50: '#fef2f2', 100: '#fee2e2', 200: '#fecaca', 300: '#fca5a5', 400: '#f87171', 500: '#ef4444', 600: '#dc2626', 700: '#b91c1c', 800: '#991b1b', 900: '#7f1d1d',
                  },
                  sage: {
                     50: '#f6f7f6', 100: '#e3e8e3', 200: '#c7d2c7', 300: '#a4b5a4', 400: '#7d917d', 500: '#647064', 600: '#4f5a4f', 700: '#414941', 800: '#363c36', 900: '#2e322e',
                  },
                  cream: {
                     50: '#fefcf8', 100: '#fdf8f0', 200: '#fbf0e0', 300: '#f7e4ca', 400: '#f2d5ad', 500: '#ebc28f', 600: '#e0a971', 700: '#d18c54', 800: '#b8724a', 900: '#955d3e',
                  }
               },
               fontFamily: {
                  'sans': ['Inter', 'system-ui', 'sans-serif'],
                  'serif': ['Playfair Display', 'serif'],
               },
               backgroundImage: {
                  'gradient-primary': 'linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%)',
                  'gradient-sage': 'linear-gradient(135deg, #647064 0%, #4f5a4f 100%)',
               }
            }
         }
      }
   </script>
</head>
<body class="bg-cream-50 font-sans">

<?php include 'header.php'; ?>

<!-- Breadcrumb -->
<section class="bg-white border-b border-sage-100 py-6">
   <div class="container mx-auto px-4">
      <nav class="flex items-center space-x-2 text-sage-600">
         <a href="index.php" class="hover:text-primary-600 transition-colors">Home</a>
         <i class="fas fa-chevron-right text-sm"></i>
         <span class="text-sage-800 font-medium">Checkout</span>
      </nav>
   </div>
</section>

<!-- Checkout Content -->
<section class="py-12">
   <div class="container mx-auto px-4">
      <!-- Page Header -->
      <div class="text-center mb-12">
         <h1 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-4">
            Checkout
         </h1>
         <p class="text-xl text-sage-600 max-w-2xl mx-auto">
            Review your order and complete your purchase
         </p>
      </div>

      <div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-12">
         <!-- Order Summary -->
         <div class="lg:order-2">
            <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-8 sticky top-24">
               <h2 class="text-2xl font-serif font-bold text-sage-800 mb-6 flex items-center">
                  <i class="fas fa-shopping-bag text-primary-500 mr-3"></i>
                  Order Summary
               </h2>
               
               <div class="space-y-4 mb-6">
                  <?php  
                     $grand_total = 0;
                     $stmt_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                     $stmt_cart->bind_param("i", $user_id);
                     $stmt_cart->execute();
                     $select_cart = $stmt_cart->get_result();
                     
                     if ($select_cart->num_rows > 0) {
                        while ($fetch_cart = $select_cart->fetch_assoc()) {
                           $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
                           $grand_total += $total_price;
                  ?>
                  <div class="flex items-center justify-between py-3 border-b border-sage-100">
                     <div class="flex items-center space-x-3">
                        <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" 
                             alt="<?php echo $fetch_cart['name']; ?>"
                             class="w-12 h-16 object-cover rounded-lg">
                        <div>
                           <h3 class="font-medium text-sage-800 text-sm leading-tight">
                              <?php echo $fetch_cart['name']; ?>
                           </h3>
                           <p class="text-sage-500 text-sm">
                              ₹<?php echo $fetch_cart['price']; ?> × <?php echo $fetch_cart['quantity']; ?>
                           </p>
                        </div>
                     </div>
                     <span class="font-semibold text-sage-800">
                        ₹<?php echo number_format($total_price); ?>
                     </span>
                  </div>
                  <?php
                        }
                     } else {
                        echo '
                        <div class="text-center py-8">
                           <div class="w-16 h-16 bg-sage-100 rounded-full flex items-center justify-center mx-auto mb-4">
                              <i class="fas fa-shopping-cart text-2xl text-sage-400"></i>
                           </div>
                           <p class="text-sage-600">Your cart is empty.</p>
                           <a href="shop.php" class="inline-block mt-4 px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                              Continue Shopping
                           </a>
                        </div>';
                     }
                  ?>
               </div>
               
               <?php if ($grand_total > 0): ?>
               <!-- Order Total -->
               <div class="border-t border-sage-200 pt-6">
                  <div class="flex items-center justify-between text-xl font-bold text-sage-800">
                     <span>Grand Total:</span>
                     <span class="text-primary-600">₹<?php echo number_format($grand_total); ?></span>
                  </div>
               </div>
               <?php endif; ?>
            </div>
         </div>

         <!-- Checkout Form -->
         <div class="lg:order-1">
            <?php if ($grand_total > 0): ?>
            <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-8">
               <h2 class="text-2xl font-serif font-bold text-sage-800 mb-6 flex items-center">
                  <i class="fas fa-credit-card text-primary-500 mr-3"></i>
                  Billing Information
               </h2>
               
               <form action="" method="post" class="space-y-6">
                  <!-- Personal Information -->
                  <div class="grid md:grid-cols-2 gap-6">
                     <div>
                        <label class="block text-sm font-medium text-sage-700 mb-2">
                           Your Name *
                        </label>
                        <input type="text" name="name" required 
                               placeholder="Enter your full name"
                               class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                     </div>
                     
                     <div>
                        <label class="block text-sm font-medium text-sage-700 mb-2">
                           Phone Number *
                        </label>
                        <input type="tel" name="number" required 
                               placeholder="Enter your phone number"
                               class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                     </div>
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-sage-700 mb-2">
                        Email Address *
                     </label>
                     <input type="email" name="email" required 
                            placeholder="Enter your email address"
                            class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-sage-700 mb-2">
                        Payment Method *
                     </label>
                     <select name="method" required
                             class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Select payment method</option>
                        <option value="cash on delivery">Cash on Delivery</option>
                        <option value="credit card">Credit Card</option>
                        <option value="debit card">Debit Card</option>
                        <option value="UPI">UPI</option>
                        <option value="netbanking">Netbanking</option>
                     </select>
                  </div>

                  <!-- Shipping Address -->
                  <div class="pt-6 border-t border-sage-200">
                     <h3 class="text-lg font-semibold text-sage-800 mb-4">
                        Shipping Address
                     </h3>
                     
                     <div class="grid md:grid-cols-2 gap-6">
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              Address Line 1 *
                           </label>
                           <input type="text" name="flat" required 
                                  placeholder="Flat/House No., Building"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              Address Line 2 *
                           </label>
                           <input type="text" name="street" required 
                                  placeholder="Street, Area, Locality"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                     </div>
                     
                     <div class="grid md:grid-cols-2 gap-6 mt-6">
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              City *
                           </label>
                           <input type="text" name="city" required 
                                  placeholder="Enter city"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              State *
                           </label>
                           <input type="text" name="state" required 
                                  placeholder="Enter state"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                     </div>
                     
                     <div class="grid md:grid-cols-2 gap-6 mt-6">
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              Country *
                           </label>
                           <input type="text" name="country" required 
                                  placeholder="Enter country"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              PIN Code *
                           </label>
                           <input type="text" name="pin_code" required 
                                  placeholder="Enter PIN code"
                                  pattern="[0-9]{6}"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                     </div>
                  </div>
                  
                  <!-- Submit Button -->
                  <div class="pt-6">
                     <button type="submit" name="order_btn" 
                             class="w-full bg-primary-500 text-white font-semibold py-4 px-6 rounded-lg hover:bg-primary-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                        <i class="fas fa-lock text-lg"></i>
                        <span>Place Order Securely</span>
                     </button>
                  </div>
               </form>
            </div>
            <?php endif; ?>
         </div>
      </div>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout - BookHaven | Complete Your Order</title>

   <!-- Tailwind CSS -->
   <script src="https://cdn.tailwindcss.com"></script>
   
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

   <script>
      tailwind.config = {
         theme: {
            extend: {
               colors: {
                  primary: {
                     50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc', 400: '#38bdf8', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e',
                  },
                  accent: {
                     50: '#fef2f2', 100: '#fee2e2', 200: '#fecaca', 300: '#fca5a5', 400: '#f87171', 500: '#ef4444', 600: '#dc2626', 700: '#b91c1c', 800: '#991b1b', 900: '#7f1d1d',
                  },
                  sage: {
                     50: '#f6f7f6', 100: '#e3e8e3', 200: '#c7d2c7', 300: '#a4b5a4', 400: '#7d917d', 500: '#647064', 600: '#4f5a4f', 700: '#414941', 800: '#363c36', 900: '#2e322e',
                  },
                  cream: {
                     50: '#fefcf8', 100: '#fdf8f0', 200: '#fbf0e0', 300: '#f7e4ca', 400: '#f2d5ad', 500: '#ebc28f', 600: '#e0a971', 700: '#d18c54', 800: '#b8724a', 900: '#955d3e',
                  }
               },
               fontFamily: {
                  'sans': ['Inter', 'system-ui', 'sans-serif'],
                  'serif': ['Playfair Display', 'serif'],
               },
               backgroundImage: {
                  'gradient-primary': 'linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%)',
                  'gradient-sage': 'linear-gradient(135deg, #647064 0%, #4f5a4f 100%)',
               }
            }
         }
      }
   </script>
</head>
<body class="bg-cream-50 font-sans">

<?php include 'header.php'; ?>

<!-- Breadcrumb -->
<section class="bg-white border-b border-sage-100 py-6">
   <div class="container mx-auto px-4">
      <nav class="flex items-center space-x-2 text-sage-600">
         <a href="index.php" class="hover:text-primary-600 transition-colors">Home</a>
         <i class="fas fa-chevron-right text-sm"></i>
         <span class="text-sage-800 font-medium">Checkout</span>
      </nav>
   </div>
</section>

<!-- Checkout Content -->
<section class="py-12">
   <div class="container mx-auto px-4">
      <!-- Page Header -->
      <div class="text-center mb-12">
         <h1 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-4">
            Checkout
         </h1>
         <p class="text-xl text-sage-600 max-w-2xl mx-auto">
            Review your order and complete your purchase
         </p>
      </div>

      <div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-12">
         <!-- Order Summary -->
         <div class="lg:order-2">
            <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-8 sticky top-24">
               <h2 class="text-2xl font-serif font-bold text-sage-800 mb-6 flex items-center">
                  <i class="fas fa-shopping-bag text-primary-500 mr-3"></i>
                  Order Summary
               </h2>
               
               <div class="space-y-4 mb-6">
                  <?php  
                     $grand_total = 0;
                     $stmt_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                     $stmt_cart->bind_param("i", $user_id);
                     $stmt_cart->execute();
                     $select_cart = $stmt_cart->get_result();
                     
                     if ($select_cart->num_rows > 0) {
                        while ($fetch_cart = $select_cart->fetch_assoc()) {
                           $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
                           $grand_total += $total_price;
                  ?>
                  <div class="flex items-center justify-between py-3 border-b border-sage-100">
                     <div class="flex items-center space-x-3">
                        <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" 
                             alt="<?php echo $fetch_cart['name']; ?>"
                             class="w-12 h-16 object-cover rounded-lg">
                        <div>
                           <h3 class="font-medium text-sage-800 text-sm leading-tight">
                              <?php echo $fetch_cart['name']; ?>
                           </h3>
                           <p class="text-sage-500 text-sm">
                              ₹<?php echo $fetch_cart['price']; ?> × <?php echo $fetch_cart['quantity']; ?>
                           </p>
                        </div>
                     </div>
                     <span class="font-semibold text-sage-800">
                        ₹<?php echo number_format($total_price); ?>
                     </span>
                  </div>
                  <?php
                        }
                     } else {
                        echo '
                        <div class="text-center py-8">
                           <div class="w-16 h-16 bg-sage-100 rounded-full flex items-center justify-center mx-auto mb-4">
                              <i class="fas fa-shopping-cart text-2xl text-sage-400"></i>
                           </div>
                           <p class="text-sage-600">Your cart is empty.</p>
                           <a href="shop.php" class="inline-block mt-4 px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                              Continue Shopping
                           </a>
                        </div>';
                     }
                  ?>
               </div>
               
               <?php if ($grand_total > 0): ?>
               <!-- Order Total -->
               <div class="border-t border-sage-200 pt-6">
                  <div class="flex items-center justify-between text-xl font-bold text-sage-800">
                     <span>Grand Total:</span>
                     <span class="text-primary-600">₹<?php echo number_format($grand_total); ?></span>
                  </div>
               </div>
               <?php endif; ?>
            </div>
         </div>

         <!-- Checkout Form -->
         <div class="lg:order-1">
            <?php if ($grand_total > 0): ?>
            <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-8">
               <h2 class="text-2xl font-serif font-bold text-sage-800 mb-6 flex items-center">
                  <i class="fas fa-credit-card text-primary-500 mr-3"></i>
                  Billing Information
               </h2>
               
               <form action="" method="post" class="space-y-6">
                  <!-- Personal Information -->
                  <div class="grid md:grid-cols-2 gap-6">
                     <div>
                        <label class="block text-sm font-medium text-sage-700 mb-2">
                           Your Name *
                        </label>
                        <input type="text" name="name" required 
                               placeholder="Enter your full name"
                               class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                     </div>
                     
                     <div>
                        <label class="block text-sm font-medium text-sage-700 mb-2">
                           Phone Number *
                        </label>
                        <input type="tel" name="number" required 
                               placeholder="Enter your phone number"
                               class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                     </div>
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-sage-700 mb-2">
                        Email Address *
                     </label>
                     <input type="email" name="email" required 
                            placeholder="Enter your email address"
                            class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-sage-700 mb-2">
                        Payment Method *
                     </label>
                     <select name="method" required
                             class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Select payment method</option>
                        <option value="cash on delivery">Cash on Delivery</option>
                        <option value="credit card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="paytm">Paytm</option>
                     </select>
                  </div>

                  <!-- Shipping Address -->
                  <div class="pt-6 border-t border-sage-200">
                     <h3 class="text-lg font-semibold text-sage-800 mb-4">
                        Shipping Address
                     </h3>
                     
                     <div class="grid md:grid-cols-2 gap-6">
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              Address Line 1 *
                           </label>
                           <input type="text" name="flat" required 
                                  placeholder="Flat/House No., Building"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              Address Line 2 *
                           </label>
                           <input type="text" name="street" required 
                                  placeholder="Street, Area, Locality"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                     </div>
                     
                     <div class="grid md:grid-cols-2 gap-6 mt-6">
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              City *
                           </label>
                           <input type="text" name="city" required 
                                  placeholder="Enter city"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              State *
                           </label>
                           <input type="text" name="state" required 
                                  placeholder="Enter state"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                     </div>
                     
                     <div class="grid md:grid-cols-2 gap-6 mt-6">
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              Country *
                           </label>
                           <input type="text" name="country" required 
                                  placeholder="Enter country"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        
                        <div>
                           <label class="block text-sm font-medium text-sage-700 mb-2">
                              PIN Code *
                           </label>
                           <input type="text" name="pin_code" required 
                                  placeholder="Enter PIN code"
                                  pattern="[0-9]{6}"
                                  class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                     </div>
                  </div>
                  
                  <!-- Submit Button -->
                  <div class="pt-6">
                     <button type="submit" name="order_btn" 
                             class="w-full bg-primary-500 text-white font-semibold py-4 px-6 rounded-lg hover:bg-primary-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                        <i class="fas fa-lock text-lg"></i>
                        <span>Place Order Securely</span>
                     </button>
                  </div>
               </form>
            </div>
            <?php endif; ?>
         </div>
      </div>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
