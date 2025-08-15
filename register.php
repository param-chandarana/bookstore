<?php
include 'config.php';
include 'input_sanitization.php';
include 'error_handling.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
   if (isset($_SESSION['admin_id'])) {
      header('location:admin_page.php');
   } else {
      header('location:index.php');
   }
   exit;
}

// Initialize message array
$message = [];

if (isset($_POST['submit'])) {
   // Define validation rules
   $validation_rules = [
      'name' => [
         'type' => 'string',
         'required' => true,
         'max_length' => 100
      ],
      'email' => [
         'type' => 'email',
         'required' => true
      ],
      'password' => [
         'type' => 'password',
         'required' => true,
         'min_length' => 8
      ],
      'cpassword' => [
         'type' => 'string',
         'required' => true
      ],
      'user_type' => [
         'type' => 'string',
         'required' => true
      ]
   ];
   
   // Validate and sanitize inputs
   $validation_result = validateInputs($_POST, $validation_rules);
   
   if (!$validation_result['valid']) {
      // Add validation errors to messages
      foreach ($validation_result['errors'] as $error) {
         $message[] = $error;
      }
   } else {
      $name = $validation_result['data']['name'];
      $email = $validation_result['data']['email'];
      $pass = $validation_result['data']['password'];
      $cpass = $_POST['cpassword']; // For comparison only
      $user_type = $validation_result['data']['user_type'];
      
      // Validate user type (whitelist)
      if (!in_array($user_type, ['admin', 'user'])) {
         $message[] = 'Invalid user type selected!';
      }
      
      // Validate password match
      elseif ($pass !== $cpass) {
         $message[] = 'Confirm password does not match!';
      } else {
         // Hash the password securely
         $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

         $stmt_select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
         $stmt_select->bind_param("s", $email);
         $stmt_select->execute();
         $select_users = $stmt_select->get_result();

      if ($select_users->num_rows > 0) {
         $message[] = 'User already exists with this email!';
      } else {
         $stmt_insert = $conn->prepare("INSERT INTO `users`(name, email, password, user_type) VALUES(?, ?, ?, ?)");
         $stmt_insert->bind_param("ssss", $name, $email, $hashed_password, $user_type);
         $stmt_insert->execute();
         $stmt_insert->close();
         $message[] = 'Registered successfully!';
         header('location:login.php');
         exit;
      }
      }
      $stmt_select->close();
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register - BookHaven | Create Your Account</title>

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
<body class="bg-gradient-to-br from-sage-50 via-cream-50 to-primary-50 min-h-screen font-sans">

<!-- Messages -->
<?php if (isset($message) && is_array($message) && !empty($message)): ?>
   <div class="fixed top-6 left-1/2 transform -translate-x-1/2 space-y-2 z-50">
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

<!-- Background Elements -->
<div class="absolute inset-0 overflow-hidden pointer-events-none">
   <div class="absolute top-20 right-10 w-32 h-32 bg-primary-200/30 rounded-full blur-3xl animate-float"></div>
   <div class="absolute bottom-20 left-10 w-40 h-40 bg-sage-200/30 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
   <div class="absolute top-1/2 right-1/4 w-24 h-24 bg-cream-300/30 rounded-full blur-2xl animate-float" style="animation-delay: 4s;"></div>
</div>

<!-- Main Content -->
<div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
   <div class="w-full max-w-md">
      <!-- Logo/Brand -->
      <div class="text-center mb-8">
         <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-primary rounded-2xl mb-4 shadow-lg">
            <i class="fas fa-user-plus text-2xl text-white"></i>
         </div>
         <h1 class="text-3xl font-serif font-bold text-sage-800 mb-2">Join BookHaven</h1>
         <p class="text-sage-600">Create your account and start your literary journey</p>
      </div>

      <!-- Register Form -->
      <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-8 animate-fade-in">
         <form action="" method="post" class="space-y-6" id="registerForm">
            <!-- Name Field -->
            <div>
               <label class="block text-sm font-medium text-sage-700 mb-2">
                  Full Name
               </label>
               <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                     <i class="fas fa-user text-sage-400"></i>
                  </div>
                  <input type="text" 
                         name="name" 
                         placeholder="Enter your full name"
                         required 
                         class="w-full pl-10 pr-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 bg-white/50">
               </div>
            </div>

            <!-- Email Field -->
            <div>
               <label class="block text-sm font-medium text-sage-700 mb-2">
                  Email Address
               </label>
               <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                     <i class="fas fa-envelope text-sage-400"></i>
                  </div>
                  <input type="email" 
                         name="email" 
                         placeholder="Enter your email address"
                         required 
                         class="w-full pl-10 pr-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 bg-white/50">
               </div>
            </div>

            <!-- Password Field -->
            <div>
               <label class="block text-sm font-medium text-sage-700 mb-2">
                  Password
               </label>
               <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                     <i class="fas fa-lock text-sage-400"></i>
                  </div>
                  <input type="password" 
                         name="password" 
                         placeholder="Create a password"
                         required 
                         id="password"
                         class="w-full pl-10 pr-12 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 bg-white/50">
                  <button type="button" 
                          onclick="togglePassword('password', 'toggleIcon1')"
                          class="absolute inset-y-0 right-0 pr-3 flex items-center text-sage-400 hover:text-sage-600">
                     <i class="fas fa-eye" id="toggleIcon1"></i>
                  </button>
               </div>
            </div>

            <!-- Confirm Password Field -->
            <div>
               <label class="block text-sm font-medium text-sage-700 mb-2">
                  Confirm Password
               </label>
               <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                     <i class="fas fa-lock text-sage-400"></i>
                  </div>
                  <input type="password" 
                         name="cpassword" 
                         placeholder="Confirm your password"
                         required 
                         id="cpassword"
                         class="w-full pl-10 pr-12 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 bg-white/50">
                  <button type="button" 
                          onclick="togglePassword('cpassword', 'toggleIcon2')"
                          class="absolute inset-y-0 right-0 pr-3 flex items-center text-sage-400 hover:text-sage-600">
                     <i class="fas fa-eye" id="toggleIcon2"></i>
                  </button>
               </div>
            </div>

            <!-- User Type Field -->
            <div>
               <label class="block text-sm font-medium text-sage-700 mb-2">
                  Account Type
               </label>
               <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                     <i class="fas fa-users text-sage-400"></i>
                  </div>
                  <select name="user_type" 
                          required
                          class="w-full pl-10 pr-4 py-3 border border-sage-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 bg-white/50 appearance-none">
                     <option value="">Select account type</option>
                     <option value="user">Customer Account</option>
                     <option value="admin">Admin Account</option>
                  </select>
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                     <i class="fas fa-chevron-down text-sage-400"></i>
                  </div>
               </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="flex items-start">
               <input type="checkbox" 
                      id="terms"
                      required
                      class="mt-1 rounded border-sage-300 text-primary-600 focus:ring-primary-500">
               <label for="terms" class="ml-2 text-sm text-sage-600">
                  I agree to BookHaven's 
                  <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">Terms of Service</a> 
                  and 
                  <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">Privacy Policy</a>
               </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    name="submit"
                    class="w-full bg-gradient-primary text-white font-semibold py-3 px-6 rounded-lg hover:opacity-90 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
               <i class="fas fa-user-plus mr-2"></i>
               Create Account
            </button>
         </form>

         <!-- Divider -->
         <div class="mt-8 pt-6 border-t border-sage-200">
            <p class="text-center text-sage-600">
               Already have an account? 
               <a href="login.php" class="text-primary-600 hover:text-primary-700 font-medium ml-1">
                  Sign in here
               </a>
            </p>
         </div>
      </div>

      <!-- Additional Info -->
      <div class="text-center mt-8 text-sm text-sage-500">
         <p>© 2025 BookHaven. Literary Adventures Await.</p>
      </div>
   </div>
</div>

<!-- JavaScript -->
<script>
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

   // Form validation
   document.getElementById('registerForm').addEventListener('submit', function(e) {
      const password = document.querySelector('input[name="password"]').value;
      const confirmPassword = document.querySelector('input[name="cpassword"]').value;
      
      if (password !== confirmPassword) {
         e.preventDefault();
         alert('Passwords do not match!');
         return false;
      }
      
      if (password.length < 6) {
         e.preventDefault();
         alert('Password must be at least 6 characters long!');
         return false;
      }
   });

   // Auto-hide messages
   setTimeout(() => {
      const messages = document.querySelectorAll('.fixed.top-6 > div');
      messages.forEach(msg => {
         msg.style.opacity = '0';
         msg.style.transform = 'translateY(-20px)';
         setTimeout(() => msg.remove(), 300);
      });
   }, 5000);
</script>

</body>
</html>