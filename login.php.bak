<?php

ob_start();

session_start();
include "koneksi.php";
//$koneksi = new mysqli("localhost","u1588895_ausci","25824682auscisevenoffshore","u1588895_db_ausci");




?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PAYROLL</title>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/londinium-theme.min.css" rel="stylesheet" type="text/css">
<link href="css/styles.min.css" rel="stylesheet" type="text/css">
<link href="css/icons.min.css" rel="stylesheet" type="text/css">
<!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">-->
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/plugins/charts/sparkline.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/uniform.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/select2.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/inputmask.js"></script>
<script type="text/javascript" src="js/plugins/forms/autosize.js"></script>
<script type="text/javascript" src="js/plugins/forms/inputlimit.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/listbox.js"></script>
<script type="text/javascript" src="js/plugins/forms/multiselect.js"></script>
<script type="text/javascript" src="js/plugins/forms/validate.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/tags.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/switch.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/uploader/plupload.full.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/uploader/plupload.queue.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/wysihtml5/toolbar.js"></script>
<script type="text/javascript" src="js/plugins/interface/daterangepicker.js"></script>
<script type="text/javascript" src="js/plugins/interface/fancybox.min.js"></script>
<script type="text/javascript" src="js/plugins/interface/moment.js"></script>
<script type="text/javascript" src="js/plugins/interface/jgrowl.min.js"></script>
<script type="text/javascript" src="js/plugins/interface/datatables.min.js"></script>
<script type="text/javascript" src="js/plugins/interface/colorpicker.js"></script>
<script type="text/javascript" src="js/plugins/interface/fullcalendar.min.js"></script>
<script type="text/javascript" src="js/plugins/interface/timepicker.min.js"></script>
<script type="text/javascript" src="js/plugins/interface/collapsible.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/application.js"></script>
<style>
            body {
           /* background-image : url("images/rumah.jpg"); */
                background-color : whitesmoke;
                background-size:cover;
                background-position:center;
                background-repeat:no-repeat
                /*background-color : #cccccc;*/
            }
        </style>
</head>
<body class="bg-gradient-default">
<!-- Navbar -->

<!-- /navbar -->
<!-- Login wrapper -->
<div class="login-wrapper">

  <form method="POST">
    

    

    <div class="well">

       <div style="font-style: center;" class="form-group has-feedback">
        
        <label  style="font-style: center;"></label></div>


    <div class="text-white text-center font-weight-bold" style="font-size: 30px;"><img src="images/iconhr.jpeg" alt="" width="250" height="200"> </div>
     

      <div class="form-group">
          
                <!-- /.input group -->
              </div>
      <div class="form-group has-feedback">
        
        <label>Username</label>
        <input type="text" class="form-control" placeholder="Username" name="tusername">
        <i class="icon-users form-control-feedback"></i></div>
      <div class="form-group has-feedback">
        <label>Password</label>
       <input type="password" class="form-control" placeholder="Password" name="tpass">
        <i class="icon-lock form-control-feedback"></i></div>
      <div class="row form-actions">
        

      
       

       
        
      </div>
      <div class="row">
      <div class="col-xs-12">
          <button type="submit" name="login"  style="width:100%; background-color : blue;border:none;" name="login"  class="btn btn-warning btn-block"> Login</button>
        </div>
          </div>
     
      
        
    </div>

    
    

    
  </form>
  
</div>


<!-- /login wrapper -->
<!-- Footer -->

<!-- /footer -->
</body>
</html>

<?php
  if (isset($_POST['login'])){

    $username = @$_POST['tusername'];
      $idperusahaan = @$_POST['tidp'];
    $pass = @$_POST['tpass'];
  $uname=str_replace(' ','',$username) ;
$tgl1 = date("Y-m-d-H-i-s");
// if($cek =='user'){
 $sql = $koneksi -> query("SELECT * FROM ms_login WHERE user_login ='$uname' AND lg_password = '$pass' ");
$tampil = $sql->fetch_assoc();



  
  if(isset($tampil)){
     session_start();


     $_SESSION['iduser'] = $tampil['id_login'];
    $_SESSION['nama'] = $tampil['user_login'];
    $_SESSION['passuser']     = $tampil['lg_password'];
     $_SESSION['level']     = $tampil['level'];
     $id=$tampil['id_user'];
     $lvl =$tampil['level'];
//if($lvl=='superadmin'){
//  $koneksi -> query("insert into app_user (id_user,nama_user,tgl) SELECT id_user , nama , '$tgl1'  from tb_user  where id_user ='$id'  ");
 // header("location:indexadmin.php"); 
//}elseif($lvl=='manajer'){
 // $koneksi -> query("insert into app_user (id_user,nama_user,tgl) SELECT id_user , nama , '$tgl1'  from tb_user  where id_user ='$id'  ");
 // header("location:indexmanajer.php"); 
//}elseif($lvl=='operator'){
 // $koneksi -> query("insert into app_user (id_user,nama_user,tgl) SELECT id_user , nama , '$tgl1'  from tb_user  where id_user ='$id'  ");
  //header("location:indexoperator.php"); 
//}elseif($lvl=='finance'){
  //$koneksi -> query("insert into app_user (id_user,nama_user,tgl) SELECT id_user , nama , '$tgl1'  from tb_user  where id_user ='$id'  ");
  //header("location:indexfinance.php"); 
//}

//else{
 // header("location:indexuser.php");
///}

 //$koneksi -> query("insert into app_user (id_user,nama_user,tgl) SELECT id_user , nama , '$tgl1'  from tb_user  where id_user ='$id'  ");
  header("location:index.php"); 
}else
{
  ?>

  <script type="text/javascript">
  alert("Data Not Found")
  </script> 
<?php 
}


      
 


  }


?>