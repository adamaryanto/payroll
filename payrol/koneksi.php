
<?php 
$host='localhost'; 
$user='root'; 
$pass=''; 
$database='db_hr';
$koneksi=mysqli_connect($host, $user, $pass); 

mysqli_select_db($koneksi,$database); 
if ($koneksi)
{ //echo "success"; 
} 
else 
{ echo "failure";}
?>


<?php 
//$host='localhost'; 
//$user='u870891653_mahmudin'; 
//$pass='25824682mahmudinmikhA'; 
//$database='u870891653_penjualan_db';
//$koneksi=mysqli_connect($host, $user, $pass); 

//mysqli_select_db($koneksi,$database); 
//if ($koneksi)
//{ //echo "success"; 
//} 
//else 
//{ echo "failure";}
?>
