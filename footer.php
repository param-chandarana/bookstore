<!-- Footer Section -->
<footer class="bg-gradient-to-br from-sage-800 via-sage-900 to-primary-900 text-white relative overflow-hidden">
   <!-- Background Decorations -->
   <div class="absolute inset-0">
      <div class="absolute top-20 left-20 w-40 h-40 bg-cream-300/5 rounded-full blur-3xl"></div>
      <div class="absolute bottom-20 right-32 w-56 h-56 bg-primary-400/5 rounded-full blur-3xl"></div>
      <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-accent-400/5 rounded-full blur-2xl"></div>
   </div>

   <div class="relative z-10">
      <!-- Main Footer Content -->
      <div class="container mx-auto px-4 py-16">
         <!-- Top Section with Logo and Newsletter -->
         <div class="grid lg:grid-cols-3 gap-12 mb-16">
            <!-- Brand Section -->
            <div class="lg:col-span-1">
               <div class="flex items-center space-x-3 mb-6">
                  <div class="w-12 h-12 bg-gradient-to-r from-cream-400 to-cream-300 rounded-xl flex items-center justify-center">
                     <i class="fas fa-book-open text-sage-800 text-xl"></i>
                  </div>
                  <div>
                     <h2 class="text-2xl font-serif font-bold">Book<span class="text-cream-300">Haven</span></h2>
                     <p class="text-sm text-blue-200">Literary Adventures Await</p>
                  </div>
               </div>
               <p class="text-blue-100 leading-relaxed mb-6">
                  Discover your next favourite book with our carefully curated collection. From timeless classics to contemporary bestsellers, we bring literary treasures to passionate readers worldwide.
               </p>
               
               <!-- Social Media -->
               <div class="flex space-x-4">
                  <a href="#" class="group w-10 h-10 bg-white/10 hover:bg-cream-400 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                     <i class="fab fa-facebook-f text-white group-hover:text-sage-800 transition-colors"></i>
                  </a>
                  <a href="#" class="group w-10 h-10 bg-white/10 hover:bg-cream-400 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                     <i class="fab fa-twitter text-white group-hover:text-sage-800 transition-colors"></i>
                  </a>
                  <a href="#" class="group w-10 h-10 bg-white/10 hover:bg-cream-400 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                     <i class="fab fa-instagram text-white group-hover:text-sage-800 transition-colors"></i>
                  </a>
                  <a href="#" class="group w-10 h-10 bg-white/10 hover:bg-cream-400 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                     <i class="fab fa-linkedin text-white group-hover:text-sage-800 transition-colors"></i>
                  </a>
               </div>
            </div>

            <!-- Newsletter Section -->
            <div class="lg:col-span-2">
               <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10">
                  <div class="text-center mb-6">
                     <h3 class="text-2xl font-serif font-bold text-cream-200 mb-2">Stay Updated</h3>
                     <p class="text-blue-100">Subscribe to our newsletter for the latest book recommendations and exclusive offers.</p>
                  </div>
                  <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                     <input type="email" placeholder="Enter your email address" 
                            class="flex-1 px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-cream-400 focus:border-transparent backdrop-blur-sm">
                     <button type="submit" 
                             class="px-8 py-3 bg-cream-400 text-sage-800 rounded-lg font-semibold hover:bg-cream-300 transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        Subscribe
                     </button>
                  </form>
               </div>
            </div>
         </div>

         <!-- Links Grid -->
         <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
            <!-- Quick Links -->
            <div>
               <h3 class="text-lg font-semibold text-cream-200 mb-6 flex items-center">
                  <i class="fas fa-link text-cream-400 mr-2"></i>
                  Quick Links
               </h3>
               <div class="space-y-3">
                  <a href="index.php" class="block text-blue-100 hover:text-cream-300 transition-colors duration-300 hover:translate-x-1 transform">
                     <i class="fas fa-home w-4 mr-2 text-cream-400"></i>Home
                  </a>
                  <a href="about.php" class="block text-blue-100 hover:text-cream-300 transition-colors duration-300 hover:translate-x-1 transform">
                     <i class="fas fa-info-circle w-4 mr-2 text-cream-400"></i>About
                  </a>
                  <a href="shop.php" class="block text-blue-100 hover:text-cream-300 transition-colors duration-300 hover:translate-x-1 transform">
                     <i class="fas fa-store w-4 mr-2 text-cream-400"></i>Shop
                  </a>
                  <a href="contact.php" class="block text-blue-100 hover:text-cream-300 transition-colors duration-300 hover:translate-x-1 transform">
                     <i class="fas fa-envelope w-4 mr-2 text-cream-400"></i>Contact
                  </a>
               </div>
            </div>

            <!-- Account Links -->
            <div>
               <h3 class="text-lg font-semibold text-cream-200 mb-6 flex items-center">
                  <i class="fas fa-user text-cream-400 mr-2"></i>
                  My Account
               </h3>
               <div class="space-y-3">
                  <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
                     <!-- Logged in user links -->
                     <a href="profile.php" class="block text-blue-100 hover:text-cream-300 transition-colors duration-300 hover:translate-x-1 transform">
                        <i class="fas fa-user-circle w-4 mr-2 text-cream-400"></i>My Profile
                     </a>
                     <a href="cart.php" class="block text-blue-100 hover:text-cream-300 transition-colors duration-300 hover:translate-x-1 transform">
                        <i class="fas fa-shopping-cart w-4 mr-2 text-cream-400"></i>Cart
                     </a>
                     <a href="orders.php" class="block text-blue-100 hover:text-cream-300 transition-colors duration-300 hover:translate-x-1 transform">
                        <i class="fas fa-box w-4 mr-2 text-cream-400"></i>Orders
                     </a>
                     <a href="logout.php" class="block text-blue-100 hover:text-cream-300 transition-colors duration-300 hover:translate-x-1 transform">
                        <i class="fas fa-sign-out-alt w-4 mr-2 text-cream-400"></i>Logout
                     </a>
                  <?php else: ?>
                     <!-- Guest user links -->
                     <a href="login.php" class="block text-blue-100 hover:text-cream-300 transition-colors duration-300 hover:translate-x-1 transform">
                        <i class="fas fa-sign-in-alt w-4 mr-2 text-cream-400"></i>Login
                     </a>
                     <a href="register.php" class="block text-blue-100 hover:text-cream-300 transition-colors duration-300 hover:translate-x-1 transform">
                        <i class="fas fa-user-plus w-4 mr-2 text-cream-400"></i>Register
                     </a>
                     <a href="cart.php" class="block text-blue-100 hover:text-cream-300 transition-colors duration-300 hover:translate-x-1 transform">
                        <i class="fas fa-shopping-cart w-4 mr-2 text-cream-400"></i>Cart
                     </a>
                  <?php endif; ?>
               </div>
            </div>

            <!-- Contact Info -->
            <div>
               <h3 class="text-lg font-semibold text-cream-200 mb-6 flex items-center">
                  <i class="fas fa-address-book text-cream-400 mr-2"></i>
                  Contact Info
               </h3>
               <div class="space-y-3">
                  <div class="flex items-center text-blue-100">
                     <i class="fas fa-phone w-4 mr-3 text-cream-400"></i>
                     <span>+91 9876543210</span>
                  </div>
                  <div class="flex items-center text-blue-100">
                     <i class="fas fa-envelope w-4 mr-3 text-cream-400"></i>
                     <span>hello@bookhaven.com</span>
                  </div>
                  <div class="flex items-start text-blue-100">
                     <i class="fas fa-map-marker-alt w-4 mr-3 text-cream-400 mt-1"></i>
                     <span>123 Literary Lane, Book District, Mumbai - 400001, India</span>
                  </div>
               </div>
            </div>

            <!-- Business Hours -->
            <div>
               <h3 class="text-lg font-semibold text-cream-200 mb-6 flex items-center">
                  <i class="fas fa-clock text-cream-400 mr-2"></i>
                  Store Hours
               </h3>
               <div class="space-y-3">
                  <div class="text-blue-100">
                     <div class="font-medium text-cream-300">Monday - Friday</div>
                     <div class="text-sm">9:00 AM - 8:00 PM</div>
                  </div>
                  <div class="text-blue-100">
                     <div class="font-medium text-cream-300">Saturday</div>
                     <div class="text-sm">10:00 AM - 6:00 PM</div>
                  </div>
               </div>
            </div>
         </div>

         <!-- Divider -->
         <div class="border-t border-white/20 mb-8"></div>

         <!-- Bottom Section -->
         <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="text-blue-100 text-center md:text-left">
               <p>&copy; <?php echo date('Y'); ?> <span class="text-cream-300 font-semibold">BookHaven</span>. All rights reserved.</p>
            </div>
            
            <!-- Additional Links -->
            <div class="flex space-x-6 text-sm">
               <a href="#" class="text-blue-100 hover:text-cream-300 transition-colors duration-300">Privacy Policy</a>
               <a href="#" class="text-blue-100 hover:text-cream-300 transition-colors duration-300">Terms of Service</a>
               <a href="#" class="text-blue-100 hover:text-cream-300 transition-colors duration-300">Shipping Info</a>
            </div>
         </div>
      </div>
   </div>

   <!-- Back to Top Button -->
   <button id="back-to-top" class="fixed bottom-8 right-8 w-12 h-12 bg-cream-400 text-sage-800 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 opacity-0 pointer-events-none z-40">
      <i class="fas fa-chevron-up"></i>
   </button>
</footer>

<!-- Back to Top Script -->
<script>
   // Back to Top functionality
   const backToTopBtn = document.getElementById('back-to-top');
   
   window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
         backToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
      } else {
         backToTopBtn.classList.add('opacity-0', 'pointer-events-none');
      }
   });
   
   backToTopBtn.addEventListener('click', function() {
      window.scrollTo({
         top: 0,
         behavior: 'smooth'
      });
   });
</script>