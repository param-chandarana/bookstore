<!-- Admin Footer -->
<footer class="bg-sage-800 border-t border-sage-700 mt-auto">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
         
         <!-- Company Info -->
         <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-primary rounded-lg flex items-center justify-center">
               <i class="fas fa-book-open text-white text-sm"></i>
            </div>
            <div>
               <h3 class="text-white font-serif font-semibold">
                  Book<span class="text-primary-300">Haven</span>
               </h3>
               <p class="text-sage-300 text-xs">Admin Panel</p>
            </div>
         </div>

         <!-- Quick Links -->
         <div class="flex items-center space-x-6 text-sm">
            <a href="admin_page.php" class="text-sage-300 hover:text-white transition-colors duration-300 flex items-center space-x-1">
               <i class="fas fa-tachometer-alt text-xs"></i>
               <span>Dashboard</span>
            </a>
            <a href="profile.php" class="text-sage-300 hover:text-white transition-colors duration-300 flex items-center space-x-1">
               <i class="fas fa-user text-xs"></i>
               <span>Profile</span>
            </a>
            <a href="logout.php" class="text-sage-300 hover:text-white transition-colors duration-300 flex items-center space-x-1">
               <i class="fas fa-sign-out-alt text-xs"></i>
               <span>Logout</span>
            </a>
         </div>

         <!-- Copyright -->
         <div class="text-center md:text-right">
            <p class="text-sage-400 text-xs">
               © <?php echo date('Y'); ?> BookHaven. All rights reserved.
            </p>
            <p class="text-sage-500 text-xs mt-1">
               Admin Panel
            </p>
         </div>
      </div>

      <!-- Bottom Border -->
      <div class="mt-4 pt-4 border-t border-sage-700">
         <div class="flex flex-col sm:flex-row items-center justify-between space-y-2 sm:space-y-0 text-xs text-sage-400">
            <p>
               Powered by <span class="text-primary-300 font-medium">BookHaven Admin System</span>
            </p>
            <div class="flex items-center space-x-4">
               <span class="flex items-center space-x-1">
                  <i class="fas fa-clock text-primary-300"></i>
                  <span id="current-time"></span>
               </span>
            </div>
         </div>
      </div>
   </div>
</footer>

<!-- Real-time Clock Script -->
<script>
function updateTime() {
   const now = new Date();
   const timeString = now.toLocaleTimeString('en-US', { 
      hour12: true,
      hour: '2-digit',
      minute: '2-digit'
   });
   document.getElementById('current-time').textContent = timeString;
}

// Update time immediately and then every second
updateTime();
setInterval(updateTime, 1000);
</script>
