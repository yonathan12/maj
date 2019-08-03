<div class="container-fluid">
    <div class="col-lg-3">
    <?= $this->session->flashdata('message');?>    
    </div>
    <form action="<?= base_url('laporan/detailPenjualan')?>" method="POST">
    <div class="form-group row">
        <div class="col-sm-2">
            <input type="date" class="form-control" name="tanggal1">         
        </div>
        <div class="col-sm-2">
            <input type="date" class="form-control" name="tanggal2">         
        </div>
        <button type="submit" class="btn btn-primary col-sm-2">Simpan</button>
        &nbsp
        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">Export Laporan Penjualan</a>
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
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Export Data Penjualan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url();?>laporan/exportPenjualan" method="POST">
      <div class="modal-body">
      <div class="col-sm-6">
            <input type="date" class="form-control" name="tanggal1">         
        </div>
      </div>
      <div class="modal-body">
      <div class="col-sm-6">
            <input type="date" class="form-control" name="tanggal2">         
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Export</button>
      </div>
      </form>
    </div>
  </div>
</div>


     
