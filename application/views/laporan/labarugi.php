<div class="container-fluid">

<div class="col-lg-3">
    <?= $this->session->flashdata('message');?>    
    </div>
    <?= validation_errors(); ?>
    <form action="<?= base_url('laporan/labarugi')?>" method="POST">
    <div class="form-group row">
        <label for="Valas" class="col-sm-2 col-form-label">Valas</label>
        <div class="col-sm-2">
            <select name="valas" class="form-control">
                <option value="">Pilih Valas</option>
                <?php foreach ($valas as $v) : ?> 
                <option value="<?= $v['Id_valas'] ?>"><?= $v['valas'] ?></option>
                <?php endforeach; ?>
            </select>
         <?= form_error('valas','<small class="text-danger pl-3">','</small>'); ?>
        </div>
        <div class="col-sm-3">
            <input type="date" class="form-control" name="tanggal">
         <?= form_error('valas','<small class="text-danger pl-3">','</small>'); ?>
        </div>
        <button type="submit" class="btn btn-primary col-sm-2">Simpan</button>
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
        <?
        $no = 1;
                        foreach($laba as $s){
                    ?>
                        <tr>
                            <td><?php echo $no++;?></td>
                            <td><?php  echo "IDR ".number_format($s['Total']);?></td>
                            <td><?php echo $s['tgl_laporan']; ?></td> 
                            <td><?php echo $s['date_created']; ?></td> 
                            <td><a href="<?= base_url(); ?>laporan/detail/<?= $s['tgl_laporan']; ?>" class="fas fa-info" >&nbsp
                            <a href="<?= base_url(); ?>laporan/hapus/<?= $s['tgl_laporan']; ?>" class="fas fa-trash-alt" onclick="return confirm('Yakin');"></a></td>
                        </tr>
                        <?php }?>
        </tbody>
    </table>
</div>


     
