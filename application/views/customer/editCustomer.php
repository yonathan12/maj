<div class="container">
<h1 class="h3 mb-4 text-gray-800">Edit Customer</h1>
    <div class="col-lg-3">
    <?= $this->session->flashdata('message');?>    
    </div>
    <div class="col lg-5">
    <form name="" action="<?= base_url('customer/edit')?>" method="POST" >
            
            <div class="form-group">
                <label for="exampleFormControlInput1">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="<?= $customer['nama']; ?>">
                <input type="text" hidden="" name="id" value="<?= $customer['Id']; ?>">
                <?= form_error('nama','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Tempat Lahir</label>
                <input type="text" class="form-control" id="tempat_lhr" name="tempat_lhr" placeholder="Tempat Lahir" value="<?= $customer['tempat_lahir']; ?>">
                <?= form_error('tempat_lhr','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tgl_lhr" name="tgl_lhr" value="<?= $customer['tgl_lahir']; ?>">
                <?= form_error('tgl_lhr','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">No Ktp</label>
                <input type="number" class="form-control" id="ktp" name="ktp" placeholder="No KTP" value="<?= $customer['no_ktp']; ?>">
                <?= form_error('ktp','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" value="<?= $customer['alamat']; ?>">
                <?= form_error('alamat','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Telp</label>
                <input type="number" class="form-control" id="telp" placeholder="Telp" name="telp" value="<?= $customer['telp']; ?>">
                <?= form_error('telp','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">No NPWP</label>
                <input type="number" class="form-control" id="npwp" name="npwp" placeholder="No NPWP" value="<?= $customer['no_npwp']; ?>">            
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Pekerjaan</label>
                <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" placeholder="Pekerjaan" value="<?= $customer['pekerjaan']; ?>">
                <?= form_error('pekerjaan','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Kewarganegaraan</label>
                <input type="text" class="form-control" id="kewarganegaraan" name="kewarganegaraan" placeholder="Kewarganegaraan" value="<?= $customer['kewarganegaraan']; ?>">
                <?= form_error('kewarganegaraan','<small class="text-danger pl-3">','</small>'); ?>
            </div>
            
            <button type="submit" class="btn btn-success">Simpan</button>
            <button type="reset" class="btn btn-primary">Reset</button>
            <a href="<?= base_url('customer'); ?>" class="btn btn-danger">Kembali</a>
        </form>
    </div>
    <br/>
   
</div>
