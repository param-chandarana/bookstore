<?php

include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('location:login.php');
    exit();
}

if (isset($_POST['add_to_cart'])) {

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = (int) $_POST['product_quantity'];

   $stmt_check = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $stmt_check->bind_param("si", $product_name, $user_id);
   $stmt_check->execute();
   $check_cart_numbers = $stmt_check->get_result();

   if ($check_cart_numbers->num_rows > 0) {
      $message[] = 'Already added to cart!';
   } else {
      $stmt_insert = $conn->prepare("INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES(?, ?, ?, ?, ?)");
      $stmt_insert->bind_param("isdis", $user_id, $product_name, $product_price, $product_quantity, $product_image);
      $stmt_insert->execute();
      $stmt_insert->close();
      $message[] = 'Product added to cart!';
   }
   $stmt_check->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>BookHaven - Curated Books for Every Reader</title>

   <!-- Tailwind CSS -->
   <script src="https://cdn.tailwindcss.com"></script>
   
   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

   <script>
      tailwind.config = {
         theme: {
            extend: {
               colors: {
                  primary: {
                     50: '#f0f9ff',
                     100: '#e0f2fe',
                     200: '#bae6fd',
                     300: '#7dd3fc',
                     400: '#38bdf8',
                     500: '#0ea5e9',
                     600: '#0284c7',
                     700: '#0369a1',
                     800: '#075985',
                     900: '#0c4a6e',
                  },
                  accent: {
                     50: '#fef2f2',
                     100: '#fee2e2',
                     200: '#fecaca',
                     300: '#fca5a5',
                     400: '#f87171',
                     500: '#ef4444',
                     600: '#dc2626',
                     700: '#b91c1c',
                     800: '#991b1b',
                     900: '#7f1d1d',
                  },
                  sage: {
                     50: '#f6f7f6',
                     100: '#e3e8e3',
                     200: '#c7d2c7',
                     300: '#a4b5a4',
                     400: '#7d917d',
                     500: '#647064',
                     600: '#4f5a4f',
                     700: '#414941',
                     800: '#363c36',
                     900: '#2e322e',
                  },
                  cream: {
                     50: '#fefcf8',
                     100: '#fdf8f0',
                     200: '#fbf0e0',
                     300: '#f7e4ca',
                     400: '#f2d5ad',
                     500: '#ebc28f',
                     600: '#e0a971',
                     700: '#d18c54',
                     800: '#b8724a',
                     900: '#955d3e',
                  }
               },
               fontFamily: {
                  'sans': ['Inter', 'system-ui', 'sans-serif'],
                  'serif': ['Playfair Display', 'serif'],
               },
               animation: {
                  'fade-in': 'fadeIn 0.5s ease-in-out',
                  'slide-up': 'slideUp 0.6s ease-out',
                  'float': 'float 3s ease-in-out infinite',
               },
               backgroundImage: {
                  'gradient-primary': 'linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%)',
                  'gradient-sage': 'linear-gradient(135deg, #647064 0%, #4f5a4f 100%)',
                  'gradient-cream': 'linear-gradient(135deg, #f7e4ca 0%, #ebc28f 100%)',
               }
            }
         }
      }
   </script>

   <style>
      @keyframes fadeIn {
         from { opacity: 0; transform: translateY(20px); }
         to { opacity: 1; transform: translateY(0); }
      }
      @keyframes slideUp {
         from { opacity: 0; transform: translateY(30px); }
         to { opacity: 1; transform: translateY(0); }
      }
      @keyframes float {
         0%, 100% { transform: translateY(0px); }
         50% { transform: translateY(-10px); }
      }
   </style>
</head>
<body class="bg-cream-50 font-sans">

<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="relative min-h-screen bg-gradient-to-br from-primary-600 via-primary-700 to-sage-800 overflow-hidden">
   <!-- Background Elements -->
   <div class="absolute inset-0 bg-black/20"></div>
   <div class="absolute top-20 left-10 w-32 h-32 bg-cream-300/20 rounded-full blur-xl animate-float"></div>
   <div class="absolute bottom-32 right-20 w-48 h-48 bg-accent-400/20 rounded-full blur-2xl animate-float" style="animation-delay: 1s;"></div>
   
   <!-- Content -->
   <div class="relative z-10 container mx-auto px-4 py-32 lg:py-48 flex items-center min-h-screen">
      <div class="grid lg:grid-cols-2 gap-12 items-center w-full">
         <div class="text-white animate-slide-up">
            <span class="inline-block px-4 py-2 bg-cream-400/20 rounded-full text-cream-100 text-sm font-medium mb-6 backdrop-blur-sm">
               📚 Curated Collection
            </span>
            <h1 class="text-5xl lg:text-7xl font-serif font-bold leading-tight mb-6">
               Discover Your Next
               <span class="text-transparent bg-clip-text bg-gradient-to-r from-cream-200 to-cream-400">
                  Literary Adventure
               </span>
            </h1>
            <p class="text-xl text-blue-100 mb-8 leading-relaxed max-w-lg">
               Hand-picked books delivered to your door. From timeless classics to contemporary masterpieces, find your perfect read in our carefully curated collection.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
               <a href="about.php" class="group inline-flex items-center px-8 py-4 bg-cream-400 text-sage-800 rounded-full font-semibold hover:bg-cream-300 transition-all duration-300 hover:scale-105 hover:shadow-xl">
                  <span>Discover More</span>
                  <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
               </a>
               <a href="shop.php" class="group inline-flex items-center px-8 py-4 border-2 border-cream-300 text-cream-100 rounded-full font-semibold hover:bg-cream-300 hover:text-sage-800 transition-all duration-300">
                  <span>Browse Books</span>
                  <i class="fas fa-book-open ml-2"></i>
               </a>
            </div>
         </div>
         
         <!-- Hero Image/Animation -->
         <div class="relative hidden lg:block animate-fade-in" style="animation-delay: 0.3s;">
            <div class="relative w-full h-96 lg:h-[500px]">
               <div class="absolute inset-0 bg-gradient-to-r from-cream-400/30 to-accent-400/30 rounded-3xl backdrop-blur-sm"></div>
               <div class="absolute top-8 left-8 w-16 h-16 bg-cream-300 rounded-2xl rotate-12 shadow-xl"></div>
               <div class="absolute top-20 right-12 w-12 h-12 bg-accent-400 rounded-xl -rotate-12 shadow-lg"></div>
               <div class="absolute bottom-16 left-16 w-20 h-20 bg-sage-400 rounded-full shadow-xl"></div>
               <div class="flex items-center justify-center h-full">
                  <i class="fas fa-book-open text-8xl text-cream-200/80"></i>
               </div>
            </div>
         </div>
      </div>
   </div>
   
   <!-- Scroll Indicator -->
   <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-cream-200 animate-bounce">
      <i class="fas fa-chevron-down text-xl"></i>
   </div>
</section>

<!-- Featured Products Section -->
<section class="py-20 bg-white">
   <div class="container mx-auto px-4">
      <!-- Section Header -->
      <div class="text-center mb-16">
         <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-4">
            Featured Collection
         </span>
         <h2 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-6">
           Latest Literary Treasures
         </h2>
         <p class="text-xl text-sage-600 max-w-2xl mx-auto">
            Discover our handpicked selection of books that will transport you to new worlds and expand your horizons.
         </p>
      </div>

      <!-- Products Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
         <?php  
            $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('Query failed');
            if (mysqli_num_rows($select_products) > 0) {
               while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
         <form action="" method="post" class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-sage-100 hover:border-primary-200 hover:-translate-y-2">
            <!-- Product Image -->
            <div class="relative overflow-hidden bg-gradient-to-br from-sage-50 to-cream-100 h-80">
               <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                    src="uploaded_img/<?php echo $fetch_products['image']; ?>" 
                    alt="<?php echo htmlspecialchars($fetch_products['name']); ?>">
               <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
               
               <!-- Price Badge -->
               <div class="absolute top-4 left-4 bg-accent-500 text-white px-3 py-1 rounded-full font-semibold text-sm shadow-lg">
                  ₹<?php echo htmlspecialchars($fetch_products['price']); ?>
               </div>
            </div>
            
            <!-- Product Info -->
            <div class="p-6">
               <h3 class="text-xl font-serif font-semibold text-sage-800 mb-3 line-clamp-2 group-hover:text-primary-700 transition-colors">
                  <?php echo htmlspecialchars($fetch_products['name']); ?>
               </h3>
               
               <!-- Quantity and Add to Cart -->
               <div class="flex items-center gap-4 mt-4">
                  <div class="flex items-center border border-sage-200 rounded-lg overflow-hidden">
                     <label class="sr-only">Quantity</label>
                     <input type="number" min="1" name="product_quantity" value="1" 
                            class="w-16 px-3 py-2 text-center bg-sage-50 border-0 focus:outline-none focus:bg-primary-50 transition-colors">
                  </div>
                  <button type="submit" name="add_to_cart" 
                          class="flex-1 bg-gradient-primary text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2">
                     <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                  </button>
               </div>
               
               <!-- Hidden Fields -->
               <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['name']); ?>">
               <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($fetch_products['price']); ?>">
               <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($fetch_products['image']); ?>">
            </div>
         </form>
         <?php
               }
            } else {
               echo '<div class="col-span-full text-center py-16">';
               echo '<i class="fas fa-book text-6xl text-sage-300 mb-4"></i>';
               echo '<p class="text-xl text-sage-600">No products available yet. Check back soon!</p>';
               echo '</div>';
            }
         ?>
      </div>

      <!-- Load More Button -->
      <div class="text-center">
         <a href="shop.php" class="inline-flex items-center px-8 py-4 bg-sage-600 text-white rounded-xl font-semibold hover:bg-sage-700 transition-all duration-300 hover:scale-105 hover:shadow-lg">
            <span>Explore All Books</span>
            <i class="fas fa-arrow-right ml-2"></i>
         </a>
      </div>
   </div>
</section>

<!-- About Section -->
<section class="py-20 bg-gradient-to-br from-sage-50 to-cream-100">
   <div class="container mx-auto px-4">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
         <!-- Image Side -->
         <div class="relative">
            <div class="relative bg-white p-8 rounded-3xl shadow-2xl">
               <img src="images/about-img.jpg" alt="About BookHaven" 
                    class="w-full h-96 object-cover rounded-2xl">
               
               <!-- Floating Elements -->
               <div class="absolute -top-6 -right-6 w-24 h-24 bg-primary-500 rounded-2xl shadow-xl flex items-center justify-center">
                  <i class="fas fa-quote-left text-2xl text-white"></i>
               </div>
               <div class="absolute -bottom-4 -left-4 w-32 h-20 bg-accent-500 rounded-xl shadow-lg flex items-center justify-center">
                  <span class="text-white font-bold text-lg">Since 2020</span>
               </div>
            </div>
            
            <!-- Background Decoration -->
            <div class="absolute -z-10 top-8 left-8 w-full h-full bg-gradient-sage rounded-3xl opacity-20"></div>
         </div>
         
         <!-- Content Side -->
         <div class="space-y-8">
            <div>
               <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-4">
                  Our Story
               </span>
               <h2 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-6">
                  Passionate About 
                  <span class="text-transparent bg-clip-text bg-gradient-primary">Books & Readers</span>
               </h2>
               <p class="text-lg text-sage-600 leading-relaxed mb-6">
                  At BookHaven, we believe that every book has the power to transform lives. Our mission is to connect passionate readers with carefully curated literary treasures that inspire, educate, and entertain.
               </p>
               <p class="text-lg text-sage-600 leading-relaxed">
                  From contemporary bestsellers to timeless classics, we handpick each title to ensure you discover your next favourite read. Join our community of book lovers and embark on countless adventures through the pages of extraordinary stories.
               </p>
            </div>
            
            <!-- Features -->
            <div class="grid grid-cols-2 gap-6">
               <div class="flex items-center space-x-3">
                  <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                     <i class="fas fa-shipping-fast text-primary-600"></i>
                  </div>
                  <div>
                     <h4 class="font-semibold text-sage-800">Fast Delivery</h4>
                     <p class="text-sm text-sage-600">Within 3-5 days</p>
                  </div>
               </div>
               
               <div class="flex items-center space-x-3">
                  <div class="w-12 h-12 bg-accent-100 rounded-full flex items-center justify-center">
                     <i class="fas fa-heart text-accent-600"></i>
                  </div>
                  <div>
                     <h4 class="font-semibold text-sage-800">Curated Selection</h4>
                     <p class="text-sm text-sage-600">Hand-picked books</p>
                  </div>
               </div>
            </div>
            
            <a href="about.php" class="inline-flex items-center px-8 py-4 bg-gradient-primary text-white rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105">
               <span>Learn More About Us</span>
               <i class="fas fa-arrow-right ml-2"></i>
            </a>
         </div>
      </div>
   </div>
</section>

<!-- Contact CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary-600 via-primary-700 to-sage-800 relative overflow-hidden">
   <!-- Background Elements -->
   <div class="absolute inset-0">
      <div class="absolute top-20 left-20 w-32 h-32 bg-cream-300/10 rounded-full blur-xl"></div>
      <div class="absolute bottom-20 right-32 w-48 h-48 bg-accent-400/10 rounded-full blur-2xl"></div>
   </div>
   
   <div class="container mx-auto px-4 relative z-10">
      <div class="max-w-4xl mx-auto text-center">
         <!-- Content -->
         <div class="mb-12">
            <span class="inline-block px-4 py-2 bg-cream-400/20 text-cream-100 rounded-full text-sm font-medium mb-6 backdrop-blur-sm">
               Get in Touch
            </span>
            <h2 class="text-4xl lg:text-5xl font-serif font-bold text-white mb-6">
               Have Questions About Our Books?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto leading-relaxed">
               Our team of book enthusiasts is here to help you find your perfect read. Whether you need recommendations or have questions about orders, we're just a message away.
            </p>
         </div>
         
         <!-- Action Buttons -->
         <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
            <a href="contact.php" class="group inline-flex items-center px-8 py-4 bg-cream-400 text-sage-800 rounded-full font-semibold hover:bg-cream-300 transition-all duration-300 hover:scale-105 hover:shadow-xl">
               <i class="fas fa-envelope mr-3"></i>
               <span>Contact Us</span>
               <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
            
            <div class="flex items-center space-x-6 text-cream-200">
               <div class="flex items-center space-x-2">
                  <i class="fas fa-phone text-lg"></i>
                  <span class="font-medium">+91 9876543210</span>
               </div>
               <div class="flex items-center space-x-2">
                  <i class="fas fa-clock text-lg"></i>
                  <span class="font-medium">9 AM - 8 PM</span>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
