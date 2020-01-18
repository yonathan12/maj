<div class="container">
<h2>Void Transaksi Penjualan</h2>
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('message'); ?>"> </div>
  <?php $this->session->flashdata('message') ? $this->session->flashdata('message') : '' ?>

  <div class="flash-data1" data-flashdata="<?= $this->session->flashdata('message1'); ?>"> </div>
  <?php $this->session->flashdata('message1') ? $this->session->flashdata('message1') : '' ?>
    <div class="col lg-5">
    <form name="hitungRateBeli" action="<?= base_url('void/jual')?>" method="POST" >
            <div class="form-group">
                <label for="exampleFormControlInput1">Kode Transaksi</label>
                <input type="text" value="<?= $kode; ?>" hidden name="kode">
                <input type="text" class="form-control" id="kd_trx_jual" placeholder="Kode Transaksi" name="kd_trx">
                <?= form_error('rate_valas','<small class="text-danger pl-3">','</small>'); ?>
            </div>
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
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    </div>