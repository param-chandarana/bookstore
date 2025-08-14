<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
   header('Location: login.php');
   exit;
}

$user_id = $_SESSION['user_id'];

// Update cart item quantity
if (isset($_POST['update_cart'])) {
   $cart_id = intval($_POST['cart_id']);
   $cart_quantity = max(1, intval($_POST['cart_quantity'])); // Minimum 1

   $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
   $stmt->bind_param("iii", $cart_quantity, $cart_id, $user_id);
   $stmt->execute();
   $stmt->close();
}

// Delete a single cart item
if (isset($_GET['delete'])) {
   $delete_id = intval($_GET['delete']);

   $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
   $stmt->bind_param("ii", $delete_id, $user_id);
   $stmt->execute();
   $stmt->close();

   header('Location: cart.php');
   exit;
}

// Delete all items from the cart
if (isset($_GET['delete_all'])) {
   $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
   $stmt->bind_param("i", $user_id);
   $stmt->execute();
   $stmt->close();

   header('Location: cart.php');
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart - BookHaven | Your Selected Books</title>

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
   <section class="relative bg-gradient-primary text-white py-20 overflow-hidden">
      <!-- Background Elements -->
      <div class="absolute inset-0 overflow-hidden">
         <div class="absolute -top-4 -right-4 w-72 h-72 bg-white opacity-10 rounded-full blur-3xl animate-float"></div>
         <div class="absolute -bottom-8 -left-8 w-96 h-96 bg-primary-300 opacity-20 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>
      </div>
      
      <div class="relative container mx-auto px-4 text-center">
         <div class="animate-fade-in">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-6 backdrop-blur-sm">
               <i class="fas fa-shopping-cart text-3xl"></i>
            </div>
            <h1 class="text-5xl md:text-6xl font-serif font-bold mb-6">Your Cart</h1>
            <p class="text-xl md:text-2xl text-primary-100 max-w-2xl mx-auto leading-relaxed">
               Review your selected books and proceed to checkout
            </p>
         </div>
      </div>
   </section>

   <!-- Cart Section -->
   <section class="py-16 md:py-24">
      <div class="container mx-auto px-4">
         <?php
         $grand_total = 0;
         $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
         $stmt->bind_param("i", $user_id);
         $stmt->execute();
         $result = $stmt->get_result();

         if ($result->num_rows > 0) {
         ?>
            <!-- Cart Header -->
            <div class="flex flex-col lg:flex-row gap-8">
               <!-- Cart Items -->
               <div class="lg:w-2/3">
                  <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                     <div class="bg-gradient-sage text-white p-6">
                        <h2 class="text-2xl font-serif font-bold flex items-center">
                           <i class="fas fa-shopping-bag mr-3"></i>
                           Cart Items (<?php echo $result->num_rows; ?>)
                        </h2>
                     </div>
                     
                     <div class="p-6 space-y-6">
                        <?php
                        $result->data_seek(0); // Reset result pointer
                        while ($fetch_cart = $result->fetch_assoc()) {
                           $sub_total = $fetch_cart['quantity'] * $fetch_cart['price'];
                           $grand_total += $sub_total;
                        ?>
                           <div class="bg-cream-50 rounded-xl p-6 hover:shadow-md transition-all duration-300 border border-cream-200 group">
                              <div class="flex flex-col md:flex-row gap-6">
                                 <!-- Product Image -->
                                 <div class="md:w-1/4">
                                    <div class="relative overflow-hidden rounded-lg bg-white shadow-sm">
                                       <img src="uploaded_img/<?php echo htmlspecialchars($fetch_cart['image']); ?>" 
                                            alt="<?php echo htmlspecialchars($fetch_cart['name']); ?>"
                                            class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                 </div>
                                 
                                 <!-- Product Details -->
                                 <div class="md:w-3/4 flex flex-col justify-between">
                                    <div>
                                       <h3 class="text-xl font-semibold text-sage-900 mb-2 group-hover:text-primary-600 transition-colors">
                                          <?php echo htmlspecialchars($fetch_cart['name']); ?>
                                       </h3>
                                       <p class="text-2xl font-bold text-primary-600 mb-4">
                                          ₹<?php echo number_format($fetch_cart['price'], 2); ?>
                                       </p>
                                    </div>
                                    
                                    <!-- Quantity and Actions -->
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                       <!-- Quantity Controls -->
                                       <form action="" method="post" class="flex items-center gap-3">
                                          <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                          <label class="text-sm font-medium text-sage-700">Quantity:</label>
                                          <div class="flex items-center border border-sage-300 rounded-lg overflow-hidden">
                                             <input type="number" 
                                                    min="1" 
                                                    name="cart_quantity" 
                                                    value="<?php echo $fetch_cart['quantity']; ?>"
                                                    class="w-16 px-3 py-2 text-center border-0 focus:ring-0 focus:outline-none bg-white">
                                          </div>
                                          <button type="submit" 
                                                  name="update_cart"
                                                  class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                             Update
                                          </button>
                                       </form>
                                       
                                       <!-- Remove Button and Subtotal -->
                                       <div class="flex items-center gap-4">
                                          <div class="text-right">
                                             <p class="text-sm text-sage-600">Subtotal</p>
                                             <p class="text-xl font-bold text-sage-900">₹<?php echo number_format($sub_total, 2); ?></p>
                                          </div>
                                          <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" 
                                             onclick="return confirm('Remove this item from your cart?');"
                                             class="bg-accent-500 hover:bg-accent-600 text-white p-2 rounded-lg transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2"
                                             title="Remove item">
                                             <i class="fas fa-trash-alt"></i>
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        <?php } ?>
                     </div>
                  </div>
                  
                  <!-- Cart Actions -->
                  <div class="mt-8 flex flex-col sm:flex-row gap-4">
                     <a href="shop.php" 
                        class="flex-1 bg-sage-500 hover:bg-sage-600 text-white py-3 px-6 rounded-xl font-semibold text-center transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-sage-500 focus:ring-offset-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Continue Shopping
                     </a>
                     <a href="cart.php?delete_all" 
                        onclick="return confirm('Remove all items from your cart?');"
                        class="flex-1 bg-accent-500 hover:bg-accent-600 text-white py-3 px-6 rounded-xl font-semibold text-center transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2">
                        <i class="fas fa-trash mr-2"></i>
                        Clear Cart
                     </a>
                  </div>
               </div>
               
               <!-- Order Summary -->
               <div class="lg:w-1/3">
                  <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-8">
                     <div class="bg-gradient-primary text-white p-6">
                        <h3 class="text-2xl font-serif font-bold flex items-center">
                           <i class="fas fa-receipt mr-3"></i>
                           Order Summary
                        </h3>
                     </div>
                     
                     <div class="p-6">
                        <div class="space-y-4 mb-6">
                           <div class="flex justify-between text-sage-600">
                              <span>Items (<?php echo $result->num_rows; ?>)</span>
                              <span>₹<?php echo number_format($grand_total, 2); ?></span>
                           </div>
                           <div class="flex justify-between text-sage-600">
                              <span>Shipping</span>
                              <span class="text-primary-600 font-medium">Free</span>
                           </div>
                           <div class="border-t border-sage-200 pt-4">
                              <div class="flex justify-between text-xl font-bold text-sage-900">
                                 <span>Total</span>
                                 <span class="text-primary-600">₹<?php echo number_format($grand_total, 2); ?></span>
                              </div>
                           </div>
                        </div>
                        
                        <a href="checkout.php" 
                           class="w-full bg-gradient-primary hover:shadow-lg text-white py-4 px-6 rounded-xl font-bold text-lg text-center transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 block">
                           <i class="fas fa-credit-card mr-2"></i>
                           Proceed to Checkout
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         <?php
         } else {
         ?>
            <!-- Empty Cart -->
            <div class="text-center py-16">
               <div class="max-w-md mx-auto">
                  <div class="mb-8">
                     <div class="inline-flex items-center justify-center w-32 h-32 bg-sage-100 rounded-full mb-6">
                        <i class="fas fa-shopping-cart text-5xl text-sage-400"></i>
                     </div>
                     <h2 class="text-3xl font-serif font-bold text-sage-900 mb-4">Your cart is empty</h2>
                     <p class="text-lg text-sage-600 mb-8">
                        Looks like you haven't added any books to your cart yet. 
                        Discover our amazing collection and find your next favorite read!
                     </p>
                  </div>
                  
                  <a href="shop.php" 
                     class="inline-flex items-center bg-gradient-primary hover:shadow-lg text-white py-4 px-8 rounded-xl font-bold text-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                     <i class="fas fa-book mr-3"></i>
                     Start Shopping
                  </a>
               </div>
            </div>
         <?php
         }
         $stmt->close();
         ?>
      </div>
   </section>

   <!-- Related Products Section -->
   <section class="py-16 bg-sage-50">
      <div class="container mx-auto px-4">
         <div class="text-center mb-12">
            <h2 class="text-4xl font-serif font-bold text-sage-900 mb-4">You Might Also Like</h2>
            <p class="text-lg text-sage-600 max-w-2xl mx-auto">
               Discover more amazing books from our curated collection
            </p>
         </div>
         
         <!-- Quick shop link -->
         <div class="text-center">
            <a href="shop.php" 
               class="inline-flex items-center bg-primary-500 hover:bg-primary-600 text-white py-3 px-8 rounded-xl font-semibold transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
               <i class="fas fa-search mr-2"></i>
               Browse All Books
            </a>
         </div>
      </div>
   </section>

<?php include 'footer.php'; ?>

</body>
</html>
