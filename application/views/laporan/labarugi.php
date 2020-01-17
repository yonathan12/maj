<div class="container-fluid">
<div class="col-lg-3">
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('message');?>"> </div>
<?php $this->session->flashdata('message')?$this->session->flashdata('message'):''?>
<div class="flash-data1" data-flashdata="<?= $this->session->flashdata('message1');?>"> </div>
<?php $this->session->flashdata('message1')?$this->session->flashdata('message1'):''?> 
</div>
    <form action="<?= base_url('laporan/labarugi')?>" method="POST">
    <div class="form-group row">
        <div class="col-sm-3">
            <input type="date" class="form-control" name="tanggal">
        </div>
            <button type="submit" class="btn btn-primary col-sm-2">Buat Laporan</button>
            &nbsp;
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">Export Laporan Laba Rugi</a>
    </div>
    </form>
    
<hr class="sidebar-divider">
<h3 text-align="center">Laba Rugi</h3>
    <table id="table2" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Total Laba</th>
                <th>Tanggal Laporan</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach($laba as $s){
            ?>
            <tr>
                <td><?php echo $no++;?></td>
                <td><?php  echo "IDR ".number_format($s['Total']);?></td>
                <td><?php echo $s['tgl_laporan']; ?></td> 
                <td><?php echo $s['date_created']; ?></td> 
                <td>
                    <a href="<?= base_url(); ?>laporan/detail/<?= $s['tgl_laporan']; ?>" class="fas fa-info" >&nbsp
                    <a href="<?= base_url(); ?>laporan/hapus/<?= $s['tgl_laporan']; ?>" class="fas fa-trash-alt" onclick="return confirm('Yakin');"></a></td>
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
        <h5 class="modal-title" id="exampleModalLabel">Export Data Laba Rugi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url();?>laporan/exportLabarugi" method="POST">
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