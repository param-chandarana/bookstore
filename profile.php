<?php
include 'config.php';
session_start();

// Check if user is logged in (either regular user or admin)
$user_id = null;
$is_admin = false;

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
   $is_admin = false;
} elseif (isset($_SESSION['admin_id'])) {
   $user_id = $_SESSION['admin_id'];
   $is_admin = true;
}

if (!$user_id) {
   header('location:login.php');
   exit;
}

// Initialize message array
$message = [];

// Handle profile update
if (isset($_POST['update_profile'])) {
   $name = $_POST['name'];
   $email = $_POST['email'];

   // Check if email already exists for another user
   $stmt_check = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND id != ?");
   $stmt_check->bind_param("si", $email, $user_id);
   $stmt_check->execute();
   $check_email = $stmt_check->get_result();

   if ($check_email->num_rows > 0) {
      $message[] = 'Email already exists for another account!';
   } else {
      // Update user profile
      $stmt_update = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
      $stmt_update->bind_param("ssi", $name, $email, $user_id);
      
      if ($stmt_update->execute()) {
         // Update session variables based on user type
         if ($is_admin) {
            $_SESSION['admin_name'] = $name;
            $_SESSION['admin_email'] = $email;
         } else {
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
         }
         $message[] = 'Profile updated successfully!';
      } else {
         $message[] = 'Failed to update profile!';
      }
      $stmt_update->close();
   }
   $stmt_check->close();
}

// Handle password change
if (isset($_POST['change_password'])) {
   $current_password = md5($_POST['current_password']);
   $new_password = $_POST['new_password'];
   $confirm_password = $_POST['confirm_password'];

   // Verify current password
   $stmt_verify = $conn->prepare("SELECT * FROM `users` WHERE id = ? AND password = ?");
   $stmt_verify->bind_param("is", $user_id, $current_password);
   $stmt_verify->execute();
   $verify_result = $stmt_verify->get_result();

   if ($verify_result->num_rows == 0) {
      $message[] = 'Current password is incorrect!';
   } elseif ($new_password !== $confirm_password) {
      $message[] = 'New passwords do not match!';
   } elseif (strlen($new_password) < 6) {
      $message[] = 'New password must be at least 6 characters long!';
   } else {
      $hashed_new_password = md5($new_password);
      $stmt_password = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
      $stmt_password->bind_param("si", $hashed_new_password, $user_id);
      
      if ($stmt_password->execute()) {
         $message[] = 'Password changed successfully!';
      } else {
         $message[] = 'Failed to change password!';
      }
      $stmt_password->close();
   }
   $stmt_verify->close();
}

// Fetch current user data
$stmt_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user_data = $user_result->fetch_assoc();
$stmt_user->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Profile - BookHaven | Manage Your Account</title>

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
   </style>
</head>
<body class="bg-cream-50 font-sans">

<?php 
if ($is_admin) {
   include 'admin_header.php'; 
} else {
   include 'header.php'; 
}
?>

<!-- Success/Error Messages -->
<?php if (isset($message) && is_array($message) && !empty($message)): ?>
   <div class="fixed top-24 left-1/2 transform -translate-x-1/2 space-y-2 z-50">
      <?php foreach ($message as $msg): ?>
         <?php 
            $isSuccess = strpos($msg, 'successfully') !== false;
            $bgColor = $isSuccess ? 'bg-green-500' : 'bg-accent-500';
            $icon = $isSuccess ? 'fa-check-circle' : 'fa-exclamation-circle';
         ?>
         <div class="<?php echo $bgColor; ?> text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-up">
            <i class="fas <?php echo $icon; ?>"></i>
            <span><?php echo $msg; ?></span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-opacity-80">
               <i class="fas fa-times"></i>
            </button>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>

<!-- Breadcrumb -->
<section class="bg-white border-b border-sage-100 py-6">
   <div class="container mx-auto px-4">
      <nav class="flex items-center space-x-2 text-sage-600">
         <a href="index.php" class="hover:text-primary-600 transition-colors">Home</a>
         <i class="fas fa-chevron-right text-sm"></i>
         <span class="text-sage-800 font-medium">My Profile</span>
      </nav>
   </div>
</section>

<!-- Profile Section -->
<section class="py-12">
   <div class="container mx-auto px-4">
      <!-- Page Header -->
      <div class="text-center mb-12">
         <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-primary rounded-2xl mb-4 shadow-lg">
            <i class="fas fa-user text-2xl text-white"></i>
         </div>
         <h1 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-4">
            My Profile
         </h1>
         <p class="text-xl text-sage-600 max-w-2xl mx-auto">
            Manage your account information
         </p>
      </div>

      <div class="max-w-4xl mx-auto">
         <!-- Profile Overview Card -->
         <div class="bg-white rounded-2xl shadow-lg border border-sage-100 p-8 mb-8">
            <div class="flex flex-col md:flex-row items-center md:items-center space-y-4 md:space-y-0 md:space-x-6">
               <div class="w-24 h-24 bg-gradient-primary rounded-full flex items-center justify-center shadow-lg">
                  <i class="fas fa-user text-3xl text-white"></i>
               </div>
               <div class="text-center md:text-left flex-1">
                  <h2 class="text-2xl font-serif font-bold text-sage-800 mb-2">
                     <?php echo htmlspecialchars($user_data['name']); ?>
                  </h2>
                  <p class="text-sage-600 mb-1">
                     <i class="fas fa-envelope mr-2 text-primary-500"></i>
                     <?php echo htmlspecialchars($user_data['email']); ?>
                  </p>
                  <?php if($user_data['user_type'] == 'admin'): ?>
                  <div class="flex items-center justify-center md:justify-start mt-3">
                     <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm font-medium">
                        <i class="fas fa-crown mr-1"></i>
                        <?php echo ucfirst($user_data['user_type']); ?> Account
                     </span>
                  </div>
                  <?php endif; ?>
               </div>
            </div>
         </div>

         <!-- Tab Navigation -->
         <div class="bg-white rounded-2xl shadow-lg border border-sage-100 overflow-hidden">
            <div class="border-b border-sage-100">
               <nav class="flex">
                  <button onclick="showTab('profile-info')" id="tab-profile-info" class="tab-button flex-1 px-6 py-4 text-center font-medium transition-colors border-b-2 border-primary-500 text-primary-600 bg-primary-50">
                     <i class="fas fa-user mr-2"></i>
                     Profile Information
                  </button>
                  <button onclick="showTab('security')" id="tab-security" class="tab-button flex-1 px-6 py-4 text-center font-medium transition-colors border-b-2 border-transparent text-sage-600 hover:text-primary-600 hover:bg-sage-50">
                     <i class="fas fa-lock mr-2"></i>
                     Security
                  </button>
               </nav>
            </div>

            <!-- Profile Information Tab -->
            <div id="tab-content-profile-info" class="tab-content p-8">
               <form method="POST" action="" class="space-y-6">
                  <div class="grid md:grid-cols-2 gap-6">
                     <!-- Name -->
                     <div>
                        <label class="block text-sm font-medium text-sage-700 mb-2">
                           Full Name *
                        </label>
                        <input type="text" name="name" required 
                               value="<?php echo htmlspecialchars($user_data['name']); ?>"
                               class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                     </div>

                     <!-- Email -->
                     <div>
                        <label class="block text-sm font-medium text-sage-700 mb-2">
                           Email Address *
                        </label>
                        <input type="email" name="email" required 
                               value="<?php echo htmlspecialchars($user_data['email']); ?>"
                               class="w-full px-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                     </div>
                  </div>

                  <div class="pt-6 border-t border-sage-200">
                     <button type="submit" name="update_profile" 
                             class="bg-primary-500 text-white font-semibold py-3 px-8 rounded-lg hover:bg-primary-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i>
                        Update Profile
                     </button>
                  </div>
               </form>
            </div>

            <!-- Security Tab -->
            <div id="tab-content-security" class="tab-content p-8 hidden">
               <form method="POST" action="" class="space-y-6">
                  <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                     <div class="flex items-center">
                        <i class="fas fa-shield-alt text-yellow-600 mr-3"></i>
                        <div>
                           <h3 class="font-semibold text-yellow-800">Password Security</h3>
                           <p class="text-sm text-yellow-700">Use a strong password to keep your account secure.</p>
                        </div>
                     </div>
                  </div>

                  <!-- Current Password -->
                  <div>
                     <label class="block text-sm font-medium text-sage-700 mb-2">
                        Current Password *
                     </label>
                     <div class="relative">
                        <input type="password" name="current_password" required 
                               placeholder="Enter your current password"
                               id="current_password"
                               class="w-full px-4 py-3 pr-12 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <button type="button" onclick="togglePassword('current_password', 'toggleIcon1')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sage-400 hover:text-sage-600">
                           <i class="fas fa-eye" id="toggleIcon1"></i>
                        </button>
                     </div>
                  </div>

                  <!-- New Password -->
                  <div>
                     <label class="block text-sm font-medium text-sage-700 mb-2">
                        New Password *
                     </label>
                     <div class="relative">
                        <input type="password" name="new_password" required 
                               placeholder="Enter new password (min 6 characters)"
                               id="new_password"
                               class="w-full px-4 py-3 pr-12 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <button type="button" onclick="togglePassword('new_password', 'toggleIcon2')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sage-400 hover:text-sage-600">
                           <i class="fas fa-eye" id="toggleIcon2"></i>
                        </button>
                     </div>
                  </div>

                  <!-- Confirm New Password -->
                  <div>
                     <label class="block text-sm font-medium text-sage-700 mb-2">
                        Confirm New Password *
                     </label>
                     <div class="relative">
                        <input type="password" name="confirm_password" required 
                               placeholder="Confirm your new password"
                               id="confirm_password"
                               class="w-full px-4 py-3 pr-12 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <button type="button" onclick="togglePassword('confirm_password', 'toggleIcon3')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sage-400 hover:text-sage-600">
                           <i class="fas fa-eye" id="toggleIcon3"></i>
                        </button>
                     </div>
                  </div>

                  <div class="pt-6 border-t border-sage-200">
                     <button type="submit" name="change_password" 
                             class="bg-accent-500 text-white font-semibold py-3 px-8 rounded-lg hover:bg-accent-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-key mr-2"></i>
                        Change Password
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>

<?php 
if (!$is_admin) {
   include 'footer.php'; 
}
?>

<!-- JavaScript -->
<script>
   // Tab functionality
   function showTab(tabName) {
      // Hide all tab contents
      const tabContents = document.querySelectorAll('.tab-content');
      tabContents.forEach(content => content.classList.add('hidden'));
      
      // Remove active state from all tab buttons
      const tabButtons = document.querySelectorAll('.tab-button');
      tabButtons.forEach(button => {
         button.classList.remove('border-primary-500', 'text-primary-600', 'bg-primary-50');
         button.classList.add('border-transparent', 'text-sage-600');
      });
      
      // Show selected tab content
      document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');
      
      // Add active state to selected tab button
      const activeButton = document.getElementById(`tab-${tabName}`);
      activeButton.classList.remove('border-transparent', 'text-sage-600');
      activeButton.classList.add('border-primary-500', 'text-primary-600', 'bg-primary-50');
   }

   // Password toggle functionality
   function togglePassword(fieldId, iconId) {
      const passwordField = document.getElementById(fieldId);
      const toggleIcon = document.getElementById(iconId);
      
      if (passwordField.type === 'password') {
         passwordField.type = 'text';
         toggleIcon.classList.remove('fa-eye');
         toggleIcon.classList.add('fa-eye-slash');
      } else {
         passwordField.type = 'password';
         toggleIcon.classList.remove('fa-eye-slash');
         toggleIcon.classList.add('fa-eye');
      }
   }

   // Form validation for password change
   document.querySelector('form[method="POST"]').addEventListener('submit', function(e) {
      if (e.target.querySelector('input[name="change_password"]')) {
         const newPassword = document.querySelector('input[name="new_password"]').value;
         const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
         
         if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('New passwords do not match!');
            return false;
         }
         
         if (newPassword.length < 6) {
            e.preventDefault();
            alert('New password must be at least 6 characters long!');
            return false;
         }
      }
   });

   // Auto-hide messages
   setTimeout(() => {
      const messages = document.querySelectorAll('.fixed.top-24 > div');
      messages.forEach(msg => {
         msg.style.opacity = '0';
         msg.style.transform = 'translateY(-20px)';
         setTimeout(() => msg.remove(), 300);
      });
   }, 5000);
</script>

</body>
</html>
