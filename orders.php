<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Orders - BookHaven | Order History</title>

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
               animation: {
                  'fade-in': 'fadeIn 0.8s ease-in-out',
                  'slide-up': 'slideUp 0.6s ease-out',
                  'float': 'float 6s ease-in-out infinite',
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
      @keyframes fadeIn {
         from { opacity: 0; transform: translateY(30px); }
         to { opacity: 1; transform: translateY(0); }
      }
      @keyframes slideUp {
         from { opacity: 0; transform: translateY(50px); }
         to { opacity: 1; transform: translateY(0); }
      }
      @keyframes float {
         0%, 100% { transform: translateY(0px) rotate(0deg); }
         33% { transform: translateY(-20px) rotate(1deg); }
         66% { transform: translateY(-10px) rotate(-1deg); }
      }
   </style>
</head>
<body class="bg-cream-50 font-sans">

<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-600 via-sage-700 to-sage-800 py-20 overflow-hidden">
   <!-- Background Elements -->
   <div class="absolute inset-0 bg-black/20"></div>
   <div class="absolute top-20 left-10 w-40 h-40 bg-cream-300/20 rounded-full blur-2xl animate-float"></div>
   <div class="absolute bottom-32 right-20 w-56 h-56 bg-accent-400/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
   <div class="absolute top-1/2 left-1/3 w-32 h-32 bg-primary-300/20 rounded-full blur-xl animate-float" style="animation-delay: 4s;"></div>
   
   <div class="container mx-auto px-4 relative z-10">
      <div class="max-w-4xl mx-auto text-center text-white">
         <!-- Breadcrumb -->
         <nav class="mb-8">
            <div class="flex items-center justify-center space-x-2 text-blue-200">
               <a href="index.php" class="hover:text-cream-300 transition-colors">Home</a>
               <i class="fas fa-chevron-right text-sm"></i>
               <span class="text-cream-300">Orders</span>
            </div>
         </nav>
         
         <!-- Hero Content -->
         <div class="animate-fade-in">
            <span class="inline-block px-6 py-3 bg-cream-400/20 rounded-full text-cream-100 text-lg font-medium mb-8 backdrop-blur-sm">
               Order History
            </span>
            <h1 class="text-5xl lg:text-7xl font-serif font-bold leading-tight mb-8">
               Your Literary
               <span class="text-transparent bg-clip-text bg-gradient-to-r from-cream-200 to-cream-400 block">
                  Journey Timeline
               </span>
            </h1>
            <p class="text-xl lg:text-2xl text-blue-100 mb-12 leading-relaxed max-w-3xl mx-auto">
               Track your orders, review your reading adventures, and discover new favorites from your purchase history.
            </p>
         </div>
      </div>
   </div>
   
   <!-- Scroll Indicator -->
   <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-cream-200 animate-bounce">
      <i class="fas fa-chevron-down text-2xl"></i>
   </div>
</section>

<<!-- Orders Section -->
<section class="py-20 bg-white">
   <div class="container mx-auto px-4">
      <!-- Section Header -->
      <div class="text-center mb-16">
         <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-4">
            Order Management
         </span>
         <h2 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-6">
            Your Order History
         </h2>
         <p class="text-xl text-sage-600 max-w-2xl mx-auto">
            Review all your book purchases, track delivery status, and manage your literary collection.
         </p>
      </div>

      <!-- Orders Container -->
      <div class="max-w-6xl mx-auto">
         <?php
            $stmt = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY id DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $order_query = $stmt->get_result();

            if ($order_query->num_rows > 0) {
               echo '<div class="grid gap-8">';
               while ($fetch_orders = $order_query->fetch_assoc()) {
                  
                  // Determine status colors and icons
                  $status = strtolower($fetch_orders['payment_status']);
                  $statusConfig = [
                     'pending' => [
                        'bg' => 'bg-yellow-100 border-yellow-300',
                        'text' => 'text-yellow-800',
                        'icon' => 'fa-clock',
                        'iconBg' => 'bg-yellow-500'
                     ],
                     'completed' => [
                        'bg' => 'bg-green-100 border-green-300',
                        'text' => 'text-green-800',
                        'icon' => 'fa-check-circle',
                        'iconBg' => 'bg-green-500'
                     ],
                     'cancelled' => [
                        'bg' => 'bg-red-100 border-red-300',
                        'text' => 'text-red-800',
                        'icon' => 'fa-times-circle',
                        'iconBg' => 'bg-red-500'
                     ]
                  ];
                  
                  $config = $statusConfig[$status] ?? $statusConfig['pending'];
         ?>
         <div class="bg-gradient-to-br from-sage-50 to-cream-100 rounded-3xl shadow-xl border border-sage-200 overflow-hidden hover:shadow-2xl transition-all duration-500 animate-slide-up">
            <!-- Order Header -->
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-8 py-6 text-white">
               <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                  <div class="flex items-center gap-4">
                     <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-receipt text-xl"></i>
                     </div>
                     <div>
                        <h3 class="text-xl font-bold">Order #<?php echo str_pad($fetch_orders['id'], 6, '0', STR_PAD_LEFT); ?></h3>
                        <p class="text-blue-100">Placed on <?php echo date('M j, Y', strtotime($fetch_orders['placed_on'])); ?></p>
                     </div>
                  </div>
                  
                  <div class="flex items-center gap-4">
                     <div class="text-right">
                        <p class="text-blue-100 text-sm">Total Amount</p>
                        <p class="text-2xl font-bold">₹<?php echo number_format($fetch_orders['total_price']); ?></p>
                     </div>
                     <div class="w-16 h-16 <?php echo $config['iconBg']; ?> rounded-full flex items-center justify-center">
                        <i class="fas <?php echo $config['icon']; ?> text-white text-xl"></i>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Order Details -->
            <div class="p-8">
               <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                  <!-- Customer Information -->
                  <div class="space-y-4">
                     <h4 class="text-lg font-bold text-sage-800 flex items-center gap-2">
                        <i class="fas fa-user text-primary-500"></i>
                        Customer Details
                     </h4>
                     <div class="space-y-3 bg-white p-4 rounded-xl border border-sage-100">
                        <div>
                           <p class="text-sm text-sage-500 mb-1">Full Name</p>
                           <p class="font-semibold text-sage-800"><?php echo $fetch_orders['name']; ?></p>
                        </div>
                        <div>
                           <p class="text-sm text-sage-500 mb-1">Email</p>
                           <p class="font-semibold text-sage-800"><?php echo $fetch_orders['email']; ?></p>
                        </div>
                        <div>
                           <p class="text-sm text-sage-500 mb-1">Phone</p>
                           <p class="font-semibold text-sage-800"><?php echo $fetch_orders['number']; ?></p>
                        </div>
                     </div>
                  </div>

                  <!-- Shipping Information -->
                  <div class="space-y-4">
                     <h4 class="text-lg font-bold text-sage-800 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-primary-500"></i>
                        Shipping Address
                     </h4>
                     <div class="bg-white p-4 rounded-xl border border-sage-100">
                        <p class="text-sage-700 leading-relaxed"><?php echo $fetch_orders['address']; ?></p>
                     </div>
                     
                     <div class="bg-white p-4 rounded-xl border border-sage-100">
                        <p class="text-sm text-sage-500 mb-1">Payment Method</p>
                        <div class="flex items-center gap-2">
                           <i class="fas fa-credit-card text-primary-500"></i>
                           <p class="font-semibold text-sage-800 capitalize"><?php echo $fetch_orders['method']; ?></p>
                        </div>
                     </div>
                  </div>

                  <!-- Order Items & Status -->
                  <div class="space-y-4">
                     <h4 class="text-lg font-bold text-sage-800 flex items-center gap-2">
                        <i class="fas fa-book text-primary-500"></i>
                        Order Items
                     </h4>
                     <div class="bg-white p-4 rounded-xl border border-sage-100">
                        <div class="text-sage-700 leading-relaxed">
                           <?php 
                              $products = explode(' | ', $fetch_orders['total_products']);
                              foreach($products as $product) {
                                 if(!empty(trim($product))) {
                                    echo '<div class="flex items-center gap-2 py-1">
                                             <i class="fas fa-book-open text-primary-400 text-sm"></i>
                                             <span class="text-sm">' . trim($product) . '</span>
                                          </div>';
                                 }
                              }
                           ?>
                        </div>
                     </div>
                     
                     <!-- Status Badge -->
                     <div class="<?php echo $config['bg']; ?> border-2 <?php echo str_replace('bg-', 'border-', $config['bg']); ?> p-4 rounded-xl">
                        <div class="flex items-center justify-between">
                           <div>
                              <p class="text-sm <?php echo $config['text']; ?> opacity-75 mb-1">Payment Status</p>
                              <p class="font-bold <?php echo $config['text']; ?> text-lg capitalize"><?php echo $fetch_orders['payment_status']; ?></p>
                           </div>
                           <i class="fas <?php echo $config['icon']; ?> <?php echo $config['text']; ?> text-2xl"></i>
                        </div>
                     </div>
                  </div>
               </div>

               <!-- Order Actions -->
               <div class="mt-8 flex flex-wrap gap-4 pt-6 border-t border-sage-200">
                  <?php if($status === 'pending'): ?>
                     <button class="px-6 py-3 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors flex items-center gap-2">
                        <i class="fas fa-eye"></i>
                        <span>Track Order</span>
                     </button>
                  <?php elseif($status === 'completed'): ?>
                     <button class="px-6 py-3 bg-green-500 text-white font-medium rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                        <i class="fas fa-download"></i>
                        <span>Download Invoice</span>
                     </button>
                  <?php endif; ?>
                  
                  <button class="px-6 py-3 bg-accent-100 text-accent-700 font-medium rounded-lg hover:bg-accent-200 transition-colors flex items-center gap-2">
                     <i class="fas fa-headset"></i>
                     <span>Get Support</span>
                  </button>
               </div>
            </div>
         </div>
         <?php
               }
               echo '</div>';
            } else {
               // Empty state
               echo '
               <div class="text-center py-20">
                  <div class="w-32 h-32 bg-sage-100 rounded-full flex items-center justify-center mx-auto mb-8">
                     <i class="fas fa-shopping-bag text-4xl text-sage-400"></i>
                  </div>
                  <h3 class="text-2xl font-serif font-bold text-sage-800 mb-4">No Orders Yet</h3>
                  <p class="text-sage-600 mb-8 max-w-md mx-auto">
                     You haven\'t placed any orders yet. Start exploring our collection to find your next favorite book!
                  </p>
                  <div class="flex flex-col sm:flex-row gap-4 justify-center">
                     <a href="shop.php" class="px-8 py-4 bg-primary-500 text-white font-bold rounded-lg hover:bg-primary-600 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Start Shopping</span>
                     </a>
                     <a href="index.php" class="px-8 py-4 border-2 border-sage-300 text-sage-700 font-bold rounded-lg hover:bg-sage-50 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-home"></i>
                        <span>Browse Featured</span>
                     </a>
                  </div>
               </div>';
            }
         ?>
      </div>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JavaScript -->
<script>
   // Smooth scroll for scroll indicator
   document.querySelector('.animate-bounce')?.addEventListener('click', () => {
      document.querySelector('.py-20.bg-white').scrollIntoView({ behavior: 'smooth' });
   });

   // Add click handlers for action buttons (placeholder functionality)
   document.addEventListener('DOMContentLoaded', function() {
      // Track order buttons
      document.querySelectorAll('button').forEach(button => {
         if (button.textContent.includes('Track Order')) {
            button.addEventListener('click', function() {
               alert('Order tracking functionality would be implemented here.');
            });
         }
         
         if (button.textContent.includes('Download Invoice')) {
            button.addEventListener('click', function() {
               alert('Invoice download functionality would be implemented here.');
            });
         }
         
         if (button.textContent.includes('Reorder Items')) {
            button.addEventListener('click', function() {
               alert('Reorder functionality would be implemented here.');
            });
         }
         
         if (button.textContent.includes('Get Support')) {
            button.addEventListener('click', function() {
               window.location.href = 'contact.php';
            });
         }
      });
   });
</script>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
