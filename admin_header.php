<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="fixed top-24 right-4 bg-white border-l-4 border-primary-500 rounded-lg shadow-lg p-4 max-w-sm animate-slide-up z-50">
         <div class="flex items-center justify-between">
            <div class="flex items-center">
               <div class="w-2 h-2 bg-primary-500 rounded-full mr-3"></div>
               <span class="text-sage-800 font-medium">'.$message.'</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove();" class="text-sage-400 hover:text-sage-600 transition-colors">
               <i class="fas fa-times text-sm"></i>
            </button>
         </div>
      </div>
      <script>
         setTimeout(() => {
            const msgs = document.querySelectorAll(".fixed.top-24");
            msgs.forEach(msg => msg.remove());
         }, 4000);
      </script>
      ';
   }
}
?>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu-overlay" class="fixed inset-0 bg-black/70 z-40 opacity-0 pointer-events-none transition-opacity duration-300"></div>

<!-- Admin Header -->
<header class="sticky top-0 z-50 bg-gradient-primary shadow-lg border-b border-primary-600">
   <div class="container mx-auto px-4 lg:px-6">
      <div class="flex items-center justify-between h-16 lg:h-18">
         
         <!-- Logo -->
         <a href="admin_page.php" class="flex items-center space-x-3 group">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
               <i class="fas fa-book-open text-xl text-white"></i>
            </div>
            <div class="hidden sm:block">
               <div class="text-2xl font-serif font-bold text-white">
                  Book<span class="text-primary-200">Haven</span>
               </div>
               <p class="text-xs text-primary-200 -mt-1">Admin Panel</p>
            </div>
         </a>

         <!-- Desktop Navigation -->
         <nav class="hidden lg:flex items-center space-x-1">
            <a href="admin_page.php" class="nav-link flex items-center px-4 py-2 rounded-lg text-white/90 hover:text-white hover:bg-white/10 transition-all duration-300 font-medium">
               <i class="fas fa-tachometer-alt mr-2"></i>
               Dashboard
            </a>
            <a href="admin_products.php" class="nav-link flex items-center px-4 py-2 rounded-lg text-white/90 hover:text-white hover:bg-white/10 transition-all duration-300 font-medium">
               <i class="fas fa-book mr-2"></i>
               Products
            </a>
            <a href="admin_orders.php" class="nav-link flex items-center px-4 py-2 rounded-lg text-white/90 hover:text-white hover:bg-white/10 transition-all duration-300 font-medium">
               <i class="fas fa-shopping-cart mr-2"></i>
               Orders
            </a>
            <a href="admin_users.php" class="nav-link flex items-center px-4 py-2 rounded-lg text-white/90 hover:text-white hover:bg-white/10 transition-all duration-300 font-medium">
               <i class="fas fa-users mr-2"></i>
               Users
            </a>
            <a href="admin_contacts.php" class="nav-link flex items-center px-4 py-2 rounded-lg text-white/90 hover:text-white hover:bg-white/10 transition-all duration-300 font-medium">
               <i class="fas fa-envelope mr-2"></i>
               Messages
            </a>
         </nav>

         <!-- Right Side Icons -->
         <div class="flex items-center space-x-2">
            <!-- Admin Info Dropdown -->
            <div class="relative">
               <button id="admin-menu-btn" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-white hover:bg-white/10 transition-all duration-300">
                  <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                     <i class="fas fa-user text-sm text-white"></i>
                  </div>
                  <div class="hidden md:block text-left">
                     <div class="text-sm font-medium text-white"><?php echo $_SESSION['admin_name']; ?></div>
                     <div class="text-xs text-primary-200">Administrator</div>
                  </div>
                  <i class="fas fa-chevron-down text-xs text-white/70"></i>
               </button>

               <!-- Dropdown Menu -->
               <div id="admin-dropdown" class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-sage-100 opacity-0 pointer-events-none transform scale-95 transition-all duration-200">
                  <a href="profile.php" class="block p-4 border-b border-sage-100 hover:bg-sage-50 transition-colors">
                     <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-primary rounded-full flex items-center justify-center">
                           <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                           <div class="font-semibold text-sage-800"><?php echo $_SESSION['admin_name']; ?></div>
                           <div class="text-sm text-sage-600"><?php echo $_SESSION['admin_email']; ?></div>
                           <div class="text-xs text-primary-600 bg-primary-50 px-2 py-1 rounded-full mt-1 inline-block">
                              <i class="fas fa-crown mr-1"></i>Administrator
                           </div>
                        </div>
                     </div>
                  </a>
                  <div class="p-2">
                     <a href="logout.php" class="flex items-center px-3 py-2 text-accent-600 hover:bg-accent-50 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt mr-3 text-accent-500"></i>
                        Log Out
                     </a>
                  </div>
               </div>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg text-white hover:bg-white/10 transition-colors">
               <i class="fas fa-bars text-xl"></i>
            </button>
         </div>
      </div>
   </div>

   <!-- Mobile Navigation -->
   <div id="mobile-nav" class="lg:hidden bg-primary-700 border-t border-primary-600 transform -translate-y-full transition-transform duration-300">
      <div class="container mx-auto px-4 py-4 space-y-2">
         <a href="admin_page.php" class="flex items-center px-4 py-3 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-all">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Dashboard
         </a>
         <a href="admin_products.php" class="flex items-center px-4 py-3 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-all">
            <i class="fas fa-book mr-3"></i>
            Products
         </a>
         <a href="admin_orders.php" class="flex items-center px-4 py-3 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-all">
            <i class="fas fa-shopping-cart mr-3"></i>
            Orders
         </a>
         <a href="admin_users.php" class="flex items-center px-4 py-3 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-all">
            <i class="fas fa-users mr-3"></i>
            Users
         </a>
         <a href="admin_contacts.php" class="flex items-center px-4 py-3 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-all">
            <i class="fas fa-envelope mr-3"></i>
            Messages
         </a>
         <div class="border-t border-primary-600 pt-4 mt-4">
            <a href="logout.php" class="flex items-center px-4 py-3 text-accent-200 hover:text-accent-100 hover:bg-white/10 rounded-lg transition-all">
               <i class="fas fa-sign-out-alt mr-3"></i>
               Log Out
            </a>
         </div>
      </div>
   </div>
</header>

<!-- JavaScript for Interactive Elements -->
<script>
   // Mobile menu toggle
   document.getElementById('mobile-menu-btn').addEventListener('click', function() {
      const mobileNav = document.getElementById('mobile-nav');
      const overlay = document.getElementById('mobile-menu-overlay');
      
      if (mobileNav.style.transform === 'translateY(0px)' || mobileNav.style.transform === '') {
         mobileNav.style.transform = 'translateY(-100%)';
         overlay.classList.remove('opacity-100', 'pointer-events-auto');
         overlay.classList.add('opacity-0', 'pointer-events-none');
      } else {
         mobileNav.style.transform = 'translateY(0px)';
         overlay.classList.remove('opacity-0', 'pointer-events-none');
         overlay.classList.add('opacity-100', 'pointer-events-auto');
      }
   });

   // Admin dropdown toggle
   document.getElementById('admin-menu-btn').addEventListener('click', function() {
      const dropdown = document.getElementById('admin-dropdown');
      
      if (dropdown.classList.contains('opacity-0')) {
         dropdown.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
         dropdown.classList.add('opacity-100', 'pointer-events-auto', 'scale-100');
      } else {
         dropdown.classList.remove('opacity-100', 'pointer-events-auto', 'scale-100');
         dropdown.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
      }
   });

   // Close dropdown when clicking outside
   document.addEventListener('click', function(event) {
      const dropdown = document.getElementById('admin-dropdown');
      const button = document.getElementById('admin-menu-btn');
      
      if (!button.contains(event.target) && !dropdown.contains(event.target)) {
         dropdown.classList.remove('opacity-100', 'pointer-events-auto', 'scale-100');
         dropdown.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
      }
   });

   // Close mobile menu when clicking overlay
   document.getElementById('mobile-menu-overlay').addEventListener('click', function() {
      const mobileNav = document.getElementById('mobile-nav');
      const overlay = document.getElementById('mobile-menu-overlay');
      
      mobileNav.style.transform = 'translateY(-100%)';
      overlay.classList.remove('opacity-100', 'pointer-events-auto');
      overlay.classList.add('opacity-0', 'pointer-events-none');
   });

   // Highlight active page
   const currentPage = window.location.pathname.split('/').pop();
   const navLinks = document.querySelectorAll('.nav-link');
   
   navLinks.forEach(link => {
      if (link.getAttribute('href') === currentPage) {
         link.classList.add('bg-white/20', 'text-white');
         link.classList.remove('text-white/90');
      }
   });
</script>