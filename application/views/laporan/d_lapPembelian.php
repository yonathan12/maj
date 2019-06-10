<div class="container-fluid">
    <hr class="sidebar-divider">
    <h3 text-align="center">Laporan Pembelian</h3>
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
                        foreach($pembelian as $beli){
                    ?>
                        <tr>
                            <td><?php echo $no++;?></td>
                            <td><?= $beli['kd_trx'];?></td>
                            <td><?= $beli['nama'];?></td>
                            <td><?= $beli['valas'];?></td>
                            <td><?= number_format($beli['rate_valas']);?></td>
                            <td><?= number_format($beli['jumlah']);?></td>
                            <td><?= "IDR ".number_format($beli['total']);?></td>
                            <td><?= $beli['date_created'];?></td>
                        </tr>
                        <?php }?>
        </tbody>
    </table>
</div>


     
