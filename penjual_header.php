<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>

<header class="header">

   <div class="flex">

      <a href="login.php" class="logo">DivaShop<span>.</span></a>

      <nav class="navbar">
         <a href="penjual_home.php">Beranda</a>
		 <a href="penjual_products.php">Produk</a>
         <a href="penjual_orders.php">Orderan</a>
         <a href="penjual_about.php">Tentang</a>
         <a href="penjual_contact.php">Kontak</a>
      </nav>

     <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
        <a href="penjual_search_page.php" class="fas fa-search"></a>
        <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
         ?>
     </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <p>
           <?= $fetch_profile['name']; ?>
         </p>
         <a href="user_profile_update.php" class="btn">Ubah profile</a>
         <a href="logout.php" class="delete-btn">Keluar</a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">Masuk</a>
            <a href="register.php" class="option-btn">Daftar</a>
         </div>
     </div>

  </div>

</header>