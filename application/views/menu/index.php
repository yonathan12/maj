<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?= $title;?></h1>
    <div class="">
      <div class="flash-data" data-flashdata="<?= $this->session->flashdata('message');?>"> </div>
      <?php $this->session->flashdata('message')?$this->session->flashdata('message'):''?>
        <a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">Tambah Menu Baru</a>
        <table class="table table-striped table-bordered" id="tableMenu" style="width:100%">
          <thead>
            <tr>
              <th scope="col">No</th>
              <th scope="col">Menu</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
        <tbody>
          <?php $i = 1; ?> 
          <?php foreach ($menu as $m) : ?>
            <tr>
              <th scope="row"><?= $i;?></th>
              <td><?= $m['menu']; ?></td>
              <td>
                <a href="#" data-toggle="modal" data-target="#editMenu<?= $m['Id']; ?>" class="badge badge-primary" >Edit</a>
                <a href="menu/deleteMenu/<?= $m['Id']; ?>" class="badge badge-danger" onclick="return confirm('Yakin');">Hapus</a>
              </td>
            </tr>
          <?php $i++; ?>
          <?php  endforeach; ?>  
        </tbody>
        </table>
      </div>
  </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Tambah Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url();?>menu" method="POST" onsubmit="return validasi(this)">
        <div class="modal-body">
          <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu">
          <input type="text" name="user_id" hidden value="<?= $this->session->userdata('id'); ?>">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" >Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Menu -->
<?php
  foreach ($menu as $key => $value) :
?>
<div class="modal fade" id="editMenu<?= $value['Id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Edit Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url();?>menu/editMenu" method="POST" onsubmit="return validasi(this)">
        <div class="modal-body">
          <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu" value="<?= $value['menu']; ?>">
          <input type="text" hidden value="<?= $value['Id']; ?>" name="id">
          <input type="text" name="user_id" hidden value="<?= $this->session->userdata('id'); ?>">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success" >Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>

<script type="text/javascript">

  function validasi(form){
    var menu = form.menu.value;
      if(!menu){
        Swal.fire(
          'Pesan',
          'Menu Wajib Diisi',
          'warning'
        )
        return false;
      }
      return true;
    }

  var table = $('#tableMenu');
  table.DataTable({
      responsive: true
  });
</script>