<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Panel</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Tailwind CSS -->
   <script src="https://cdn.tailwindcss.com"></script>
   
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
               animation: {
                  'slide-up': 'slideUp 0.6s ease-out',
                  'fade-in': 'fadeIn 0.8s ease-in-out',
               },
               backgroundImage: {
                  'gradient-primary': 'linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%)',
                  'gradient-sage': 'linear-gradient(135deg, #647064 0%, #4f5a4f 100%)',
               }
            }
         }
      }
   </script>

   <style>
      @keyframes slideUp {
         from { opacity: 0; transform: translateY(50px); }
         to { opacity: 1; transform: translateY(0); }
      }
      @keyframes fadeIn {
         from { opacity: 0; transform: translateY(30px); }
         to { opacity: 1; transform: translateY(0); }
      }
      /* Override admin_style.css font-size */
      html {
         font-size: 16px !important;
      }
   </style>

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body class="bg-cream-50 font-sans min-h-screen flex flex-col">
   
<?php include 'admin_header.php'; ?>

<!-- Admin Dashboard Section -->
<main class="flex-1 pt-8 pb-12">
   <div class="container mx-auto px-4 lg:px-6">
      
      <!-- Dashboard Header -->
      <div class="mb-8">
         <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center shadow-lg">
               <i class="fas fa-chart-bar text-2xl text-white"></i>
            </div>
            <div>
               <h1 class="text-3xl lg:text-4xl font-serif font-bold text-sage-800">
                  Admin Dashboard
               </h1>
               <p class="text-sage-600">Welcome back, <?php echo $_SESSION['admin_name']; ?>!</p>
            </div>
         </div>
         <div class="w-24 h-1 bg-gradient-primary rounded-full"></div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
         
         <!-- Pending Payments -->
         <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-clock text-2xl text-yellow-600"></i>
               </div>
               <div class="text-right">
                  <?php
                     $total_pending = 0;
                     $status_pending = 'pending';
                     $stmt_pending = $conn->prepare("SELECT total_price FROM `orders` WHERE payment_status = ?");
                     $stmt_pending->bind_param("s", $status_pending);
                     $stmt_pending->execute();
                     $select_pending = $stmt_pending->get_result();
                     if($select_pending->num_rows > 0){
                        while($fetch_pending = $select_pending->fetch_assoc()){
                           $total_price = $fetch_pending['total_price'];
                           $total_pending += $total_price;
                        }
                     $stmt_pending->close();
                     }
                  ?>
                  <h3 class="text-2xl font-bold text-sage-800 mb-1">₹<?php echo number_format($total_pending); ?></h3>
                  <p class="text-sage-600 text-sm font-medium">Pending Payments</p>
               </div>
            </div>
            <div class="w-full bg-yellow-100 rounded-full h-2">
               <div class="bg-yellow-500 h-2 rounded-full w-3/4"></div>
            </div>
         </div>

         <!-- Completed Payments -->
         <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-check-circle text-2xl text-green-600"></i>
               </div>
               <div class="text-right">
                  <?php
                     $total_completed = 0;
                     $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
                     if(mysqli_num_rows($select_completed) > 0){
                        while($fetch_completed = mysqli_fetch_assoc($select_completed)){
                           $total_price = $fetch_completed['total_price'];
                           $total_completed += $total_price;
                        };
                     };
                  ?>
                  <h3 class="text-2xl font-bold text-sage-800 mb-1">₹<?php echo number_format($total_completed); ?></h3>
                  <p class="text-sage-600 text-sm font-medium">Completed Payments</p>
               </div>
            </div>
            <div class="w-full bg-green-100 rounded-full h-2">
               <div class="bg-green-500 h-2 rounded-full w-full"></div>
            </div>
         </div>

         <!-- Orders Placed -->
         <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-shopping-cart text-2xl text-primary-600"></i>
               </div>
               <div class="text-right">
                  <?php 
                     $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
                     $number_of_orders = mysqli_num_rows($select_orders);
                  ?>
                  <h3 class="text-2xl font-bold text-sage-800 mb-1"><?php echo $number_of_orders; ?></h3>
                  <p class="text-sage-600 text-sm font-medium">Orders Placed</p>
               </div>
            </div>
            <div class="w-full bg-primary-100 rounded-full h-2">
               <div class="bg-primary-500 h-2 rounded-full w-5/6"></div>
            </div>
         </div>

         <!-- Products Added -->
         <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-book text-2xl text-purple-600"></i>
               </div>
               <div class="text-right">
                  <?php 
                     $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                     $number_of_products = mysqli_num_rows($select_products);
                  ?>
                  <h3 class="text-2xl font-bold text-sage-800 mb-1"><?php echo $number_of_products; ?></h3>
                  <p class="text-sage-600 text-sm font-medium">Products Added</p>
               </div>
            </div>
            <div class="w-full bg-purple-100 rounded-full h-2">
               <div class="bg-purple-500 h-2 rounded-full w-4/5"></div>
            </div>
         </div>

      </div>

      <!-- Additional Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
         
         <!-- Normal Users -->
         <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-users text-2xl text-blue-600"></i>
               </div>
               <div class="text-right">
                  <?php 
                     $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
                     $number_of_users = mysqli_num_rows($select_users);
                  ?>
                  <h3 class="text-2xl font-bold text-sage-800 mb-1"><?php echo $number_of_users; ?></h3>
                  <p class="text-sage-600 text-sm font-medium">Normal Users</p>
               </div>
            </div>
            <div class="w-full bg-blue-100 rounded-full h-2">
               <div class="bg-blue-500 h-2 rounded-full w-3/4"></div>
            </div>
         </div>

         <!-- Admin Users -->
         <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-user-shield text-2xl text-red-600"></i>
               </div>
               <div class="text-right">
                  <?php 
                     $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
                     $number_of_admins = mysqli_num_rows($select_admins);
                  ?>
                  <h3 class="text-2xl font-bold text-sage-800 mb-1"><?php echo $number_of_admins; ?></h3>
                  <p class="text-sage-600 text-sm font-medium">Admin Users</p>
               </div>
            </div>
            <div class="w-full bg-red-100 rounded-full h-2">
               <div class="bg-red-500 h-2 rounded-full w-1/2"></div>
            </div>
         </div>

         <!-- Total Accounts -->
         <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-user-friends text-2xl text-indigo-600"></i>
               </div>
               <div class="text-right">
                  <?php 
                     $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
                     $number_of_account = mysqli_num_rows($select_account);
                  ?>
                  <h3 class="text-2xl font-bold text-sage-800 mb-1"><?php echo $number_of_account; ?></h3>
                  <p class="text-sage-600 text-sm font-medium">Total Accounts</p>
               </div>
            </div>
            <div class="w-full bg-indigo-100 rounded-full h-2">
               <div class="bg-indigo-500 h-2 rounded-full w-full"></div>
            </div>
         </div>

         <!-- New Messages -->
         <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-envelope text-2xl text-teal-600"></i>
               </div>
               <div class="text-right">
                  <?php 
                     $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
                     $number_of_messages = mysqli_num_rows($select_messages);
                  ?>
                  <h3 class="text-2xl font-bold text-sage-800 mb-1"><?php echo $number_of_messages; ?></h3>
                  <p class="text-sage-600 text-sm font-medium">New Messages</p>
               </div>
            </div>
            <div class="w-full bg-teal-100 rounded-full h-2">
               <div class="bg-teal-500 h-2 rounded-full w-2/3"></div>
            </div>
         </div>

      </div>

      <!-- Quick Actions -->
      <div class="mt-8">
         <h2 class="text-2xl font-serif font-bold text-sage-800 mb-6">Quick Actions</h2>
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="admin_products.php" class="bg-white rounded-xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group text-center">
               <div class="w-12 h-12 bg-gradient-primary rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-plus text-white"></i>
               </div>
               <h3 class="font-semibold text-sage-800 mb-1">Add Product</h3>
               <p class="text-sm text-sage-600">Add new books to inventory</p>
            </a>
            
            <a href="admin_orders.php" class="bg-white rounded-xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group text-center">
               <div class="w-12 h-12 bg-gradient-sage rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-list text-white"></i>
               </div>
               <h3 class="font-semibold text-sage-800 mb-1">View Orders</h3>
               <p class="text-sm text-sage-600">Manage customer orders</p>
            </a>
            
            <a href="admin_users.php" class="bg-white rounded-xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group text-center">
               <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-users text-white"></i>
               </div>
               <h3 class="font-semibold text-sage-800 mb-1">Manage Users</h3>
               <p class="text-sm text-sage-600">View and manage users</p>
            </a>
            
            <a href="admin_contacts.php" class="bg-white rounded-xl shadow-lg border border-sage-100 p-6 hover:shadow-xl transition-all duration-300 group text-center">
               <div class="w-12 h-12 bg-teal-500 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                  <i class="fas fa-envelope text-white"></i>
               </div>
               <h3 class="font-semibold text-sage-800 mb-1">View Messages</h3>
               <p class="text-sm text-sage-600">Check customer messages</p>
            </a>
         </div>
      </div>

   </div>
</main>

<?php include 'admin_footer.php'; ?>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>