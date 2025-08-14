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
   <title>Messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title"> Messages </h1>

   <div class="box-container">
   <?php
      $stmt_select = $conn->prepare("SELECT * FROM `message`");
      $stmt_select->execute();
      $select_message = $stmt_select->get_result();
      if($select_message->num_rows > 0){
         while($fetch_message = $select_message->fetch_assoc()){
      $stmt_select->close();
   ?>
   <div class="box">
      <p> User ID: <span><?php echo $fetch_message['user_id']; ?></span> </p>
      <p> Name: <span><?php echo $fetch_message['name']; ?></span> </p>
      <p> Number: <span><?php echo $fetch_message['number']; ?></span> </p>
      <p> Email: <span><?php echo $fetch_message['email']; ?></span> </p>
      <p> Message: <span><?php echo $fetch_message['message']; ?></span> </p>
      <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">delete message</a>
   </div>
   <?php
      };
   }else{
      echo '<p class="empty">You have no messages!</p>';
   }
   ?>
   </div>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>