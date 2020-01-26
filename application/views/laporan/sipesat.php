<div class="container-fluid">
  <div class="col-lg-3">
    <div class="flash-data" data-flashdata="<?= $this->session->flashdata('message'); ?>"> </div>
    <?php $this->session->flashdata('message') ? $this->session->flashdata('message') : '' ?>
    <div class="flash-data1" data-flashdata="<?= $this->session->flashdata('message1'); ?>"> </div>
    <?php $this->session->flashdata('message1') ? $this->session->flashdata('message1') : '' ?>
  </div>
  <form action="<?= base_url(); ?>laporan/exportSipesat" method="POST" onsubmit="return validasi(this);">
    <div class="modal-body">
      <div class="form-group row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Triwulan</label>
        <div class="col-sm-4">
          <select name="periode" class="form-control">
            <option value="">Pilih Periode</option>
            <option value="1">Januari</option>
            <option value="2">April</option>
            <option value="3">Juli</option>
            <option value="4">Oktober</option>
          </select>
        </div>
      </div>
    </div>

    <div class="modal-body">
    <div class="form-group row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Tahun</label>
        <div class="col-sm-4">
        <input type="text" class="form-control" id="tahun" placeholder="Tahun" name="tahun" value="<?= date("Y"); ?>" maxlength="4">
        </div>
      </div>
    </div>
      <button type="reset" class="btn btn-danger" data-dismiss="modal">Batal</button>
      <button type="submit" class="btn btn-success">Export</button>
  </form>

</div>
<script>
  function validasi(form) {
    var periode = form.periode.value;

    if (!periode || periode == "") {
      Swal.fire(
        'Pesan',
        'Periode Wajib Diisi',
        'warning'
      )
      return false;
    }
    return true;

  }
</script>