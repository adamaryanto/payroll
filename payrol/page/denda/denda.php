<?php

$tampil = $koneksi->query("SELECT * from tb_denda");
$data = $tampil->fetch_assoc();
$dendamasuk = $data['denda_masuk'];
$dendaistirahat = $data['denda_istirahat'];

?>
<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-primary">
            <div class="box-header with-border" style=" background-color:#5F9EA0; border:1px ; color:white; ">
                <h3 class="box-title">Denda</h3>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="panel-body">

                        <div class="row" style=" background-color:white; border:1px ; color:black; ">


                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Denda Masuk</label>
                                <input placeholder="*" autocomplete="off" type="text" name="tdendamasuk" value="<?php echo $dendamasuk; ?>" required class="form-control" />

                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Denda Istirahat</label>
                                <input placeholder="*" autocomplete="off" type="text" name="tdendaistirahat" value="<?php echo $dendaistirahat; ?>" required class="form-control" />

                            </div>
                        </div>

                        <div class="row" style=" background-color:white; border:1px ; color:black; ">
                            <div class="form-group col-md-4">
                                <div>
                                    <div class="col">
                                        <h3><label style="color:red ;">* </label><label>Harus Diisi</label> </h3>
                                    </div>
                                </div>
                                <div>
                                    <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>

<?php

$tdendamasuk = @$_POST['tdendamasuk'];
$tdendaistirahat = @$_POST['tdendaistirahat'];
$simpan = @$_POST['simpan'];
if ($simpan) {
    $sql = $koneksi->query("update tb_denda set denda_masuk = '$tdendamasuk' , denda_istirahat = '$tdendaistirahat'  ");
    if ($sql) {
?>
        <script type="text/javascript">
            alert("Data Tersimpan");
            window.location.href = "?page=denda";
        </script>
<?php
    }
} //simpan if
?>