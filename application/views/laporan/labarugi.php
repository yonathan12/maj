<div class="container-fluid">
<div class="col-lg-3">
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('message');?>"> </div>
<?php $this->session->flashdata('message')?$this->session->flashdata('message'):''?>
<div class="flash-data1" data-flashdata="<?= $this->session->flashdata('message1');?>"> </div>
<?php $this->session->flashdata('message1')?$this->session->flashdata('message1'):''?> 
</div>
<?= validation_errors(); ?>
    <form action="<?= base_url('laporan/labarugi')?>" method="POST">
    <div class="form-group row">
        <div class="col-sm-3">
            <input type="date" class="form-control" name="tanggal">
            <?= form_error('tanggal','<small class="text-danger pl-3">','</small>'); ?>
        </div>
            <button type="submit" class="btn btn-primary col-sm-2">Buat Laporan</button>
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