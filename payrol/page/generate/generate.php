<?php
$tampil=$koneksi->query("SELECT * from tb_mesin");
$data=$tampil->fetch_assoc();
$idmesin =$data['id_mesin'];
$nomesin = $data['no_mesin'];
$namamesin = $data['nama_mesin'];
$ipmesin = $data['ip_mesin'];
$commkey = $data['comm_key'];
$port = $data['port'];
?>
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary"  >
                    <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
              <h3 class="box-title">Data LOG</h3>
            </div>
             <form method="POST"  enctype="multipart/form-data">
                        <div class="panel-body">
                           
                      <div class="row" style=" background-color:white; border:1px ; color:black; "> 
                          
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">IP Mesin</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tipmesin" value="<?php echo $ipmesin; ?>"  required class="form-control"/>
                    
                </div>  
                 <div class="form-group col-md-4">
                    <label class="font-weight-bold">Port</label>
                    <input placeholder="*" autocomplete="off" type="text" name="tport" value="<?php echo $port; ?>"  required class="form-control"/>
                    
                </div>                       
             
                </div>

              
            <div class="row" style=" background-color:white; border:1px ; color:black; "> 

                 <div class="form-group col-md-6">
                     
                       <input type="submit" name="simpan"  value="Generate" class="btn btn-success">
                </div>
                </div>

                
                                    </form>
                                    <div class="form-group "></div>

                            <div class="table-responsive">
                                
                                <table class="table table-bordered table-striped" id="dataTables-example">
                                    <thead >
                                        <tr>
                                        <th width="5%">No</th>
                                           <th >NIK</th>
                                            <th >Nama Karyawan</th>
                                            <th >Tanggal</th>
                                             <th >Waktu</th>
                                             
                        

                                        </tr>
                                    </thead>
                                    <tbody>
                                                                <?php


$no = 1;
$tampil = $koneksi->query("select A.userid , A.tgl , A.detail_waktu , B.nama_karyawan from tb_record A LEFT JOIN ms_karyawan B on A.userid = B.no_absen where B.no_absen <>'' order by detail_waktu DESC  LIMIT 1000");

    while ($data=$tampil->fetch_assoc())
    {

?>


                                        <tr>
<td><?php echo $no ?></td>
<td><?php echo $data['userid'] ?></td>
<td><?php echo $data['nama_karyawan'] ?></td>
<td><?php echo $data['tgl'] ?></td>
<td><?php echo $data['detail_waktu'] ?></td>


<!--
<td>
    
<a  href="?page=order&id=<?php echo $data['id_transaksi'];?>"  class="btn btn-success"> Update</a>


</td>
-->
                                      
                                            
                                        </tr>

                                       <?php  $no++; } ?>

                                    </tbody>   
                                    </table>
                            </div>

                           

                    </div>
                </div>
        </div>
    </div>
  
    <script>
 $(document).ready( function () {
$('#dataTables-example').DataTable({
    pageLength: 100,
    "searching": true
}
);

} );
</script>

<?php


$IP= $ipmesin;
$Key= $commkey;
$PORT= $port;

$simpan = @$_POST ['simpan'];
$print = @$_POST ['print'];
$excel = @$_POST ['excel'];
if($simpan){

$Connect = fsockopen($IP, $PORT, $errno, $errstr, 1);
if($Connect){
        
        $soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
        $newLine="\r\n";
        fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
        fputs($Connect, "Content-Type: text/xml".$newLine);
        fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
        fputs($Connect, $soap_request.$newLine);
        $buffer="";
        while($Response=fgets($Connect, 1024)){
            $buffer=$buffer.$Response;
        }
    }
    else {echo "Koneksi Gagal";}

    
    include("parse.php");
    $buffer=Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
    $buffer=explode("\r\n",$buffer);
    for($a=0;$a<count($buffer);$a++){
        $data=Parse_Data($buffer[$a],"<Row>","</Row>");
        $PIN=Parse_Data($data,"<PIN>","</PIN>");
        $DateTime=Parse_Data($data,"<DateTime>","</DateTime>");
        $Verified=Parse_Data($data,"<Verified>","</Verified>");
        $Status=Parse_Data($data,"<Status>","</Status>");

    $koneksi->query("insert into tb_record (data,userid,tgl,verifikasi,status,detail_waktu) values ('$data','$PIN','$DateTime','$Verified','$Status','$DateTime') ");

}

    ?><script type="text/javascript">
                   alert("Data Tersimpan");
                window.location.href="?page=generate";

            </script>
            <?php
}
 


?>