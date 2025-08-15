<?php
include 'config.php';
include 'input_sanitization.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit;
}

if (isset($_POST['send'])) {
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
      'number' => [
         'type' => 'phone',
         'required' => true
      ],
      'message' => [
         'type' => 'string',
         'required' => true,
         'max_length' => 1000
      ]
   ];
   
   // Validate and sanitize inputs
   $validation_result = validateInputs($_POST, $validation_rules);
   
   if (!$validation_result['valid']) {
      foreach ($validation_result['errors'] as $error) {
         $message[] = $error;
      }
   } else {
      $name = $validation_result['data']['name'];
      $email = $validation_result['data']['email'];
      $number = $validation_result['data']['number'];
      $msg = $validation_result['data']['message'];

      $stmt_select = $conn->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
      $stmt_select->bind_param("ssss", $name, $email, $number, $msg);
      $stmt_select->execute();
      $select_message = $stmt_select->get_result();

      if ($select_message->num_rows > 0) {
         $message[] = 'Message has already been sent!';
      } else {
         $stmt_insert = $conn->prepare("INSERT INTO `message` (user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)");
         $stmt_insert->bind_param("issss", $user_id, $name, $email, $number, $msg);
         $stmt_insert->execute();
         $stmt_insert->close();
         $message[] = 'Message sent successfully!';
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
   <title>Contact Us - BookHaven | Get in Touch</title>

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
<?php if (isset($message)): ?>
   <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 space-y-2">
      <?php foreach ($message as $msg): ?>
         <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-up">
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
               <span class="text-cream-300">Contact</span>
            </div>
         </nav>
         
         <!-- Hero Content -->
         <div class="animate-fade-in">
            <span class="inline-block px-6 py-3 bg-cream-400/20 rounded-full text-cream-100 text-lg font-medium mb-8 backdrop-blur-sm">
               Get in Touch
            </span>
            <h1 class="text-5xl lg:text-7xl font-serif font-bold leading-tight mb-8">
               We'd Love to
               <span class="text-transparent bg-clip-text bg-gradient-to-r from-cream-200 to-cream-400 block">
                  Hear From You
               </span>
            </h1>
            <p class="text-xl lg:text-2xl text-blue-100 mb-12 leading-relaxed max-w-3xl mx-auto">
               Whether you need book recommendations, have questions about your order, or just want to chat about literature, we're here to help.
            </p>
         </div>
      </div>
   </div>
   
   <!-- Scroll Indicator -->
   <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-cream-200 animate-bounce">
      <i class="fas fa-chevron-down text-2xl"></i>
   </div>
</section>

<<!-- Contact Section -->
<section class="py-20 bg-white">
   <div class="container mx-auto px-4">
      <div class="grid lg:grid-cols-2 gap-16 items-start">
         <!-- Contact Form -->
         <div class="animate-slide-up">
            <div class="bg-gradient-to-br from-sage-50 to-cream-100 p-8 rounded-3xl shadow-2xl">
               <div class="mb-8">
                  <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-4">
                     Send Message
                  </span>
                  <h2 class="text-3xl lg:text-4xl font-serif font-bold text-sage-800 mb-4">
                     Start a Conversation
                  </h2>
                  <p class="text-sage-600 leading-relaxed">
                     Have a question or suggestion? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                  </p>
               </div>

               <form action="" method="post" class="space-y-6">
                  <!-- Name Field -->
                  <div>
                     <label for="name" class="block text-sm font-medium text-sage-700 mb-2">Full Name</label>
                     <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                           <i class="fas fa-user text-sage-400"></i>
                        </div>
                        <input type="text" 
                               id="name"
                               name="name" 
                               required 
                               placeholder="Enter your full name"
                               class="w-full pl-10 pr-4 py-3 border border-sage-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors placeholder-sage-400">
                     </div>
                  </div>

                  <!-- Email Field -->
                  <div>
                     <label for="email" class="block text-sm font-medium text-sage-700 mb-2">Email Address</label>
                     <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                           <i class="fas fa-envelope text-sage-400"></i>
                        </div>
                        <input type="email" 
                               id="email"
                               name="email" 
                               required 
                               placeholder="Enter your email address"
                               class="w-full pl-10 pr-4 py-3 border border-sage-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors placeholder-sage-400">
                     </div>
                  </div>

                  <!-- Phone Field -->
                  <div>
                     <label for="number" class="block text-sm font-medium text-sage-700 mb-2">Phone Number</label>
                     <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                           <i class="fas fa-phone text-sage-400"></i>
                        </div>
                        <input type="tel" 
                               id="number"
                               name="number" 
                               required 
                               placeholder="Enter your phone number"
                               class="w-full pl-10 pr-4 py-3 border border-sage-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors placeholder-sage-400">
                     </div>
                  </div>

                  <!-- Message Field -->
                  <div>
                     <label for="message" class="block text-sm font-medium text-sage-700 mb-2">Message</label>
                     <div class="relative">
                        <div class="absolute top-3 left-3 pointer-events-none">
                           <i class="fas fa-comment text-sage-400"></i>
                        </div>
                        <textarea id="message"
                                  name="message" 
                                  required 
                                  rows="5"
                                  placeholder="Tell us what's on your mind..."
                                  class="w-full pl-10 pr-4 py-3 border border-sage-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors placeholder-sage-400 resize-none"></textarea>
                     </div>
                  </div>

                  <!-- Submit Button -->
                  <button type="submit" 
                          name="send"
                          class="w-full bg-primary-500 text-white font-bold py-4 px-6 rounded-lg hover:bg-primary-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                     <i class="fas fa-paper-plane"></i>
                     <span>Send Message</span>
                  </button>
               </form>
            </div>
         </div>

         <!-- Contact Information -->
         <div class="space-y-8 animate-slide-up" style="animation-delay: 0.2s;">
            <!-- Contact Info Header -->
            <div>
               <span class="inline-block px-4 py-2 bg-accent-100 text-accent-700 rounded-full text-sm font-medium mb-4">
                  Contact Information
               </span>
               <h2 class="text-3xl lg:text-4xl font-serif font-bold text-sage-800 mb-6">
                  Let's Connect & Build Something Amazing Together
               </h2>
               <p class="text-xl text-sage-600 leading-relaxed">
                  We're passionate about books and love connecting with fellow readers. Reach out through any of these channels.
               </p>
            </div>

            <!-- Contact Cards -->
            <div class="space-y-6">
               <!-- Phone Contact -->
               <div class="bg-gradient-to-r from-primary-50 to-primary-100 p-6 rounded-2xl border border-primary-200 hover:shadow-lg transition-all duration-300 group">
                  <div class="flex items-start gap-4">
                     <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-phone text-white text-lg"></i>
                     </div>
                     <div>
                        <h3 class="text-lg font-bold text-sage-800 mb-2">Phone Support</h3>
                        <p class="text-sage-600 mb-2">Call us for immediate assistance</p>
                        <a href="tel:+919876543210" class="text-primary-600 block font-medium hover:text-primary-700 transition-colors">
                           +91 98765 43210
                        </a>
                        <p class="text-sm text-sage-500 mt-1">Mon-Fri: 9AM - 8PM IST</p>
                     </div>
                  </div>
               </div>

               <!-- Email Contact -->
               <div class="bg-gradient-to-r from-sage-50 to-sage-100 p-6 rounded-2xl border border-sage-200 hover:shadow-lg transition-all duration-300 group">
                  <div class="flex items-start gap-4">
                     <div class="w-12 h-12 bg-sage-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-envelope text-white text-lg"></i>
                     </div>
                     <div>
                        <h3 class="text-lg font-bold text-sage-800 mb-2">Email Us</h3>
                        <p class="text-sage-600 mb-2">Send us an email anytime</p>
                        <a href="mailto:hello@bookhaven.com" class="text-sage-600 font-medium hover:text-sage-700 transition-colors">
                           hello@bookhaven.com
                        </a>
                        <p class="text-sm text-sage-500 mt-1">We reply within 24 hours</p>
                     </div>
                  </div>
               </div>

               <!-- Location Contact -->
               <div class="bg-gradient-to-r from-accent-50 to-accent-100 p-6 rounded-2xl border border-accent-200 hover:shadow-lg transition-all duration-300 group">
                  <div class="flex items-start gap-4">
                     <div class="w-12 h-12 bg-accent-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                     </div>
                     <div>
                        <h3 class="text-lg font-bold text-sage-800 mb-2">Visit Our Store</h3>
                        <p class="text-sage-600 mb-2">Come browse our collection in person</p>
                        <address class="text-sage-600 font-medium not-italic">
                           123 Literary Lane,<br>
                           Book District, Mumbai - 400001
                        </address>
                        <p class="text-sm text-sage-500 mt-1">Mon-Fri: 9AM - 8PM, Sun: 10AM - 6PM</p>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Social Media -->
            <div class="bg-gradient-to-br from-cream-50 to-cream-100 p-6 rounded-2xl">
               <h3 class="text-lg font-bold text-sage-800 mb-4">Follow Us</h3>
               <div class="flex gap-4">
                  <a href="#" class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center text-white hover:bg-primary-600 transition-colors">
                     <i class="fab fa-facebook-f"></i>
                  </a>
                  <a href="#" class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center text-white hover:bg-primary-600 transition-colors">
                     <i class="fab fa-twitter"></i>
                  </a>
                  <a href="#" class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center text-white hover:bg-primary-600 transition-colors">
                     <i class="fab fa-instagram"></i>
                  </a>
                  <a href="#" class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center text-white hover:bg-primary-600 transition-colors">
                     <i class="fab fa-linkedin-in"></i>
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gradient-to-br from-sage-50 to-cream-100">
   <div class="container mx-auto px-4">
      <div class="text-center mb-16">
         <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-4">
            Frequently Asked
         </span>
         <h2 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-6">
            Common Questions
         </h2>
         <p class="text-xl text-sage-600 max-w-2xl mx-auto">
            Quick answers to questions you might have about BookHaven and our services.
         </p>
      </div>

      <div class="max-w-4xl mx-auto">
         <div class="space-y-4">
            <!-- FAQ 1 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
               <button class="w-full px-6 py-6 text-left flex items-center justify-between hover:bg-sage-50 transition-colors" onclick="toggleFAQ(this)">
                  <h3 class="text-lg font-bold text-sage-800">How long does shipping take?</h3>
                  <i class="fas fa-chevron-down text-sage-600 transform transition-transform duration-300"></i>
               </button>
               <div class="px-6 pb-6 text-sage-600 hidden">
                  <p>We offer free standard shipping (3-5 business days) on orders over ₹499. All orders are processed within 24 hours on business days.</p>
               </div>
            </div>

            <!-- FAQ 2 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
               <button class="w-full px-6 py-6 text-left flex items-center justify-between hover:bg-sage-50 transition-colors" onclick="toggleFAQ(this)">
                  <h3 class="text-lg font-bold text-sage-800">What is your return policy?</h3>
                  <i class="fas fa-chevron-down text-sage-600 transform transition-transform duration-300"></i>
               </button>
               <div class="px-6 pb-6 text-sage-600 hidden">
                  <p>We offer a 15-day return policy for all books in original condition. Returns are free and easy - just contact us and we'll send you a prepaid return label. Refunds are processed within 3-5 business days.</p>
               </div>
            </div>

            <!-- FAQ 3 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
               <button class="w-full px-6 py-6 text-left flex items-center justify-between hover:bg-sage-50 transition-colors" onclick="toggleFAQ(this)">
                  <h3 class="text-lg font-bold text-sage-800">Do you offer book recommendations?</h3>
                  <i class="fas fa-chevron-down text-sage-600 transform transition-transform duration-300"></i>
               </button>
               <div class="px-6 pb-6 text-sage-600 hidden">
                  <p>Absolutely! Our team of literary enthusiasts loves helping readers discover their next favourite book. Contact us with your preferences and reading history, and we'll provide personalized recommendations.</p>
               </div>
            </div>

            <!-- FAQ 4 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
               <button class="w-full px-6 py-6 text-left flex items-center justify-between hover:bg-sage-50 transition-colors" onclick="toggleFAQ(this)">
                  <h3 class="text-lg font-bold text-sage-800">Can I track my order?</h3>
                  <i class="fas fa-chevron-down text-sage-600 transform transition-transform duration-300"></i>
               </button>
               <div class="px-6 pb-6 text-sage-600 hidden">
                  <p>Yes! Once your order ships, you'll receive a tracking number via email. You can also log into your account to view order status and tracking information at any time.</p>
               </div>
            </div>

            <!-- FAQ 5 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
               <button class="w-full px-6 py-6 text-left flex items-center justify-between hover:bg-sage-50 transition-colors" onclick="toggleFAQ(this)">
                  <h3 class="text-lg font-bold text-sage-800">Do you have a physical bookstore?</h3>
                  <i class="fas fa-chevron-down text-sage-600 transform transition-transform duration-300"></i>
               </button>
               <div class="px-6 pb-6 text-sage-600 hidden">
                  <p>Yes! Visit our cozy bookstore at 123 Literary Lane in Mumbai. We host regular author events, book clubs, and readings. Contact us or subscribe to our newsletter for upcoming activities.</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JavaScript -->
<script>
   // FAQ Toggle Function
   function toggleFAQ(button) {
      const content = button.nextElementSibling;
      const icon = button.querySelector('i');
      const isOpen = !content.classList.contains('hidden');
      
      // Close all FAQs
      document.querySelectorAll('.bg-white .hidden').forEach(el => {
         if (!el.classList.contains('hidden')) {
            el.classList.add('hidden');
         }
      });
      
      // Reset all icons
      document.querySelectorAll('.bg-white i').forEach(i => {
         i.classList.remove('rotate-180');
      });
      
      // Toggle current FAQ
      if (isOpen) {
         content.classList.add('hidden');
         icon.classList.remove('rotate-180');
      } else {
         content.classList.remove('hidden');
         icon.classList.add('rotate-180');
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
