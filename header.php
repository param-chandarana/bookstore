<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="fixed top-20 right-4 bg-white border-l-4 border-primary-500 rounded-lg shadow-lg p-4 max-w-sm animate-slide-up" style="z-index: 99999 !important; position: fixed !important;">
         <div class="flex items-center justify-between">
            <div class="flex items-center">
               <div class="w-2 h-2 bg-primary-500 rounded-full mr-3"></div>
               <span class="text-sage-800 font-medium inline-block p-1">'.$message.'</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove();" class="text-sage-400 hover:text-sage-600 transition-colors">
               <i class="fas fa-times text-sm"></i>
            </button>
         </div>
      </div>
      <script>
         setTimeout(() => {
            const msgs = document.querySelectorAll(".fixed.top-20");
            msgs.forEach(msg => msg.remove());
         }, 4000);
      </script>
      ';
   }
}
?>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu-overlay" class="fixed inset-0 bg-black/70 z-40 opacity-0 pointer-events-none transition-opacity duration-300"></div>

<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-lg border-b border-sage-100 shadow-sm">
   <!-- Top Bar (Login/Register) -->
   <?php if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])): ?>
      <div class="bg-gradient-primary text-white py-2">
         <div class="container mx-auto px-4">
            <div class="flex items-center justify-center text-sm">
               <span class="mr-2">Welcome to BookHaven!</span>
               <a href="login.php" class="hover:text-cream-200 transition-colors underline underline-offset-2">Login</a>
               <span class="mx-2">|</span>
               <a href="register.php" class="hover:text-cream-200 transition-colors underline underline-offset-2">Register</a>
            </div>
         </div>
      </div>
   <?php endif; ?>

   <!-- Main Navigation -->
   <nav class="container mx-auto px-4 py-3">
      <div class="flex items-center justify-between">
         <!-- Logo -->
         <a href="index.php" class="flex items-center space-x-2 group">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-primary rounded-lg sm:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
               <i class="fas fa-book-open text-white text-sm sm:text-lg"></i>
            </div>
            <div>
               <h1 class="text-lg sm:text-2xl font-serif font-bold text-sage-800 group-hover:text-primary-600 transition-colors">
                  Book<span class="text-primary-600">Haven</span>
               </h1>
               <p class="hidden sm:block text-xs text-sage-500 -mt-1">Literary Adventures Await</p>
            </div>
         </a>

         <!-- Desktop Navigation -->
         <div class="hidden lg:flex items-center space-x-6 xl:space-x-8">
            <a href="index.php" class="nav-link relative text-sage-700 hover:text-primary-600 font-medium transition-colors duration-300 group">
               <span>Home</span>
               <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="about.php" class="nav-link relative text-sage-700 hover:text-primary-600 font-medium transition-colors duration-300 group">
               <span>About</span>
               <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="shop.php" class="nav-link relative text-sage-700 hover:text-primary-600 font-medium transition-colors duration-300 group">
               <span>Shop</span>
               <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="contact.php" class="nav-link relative text-sage-700 hover:text-primary-600 font-medium transition-colors duration-300 group">
               <span>Contact</span>
               <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="orders.php" class="nav-link relative text-sage-700 hover:text-primary-600 font-medium transition-colors duration-300 group">
               <span>Orders</span>
               <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 group-hover:w-full transition-all duration-300"></span>
            </a>
         </div>

         <!-- Action Icons -->
         <div class="flex items-center space-x-2 sm:space-x-3">
            <!-- Search -->
            <a href="search_page.php" class="p-2 text-sage-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-300 group">
               <i class="fas fa-search text-base sm:text-lg group-hover:scale-110 transition-transform"></i>
            </a>

            <!-- Cart -->
            <a href="cart.php" class="relative p-2 text-sage-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-300 group">
               <i class="fas fa-shopping-cart text-base sm:text-lg group-hover:scale-110 transition-transform"></i>
               <?php
                  $stmt_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                  $stmt_cart->bind_param("i", $user_id);
                  $stmt_cart->execute();
                  $select_cart_number = $stmt_cart->get_result();
                  $cart_rows_number = $select_cart_number->num_rows;
                  $stmt_cart->close();
               ?>
               <?php if($cart_rows_number > 0): ?>
                  <span class="absolute -top-1 -right-1 bg-accent-500 text-white text-xs rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center font-medium">
                     <?php echo $cart_rows_number; ?>
                  </span>
               <?php endif; ?>
            </a>

            <!-- User Menu -->
            <div class="hidden sm:block relative">
               <button id="user-btn" class="p-2 text-sage-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-300 group">
                  <i class="fas fa-user text-base sm:text-lg group-hover:scale-110 transition-transform"></i>
               </button>
               
               <!-- User Dropdown -->
               <div id="user-dropdown" class="absolute right-0 top-full mt-2 w-64 bg-white rounded-xl shadow-xl border border-sage-100 opacity-0 pointer-events-none transform translate-y-2 transition-all duration-300">
                  <a href="profile.php" class="block p-4 border-b border-sage-100 hover:bg-sage-50 transition-colors">
                     <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-primary rounded-full flex items-center justify-center">
                           <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                           <p class="font-semibold text-sage-800"><?php echo $_SESSION['user_name'] ?? 'Guest'; ?></p>
                           <p class="text-sm text-sage-500"><?php echo $_SESSION['user_email'] ?? ''; ?></p>
                        </div>
                     </div>
                  </a>
                  <div class="p-2">
                     <a href="logout.php" class="flex items-center space-x-3 px-3 py-2 text-accent-600 hover:bg-accent-50 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                     </a>
                  </div>
               </div>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="lg:hidden p-2 text-sage-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-300">
               <div class="w-5 h-5 flex flex-col justify-center space-y-1">
                  <span class="block w-full h-0.5 bg-current transform transition-transform duration-300"></span>
                  <span class="block w-full h-0.5 bg-current transition-opacity duration-300"></span>
                  <span class="block w-full h-0.5 bg-current transform transition-transform duration-300"></span>
               </div>
            </button>
         </div>
      </div>
   </nav>

   <!-- Mobile Navigation Menu -->
   <div id="mobile-menu" class="lg:hidden fixed top-0 left-0 right-0 bg-white shadow-2xl transform -translate-y-full transition-transform duration-300 z-50 border-b border-sage-200">
      <!-- Mobile Menu Header -->
      <div class="flex items-center justify-between p-4 bg-gradient-to-r from-primary-50 to-sage-50 border-b border-sage-100">
         <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-primary rounded-lg flex items-center justify-center">
               <i class="fas fa-book-open text-white text-sm"></i>
            </div>
            <div>
               <h2 class="text-lg font-serif font-bold text-sage-800">BookHaven</h2>
               <p class="text-xs text-sage-500">Literary Adventures</p>
            </div>
         </div>
         <button id="mobile-menu-close" class="p-2 text-sage-600 hover:text-primary-600 hover:bg-white/50 rounded-lg transition-all duration-300">
            <i class="fas fa-times text-lg"></i>
         </button>
      </div>
      
      <!-- User Info Section (Mobile) -->
      <div class="p-4 bg-gradient-to-r from-primary-50 to-sage-50 border-b border-sage-100">
         <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-primary rounded-full flex items-center justify-center">
               <i class="fas fa-user text-white"></i>
            </div>
            <div>
               <p class="font-semibold text-sage-800"><?php echo $_SESSION['user_name'] ?? 'Guest'; ?></p>
               <p class="text-sm text-sage-500"><?php echo $_SESSION['user_email'] ?? 'Welcome!'; ?></p>
            </div>
         </div>
      </div>
      
      <!-- Navigation Links -->
      <nav class="p-4 space-y-2 max-h-96 overflow-y-auto">
         <a href="index.php" class="flex items-center space-x-3 px-4 py-3 text-sage-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-all duration-300">
            <i class="fas fa-home w-5 text-center"></i>
            <span class="font-medium">Home</span>
         </a>
         <a href="about.php" class="flex items-center space-x-3 px-4 py-3 text-sage-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-all duration-300">
            <i class="fas fa-info-circle w-5 text-center"></i>
            <span class="font-medium">About</span>
         </a>
         <a href="shop.php" class="flex items-center space-x-3 px-4 py-3 text-sage-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-all duration-300">
            <i class="fas fa-store w-5 text-center"></i>
            <span class="font-medium">Shop</span>
         </a>
         <a href="contact.php" class="flex items-center space-x-3 px-4 py-3 text-sage-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-all duration-300">
            <i class="fas fa-envelope w-5 text-center"></i>
            <span class="font-medium">Contact</span>
         </a>
         <a href="orders.php" class="flex items-center space-x-3 px-4 py-3 text-sage-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-all duration-300">
            <i class="fas fa-box w-5 text-center"></i>
            <span class="font-medium">My Orders</span>
         </a>
         <a href="search_page.php" class="flex items-center space-x-3 px-4 py-3 text-sage-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-all duration-300">
            <i class="fas fa-search w-5 text-center"></i>
            <span class="font-medium">Search Books</span>
         </a>
         <a href="cart.php" class="flex items-center space-x-3 px-4 py-3 text-sage-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-all duration-300 relative">
            <i class="fas fa-shopping-cart w-5 text-center"></i>
            <span class="font-medium">My Cart</span>
            <?php if($cart_rows_number > 0): ?>
               <span class="ml-auto bg-accent-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-medium">
                  <?php echo $cart_rows_number; ?>
               </span>
            <?php endif; ?>
         </a>
         
         <!-- Mobile Logout -->
         <div class="pt-4 mt-4 border-t border-sage-200">
            <a href="logout.php" class="flex items-center space-x-3 px-4 py-3 text-accent-600 hover:bg-accent-50 rounded-lg transition-all duration-300">
               <i class="fas fa-sign-out-alt w-5 text-center"></i>
               <span class="font-medium">Logout</span>
            </a>
         </div>
      </nav>
   </div>
</header>

<script>
   // Mobile Menu Toggle
   const mobileMenuBtn = document.getElementById('mobile-menu-btn');
   const mobileMenu = document.getElementById('mobile-menu');
   const mobileMenuClose = document.getElementById('mobile-menu-close');
   const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

   function toggleMobileMenu() {
      mobileMenu.classList.toggle('-translate-y-full');
      mobileMenuOverlay.classList.toggle('opacity-0');
      mobileMenuOverlay.classList.toggle('pointer-events-none');
      
      // Animate hamburger menu
      const spans = mobileMenuBtn.querySelectorAll('span');
      if (!mobileMenu.classList.contains('-translate-y-full')) {
         spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
         spans[1].style.opacity = '0';
         spans[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
      } else {
         spans[0].style.transform = 'none';
         spans[1].style.opacity = '1';
         spans[2].style.transform = 'none';
      }
   }

   mobileMenuBtn.addEventListener('click', toggleMobileMenu);
   mobileMenuClose.addEventListener('click', toggleMobileMenu);
   mobileMenuOverlay.addEventListener('click', toggleMobileMenu);

   // User Dropdown Toggle
   const userBtn = document.getElementById('user-btn');
   const userDropdown = document.getElementById('user-dropdown');

   userBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      userDropdown.classList.toggle('opacity-0');
      userDropdown.classList.toggle('pointer-events-none');
      userDropdown.classList.toggle('translate-y-2');
   });

   // Close dropdown when clicking outside
   document.addEventListener('click', function() {
      userDropdown.classList.add('opacity-0', 'pointer-events-none', 'translate-y-2');
   });

   userDropdown.addEventListener('click', function(e) {
      e.stopPropagation();
   });
</script>