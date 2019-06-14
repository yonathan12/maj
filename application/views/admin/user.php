
        <!-- Begin Page Content -->
        <div class="container-fluid">
        
          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800"><?= $title;?></h1>
        
          <div class="col-md-3">
          <?= $this->session->flashdata('message');?>
          </div>
          <?= validation_errors(); ?>
        <a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">Add New User</a>

        <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Level</th>
                <th>Account</th>
                <th>Date Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
                        $no = 1;
                        foreach($AllData as $usr){
                    ?>
                        <tr>
                            <td><?php echo $no++;?></td>
                            <td><?php echo $usr['name']; ?></td>
                            <td><?php echo $usr['email'];?></td>
                            <td>
                                <?php
                                    if ($usr['role_id'] == 1) {
                                        echo 'Admin';
                                    } else {
                                        echo 'Staff';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($usr['is_active'] == 1) {
                                    # code...
                                    echo "Actived";
                                } else {
                                    # code...
                                    echo "Not Actived";
                                }
                                ?>
                            </td>
                            <td><?= date('d F Y',$usr['date_created']);?></td>
                            <td style="text-align: center;">                                
                                <a href="<?= base_url(); ?>admin/detail/<?= $usr['Id']; ?>" class="glyphicon glyphicon-th-list btn btn-sm btn-success"></a>                             
                                <a href="<?= base_url(); ?>admin/ubah/<?= $usr['Id']; ?>" class="glyphicon glyphicon-pencil btn btn-sm btn-primary" ></a>
                                <a href="<?= base_url(); ?>admin/hapusUser/<?= $usr['Id']; ?>" class="btn btn-sm btn-danger glyphicon glyphicon-trash" onclick="return confirm('Yakin');"></a>
                            </td>
                        </tr>
                    <?php }?>
        </tbody>
        </table>
      </div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url();?>admin/user" method="POST">
      <div class="modal-body">
      <input type="text" class="form-control" id="role" name="name" placeholder="Nama">
      </div>
      <div class="modal-body">
      <input type="text" class="form-control" id="role" name="email" placeholder="Email">
      </div>
      <div class="modal-body">
      <input type="password" class="form-control" id="role" name="password" placeholder="Password">
      </div>
      <div class="modal-body">
        <select class="custom-select" name="role_id">        
        <option selected value="">Pilih Level</option>
          <?php foreach ($role as $r) {         
          ?>
          <option value="<?= $r['Id']; ?>"><?= $r['role']; ?></option>        
          <?php } ?>
      </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
