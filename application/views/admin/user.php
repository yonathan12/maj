
        <!-- Begin Page Content -->
        <div class="container-fluid">
        
          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800"><?= $title;?></h1>
        
          <?php echo form_error('menu', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
        <?= $this->session->flashdata('message');?>

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
                            <td><?= date('d F Y',$user['date_created']);?></td>
                            <td style="text-align: center;">                                
                                <a href="<?= base_url(); ?>admin/detail/<?= $usr['Id']; ?>" class="glyphicon glyphicon-th-list btn btn-sm btn-success"></a>                             
                                <a href="<?= base_url(); ?>admin/ubah/<?= $usr['Id']; ?>" class="glyphicon glyphicon-pencil btn btn-sm btn-primary" ></a>
                                <a href="<?= base_url(); ?>admin/hapus/<?= $usr['Id']; ?>" class="btn btn-sm btn-danger glyphicon glyphicon-trash" onclick="return confirm('Yakin');"></a>
                            </td>
                        </tr>
                    <?php }?>
        </tbody>
    </table>

      </div>