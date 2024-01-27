<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['penjual_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Penjual DivaShop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'penjual_header.php'; ?>

<section class="about">

   <div class="row">

      <div class="box">
         <img src="images/about-img-1.png" alt="">
         <h3>DivaShop</h3>
         <p>Website kami adalah platform untuk menghubungkan antara penjual dan pembeli dalam mededikasikan untuk menyediakan bahan-bahan organik berkualitas tinggi kepada para pelanggan yang peduli akan kesehatan dan lingkungan. Kami memberi wadah kepada penjual untuk menawarkan beragam produk organik mulai dari buah-buahan, sayuran, daging, ikan, dan produk-produk organik lainnya yang dihasilkan dengan memperhatikan standar kualitas dan keberlanjutan. Terkasih pengguna, terima kasih telah menggunakan platform kami, kami sangat menghargai kepercayaan Anda! Mohon bagikan pandangan Anda untuk membantu kami terus mengembangkan platform ini menjadi lebih baik.</p>
         <a href="penjual_contact.php" class="btn">Masukkan Untuk Kami</a>
      </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>