   <?php


  $tampildetail=$koneksi->query("select COUNT(id_karyawan) as jmlkaryawan from ms_karyawan ");

$datadetail=$tampildetail->fetch_assoc();
$jmlkaryawan = $datadetail['jmlkaryawan'];



 ?>
<h1>
        Dashboard
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
       
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
              <h4><?php echo $jmlkaryawan ; ?><sup style="font-size: 20px"></sup> </h4>

              <p>Database Karyawan</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="?page=pevisa&aksi=fovisa" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
       <!-- ./col -->
        
        <!-- ./col -->
       
        

     
       


      </div>


    
    </div>