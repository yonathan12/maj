<div class="container">
    <div class="col-lg-3">
    <?= $this->session->flashdata('message');?>    
    </div>
    <div class="col lg-5">
    <form name="" action="<?= base_url('customer/addCustomer')?>" method="POST" >
            
            <div class="form-group">
                <label for="exampleFormControlInput1">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
                <?= form_error('nama','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">No Ktp</label>
                <input type="number" class="form-control" id="ktp" name="ktp" placeholder="No KTP" >
                <?= form_error('ktp','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat">
                <?= form_error('alamat','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Telp</label>
                <input type="number" class="form-control" id="telp" placeholder="Telp" name="telp" >
                <?= form_error('telp','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email" name="email" >
                <?= form_error('email','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            
            <button type="submit" class="btn btn-success">Tambah</button>
            <button type="reset" class="btn btn-primary">Reset</button>
            <a href="<?= base_url('customer'); ?>" class="btn btn-danger">Kembali</a>
        </form>
    </div>
    <br/>
   
</div>