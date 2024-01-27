<?php

include 'config.php';

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = $_POST['cpass'];
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    $user_type = $_POST['user_type']; // Menambahkan baris ini untuk mendapatkan jenis pengguna yang dipilih

    $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select->execute([$email]);

    if ($select->rowCount() > 0) {
        $message[] = 'Email anda sudah ada!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Sandi tidak sama!';
        } else {
            $insert = $conn->prepare("INSERT INTO `users` (name, email, password, image, user_type) VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$name, $email, $pass, $image, $user_type]); // Menambahkan jenis pengguna ke dalam kueri

            if ($insert) {
                if ($image_size > 2000000) {
                    $message[] = 'Ukuran foto terlalu besar!';
                } else {
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'Pendaftaran berhasil!';
                    header('location:login.php');
                }
            }
        }
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
   <link rel="stylesheet" href="css/components.css">

</head>
<body>

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
   
<section class="form-container">

   <form action="" enctype="multipart/form-data" method="POST">
      <h3>Daftar Sekarang</h3>
      <input type="text" name="name" class="box" placeholder="masukkan nama anda" required>
      <input type="email" name="email" class="box" placeholder="masukkan email anda" required>
      <input type="password" name="pass" class="box" placeholder="masukkan sandi" required>
      <input type="password" name="cpass" class="box" placeholder="konfirmasi sandi" required>
      <input type="file" name="image" class="box" required accept="image/jpg, image/jpeg, image/png">
	  <select name="user_type" class="drop-down">
              <option value="admin">Admin</option>
              <option value="user">Pembeli</option>
			  <option value="penjual">Penjual</option>
            </select>
      <input type="submit" value="Daftar" class="btn" name="submit">
      <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
   </form>

</section>


</body>
</html>