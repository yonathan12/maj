<div class="container-fluid">

<div class="col-lg-3">
    <?= $this->session->flashdata('message');?>    
    </div>
    <?= validation_errors(); ?>
    <a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">New Transaction</a>
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>Valas</th>
                <th>Rate Valas</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Date Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
                        $no = 1;
                        foreach($pembelian as $beli){
                    ?>
                        <tr>
                        
                            <td><?php echo $no++;?></td>
                            <td><?php echo $beli['customer']; ?></td>
                            <td><?php echo $beli['valas']; ?></td>
                            <td><?php echo number_format($beli['rate_valas']);?></td>
                            <td><?php echo number_format($beli['jumlah']);?></td>
                            <td><?php echo "IDR ".number_format($beli['total']);?></td>
                            <td><?= $beli['date_created'];?></td>
                            <td>                                
                                <a href="<?= base_url(); ?>transaksi/printInvoice/<?= $beli['kd_trx']; ?>" class="fas fa-print"></a>
                                <a href="<?= base_url(); ?>admin/detail/<?= $beli['kd_trx']; ?>" class="fas fa-info"></a>                             
                                <a href="<?= base_url(); ?>admin/ubah/<?= $beli['kd_trx']; ?>" class="fas fa-edit" ></a>
                                <a href="<?= base_url(); ?>transaksi/hapusBeli/<?= $beli['kd_trx']; ?>" class="fas fa-trash-alt" onclick="return confirm('Yakin');"></a>
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


    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Pembelian</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form name="hitungRateBeli" action="<?= base_url('transaksi/pembelian')?>" method="POST">
        <div class="form-group">
            <label for="exampleFormControlInput1">Customer</label>
            <input type="text" class="form-control" id="customer" name="customer" placeholder="Customer">
            <input type="text" value="<?= $kode; ?>" hidden name="kode">
            <?= form_error('customer','<small class="text-danger pl-3">','</small>'); ?>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Valas</label>
            <select name="valas" class="form-control">
            <option value="">Select Menu</option>
            <?php foreach ($valas as $v) : ?> 
            <option value="<?= $v['Id_valas'] ?>"><?= $v['valas'] ?></option>
            <?php endforeach; ?>
            </select>
            <?= form_error('valas','<small class="text-danger pl-3">','</small>'); ?>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Rate Valas</label>
            <input type="number" class="form-control" id="rate_valas" name="rate_valas" placeholder="Rate Valas">
            <?= form_error('rate_valas','<small class="text-danger pl-3">','</small>'); ?>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Jumlah</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah">
            <?= form_error('jumlah','<small class="text-danger pl-3">','</small>'); ?>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Total</label>
            <input type="number" class="form-control" id="total" placeholder="Total" name="total" readonly >
            <?= form_error('total','<small class="text-danger pl-3">','</small>'); ?>
        </div>
        <input type=button name=submit onClick="hitungBeli()" class="btn btn-primary" value="Hitung">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
  </div>
</div>

     
