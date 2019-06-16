<div class="container">
    <div class="col-lg-3">
    <?= $this->session->flashdata('message');?>    
    </div>
    <div class="col lg-5">
    <form name="" action="<?= base_url('customer/addCustomer')?>" method="POST" >
            
            <div class="form-group">
                <label for="exampleFormControlInput1">Nama</label>
                <input type="text" hidden="" class="form-control" id="kode" name="kode" value="<?= $kode; ?>">
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
                <?= form_error('nama','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Tempat Lahir</label>
                <input type="text" class="form-control" id="tempat_lhr" name="tempat_lhr" placeholder="Tempat Lahir" >
                <?= form_error('tempat_lhr','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tgl_lhr" name="tgl_lhr">
                <?= form_error('tgl_lhr','<small class="text-danger pl-3">','</small>'); ?>
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
                <label for="exampleFormControlInput1">No NPWP</label>
                <input type="number" class="form-control" id="npwp" name="npwp" placeholder="No NPWP" >            
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Pekerjaan</label>
                <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" placeholder="Pekerjaan">
                <?= form_error('pekerjaan','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Kewarganegaraan</label>
                <input type="text" class="form-control" id="kewarganegaraan" name="kewarganegaraan" placeholder="Kewarganegaraan">
                <?= form_error('kewarganegaraan','<small class="text-danger pl-3">','</small>'); ?>
            </div>            
            <button type="submit" class="btn btn-success">Tambah</button>
            <button type="reset" class="btn btn-primary">Reset</button>
            <a href="<?= base_url('customer'); ?>" class="btn btn-danger">Kembali</a>
        </form>
    </div>
    <br/>
   
</div>