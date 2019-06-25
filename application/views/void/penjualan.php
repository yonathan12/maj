<div class="container-fluid">
    <div class="col-lg-3">
    <?= $this->session->flashdata('message');?>    
    </div>
    <?= validation_errors(); ?>    
    <a href="<?= base_url();?>void/jual" class="btn btn-primary mb-3">New Void</a>
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Nomor Transaksi</th>
                <th>Total</th>
                <th>Date Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
                        $no = 1;
                        foreach($penjualan as $jual){
                    ?>
                        <tr>
                        
                            <td><?php echo $no++;?></td>
                            <td><?php echo $jual['kd_trx']; ?></td>
                            <td><?php echo $jual['customer']; ?></td>
                            <td><?php echo "IDR ".number_format($jual['Total']);?></td>
                            <td><?= $jual['date_created'];?></td>
                            <td>                                
                                <a href="<?= base_url(); ?>transaksi/printInvoice/<?= $jual['kd_trx']; ?>" class="fas fa-print"></a>                                
                            </td>
                        </tr>
                    <?php }?>
        </tbody>
    </table>
    <!-- Divider -->
    <hr class="sidebar-divider">
    <h3 text-align="center">Stock</h3>
    <table id="table2" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Valas</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
        <?php 
                        $no = 1;
                        foreach($stock as $s){
                    ?>
                        <tr>
                            <td><?php echo $no++;?></td>
                            <td><?php echo $s['valas']; ?></td>
                            <td><?php echo $s['sa'];?></td>
                        </tr>
                    <?php }?>
        </tbody>
    </table>
</div>   