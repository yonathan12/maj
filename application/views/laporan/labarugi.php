<div class="container-fluid">

<div class="col-lg-3">
    <?= $this->session->flashdata('message');?>    
    </div>
    <?= validation_errors(); ?>
    <form action="<?= base_url('laporan/labarugi')?>" method="POST">
        <div class="form-group col-lg-3">
            <label for="exampleFormControlInput1">Valas</label>
            <select name="valas" class="form-control">
            <option value="">Select Menu</option>
            <?php foreach ($valas as $v) : ?> 
            <option value="<?= $v['Id_valas'] ?>"><?= $v['valas'] ?></option>
            <?php endforeach; ?>
            </select>
            <?= form_error('valas','<small class="text-danger pl-3">','</small>'); ?>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <!-- <hr class="sidebar-divider">
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
       
        </tbody>
    </table> -->
</div>


     
