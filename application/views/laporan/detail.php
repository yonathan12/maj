<div class="container">
<a href="<?= base_url();?>laporan/labarugi" class="btn btn-success mb-3">Kembali</a>
<table id="table2" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Valas</th>
                <th>Laba</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
                        foreach($detail as $row){
                    ?>
                        <tr>
                            <td><?php echo $no++;?></td>
                            <td><?php echo $row['valas']; ?></td> 
                            <td><?php  echo "IDR ".number_format($row['total']);?></td>
                            <td><a href="<?= base_url(); ?>laporan/hapus/<?= $row['date_created']; ?>" class="fas fa-trash-alt" onclick="return confirm('Yakin');"></a>
                            <a href="<?= base_url(); ?>laporan/detail/<?= $row['date_created']; ?>" class="fas fa-info" ></a></td>
                        </tr>
                        <?php }?>
        </tbody>
    </table>
    
</div>