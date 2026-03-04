<?php
 $id = $_GET['id'];

$ttgl1 = date("Y-m-d");
   ?>

<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Data Karyawan</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                      
              
            <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                   <div class="form-group col-md-2">
                    <label class="font-weight-bold"> Dari Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl1" required value="<?php echo $ttgl1 ; ?>" class="form-control"/>
                     <input type="submit" name="simpan"  value="Search" class="btn btn-primary">
                </div>
                <div class="form-group col-md-2">
                    <label class="font-weight-bold">Sampai Tanggal</label>
                    <input placeholder="*" autocomplete="off" type="date" name="ttgl2" value="<?php echo $ttgl1 ; ?>" required class="form-control"/>
                    
                </div>

                </div>

                
                                    </form>
                                    <div class="form-group "></div>

                          
                       

                    </div>
                </div>
        </div>
    </div>
  
    <script>
 $(document).ready( function () {
$('#dataTables-example').DataTable({
    scrollY: true,
    
    scrollY: 400,
    pageLength: 1000,
    "searching": true
}
);

} );
   


</script>

<?php
  
$ttgl11 = @$_POST ['ttgl1'];
$ttgl22 = @$_POST ['ttgl2'];
$simpan = @$_POST ['simpan'];
if($simpan) {
  ?>
                <script type="text/javascript">
              
                window.location.href="slip.php?id=<?php echo $id; ?>&ttgl1=<?php echo $ttgl11 ?>&ttgl2=<?php echo $ttgl22 ?>";

            </script>
            <?php


 



}//simpan if


?>