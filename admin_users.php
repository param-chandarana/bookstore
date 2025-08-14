<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_GET['delete'])){
   $delete_id = intval($_GET['delete']);
   $stmt = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $stmt->bind_param("i", $delete_id);
   $stmt->execute();
   $stmt->close();
   header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Management - Admin Panel</title>

   <!-- Tailwind CSS -->
   <script src="https://cdn.tailwindcss.com"></script>
   
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
   <!-- Google Fonts -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

   <script>
      tailwind.config = {
         theme: {
            extend: {
               colors: {
                  'primary': {
                     50: '#eff6ff',
                     100: '#dbeafe', 
                     200: '#bfdbfe',
                     300: '#93c5fd',
                     400: '#60a5fa',
                     500: '#3b82f6',
                     600: '#2563eb',
                     700: '#1d4ed8',
                     800: '#1e40af',
                     900: '#1e3a8a'
                  },
                  'sage': {
                     50: '#f8faf8',
                     100: '#f1f5f1', 
                     200: '#e2ebe2',
                     300: '#c8d8c8',
                     400: '#9bb99b',
                     500: '#647064',
                     600: '#4a5d4a',
                     700: '#3d4f3d',
                     800: '#2e322e'
                  },
                  'cream': {
                     50: '#fefcf8',
                     100: '#fdf9f1',
                     200: '#fbf2e3',
                     300: '#f7e6c7',
                     400: '#f0d49e',
                     500: '#e6bc7c',
                     600: '#d4a574',
                     700: '#b8926c',
                     800: '#955d3e'
                  },
                  'accent': {
                     500: '#ef4444',
                     600: '#dc2626',
                     700: '#b91c1c'
                  }
               },
               backgroundImage: {
                  'gradient-primary': 'linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%)',
                  'gradient-sage': 'linear-gradient(135deg, #9bb99b 0%, #647064 100%)'
               },
               fontFamily: {
                  'serif': ['Playfair Display', 'serif'],
                  'sans': ['Inter', 'sans-serif']
               }
            }
         }
      }
   </script>

   <style>
      html { font-size: 16px !important; }
   </style>
</head>
<body class="bg-cream-50 font-sans min-h-screen flex flex-col">
   
<?php include 'admin_header.php'; ?>

<!-- Main Content -->
<div class="flex-1 pt-24 pb-12">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Page Header -->
      <div class="mb-8">
         <div class="flex items-center space-x-4 mb-6">
            <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center shadow-lg">
               <i class="fas fa-users text-white text-xl"></i>
            </div>
            <div>
               <h1 class="text-3xl font-serif font-bold text-sage-800">User Management</h1>
               <p class="text-sage-600 mt-1">Manage all registered users and their accounts</p>
            </div>
         </div>

         <!-- Stats Cards -->
         <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <?php
            $total_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `users`"));
            $admin_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'"));
            $regular_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'"));
            ?>
            
            <div class="bg-white rounded-xl border border-sage-100 p-6 shadow-sm hover:shadow-md transition-shadow">
               <div class="flex items-center justify-between">
                  <div>
                     <h3 class="text-sm font-medium text-sage-600 uppercase tracking-wide">Total Users</h3>
                     <p class="text-3xl font-bold text-sage-800"><?php echo $total_users; ?></p>
                  </div>
                  <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                     <i class="fas fa-users text-blue-600 text-xl"></i>
                  </div>
               </div>
            </div>

            <div class="bg-white rounded-xl border border-sage-100 p-6 shadow-sm hover:shadow-md transition-shadow">
               <div class="flex items-center justify-between">
                  <div>
                     <h3 class="text-sm font-medium text-sage-600 uppercase tracking-wide">Admin Users</h3>
                     <p class="text-3xl font-bold text-sage-800"><?php echo $admin_users; ?></p>
                  </div>
                  <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                     <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                  </div>
               </div>
            </div>

            <div class="bg-white rounded-xl border border-sage-100 p-6 shadow-sm hover:shadow-md transition-shadow">
               <div class="flex items-center justify-between">
                  <div>
                     <h3 class="text-sm font-medium text-sage-600 uppercase tracking-wide">Regular Users</h3>
                     <p class="text-3xl font-bold text-sage-800"><?php echo $regular_users; ?></p>
                  </div>
                  <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                     <i class="fas fa-user text-green-600 text-xl"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Users Grid -->
      <div class="bg-white rounded-xl border border-sage-100 shadow-sm overflow-hidden">
         <div class="p-6 border-b border-sage-100">
            <h2 class="text-xl font-semibold text-sage-800">All Users</h2>
            <p class="text-sage-600 text-sm mt-1">View and manage user accounts</p>
         </div>

         <?php
         $stmt_select = $conn->prepare("SELECT * FROM `users` ORDER BY id DESC");
         $stmt_select->execute();
         $select_users = $stmt_select->get_result();
         
         if($select_users->num_rows > 0) {
         ?>
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            <?php while($fetch_users = $select_users->fetch_assoc()) { ?>
            <div class="bg-cream-50 rounded-xl border border-sage-100 overflow-hidden hover:shadow-lg transition-all duration-300 group">
               <!-- User Avatar -->
               <div class="bg-gradient-sage p-6 text-center">
                  <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                     <i class="fas fa-user text-3xl text-sage-600"></i>
                  </div>
                  <h3 class="text-white font-semibold text-lg"><?php echo htmlspecialchars($fetch_users['name']); ?></h3>
               </div>

               <!-- User Info -->
               <div class="p-6">
                  <div class="space-y-3 mb-4">
                     <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-sage-600">User ID:</span>
                        <span class="text-sm font-semibold text-sage-800">#<?php echo $fetch_users['id']; ?></span>
                     </div>
                     
                     <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-sage-600">Email:</span>
                        <span class="text-sm text-sage-800 truncate ml-2" title="<?php echo htmlspecialchars($fetch_users['email']); ?>">
                           <?php echo htmlspecialchars($fetch_users['email']); ?>
                        </span>
                     </div>
                     
                     <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-sage-600">User Type:</span>
                        <?php if($fetch_users['user_type'] == 'admin') { ?>
                           <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                              <i class="fas fa-crown mr-1"></i>
                              Admin
                           </span>
                        <?php } else { ?>
                           <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                              <i class="fas fa-user mr-1"></i>
                              User
                           </span>
                        <?php } ?>
                     </div>
                  </div>

                  <!-- Action Buttons -->
                  <?php if($fetch_users['user_type'] != 'admin' || $admin_users > 1) { ?>
                  <div class="pt-4 border-t border-sage-100">
                     <button onclick="confirmDelete(<?php echo $fetch_users['id']; ?>, '<?php echo htmlspecialchars($fetch_users['name']); ?>')"
                             class="w-full bg-accent-500 text-white py-2 px-4 rounded-lg hover:bg-accent-600 transition-colors text-sm font-medium flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>
                        Delete User
                     </button>
                  </div>
                  <?php } else { ?>
                  <div class="pt-4 border-t border-sage-100">
                     <div class="w-full bg-sage-300 text-sage-600 py-2 px-4 rounded-lg text-sm font-medium flex items-center justify-center cursor-not-allowed">
                        <i class="fas fa-lock mr-2"></i>
                        Protected Admin
                     </div>
                  </div>
                  <?php } ?>
               </div>
            </div>
            <?php } ?>
         </div>
         <?php 
         } else {
         ?>
         <!-- Empty State -->
         <div class="text-center py-12">
            <div class="w-24 h-24 bg-sage-100 rounded-full flex items-center justify-center mx-auto mb-4">
               <i class="fas fa-users text-4xl text-sage-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-sage-700 mb-2">No Users Found</h3>
            <p class="text-sage-500">There are no registered users in the system.</p>
         </div>
         <?php } ?>
         
         <?php $stmt_select->close(); ?>
      </div>
   </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
   <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
      <div class="p-6">
         <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
               <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <div>
               <h3 class="text-lg font-semibold text-sage-800">Confirm Deletion</h3>
               <p class="text-sage-600 text-sm">This action cannot be undone</p>
            </div>
         </div>
         
         <p class="text-sage-700 mb-6">
            Are you sure you want to delete user <span id="userName" class="font-semibold"></span>? This will permanently remove their account and all associated data.
         </p>
         
         <div class="flex space-x-3">
            <button onclick="closeDeleteModal()" 
                    class="flex-1 bg-sage-500 text-white py-3 px-4 rounded-lg hover:bg-sage-600 transition-colors font-medium">
               Cancel
            </button>
            <a id="deleteLink" href="#" 
               class="flex-1 bg-accent-500 text-white py-3 px-4 rounded-lg hover:bg-accent-600 transition-colors font-medium text-center">
               Delete User
            </a>
         </div>
      </div>
   </div>
</div>

<script>
function confirmDelete(userId, userName) {
   document.getElementById('userName').textContent = userName;
   document.getElementById('deleteLink').href = 'admin_users.php?delete=' + userId;
   document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
   document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
   if (e.target === this) {
      closeDeleteModal();
   }
});

// Auto-hide any messages
setTimeout(() => {
   const messages = document.querySelectorAll('.fixed.top-24 > div');
   messages.forEach(msg => {
      msg.style.opacity = '0';
      msg.style.transform = 'translateY(-20px)';
      setTimeout(() => msg.remove(), 300);
   });
}, 3000);
</script>

<?php include 'admin_footer.php'; ?>

</body>
</html>