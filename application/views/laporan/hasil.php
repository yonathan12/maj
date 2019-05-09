<div class="container-fluid">

<div class="col-lg-3">
    <?= $this->session->flashdata('message');?>    
    </div>
    
    <form action="<?= base_url('laporan/laporanlaba')?>" method="POST">
    <div class="form-group">
            <label for="exampleFormControlInput1">Valas</label>
            <input type="text" class="form-control" id="valas" name="valas" placeholder="Valas" readonly value="<?= $valas;
            ?>">
        </div>
    <div class="form-group">
            <label for="exampleFormControlInput1">Total</label>
            <input type="number" class="form-control" id="total" name="total" placeholder="Total" readonly value="<?= $hasil;
            ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>    <!-- Divider -->
</div>


     
