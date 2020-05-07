<div class="container">
  <div class="flash-data" data-flashdata="<?= $this->session->flashdata('message'); ?>"> </div>
  <?php $this->session->flashdata('message') ? $this->session->flashdata('message') : '' ?>

  <div class="flash-data1" data-flashdata="<?= $this->session->flashdata('message1'); ?>"> </div>
  <?php $this->session->flashdata('message1') ? $this->session->flashdata('message1') : '' ?>
  <a href="#" class="btn btn-facebook" data-toggle="modal" data-target="#modalAddCustomer">Tambah Data Customer</a>
  <a href="#upload" class="btn btn-success" data-toggle="modal" data-target="#upload" title="Import Excel"><i class="fa fa-file-excel-o"></i> Import Excel</a>
  <a href="<?= base_url('/assets/format/Upload_Customer.xlsx'); ?>" class="btn  btn-warning"><i class="fa fa-download"></i> Format Excel</a>
  <a href="<?php echo base_url(); ?>customer/export" class="btn btn-warning btn-flat"><i class="fa fa-download"></i> Export Excel</a>
  <br></br>
  <table id="tableCustomer" class="table table-hover">
    <thead>
      <tr>
        <th>No</th>
        <th>Nomor Nasabah</th>
        <th>Nama</th>
        <th>No KTP</th>
        <th>Telp</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
</div>

<!-- Modal Tambah Data Nasabah -->
<div class="modal fade" id="modalAddCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Nasabah</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('customer/addCustomer') ?>" method="POST" onsubmit="return validasi(this)">
        <div class="form-group">
          <label for="exampleFormControlInput1">Tipe Nasabah</label>
          <select class="custom-select" name="tipe_nasabah" id="tipe_nasabah">
            <option selected value="">Tipe Nasabah</option>
            <option value="P">Perorangan</option>
            <option value="B">Badan Usaha</option>
          </select>
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Nama</label>
          <input type="text" hidden="" class="form-control" id="kode" name="kode" value="<?= $kode; ?>">
          <input type="text" value="<?= $this->session->userdata('id'); ?>" hidden name="user_id">
          <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
          <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Tempat Lahir</label>
          <input type="text" class="form-control" id="tempat_lhr" name="tempat_lhr" placeholder="Tempat Lahir">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Tanggal Lahir</label>
          <input type="date" class="form-control" id="tgl_lhr" name="tgl_lhr">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">No Ktp</label>
          <input type="number" class="form-control" id="ktp" name="ktp" placeholder="No KTP" maxlength="16">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Alamat</label>
          <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Telp</label>
          <input type="number" class="form-control" id="telp" placeholder="Telp" name="telp">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">No NPWP</label>
          <input type="number" class="form-control" id="npwp" name="npwp" placeholder="No NPWP">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Pekerjaan</label>
          <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" placeholder="Pekerjaan">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Kewarganegaraan</label>
          <input type="text" class="form-control" id="kewarganegaraan" name="kewarganegaraan" placeholder="Kewarganegaraan">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Tambah</button>
          <button type="reset" class="btn btn-primary">Reset</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
        </div>

      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Data Nasabah -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Data Nasabah</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form name="" action="<?= base_url('customer/edit') ?>" method="POST" onsubmit="return validasi(this)">
        <div class="form-group">
          <label for="exampleFormControlInput1">Tipe Nasabah</label>
          <select class="custom-select" name="tipe_nasabah" id="tipe_nasabah_edit">
            <option value="P">Perorangan</option>
            <option value="B">Badan Usaha</option>
          </select>
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Nama</label>
          <input type="text" class="form-control" id="nama_edit" name="nama" placeholder="Nama">
          <input type="text" value="<?= $this->session->userdata('id'); ?>" hidden name="user_id">
          <input type="text" hidden="" name="id" id="id_edit">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Tempat Lahir</label>
          <input type="text" class="form-control" id="tempat_lhr_edit" name="tempat_lhr" placeholder="Tempat Lahir"></div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Tanggal Lahir</label>
          <input type="date" class="form-control" id="tgl_lhr_edit" name="tgl_lhr">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">No Ktp</label>
          <input type="number" class="form-control" id="ktp_edit" name="ktp" placeholder="No KTP">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Alamat</label>
          <input type="text" class="form-control" id="alamat_edit" name="alamat" placeholder="Alamat">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Telp</label>
          <input type="number" class="form-control" id="telp_edit" placeholder="Telp" name="telp">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">No NPWP</label>
          <input type="number" class="form-control" id="npwp_edit" name="npwp" placeholder="No NPWP">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Pekerjaan</label>
          <input type="text" class="form-control" id="pekerjaan_edit" name="pekerjaan" placeholder="Pekerjaan">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Kewarganegaraan</label>
          <input type="text" class="form-control" id="kewarganegaraan_edit" name="kewarganegaraan" placeholder="Kewarganegaraan">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
          <button type="reset" class="btn btn-primary">Reset</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script type="text/javascript">
  function validasi(form) {
    var nama = form.nama.value;
    var ktp = form.ktp.value;
    var tipe = form.tipe_nasabah.value;

    if (!nama) {
      Swal.fire(
        'Pesan',
        'Nama Wajib Diisi',
        'warning'
      )
      return false;
    } else if ((!ktp) && (tipe === 'P')) {
      Swal.fire(
        'Pesan',
        'KTP Wajib Diisi',
        'warning'
      )
      return false;
    }

    if (ktp.length > 16) {
      Swal.fire(
        'Pesan',
        'Maksimal 16 Karakter',
        'warning'
      )
      return false;
    }

    return true;
  }

  function editData(e) {

    $('#modal_edit').modal('hide');
    var id = e.id;
    $.ajax({
      url: "<?= base_url('customer/getDetail/'); ?>" + id,
      type: 'GET',
      data: {
        id: id
      },
      success: function(rsp) {
        var data = rsp.data;
        $('#form').trigger("reset");
        if (data.status === true) {

          $('#id_edit').val(data.data.Id);
          $('#tipe_nasabah_edit').val(data.data.tipe_nasabah);
          $('#nama_edit').val(data.data.nama);
          $('#tempat_lhr_edit').val(data.data.tempat_lhr);
          $('#tgl_lhr_edit').val(data.data.tgl_lhr);
          $('#ktp_edit').val(data.data.no_ktp);
          $('#alamat_edit').val(data.data.alamat);
          $('#telp_edit').val(data.data.telp);
          $('#npwp_edit').val(data.data.no_npwp);
          $('#pekerjaan_edit').val(data.data.pekerjaan);
          $('#kewarganegaraan_edit').val(data.data.kewarganegaraan);
          $('#modal_edit').modal('show');
        }
      }
    });
  }
</script>