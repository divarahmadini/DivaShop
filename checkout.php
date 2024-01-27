<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = $_POST['flat'] .' '. $_POST['street'] .' '. $_POST['city'] .' '. $_POST['state'] .' '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_query->execute([$user_id]);
   if($cart_query->rowCount() > 0){
      while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
         $cart_products[] = $cart_item['name'].' ( '.$cart_item['quantity'].' )';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      };
   };

   $total_products = implode(', ', $cart_products);

   $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
   $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);

   if($cart_total == 0){
      $message[] = 'Keranjang anda kosong!';
   }elseif($order_query->rowCount() > 0){
      $message[] = 'Orderan sudah ada!';
   }else{
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);
      $message[] = 'Berhasil Dipesan!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>DivaShop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="display-orders">

   <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if($select_cart_items->rowCount() > 0){
         while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
            $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= $fetch_cart_items['name']; ?> <span>(<?= 'RP. '.$fetch_cart_items['price'].'/kg x '. $fetch_cart_items['quantity']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">Keranjang anda kosong!</p>';
   }
   ?>
   <div class="grand-total">Total : <span>RP. <?= $cart_grand_total; ?></span></div>
</section>

<section class="checkout-orders">

   <form action="" method="POST">

      <h3>LAKUKAN PEMBELIAN</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Nama :</span>
            <input type="text" name="name" placeholder="Masukkan Nama" class="box" required>
         </div>
         <div class="inputBox">
            <span>Nomor :</span>
            <input type="number" name="number" placeholder="Masukkan nomor" class="box" required>
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="email" name="email" placeholder="Masukkan Email" class="box" required>
         </div>
         <div class="inputBox">
            <span>Metode Pembayaran :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">cash on delivery</option>
               <option value="credit card">Transfer Bank</option>
               <option value="paytm">Kartu Kredit</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Provinsi :</span>
            <input type="text" name="flat" placeholder="Cth: Sumatera Barat" class="box" required>
         </div>
         <div class="inputBox">
            <span>Kota :</span>
            <input type="text" name="street" placeholder="Cth: Solok" class="box" required>
         </div>
         <div class="inputBox">
            <span>Kecamatan :</span>
            <input type="text" name="city" placeholder="Cth: Gunung Talang" class="box" required>
         </div>
         <div class="inputBox">
            <span>Jalan :</span>
            <input type="text" name="state" placeholder="Cth: Jalan soekarno no.7" class="box" required>
         </div>
         <div class="inputBox">
            <span>Detail Alamat :</span>
            <input type="text" name="country" placeholder="cth: Rumah warna oren disamping salon anata" class="box" required>
         </div>
         <div class="inputBox">
            <span>Kode Pos :</span>
            <input type="number" min="0" name="pin_code" placeholder="cth: 25221" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1)?'':'disabled'; ?>" value="Pesan">

   </form>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>