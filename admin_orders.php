<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = intval($_POST['order_id']);
   $update_payment = $_POST['update_payment'];
   $stmt = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $stmt->bind_param("si", $update_payment, $order_update_id);
   $stmt->execute();
   $stmt->close();
   $message[] = 'payment status has been updated!';

}

if(isset($_GET['delete'])){
   $delete_id = intval($_GET['delete']);
   $stmt = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $stmt->bind_param("i", $delete_id);
   $stmt->execute();
   $stmt->close();
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Order Management - Admin Panel</title>

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

<!-- Order Management Section -->
<main class="flex-1 pt-8 pb-12">
   <div class="container mx-auto px-4 lg:px-6">
      
      <!-- Page Header -->
      <div class="mb-8">
         <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
               <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center shadow-lg">
                  <i class="fas fa-shopping-cart text-2xl text-white"></i>
               </div>
               <div>
                  <h1 class="text-3xl lg:text-4xl font-serif font-bold text-sage-800">
                     Order Management
                  </h1>
                  <p class="text-sage-600">View and manage customer orders</p>
               </div>
            </div>
            
            <!-- Order Statistics -->
            <div class="hidden md:flex space-x-4">
               <?php
                  $total_orders = mysqli_query($conn, "SELECT COUNT(*) as total FROM `orders`") or die('query failed');
                  $total_count = mysqli_fetch_assoc($total_orders)['total'];
                  
                  $pending_orders = mysqli_query($conn, "SELECT COUNT(*) as pending FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
                  $pending_count = mysqli_fetch_assoc($pending_orders)['pending'];
                  
                  $completed_orders = mysqli_query($conn, "SELECT COUNT(*) as completed FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
                  $completed_count = mysqli_fetch_assoc($completed_orders)['completed'];
               ?>
               <div class="text-center">
                  <div class="text-2xl font-bold text-sage-800"><?php echo $total_count; ?></div>
                  <div class="text-sm text-sage-600">Total Orders</div>
               </div>
               <div class="text-center">
                  <div class="text-2xl font-bold text-yellow-600"><?php echo $pending_count; ?></div>
                  <div class="text-sm text-sage-600">Pending</div>
               </div>
               <div class="text-center">
                  <div class="text-2xl font-bold text-green-600"><?php echo $completed_count; ?></div>
                  <div class="text-sm text-sage-600">Completed</div>
               </div>
            </div>
         </div>
         <div class="w-24 h-1 bg-gradient-primary rounded-full mt-4"></div>
      </div>

      <!-- Orders List -->
      <div class="bg-white rounded-2xl shadow-lg border border-sage-100">
         <?php
         $select_orders = mysqli_query($conn, "SELECT * FROM `orders` ORDER BY id DESC") or die('query failed');
         if(mysqli_num_rows($select_orders) > 0){
         ?>
         
         <div class="p-6 border-b border-sage-100">
            <h2 class="text-xl font-serif font-bold text-sage-800">Recent Orders</h2>
         </div>

         <div class="overflow-x-auto">
            <div class="space-y-4 p-6">
               <?php while($fetch_orders = mysqli_fetch_assoc($select_orders)){ ?>
               <div class="bg-cream-50 rounded-xl border border-sage-100 hover:shadow-lg transition-all duration-300">
                  <div class="p-6">
                     <!-- Order Header -->
                     <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4">
                        <div class="flex items-center space-x-4 mb-4 lg:mb-0">
                           <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                              <i class="fas fa-receipt text-primary-600"></i>
                           </div>
                           <div>
                              <h3 class="font-semibold text-sage-800">Order #<?php echo $fetch_orders['id']; ?></h3>
                              <p class="text-sm text-sage-600">
                                 <i class="fas fa-calendar mr-1"></i>
                                 <?php echo date('M d, Y', strtotime($fetch_orders['placed_on'])); ?>
                              </p>
                           </div>
                        </div>
                        
                        <!-- Status Badge -->
                        <?php 
                           $status = $fetch_orders['payment_status'];
                           $badge_class = $status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                           $icon = $status == 'completed' ? 'fa-check-circle' : 'fa-clock';
                        ?>
                        <div class="flex items-center space-x-3">
                           <span class="<?php echo $badge_class; ?> px-3 py-1 rounded-full text-sm font-medium">
                              <i class="fas <?php echo $icon; ?> mr-1"></i>
                              <?php echo ucfirst($status); ?>
                           </span>
                           <div class="text-right">
                              <div class="text-2xl font-bold text-primary-600">
                                 ₹<?php echo number_format($fetch_orders['total_price']); ?>
                              </div>
                              <div class="text-sm text-sage-600"><?php echo $fetch_orders['method']; ?></div>
                           </div>
                        </div>
                     </div>

                     <!-- Customer Info -->
                     <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                        <div class="bg-white p-4 rounded-lg">
                           <h4 class="font-medium text-sage-700 mb-2">Customer Details</h4>
                           <p class="text-sage-800 font-semibold"><?php echo $fetch_orders['name']; ?></p>
                           <p class="text-sm text-sage-600">
                              <i class="fas fa-envelope mr-1"></i>
                              <?php echo $fetch_orders['email']; ?>
                           </p>
                           <p class="text-sm text-sage-600">
                              <i class="fas fa-phone mr-1"></i>
                              <?php echo $fetch_orders['number']; ?>
                           </p>
                        </div>

                        <div class="bg-white p-4 rounded-lg">
                           <h4 class="font-medium text-sage-700 mb-2">Delivery Address</h4>
                           <p class="text-sm text-sage-800"><?php echo $fetch_orders['address']; ?></p>
                        </div>

                        <div class="bg-white p-4 rounded-lg">
                           <h4 class="font-medium text-sage-700 mb-2">Order Summary</h4>
                           <p class="text-sm text-sage-800 mb-1">
                              Products: <?php echo $fetch_orders['total_products']; ?>
                           </p>
                           <p class="text-sm text-sage-800">
                              User ID: <?php echo $fetch_orders['user_id']; ?>
                           </p>
                        </div>
                     </div>

                     <!-- Actions -->
                     <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4 border-t border-sage-200 space-y-3 sm:space-y-0">
                        <form action="" method="post" class="flex items-center space-x-3">
                           <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                           <label class="text-sm font-medium text-sage-700">Update Status:</label>
                           <select name="update_payment" class="px-3 py-2 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                              <option value="pending" <?php echo ($fetch_orders['payment_status'] == 'pending') ? 'selected' : ''; ?>>
                                 Pending
                              </option>
                              <option value="completed" <?php echo ($fetch_orders['payment_status'] == 'completed') ? 'selected' : ''; ?>>
                                 Completed
                              </option>
                           </select>
                           <button type="submit" name="update_order" 
                                   class="bg-primary-500 text-white px-4 py-2 rounded-lg hover:bg-primary-600 transition-colors text-sm font-medium">
                              <i class="fas fa-save mr-1"></i>
                              Update
                           </button>
                        </form>

                        <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" 
                           onclick="return confirm('Are you sure you want to delete this order?');"
                           class="bg-accent-500 text-white px-4 py-2 rounded-lg hover:bg-accent-600 transition-colors text-sm font-medium text-center">
                           <i class="fas fa-trash mr-1"></i>
                           Delete Order
                        </a>
                     </div>
                  </div>
               </div>
               <?php } ?>
            </div>
         </div>

         <?php 
         } else {
         ?>
         <!-- Empty State -->
         <div class="text-center py-16">
            <div class="w-24 h-24 bg-sage-100 rounded-full flex items-center justify-center mx-auto mb-6">
               <i class="fas fa-shopping-cart text-4xl text-sage-400"></i>
            </div>
            <h3 class="text-2xl font-semibold text-sage-700 mb-4">No Orders Found</h3>
            <p class="text-sage-500 mb-8 max-w-md mx-auto">
               No orders have been placed yet. Orders will appear here once customers start purchasing from your store.
            </p>
            <a href="admin_products.php" 
               class="bg-primary-500 text-white px-6 py-3 rounded-lg hover:bg-primary-600 transition-colors inline-flex items-center">
               <i class="fas fa-plus mr-2"></i>
               Add Products
            </a>
         </div>
         <?php } ?>
      </div>

   </div>
</main>

<!-- Custom JavaScript -->
<script>
   // Auto-hide messages
   setTimeout(() => {
      const messages = document.querySelectorAll('.fixed.top-24 > div');
      messages.forEach(msg => {
         msg.style.opacity = '0';
         msg.style.transform = 'translateY(-20px)';
         setTimeout(() => msg.remove(), 300);
      });
   }, 5000);

   // Confirm before status change
   document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', function(e) {
         if (this.querySelector('select[name="update_payment"]')) {
            const select = this.querySelector('select[name="update_payment"]');
            if (select.value && !confirm(`Are you sure you want to change the payment status to "${select.value}"?`)) {
               e.preventDefault();
            }
         }
      });
   });
</script>

<?php include 'admin_footer.php'; ?>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>