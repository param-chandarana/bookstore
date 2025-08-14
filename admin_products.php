<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $author = $_POST['author'];
   $category = $_POST['category'];
   $description = $_POST['description'];
   $price = $_POST['price'];
   $stock_quantity = $_POST['stock_quantity'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $stmt_select = $conn->prepare("SELECT name FROM `products` WHERE name = ?");
   $stmt_select->bind_param("s", $name);
   $stmt_select->execute();
   $select_product_name = $stmt_select->get_result();

   if($select_product_name->num_rows > 0){
      $message[] = 'product name already added';
   }else{
      $stmt_insert = $conn->prepare("INSERT INTO `products`(name, author, category, description, price, stock_quantity, image) VALUES(?, ?, ?, ?, ?, ?, ?)");
      $stmt_insert->bind_param("ssssiis", $name, $author, $category, $description, $price, $stock_quantity, $image);
      $stmt_insert->execute();
      $add_product_query = $stmt_insert->affected_rows > 0;
      $stmt_insert->close();

      if($add_product_query){
         if($image_size > 2000000){
            $message[] = 'image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Product added successfully!';
         }
      }else{
         $message[] = 'product could not be added!';
      }
   }
   $stmt_select->close();
}

if(isset($_GET['delete'])){
   $delete_id = intval($_GET['delete']);
   $stmt_img = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $stmt_img->bind_param("i", $delete_id);
   $stmt_img->execute();
   $delete_image_query = $stmt_img->get_result();
   $fetch_delete_image = $delete_image_query->fetch_assoc();
   if ($fetch_delete_image) {
      unlink('uploaded_img/'.$fetch_delete_image['image']);
   }
   $stmt_img->close();
   $stmt_del = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $stmt_del->bind_param("i", $delete_id);
   $stmt_del->execute();
   $stmt_del->close();
   header('location:admin_products.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_author = $_POST['update_author'];
   $update_category = $_POST['update_category'];
   $update_description = $_POST['update_description'];
   $update_price = $_POST['update_price'];
   $update_stock_quantity = $_POST['update_stock_quantity'];

   $stmt_update = $conn->prepare("UPDATE `products` SET name = ?, author = ?, category = ?, description = ?, price = ?, stock_quantity = ? WHERE id = ?");
   $stmt_update->bind_param("ssssiii", $update_name, $update_author, $update_category, $update_description, $update_price, $update_stock_quantity, $update_p_id);
   $stmt_update->execute();
   $stmt_update->close();

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products Management - Admin Panel</title>

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

<!-- Product Management Section -->
<main class="flex-1 pt-8 pb-12">
   <div class="container mx-auto px-4 lg:px-6">
      
      <!-- Page Header -->
      <div class="mb-8">
         <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center shadow-lg">
               <i class="fas fa-book text-2xl text-white"></i>
            </div>
            <div>
               <h1 class="text-3xl lg:text-4xl font-serif font-bold text-sage-800">
                  Product Management
               </h1>
               <p class="text-sage-600">Add, edit, and manage your book inventory</p>
            </div>
         </div>
         <div class="w-24 h-1 bg-gradient-primary rounded-full"></div>
      </div>

      <!-- Add Product Form -->
      <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-8 mb-8">
         <div class="flex items-center space-x-3 mb-6">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
               <i class="fas fa-plus text-green-600"></i>
            </div>
            <h2 class="text-2xl font-serif font-bold text-sage-800">Add New Product</h2>
         </div>

         <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
               <!-- Product Name -->
               <div>
                  <label class="block text-sm font-medium text-sage-700 mb-2">
                     Product Name *
                  </label>
                  <input type="text" name="name" required
                         placeholder="Enter product name"
                         class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
               </div>

               <!-- Author Name -->
               <div>
                  <label class="block text-sm font-medium text-sage-700 mb-2">
                     Author Name *
                  </label>
                  <input type="text" name="author" required
                         placeholder="Enter author name"
                         class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
               </div>

               <!-- Category -->
               <div>
                  <label class="block text-sm font-medium text-sage-700 mb-2">
                     Category *
                  </label>
                  <select name="category" required
                          class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                     <option value="">Select Category</option>
                     <option value="Fiction">Fiction</option>
                     <option value="Non-Fiction">Non-Fiction</option>
                     <option value="Poetry">Poetry</option>
                     <option value="Biography">Biography</option>
                  </select>
                  </select>
               </div>

               <!-- Product Price -->
               <div>
                  <label class="block text-sm font-medium text-sage-700 mb-2">
                     Product Price (₹) *
                  </label>
                  <input type="number" name="price" min="0" required
                         placeholder="Enter product price"
                         class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
               </div>

               <!-- Stock Quantity -->
               <div>
                  <label class="block text-sm font-medium text-sage-700 mb-2">
                     Stock Quantity *
                  </label>
                  <input type="number" name="stock_quantity" min="0" required
                         placeholder="Enter stock quantity"
                         class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
               </div>
            </div>

            <!-- Description -->
            <div>
               <label class="block text-sm font-medium text-sage-700 mb-2">
                  Product Description
               </label>
               <textarea name="description" rows="4"
                         placeholder="Enter product description (optional)"
                         class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-vertical"></textarea>
               <p class="text-sm text-sage-500 mt-1">Brief description of the book</p>
            </div>

            <!-- Product Image -->
            <div>
               <label class="block text-sm font-medium text-sage-700 mb-2">
                  Product Image *
               </label>
               <div class="relative">
                  <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" required
                         class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
               </div>
               <p class="text-sm text-sage-500 mt-1">Accepted formats: JPG, JPEG, PNG.</p>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
               <button type="submit" name="add_product"
                       class="bg-primary-500 text-white font-semibold py-3 px-8 rounded-lg hover:bg-primary-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                  <i class="fas fa-plus mr-2"></i>
                  Add Product
               </button>
            </div>
         </form>
      </div>

      <!-- Products Grid -->
      <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-8">
         <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
               <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                  <i class="fas fa-book text-blue-600"></i>
               </div>
               <h2 class="text-2xl font-serif font-bold text-sage-800">Current Products</h2>
            </div>
            <?php
               $select_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM `products`") or die('query failed');
               $count_result = mysqli_fetch_assoc($select_count);
               $total_products = $count_result['total'];
            ?>
            <span class="bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-medium">
               <?php echo $total_products; ?> Products
            </span>
         </div>

         <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products` ORDER BY id DESC") or die('query failed');
            if(mysqli_num_rows($select_products) > 0){
         ?>
         
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php while($fetch_products = mysqli_fetch_assoc($select_products)){ ?>
            <div class="bg-cream-50 rounded-xl border border-sage-100 overflow-hidden hover:shadow-lg transition-all duration-300 group">
               <!-- Product Image -->
               <div class="relative overflow-hidden bg-gradient-to-br from-cream-50 to-sage-50 aspect-[3/4]">
                  <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" 
                       alt="<?php echo $fetch_products['name']; ?>"
                       class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                  <div class="absolute top-3 right-3">
                     <span class="bg-primary-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                        ID: <?php echo $fetch_products['id']; ?>
                     </span>
                  </div>
               </div>

               <!-- Product Info -->
               <div class="p-6">
                  <h3 class="font-semibold text-sage-800 mb-1 line-clamp-2">
                     <?php echo $fetch_products['name']; ?>
                  </h3>
                  <p class="text-sage-600 text-sm mb-2">
                     by <?php echo $fetch_products['author']; ?>
                  </p>
                  <div class="mb-3">
                     <span class="inline-block bg-sage-100 text-sage-700 px-2 py-1 rounded-full text-xs font-medium">
                        <?php echo $fetch_products['category']; ?>
                     </span>
                  </div>
                  <div class="flex items-center justify-between mb-3">
                     <div class="text-2xl font-bold text-primary-600">
                        ₹<?php echo number_format($fetch_products['price']); ?>
                     </div>
                     <div class="text-sm text-sage-600">
                        Stock: <?php echo $fetch_products['stock_quantity']; ?>
                     </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex space-x-2">
                     <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" 
                        class="flex-1 bg-yellow-500 text-white text-center py-2 px-4 rounded-lg hover:bg-yellow-600 transition-colors text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                     </a>
                     <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" 
                        onclick="return confirm('Are you sure you want to delete this product?');"
                        class="flex-1 bg-accent-500 text-white text-center py-2 px-4 rounded-lg hover:bg-accent-600 transition-colors text-sm font-medium">
                        <i class="fas fa-trash mr-1"></i>
                        Delete
                     </a>
                  </div>
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
               <i class="fas fa-box-open text-4xl text-sage-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-sage-700 mb-2">No Products Found</h3>
            <p class="text-sage-500 mb-6">Start by adding your first product to the inventory.</p>
            <button onclick="document.querySelector('input[name=name]').focus()" 
                    class="bg-primary-500 text-white px-6 py-3 rounded-lg hover:bg-primary-600 transition-colors">
               <i class="fas fa-plus mr-2"></i>
               Add First Product
            </button>
         </div>
         <?php } ?>
      </div>

   </div>
</main>

<!-- Edit Product Modal -->
<?php
   if(isset($_GET['update'])){
      $update_id = intval($_GET['update']);
      $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
      if(mysqli_num_rows($update_query) > 0){
         $fetch_update = mysqli_fetch_assoc($update_query);
?>

<!-- Modal Overlay -->
<div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
   <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <!-- Modal Header -->
      <div class="border-b border-sage-100 p-6">
         <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
               <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                  <i class="fas fa-edit text-yellow-600"></i>
               </div>
               <h2 class="text-2xl font-serif font-bold text-sage-800">Edit Product</h2>
            </div>
            <button onclick="window.location.href='admin_products.php'" 
                    class="text-sage-400 hover:text-sage-600 transition-colors">
               <i class="fas fa-times text-xl"></i>
            </button>
         </div>
      </div>

      <!-- Modal Content -->
      <div class="p-6">
         <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
            <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">

            <!-- Current Product Image -->
            <div class="text-center">
               <div class="inline-block relative">
                  <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" 
                       alt="Current product image"
                       class="w-48 h-48 object-cover rounded-xl border border-sage-200 shadow-lg">
                  <div class="absolute top-2 right-2 bg-primary-500 text-white px-2 py-1 rounded-full text-xs">
                     Current Image
                  </div>
               </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
               <!-- Product Name -->
               <div>
                  <label class="block text-sm font-medium text-sage-700 mb-2">
                     Product Name *
                  </label>
                  <input type="text" name="update_name" required
                         value="<?php echo $fetch_update['name']; ?>"
                         placeholder="Enter product name"
                         class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
               </div>

               <!-- Author Name -->
               <div>
                  <label class="block text-sm font-medium text-sage-700 mb-2">
                     Author Name *
                  </label>
                  <input type="text" name="update_author" required
                         value="<?php echo $fetch_update['author']; ?>"
                         placeholder="Enter author name"
                         class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
               </div>

               <!-- Category -->
               <div>
                  <label class="block text-sm font-medium text-sage-700 mb-2">
                     Category *
                  </label>
                  <select name="update_category" required
                          class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                     <option value="">Select Category</option>
                     <option value="Fiction" <?php echo ($fetch_update['category'] == 'Fiction') ? 'selected' : ''; ?>>Fiction</option>
                     <option value="Non-Fiction" <?php echo ($fetch_update['category'] == 'Non-Fiction') ? 'selected' : ''; ?>>Non-Fiction</option>
                     <option value="Poetry" <?php echo ($fetch_update['category'] == 'Poetry') ? 'selected' : ''; ?>>Poetry</option>
                     <option value="Biography" <?php echo ($fetch_update['category'] == 'Biography') ? 'selected' : ''; ?>>Biography</option>
                  </select>
               </div>

               <!-- Product Price -->
               <div>
                  <label class="block text-sm font-medium text-sage-700 mb-2">
                     Product Price (₹) *
                  </label>
                  <input type="number" name="update_price" min="0" required
                         value="<?php echo $fetch_update['price']; ?>"
                         placeholder="Enter product price"
                         class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
               </div>

               <!-- Stock Quantity -->
               <div>
                  <label class="block text-sm font-medium text-sage-700 mb-2">
                     Stock Quantity *
                  </label>
                  <input type="number" name="update_stock_quantity" min="0" required
                         value="<?php echo $fetch_update['stock_quantity']; ?>"
                         placeholder="Enter stock quantity"
                         class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
               </div>
            </div>

            <!-- Description (Full Width) -->
            <div>
               <label class="block text-sm font-medium text-sage-700 mb-2">
                  Product Description *
               </label>
               <textarea name="update_description" required rows="4"
                         placeholder="Enter product description"
                         class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"><?php echo $fetch_update['description']; ?></textarea>
            </div>

            <!-- New Product Image -->
            <div>
               <label class="block text-sm font-medium text-sage-700 mb-2">
                  Update Product Image (Optional)
               </label>
               <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png"
                      class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
               <p class="text-sm text-sage-500 mt-1">Leave empty to keep current image. Max size: 2MB</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-4 pt-6 border-t border-sage-100">
               <button type="submit" name="update_product"
                       class="flex-1 bg-primary-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-primary-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                  <i class="fas fa-save mr-2"></i>
                  Update Product
               </button>
               <button type="button" onclick="window.location.href='admin_products.php'"
                       class="flex-1 bg-sage-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-sage-600 transition-all duration-300">
                  <i class="fas fa-times mr-2"></i>
                  Cancel
               </button>
            </div>
         </form>
      </div>
   </div>
</div>

<?php
      }
   }
?>

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

   // Close modal on ESC key
   document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
         const modal = document.querySelector('.fixed.inset-0');
         if (modal) {
            window.location.href = 'admin_products.php';
         }
      }
   });
</script>

<?php include 'admin_footer.php'; ?>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>