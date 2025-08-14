<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
   header('Location: login.php');
   exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About BookHaven - Our Story & Mission</title>

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
<section class="relative min-h-screen bg-gradient-to-br from-primary-600 via-sage-700 to-sage-800 overflow-hidden flex items-center">
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
               <span class="text-cream-300">About Us</span>
            </div>
         </nav>
         
         <!-- Hero Content -->
         <div class="animate-fade-in">
            <span class="inline-block px-6 py-3 bg-cream-400/20 rounded-full text-cream-100 text-lg font-medium mb-8 backdrop-blur-sm">
               Our Story
            </span>
            <h1 class="text-5xl lg:text-7xl font-serif font-bold leading-tight mb-8">
               Passionate About
               <span class="text-transparent bg-clip-text bg-gradient-to-r from-cream-200 to-cream-400 block">
                  Literary Adventures
               </span>
            </h1>
            <p class="text-xl lg:text-2xl text-blue-100 mb-12 leading-relaxed max-w-3xl mx-auto">
               At BookHaven, we believe every book holds the power to transform lives, spark imagination, and connect hearts across the world.
            </p>
         </div>
      </div>
   </div>
   
   <!-- Scroll Indicator -->
   <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-cream-200 animate-bounce">
      <i class="fas fa-chevron-down text-2xl"></i>
   </div>
</section>

<!-- Our Story Section -->
<section class="py-20 bg-white">
   <div class="container mx-auto px-4">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
         <!-- Content Side -->
         <div class="space-y-8 animate-slide-up">
            <div>
               <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-6">
                  Since 2020
               </span>
               <h2 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-6">
                  Our Mission is Simple:
                  <span class="text-transparent bg-clip-text bg-gradient-primary block">
                     Connect Readers with Great Stories
                  </span>
               </h2>
               
               <div class="space-y-6 text-lg text-sage-600 leading-relaxed">
                  <p>
                     Founded with a passion for literature and a dream to make quality books accessible to everyone, BookHaven has grown from a small idea into a thriving community of book lovers.
                  </p>
                  <p>
                     We carefully curate each title in our collection, ensuring that whether you're seeking escape through fiction, knowledge through non-fiction, or inspiration through poetry, you'll find exactly what your soul needs.
                  </p>
                  <p>
                     Our team of literary enthusiasts works tirelessly to discover hidden gems, promote diverse voices, and bring you both timeless classics and contemporary masterpieces.
                  </p>
               </div>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-3 gap-6 pt-8 border-t border-sage-100">
               <div class="text-center">
                  <div class="text-3xl font-bold text-primary-600 mb-2">50K+</div>
                  <div class="text-sm text-sage-600 font-medium">Happy Readers</div>
               </div>
               <div class="text-center">
                  <div class="text-3xl font-bold text-accent-500 mb-2">10K+</div>
                  <div class="text-sm text-sage-600 font-medium">Books Delivered</div>
               </div>
               <div class="text-center">
                  <div class="text-3xl font-bold text-sage-600 mb-2">500+</div>
                  <div class="text-sm text-sage-600 font-medium">Curated Titles</div>
               </div>
            </div>
         </div>
         
         <!-- Image Side -->
         <div class="relative animate-fade-in" style="animation-delay: 0.3s;">
            <div class="relative bg-gradient-to-br from-sage-100 to-cream-100 p-8 rounded-3xl shadow-2xl">
               <img src="images/about-img.jpg" alt="BookHaven Story" 
                    class="w-full h-96 object-cover rounded-2xl shadow-lg">
               
               <!-- Floating Elements -->
               <div class="absolute -top-6 -right-6 w-24 h-24 bg-primary-500 rounded-2xl shadow-xl flex items-center justify-center animate-float">
                  <i class="fas fa-heart text-2xl text-white"></i>
               </div>
               <div class="absolute -bottom-4 -left-4 w-32 h-20 bg-accent-500 rounded-xl shadow-lg flex items-center justify-center">
                  <span class="text-white font-bold text-lg">Est. 2020</span>
               </div>
               <div class="absolute top-1/2 -left-8 w-16 h-16 bg-cream-400 rounded-full shadow-lg flex items-center justify-center">
                  <i class="fas fa-book text-sage-800 text-xl"></i>
               </div>
            </div>
            
            <!-- Background Decoration -->
            <div class="absolute -z-10 top-8 left-8 w-full h-full bg-gradient-sage rounded-3xl opacity-20"></div>
         </div>
      </div>
   </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-gradient-to-br from-sage-50 to-cream-100">
   <div class="container mx-auto px-4">
      <div class="text-center mb-16">
         <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-4">
            What We Stand For
         </span>
         <h2 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-6">
            Our Core Values
         </h2>
         <p class="text-xl text-sage-600 max-w-2xl mx-auto">
            These principles guide every decision we make and every book we choose for our collection.
         </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
         <!-- Value 1 -->
         <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-primary rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
               <i class="fas fa-heart text-2xl text-white"></i>
            </div>
            <h3 class="text-2xl font-serif font-bold text-sage-800 mb-4">Passion for Literature</h3>
            <p class="text-sage-600 leading-relaxed">
               Every book in our collection is chosen with love and careful consideration for its ability to move, educate, or inspire readers.
            </p>
         </div>

         <!-- Value 2 -->
         <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-r from-accent-500 to-accent-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
               <i class="fas fa-users text-2xl text-white"></i>
            </div>
            <h3 class="text-2xl font-serif font-bold text-sage-800 mb-4">Community First</h3>
            <p class="text-sage-600 leading-relaxed">
               We're not just a bookstore; we're a community of readers supporting each other's literary journeys and discoveries.
            </p>
         </div>

         <!-- Value 3 -->
         <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-r from-sage-500 to-sage-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
               <i class="fas fa-leaf text-2xl text-white"></i>
            </div>
            <h3 class="text-2xl font-serif font-bold text-sage-800 mb-4">Sustainable Practices</h3>
            <p class="text-sage-600 leading-relaxed">
               We're committed to eco-friendly packaging and supporting publishers who share our values of environmental responsibility.
            </p>
         </div>

         <!-- Value 4 -->
         <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-r from-cream-500 to-cream-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
               <i class="fas fa-star text-2xl text-white"></i>
            </div>
            <h3 class="text-2xl font-serif font-bold text-sage-800 mb-4">Quality Assurance</h3>
            <p class="text-sage-600 leading-relaxed">
               From packaging to delivery, we ensure every aspect of your experience meets the highest standards of excellence.
            </p>
         </div>

         <!-- Value 5 -->
         <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
               <i class="fas fa-globe text-2xl text-white"></i>
            </div>
            <h3 class="text-2xl font-serif font-bold text-sage-800 mb-4">Diverse Voices</h3>
            <p class="text-sage-600 leading-relaxed">
               We actively promote books from diverse authors and cultures, believing that every story deserves to be heard and celebrated.
            </p>
         </div>

         <!-- Value 6 -->
         <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-r from-accent-400 to-accent-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
               <i class="fas fa-shipping-fast text-2xl text-white"></i>
            </div>
            <h3 class="text-2xl font-serif font-bold text-sage-800 mb-4">Reliable Service</h3>
            <p class="text-sage-600 leading-relaxed">
               Fast, secure delivery and responsive customer service ensure your literary adventures never have to wait.
            </p>
         </div>
      </div>
   </div>
</section>

<!-- Team Section -->
<section class="py-20 bg-white">
   <div class="container mx-auto px-4">
      <div class="text-center mb-16">
         <span class="inline-block px-4 py-2 bg-accent-100 text-accent-700 rounded-full text-sm font-medium mb-4">
            Meet Our Team
         </span>
         <h2 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-6">
            The Literary Enthusiasts
         </h2>
         <p class="text-xl text-sage-600 max-w-2xl mx-auto">
            Behind every great bookstore is a team of passionate readers who live and breathe literature.
         </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
         <!-- Team Member 1 -->
         <div class="group bg-gradient-to-br from-sage-50 to-cream-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <div class="relative mb-6">
               <img src="images/pic-4.png" alt="Sarah Mitchell" 
                    class="w-24 h-24 rounded-full object-cover mx-auto group-hover:scale-110 transition-transform duration-300">
               <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                  <i class="fas fa-book text-white text-xs"></i>
               </div>
            </div>
            <h3 class="text-xl font-serif font-bold text-sage-800 text-center mb-2">Sarah Mitchell</h3>
            <p class="text-accent-600 text-center mb-4 font-medium">Founder & Literary Curator</p>
            <p class="text-sage-600 text-center text-sm leading-relaxed">
               With 15 years in publishing, Sarah brings her passion for discovering hidden literary gems and connecting them with the perfect readers.
            </p>
         </div>

         <!-- Team Member 2 -->
         <div class="group bg-gradient-to-br from-sage-50 to-cream-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <div class="relative mb-6">
               <img src="images/pic-5.png" alt="Marcus Chen" 
                    class="w-24 h-24 rounded-full object-cover mx-auto group-hover:scale-110 transition-transform duration-300">
               <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-accent-500 rounded-full flex items-center justify-center">
                  <i class="fas fa-heart text-white text-xs"></i>
               </div>
            </div>
            <h3 class="text-xl font-serif font-bold text-sage-800 text-center mb-2">Marcus Chen</h3>
            <p class="text-accent-600 text-center mb-4 font-medium">Customer Experience Lead</p>
            <p class="text-sage-600 text-center text-sm leading-relaxed">
               Marcus ensures every customer interaction is memorable, from personalized recommendations to white-glove delivery service.
            </p>
         </div>

         <!-- Team Member 3 -->
         <div class="group bg-gradient-to-br from-sage-50 to-cream-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            <div class="relative mb-6">
               <img src="images/pic-6.png" alt="Emma Rodriguez" 
                    class="w-24 h-24 rounded-full object-cover mx-auto group-hover:scale-110 transition-transform duration-300">
               <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-sage-500 rounded-full flex items-center justify-center">
                  <i class="fas fa-star text-white text-xs"></i>
               </div>
            </div>
            <h3 class="text-xl font-serif font-bold text-sage-800 text-center mb-2">Emma Rodriguez</h3>
            <p class="text-accent-600 text-center mb-4 font-medium">Community Manager</p>
            <p class="text-sage-600 text-center text-sm leading-relaxed">
               Emma builds bridges between authors and readers, organizing book clubs and literary events that bring our community together.
            </p>
         </div>
      </div>

      <!-- Team Stats -->
      <div class="bg-gradient-primary p-8 rounded-3xl shadow-2xl">
         <div class="grid md:grid-cols-4 gap-8 text-center text-white">
            <div>
               <div class="text-3xl font-bold mb-2">100+</div>
               <div class="text-sm opacity-90">Years Combined Experience</div>
            </div>
            <div>
               <div class="text-3xl font-bold mb-2">24/7</div>
               <div class="text-sm opacity-90">Customer Support</div>
            </div>
            <div>
               <div class="text-3xl font-bold mb-2">5★</div>
               <div class="text-sm opacity-90">Average Customer Rating</div>
            </div>
            <div>
               <div class="text-3xl font-bold mb-2">1M+</div>
               <div class="text-sm opacity-90">Books Recommended</div>
            </div>
         </div>
      </div>
   </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-gradient-to-br from-cream-50 to-sage-50">
   <div class="container mx-auto px-4">
      <div class="text-center mb-16">
         <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-4">
            Reader Love
         </span>
         <h2 class="text-4xl lg:text-5xl font-serif font-bold text-sage-800 mb-6">
            What Our Readers Say
         </h2>
         <p class="text-xl text-sage-600 max-w-2xl mx-auto">
            Don't just take our word for it. Here's what our community of book lovers has to say about their BookHaven experience.
         </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
         <!-- Testimonial 1 -->
         <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group">
            <div class="flex items-center mb-6">
               <img src="images/pic-1.png" alt="Customer" class="w-12 h-12 rounded-full object-cover mr-4">
               <div>
                  <h4 class="font-bold text-sage-800">Jessica Adams</h4>
                  <div class="flex text-accent-500 text-sm">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                  </div>
               </div>
            </div>
            <p class="text-sage-600 leading-relaxed mb-4">
               "BookHaven has completely transformed my reading experience. Their curated recommendations are spot-on, and the quality of service is unmatched. I've discovered so many amazing authors through them!"
            </p>
            <div class="text-primary-600 font-medium text-sm">Verified Purchase</div>
         </div>

         <!-- Testimonial 2 -->
         <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group">
            <div class="flex items-center mb-6">
               <img src="images/pic-2.png" alt="Customer" class="w-12 h-12 rounded-full object-cover mr-4">
               <div>
                  <h4 class="font-bold text-sage-800">Michael Torres</h4>
                  <div class="flex text-accent-500 text-sm">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                  </div>
               </div>
            </div>
            <p class="text-sage-600 leading-relaxed mb-4">
               "The packaging is beautiful, delivery is lightning-fast, and every book arrives in perfect condition. Plus, their customer service team actually reads and can give genuine recommendations!"
            </p>
            <div class="text-primary-600 font-medium text-sm">Verified Purchase</div>
         </div>

         <!-- Testimonial 3 -->
         <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group">
            <div class="flex items-center mb-6">
               <img src="images/pic-3.png" alt="Customer" class="w-12 h-12 rounded-full object-cover mr-4">
               <div>
                  <h4 class="font-bold text-sage-800">Rachel Kim</h4>
                  <div class="flex text-accent-500 text-sm">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                  </div>
               </div>
            </div>
            <p class="text-sage-600 leading-relaxed mb-4">
               "I love how they promote diverse voices and indie authors. I've found incredible books here that I never would have discovered elsewhere. BookHaven is a true literary treasure!"
            </p>
            <div class="text-primary-600 font-medium text-sm">Verified Purchase</div>
         </div>
      </div>
   </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-primary relative overflow-hidden">
   <!-- Background Pattern -->
   <div class="absolute inset-0 opacity-10">
      <div class="absolute top-10 left-10 w-32 h-32 border-2 border-white rounded-full animate-float"></div>
      <div class="absolute top-32 right-20 w-24 h-24 border-2 border-white rounded-full animate-float" style="animation-delay: -2s;"></div>
      <div class="absolute bottom-20 left-1/4 w-40 h-40 border-2 border-white rounded-full animate-float" style="animation-delay: -4s;"></div>
      <div class="absolute bottom-32 right-1/3 w-20 h-20 border-2 border-white rounded-full animate-float" style="animation-delay: -1s;"></div>
   </div>

   <div class="container mx-auto px-4 text-center relative z-10">
      <h2 class="text-4xl lg:text-6xl font-serif font-bold text-white mb-6">
         Ready to Start Your Next
         <span class="block">Literary Adventure?</span>
      </h2>
      <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
         Join thousands of readers who trust BookHaven to deliver their next favorite book right to their doorstep.
      </p>
      
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
         <a href="shop.php" 
            class="px-8 py-4 bg-white text-primary-600 font-bold rounded-full hover:bg-cream-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
            Browse Our Collection
         </a>
         <a href="contact.php" 
            class="px-8 py-4 border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-primary-600 transition-all duration-300 transform hover:scale-105">
            Get Personal Recommendations
         </a>
      </div>
      
      <div class="mt-12 text-blue-100">
         <div class="flex flex-wrap justify-center items-center gap-8 text-sm">
            <div class="flex items-center gap-2">
               <i class="fas fa-shipping-fast text-cream-300"></i>
               <span>Free shipping on orders over ₹499</span>
            </div>
            <div class="flex items-center gap-2">
               <i class="fas fa-undo-alt text-cream-300"></i>
               <span>15-day return policy</span>
            </div>
            <div class="flex items-center gap-2">
               <i class="fas fa-gift text-cream-300"></i>
               <span>Gift wrapping available</span>
            </div>
         </div>
      </div>
   </div>
</section>

<?php include 'footer.php'; ?>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
