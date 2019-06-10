<div class="container-fluid">
    <form action="<?= base_url('laporan/detailPenjualan')?>" method="POST">
    <div class="form-group row">
        <div class="col-sm-3">
            <input type="date" class="form-control" name="tanggal1">         
        </div>
        <div class="col-sm-3">
            <input type="date" class="form-control" name="tanggal2">         
        </div>
        <button type="submit" class="btn btn-primary col-sm-2">Simpan</button>
    </div>
    </form>
    <hr class="sidebar-divider">
    <h3 text-align="center">Laporan Penjualan</h3>
    <table id="table2" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Trx</th>
                <th>Customer</th>
                <th>Valas</th>
                <th>Rate</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
        <?
        $no = 1;
                        foreach($penjualan as $jual){
                    ?>
                        <tr>
                            <td><?php echo $no++;?></td>
                            <td><?= $jual['kd_trx'];?></td>
                            <td><?= $jual['nama'];?></td>
                            <td><?= $jual['valas'];?></td>
                            <td><?= number_format($jual['rate_valas']);?></td>
                            <td><?= number_format($jual['jumlah']);?></td>
                            <td><?= "IDR ".number_format($jual['total']);?></td>
                            <td><?= $jual['date_created'];?></td>
                        </tr>
                        <?php }?>
        </tbody>
    </table>
</div>


     
