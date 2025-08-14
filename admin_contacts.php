<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = intval($_GET['delete']);
   $stmt = $conn->prepare("DELETE FROM `message` WHERE id = ?");
   $stmt->bind_param("i", $delete_id);
   $stmt->execute();
   $stmt->close();
   header('location:admin_contacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages & Contacts - Admin Panel</title>

   <!-- Tailwind CSS -->
   <script src="https://cdn.tailwindcss.com"></script>
   
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
   <!-- Google Fonts -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

   <script>
      tailwind.config = {
         theme: {
            extend: {
               colors: {
                  'primary': {
                     50: '#eff6ff',
                     100: '#dbeafe', 
                     200: '#bfdbfe',
                     300: '#93c5fd',
                     400: '#60a5fa',
                     500: '#3b82f6',
                     600: '#2563eb',
                     700: '#1d4ed8',
                     800: '#1e40af',
                     900: '#1e3a8a'
                  },
                  'sage': {
                     50: '#f8faf8',
                     100: '#f1f5f1', 
                     200: '#e2ebe2',
                     300: '#c8d8c8',
                     400: '#9bb99b',
                     500: '#647064',
                     600: '#4a5d4a',
                     700: '#3d4f3d',
                     800: '#2e322e'
                  },
                  'cream': {
                     50: '#fefcf8',
                     100: '#fdf9f1',
                     200: '#fbf2e3',
                     300: '#f7e6c7',
                     400: '#f0d49e',
                     500: '#e6bc7c',
                     600: '#d4a574',
                     700: '#b8926c',
                     800: '#955d3e'
                  },
                  'accent': {
                     500: '#ef4444',
                     600: '#dc2626',
                     700: '#b91c1c'
                  }
               },
               backgroundImage: {
                  'gradient-primary': 'linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%)',
                  'gradient-sage': 'linear-gradient(135deg, #9bb99b 0%, #647064 100%)'
               },
               fontFamily: {
                  'serif': ['Playfair Display', 'serif'],
                  'sans': ['Inter', 'sans-serif']
               }
            }
         }
      }
   </script>

   <style>
      html { font-size: 16px !important; }
   </style>
</head>
<body class="bg-cream-50 font-sans min-h-screen flex flex-col">
   
<?php include 'admin_header.php'; ?>

<!-- Main Content -->
<div class="flex-1 pt-24 pb-12">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Page Header -->
      <div class="mb-8">
         <div class="flex items-center space-x-4 mb-6">
            <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center shadow-lg">
               <i class="fas fa-envelope text-white text-xl"></i>
            </div>
            <div>
               <h1 class="text-3xl font-serif font-bold text-sage-800">Messages & Contacts</h1>
               <p class="text-sage-600 mt-1">Manage customer inquiries and contact messages</p>
            </div>
         </div>

         <!-- Stats Cards -->
         <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <?php
            $total_messages = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `message`"));
            $unique_users = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT user_id FROM `message`"));
            ?>
            
            <div class="bg-white rounded-xl border border-sage-100 p-6 shadow-sm hover:shadow-md transition-shadow">
               <div class="flex items-center justify-between">
                  <div>
                     <h3 class="text-sm font-medium text-sage-600 uppercase tracking-wide">Total Messages</h3>
                     <p class="text-3xl font-bold text-sage-800"><?php echo $total_messages; ?></p>
                  </div>
                  <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                     <i class="fas fa-envelope text-blue-600 text-xl"></i>
                  </div>
               </div>
            </div>

            <div class="bg-white rounded-xl border border-sage-100 p-6 shadow-sm hover:shadow-md transition-shadow">
               <div class="flex items-center justify-between">
                  <div>
                     <h3 class="text-sm font-medium text-sage-600 uppercase tracking-wide">Unique Customers</h3>
                     <p class="text-3xl font-bold text-sage-800"><?php echo $unique_users; ?></p>
                  </div>
                  <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                     <i class="fas fa-users text-green-600 text-xl"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Messages Grid -->
      <div class="bg-white rounded-xl border border-sage-100 shadow-sm overflow-hidden">
         <div class="p-6 border-b border-sage-100">
            <h2 class="text-xl font-semibold text-sage-800">Customer Messages</h2>
            <p class="text-sage-600 text-sm mt-1">View and manage customer inquiries</p>
         </div>

         <?php
         $stmt_select = $conn->prepare("SELECT * FROM `message` ORDER BY id DESC");
         $stmt_select->execute();
         $select_message = $stmt_select->get_result();
         
         if($select_message->num_rows > 0) {
         ?>
         <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6">
            <?php while($fetch_message = $select_message->fetch_assoc()) { ?>
            <div class="bg-cream-50 rounded-xl border border-sage-100 overflow-hidden hover:shadow-lg transition-all duration-300 group">
               <!-- Message Header -->
               <div class="bg-gradient-sage p-4">
                  <div class="flex items-center justify-between">
                     <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg">
                           <i class="fas fa-user text-sage-600 text-lg"></i>
                        </div>
                        <div>
                           <h3 class="text-white font-semibold text-lg"><?php echo htmlspecialchars($fetch_message['name']); ?></h3>
                           <p class="text-sage-200 text-sm">User ID: #<?php echo $fetch_message['user_id']; ?></p>
                        </div>
                     </div>
                     <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white">
                           <i class="fas fa-envelope mr-1"></i>
                           Message
                        </span>
                     </div>
                  </div>
               </div>

               <!-- Message Content -->
               <div class="p-6">
                  <!-- Contact Information -->
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                     <div class="flex items-center space-x-2">
                        <i class="fas fa-envelope text-sage-500 text-sm"></i>
                        <span class="text-sm text-sage-600 font-medium">Email:</span>
                        <span class="text-sm text-sage-800 truncate" title="<?php echo htmlspecialchars($fetch_message['email']); ?>">
                           <?php echo htmlspecialchars($fetch_message['email']); ?>
                        </span>
                     </div>
                     
                     <div class="flex items-center space-x-2">
                        <i class="fas fa-phone text-sage-500 text-sm"></i>
                        <span class="text-sm text-sage-600 font-medium">Phone:</span>
                        <span class="text-sm text-sage-800">
                           <?php echo htmlspecialchars($fetch_message['number']); ?>
                        </span>
                     </div>
                  </div>

                  <!-- Message Content -->
                  <div class="mb-4">
                     <label class="block text-sm font-medium text-sage-600 mb-2">Message:</label>
                     <div class="bg-white border border-sage-200 rounded-lg p-4">
                        <p class="text-sage-800 text-sm leading-relaxed">
                           <?php echo nl2br(htmlspecialchars($fetch_message['message'])); ?>
                        </p>
                     </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex space-x-3 pt-4 border-t border-sage-100">
                     <a href="mailto:<?php echo htmlspecialchars($fetch_message['email']); ?>" 
                        class="flex-1 bg-primary-500 text-white text-center py-2 px-4 rounded-lg hover:bg-primary-600 transition-colors text-sm font-medium flex items-center justify-center">
                        <i class="fas fa-reply mr-2"></i>
                        Reply
                     </a>
                     <button onclick="confirmDelete(<?php echo $fetch_message['id']; ?>, '<?php echo htmlspecialchars($fetch_message['name']); ?>')"
                             class="flex-1 bg-accent-500 text-white py-2 px-4 rounded-lg hover:bg-accent-600 transition-colors text-sm font-medium flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>
                        Delete
                     </button>
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
               <i class="fas fa-inbox text-4xl text-sage-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-sage-700 mb-2">No Messages Found</h3>
            <p class="text-sage-500 mb-6">You haven't received any customer messages yet.</p>
            <div class="text-center">
               <span class="inline-flex items-center px-4 py-2 bg-sage-100 text-sage-600 rounded-lg text-sm">
                  <i class="fas fa-info-circle mr-2"></i>
                  Messages will appear here when customers contact you
               </span>
            </div>
         </div>
         <?php } ?>
         
         <?php $stmt_select->close(); ?>
      </div>
   </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4">
   <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
      <div class="p-6">
         <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
               <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <div>
               <h3 class="text-lg font-semibold text-sage-800">Confirm Deletion</h3>
               <p class="text-sage-600 text-sm">This action cannot be undone</p>
            </div>
         </div>
         
         <p class="text-sage-700 mb-6">
            Are you sure you want to delete the message from <span id="senderName" class="font-semibold"></span>? This will permanently remove the message from your inbox.
         </p>
         
         <div class="flex space-x-3">
            <button onclick="closeDeleteModal()" 
                    class="flex-1 bg-sage-500 text-white py-3 px-4 rounded-lg hover:bg-sage-600 transition-colors font-medium">
               Cancel
            </button>
            <a id="deleteLink" href="#" 
               class="flex-1 bg-accent-500 text-white py-3 px-4 rounded-lg hover:bg-accent-600 transition-colors font-medium text-center">
               Delete Message
            </a>
         </div>
      </div>
   </div>
</div>

<script>
function confirmDelete(messageId, senderName) {
   document.getElementById('senderName').textContent = senderName;
   document.getElementById('deleteLink').href = 'admin_contacts.php?delete=' + messageId;
   document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
   document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
   if (e.target === this) {
      closeDeleteModal();
   }
});

// Auto-hide any messages
setTimeout(() => {
   const messages = document.querySelectorAll('.fixed.top-24 > div');
   messages.forEach(msg => {
      msg.style.opacity = '0';
      msg.style.transform = 'translateY(-20px)';
      setTimeout(() => msg.remove(), 300);
   });
}, 3000);
</script>

<?php include 'admin_footer.php'; ?>

</body>
</html>