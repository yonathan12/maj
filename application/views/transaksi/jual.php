<div class="container">
    <div class="col-lg-3">
    <?= $this->session->flashdata('message');?>    
    </div>
    <div class="col lg-5">
    <form name="hitungRateBeli" action="<?= base_url('transaksi/jual')?>" method="POST" >
            <input type="text" value="<?= $this->session->userdata('email'); ?>" hidden name="kode">
            <div class="form-group">
                <label for="exampleFormControlInput1">Valas</label>
                <select name="valas" class="form-control">
                <option value="">Select Menu</option>
                <?php foreach ($valas as $v) : ?> 
                <option value="<?= $v['Id_valas'] ?>"><?= $v['valas'] ?></option>
                <?php endforeach; ?>
                </select>
                <?= form_error('valas','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Rate Valas</label>
                <input type="number" class="form-control" id="rate_valas" name="rate_valas" placeholder="Rate Valas">
                <?= form_error('rate_valas','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah">
                <?= form_error('jumlah','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Total</label>
                <input type="number" class="form-control" id="total" placeholder="Total" name="total" readonly >
                <?= form_error('total','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <input type=button name=submit onClick="hitungBeli()" class="btn btn-primary" value="Hitung">
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    </div>
    <br/>
    <form class="form-inline float-right" method="POST" action="<?= base_url();?>transaksi/prosesPenjualan">
    <div class="form-group mb-2">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="Customer">
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <label for="inputPassword2" class="sr-only">Customer</label>
        <input type="text" class="form-control" id="inputPassword2" placeholder="Customer" name="customer">
        <?= form_error('customer','<small class="text-danger pl-3">','</small>'); ?>
        <input type="text" value="<?= $kode; ?>" hidden name="kode">
    </div>
    <button type="submit" class="btn btn-primary mb-2">Simpan</button>
    </form>
    <table class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Valas</th>
                <th>Rate Valas</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
                        $no = 1;
                        foreach($temp as $t){
                    ?>
                        <tr>
                        
                            <td><?php echo $no++;?></td>
                            <td><?php echo $t['valas']; ?></td>
                            <td><?php echo number_format($t['rate_valas']);?></td>
                            <td><?php echo number_format($t['jumlah']);?></td>
                            <td><?php echo "IDR ".number_format($t['total']);?></td>
                            <td>              
                                <a href="<?= base_url(); ?>transaksi/tempHapusJual/<?= $t['id_valas']; ?>" class="fas fa-trash-alt" onclick="return confirm('Yakin');"></a>
                            </td>
                        </tr>
                    <?php }?>
        </tbody>
    </table>
</div>