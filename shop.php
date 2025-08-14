<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit;
}

// Initialize message array
$message = [];

if (isset($_POST['add_to_cart'])) {
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $stmt_check = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $stmt_check->bind_param("si", $product_name, $user_id);
   $stmt_check->execute();
   $check_cart_numbers = $stmt_check->get_result();

   if ($check_cart_numbers->num_rows > 0) {
      $message[] = 'Product is already in your cart!';
   } else {
      $stmt_insert = $conn->prepare("INSERT INTO `cart` (user_id, name, price, quantity, image) VALUES (?, ?, ?, ?, ?)");
      $stmt_insert->bind_param("isdis", $user_id, $product_name, $product_price, $product_quantity, $product_image);
      $stmt_insert->execute();
      $stmt_insert->close();
      $message[] = 'Product added to cart successfully!';
   }
   $stmt_check->close();
}

// Get search, filter, and sort parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'featured';

// Build the query based on filters
$query = "SELECT * FROM `products` WHERE 1=1";
$params = [];
$types = '';

// Add search functionality
if (!empty($search)) {
   $query .= " AND (name LIKE ? OR author LIKE ?)";
   $searchParam = '%' . $search . '%';
   $params[] = $searchParam;
   $params[] = $searchParam;
   $types .= 'ss';
}

// Add category filter (assuming you have a category field)
if (!empty($filter) && $filter !== 'all') {
   $query .= " AND category = ?";
   $params[] = $filter;
   $types .= 's';
}

// Add sorting
switch ($sort) {
   case 'price_low':
      $query .= " ORDER BY price ASC";
      break;
   case 'price_high':
      $query .= " ORDER BY price DESC";
      break;
   case 'title_az':
      $query .= " ORDER BY name ASC";
      break;
   case 'title_za':
      $query .= " ORDER BY name DESC";
      break;
   case 'newest':
      $query .= " ORDER BY id DESC";
      break;
   default:
      $query .= " ORDER BY id DESC";
      break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop - BookHaven | Discover Your Next Favourite Book</title>

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

<!-- Success/Error Messages -->
<?php if (isset($message) && is_array($message) && !empty($message)): ?>
   <div class="fixed top-24 left-1/2 transform -translate-x-1/2 space-y-2" style="z-index: 99999 !important; position: fixed !important;">
      <?php foreach ($message as $msg): ?>
         <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 pointer-events-auto">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $msg; ?></span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-green-200">
               <i class="fas fa-times"></i>
            </button>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-sage-800 py-20 overflow-hidden">
   <!-- Background Elements -->
   <div class="absolute inset-0 bg-black/10"></div>
   <div class="absolute top-10 left-10 w-32 h-32 bg-cream-300/20 rounded-full blur-2xl animate-float"></div>
   <div class="absolute bottom-10 right-20 w-40 h-40 bg-accent-400/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
   <div class="absolute top-1/2 right-1/4 w-24 h-24 bg-primary-300/20 rounded-full blur-xl animate-float" style="animation-delay: 4s;"></div>
   
   <div class="container mx-auto px-4 relative z-10">
      <div class="max-w-4xl mx-auto text-center text-white">
         <!-- Breadcrumb -->
         <nav class="mb-8">
            <div class="flex items-center justify-center space-x-2 text-blue-200">
               <a href="index.php" class="hover:text-cream-300 transition-colors">Home</a>
               <i class="fas fa-chevron-right text-sm"></i>
               <span class="text-cream-300">Shop</span>
            </div>
         </nav>
         
         <!-- Hero Content -->
         <div class="animate-fade-in">
            <span class="inline-block px-6 py-3 bg-cream-400/20 rounded-full text-cream-100 text-lg font-medium mb-8 backdrop-blur-sm">
               Curated Collection
            </span>
            <h1 class="text-5xl lg:text-7xl font-serif font-bold leading-tight mb-8">
               Discover Your Next
               <span class="text-transparent bg-clip-text bg-gradient-to-r from-cream-200 to-cream-400 block">
                  Literary Adventure
               </span>
            </h1>
            <p class="text-xl lg:text-2xl text-blue-100 mb-12 leading-relaxed max-w-3xl mx-auto">
               From timeless classics to contemporary masterpieces, find the perfect book to transport you to new worlds.
            </p>
            
            <!-- Search Bar -->
            <form method="GET" action="" class="max-w-2xl mx-auto">
               <div class="relative">
                  <input type="text" 
                         name="search"
                         value="<?php echo htmlspecialchars($search); ?>"
                         placeholder="Search for books or authors..." 
                         class="w-full px-6 py-4 pr-14 rounded-full text-sage-800 placeholder-sage-500 focus:outline-none focus:ring-4 focus:ring-cream-300/50 shadow-2xl">
                  <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-primary-500 text-white p-3 rounded-full hover:bg-primary-600 transition-colors">
                     <i class="fas fa-search"></i>
                  </button>
                  <!-- Preserve other parameters -->
                  <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
                  <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
               </div>
            </form>
         </div>
      </div>
   </div>
   
   <!-- Scroll Indicator -->
   <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-cream-200 animate-bounce">
      <i class="fas fa-chevron-down text-2xl"></i>
   </div>
</section>

<!-- Products Section -->
<section class="py-20 bg-white">
   <div class="container mx-auto px-4">
      <!-- Section Header -->
      <div class="text-center mb-16">
         <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-4">
            Featured Collection
         </span>
         <h2 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-6">
            <?php if (!empty($search)): ?>
               Search Results
            <?php elseif (!empty($filter)): ?>
               <?php echo ucfirst(str_replace('-', ' ', $filter)); ?> Books
            <?php else: ?>
               Latest Products
            <?php endif; ?>
         </h2>
         <p class="text-xl text-sage-600 max-w-2xl mx-auto">
            <?php if (!empty($search)): ?>
               Showing results for "<?php echo htmlspecialchars($search); ?>"
            <?php else: ?>
               Handpicked books that will captivate your imagination and enrich your reading journey.
            <?php endif; ?>
         </p>
      </div>

      <!-- Filters & Sorting -->
      <div class="flex flex-wrap items-center justify-between mb-12 gap-4">
         <div class="flex flex-wrap gap-3">
            <a href="?search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>" 
               class="px-4 py-2 <?php echo empty($filter) || $filter === 'all' ? 'bg-primary-500 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200'; ?> rounded-full text-sm font-medium transition-colors">
               All Books
            </a>
            <a href="?search=<?php echo urlencode($search); ?>&filter=fiction&sort=<?php echo urlencode($sort); ?>" 
               class="px-4 py-2 <?php echo $filter === 'fiction' ? 'bg-primary-500 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200'; ?> rounded-full text-sm font-medium transition-colors">
               Fiction
            </a>
            <a href="?search=<?php echo urlencode($search); ?>&filter=non-fiction&sort=<?php echo urlencode($sort); ?>" 
               class="px-4 py-2 <?php echo $filter === 'non-fiction' ? 'bg-primary-500 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200'; ?> rounded-full text-sm font-medium transition-colors">
               Non-Fiction
            </a>
            <a href="?search=<?php echo urlencode($search); ?>&filter=poetry&sort=<?php echo urlencode($sort); ?>" 
               class="px-4 py-2 <?php echo $filter === 'poetry' ? 'bg-primary-500 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200'; ?> rounded-full text-sm font-medium transition-colors">
               Poetry
            </a>
            <a href="?search=<?php echo urlencode($search); ?>&filter=biography&sort=<?php echo urlencode($sort); ?>" 
               class="px-4 py-2 <?php echo $filter === 'biography' ? 'bg-primary-500 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200'; ?> rounded-full text-sm font-medium transition-colors">
               Biography
            </a>
         </div>
         
         <div class="flex items-center gap-3">
            <span class="text-sage-600 text-sm">Sort by:</span>
            <select onchange="window.location.href='?search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>&sort=' + this.value" 
                    class="px-3 py-2 border border-sage-200 rounded-lg text-sage-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
               <option value="default" <?php echo $sort === 'default' || $sort === 'featured' ? 'selected' : ''; ?>>Default</option>
               <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest First</option>
               <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
               <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
               <option value="title_az" <?php echo $sort === 'title_az' ? 'selected' : ''; ?>>Title: A-Z</option>
               <option value="title_za" <?php echo $sort === 'title_za' ? 'selected' : ''; ?>>Title: Z-A</option>
            </select>
         </div>
      </div>

      <!-- Products Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
         <?php  
            // Execute the query with prepared statement if there are parameters
            if (!empty($params)) {
               $stmt = $conn->prepare($query);
               if (!empty($types)) {
                  $stmt->bind_param($types, ...$params);
               }
               $stmt->execute();
               $select_products = $stmt->get_result();
            } else {
               $select_products = mysqli_query($conn, $query) or die('Query failed');
            }

            if (mysqli_num_rows($select_products) > 0) {
               while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
         <form action="" method="post" class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-sage-100 hover:border-primary-200 hover:-translate-y-1">
            <!-- Product Image -->
            <div class="relative overflow-hidden bg-gradient-to-br from-cream-50 to-sage-50 aspect-[3/4]">
               <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" 
                    alt="<?php echo $fetch_products['name']; ?>"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
               
               <!-- Overlay -->
               <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>
            
            <!-- Product Info -->
            <div class="p-6">
               <h3 class="font-serif font-bold text-lg text-sage-800 mb-2 group-hover:text-primary-600 transition-colors leading-tight">
                  <?php echo $fetch_products['name']; ?>
               </h3>
               
               <!-- Author -->
               <p class="text-sage-600 text-sm font-medium mb-2 flex items-center">
                  <i class="fas fa-user-edit mr-2 text-primary-500"></i>
                  by <?php echo htmlspecialchars($fetch_products['author']); ?>
               </p>

               <!-- Category -->
               <div class="mb-3">
                  <span class="inline-block bg-primary-100 text-primary-700 px-2 py-1 rounded-full text-xs font-medium">
                     <?php echo htmlspecialchars($fetch_products['category']); ?>
                  </span>
               </div>
               
               <!-- Rating -->
               <div class="flex items-center gap-1 mb-3">
                  <div class="flex text-yellow-400 text-sm">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star text-sage-300"></i>
                  </div>
                  <span class="text-sage-500 text-sm ml-1">(4.2)</span>
               </div>
               
               <!-- Price -->
               <div class="flex items-center justify-between mb-4">
                     <span class="text-2xl font-bold text-primary-600">₹<?php echo $fetch_products['price']; ?></span>
               </div>
               
               <!-- Quantity & Add to Cart -->
               <div class="flex items-center gap-3">
                  <div class="flex items-center border border-sage-200 rounded-lg overflow-hidden">
                     <button type="button" onclick="decreaseQty(this)" class="px-3 py-2 text-sage-600 hover:bg-sage-50 transition-colors">
                        <i class="fas fa-minus text-xs"></i>
                     </button>
                     <input type="number" name="product_quantity" min="1" value="1" 
                            class="w-16 py-2 text-center border-0 focus:outline-none text-sage-800 font-medium" required readonly>
                     <button type="button" onclick="increaseQty(this)" class="px-3 py-2 text-sage-600 hover:bg-sage-50 transition-colors">
                        <i class="fas fa-plus text-xs"></i>
                     </button>
                  </div>
                  
                  <button type="submit" name="add_to_cart" 
                          class="flex-1 px-4 py-3 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                     <i class="fas fa-shopping-cart text-sm"></i>
                     <span>Add to Cart</span>
                  </button>
               </div>
            </div>
            
            <!-- Hidden inputs -->
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
         </form>
         <?php
               }
            } else {
               $emptyMessage = 'We are working hard to curate an amazing collection of books for you. Please check back soon!';
               $emptyTitle = 'No Products Available';
               
               if (!empty($search)) {
                  $emptyTitle = 'No Results Found';
                  $emptyMessage = 'Sorry, we couldn\'t find any books matching "' . htmlspecialchars($search) . '". Try adjusting your search terms or browse our categories.';
               } elseif (!empty($filter)) {
                  $emptyTitle = 'No ' . ucfirst(str_replace('-', ' ', $filter)) . ' Books Available';
                  $emptyMessage = 'We don\'t have any ' . str_replace('-', ' ', $filter) . ' books in stock right now. Check out our other categories or try again later.';
               }
               
               echo '
               <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                  <div class="w-32 h-32 bg-sage-100 rounded-full flex items-center justify-center mb-6">
                     <i class="fas fa-book text-4xl text-sage-400"></i>
                  </div>
                  <h3 class="text-2xl font-serif font-bold text-sage-800 mb-4">' . $emptyTitle . '</h3>
                  <p class="text-sage-600 max-w-md mb-6">
                     ' . $emptyMessage . '
                  </p>';
                  
               if (!empty($search) || !empty($filter)) {
                  echo '<div class="flex gap-4">
                     <a href="shop.php" class="px-6 py-3 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors">
                        View All Books
                     </a>
                     <a href="contact.php" class="px-6 py-3 border-2 border-sage-300 text-sage-700 font-medium rounded-lg hover:bg-sage-50 transition-colors">
                        Contact Us
                     </a>
                  </div>';
               } else {
                  echo '<a href="contact.php" class="px-6 py-3 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors">
                     Contact Us
                  </a>';
               }
               
               echo '</div>';
            }
         ?>
      </div>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JavaScript -->
<script>
   // Quantity controls
   function increaseQty(button) {
      const input = button.parentElement.querySelector('input[name="product_quantity"]');
      input.value = parseInt(input.value) + 1;
   }
   
   function decreaseQty(button) {
      const input = button.parentElement.querySelector('input[name="product_quantity"]');
      if (parseInt(input.value) > 1) {
         input.value = parseInt(input.value) - 1;
      }
   }
   
   // Auto-hide success messages
   setTimeout(() => {
      const messages = document.querySelectorAll('.fixed.top-20 > div');
      messages.forEach(msg => {
         msg.style.opacity = '0';
         msg.style.transform = 'translateY(-20px)';
         setTimeout(() => msg.remove(), 300);
      });
   }, 4000);
   
   // Smooth scroll for scroll indicator
   document.querySelector('.animate-bounce')?.addEventListener('click', () => {
      document.querySelector('.py-20.bg-white').scrollIntoView({ behavior: 'smooth' });
   });
</script>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
