<div class="container-fluid">

<div class="col-lg-3">
    <?= $this->session->flashdata('message');?>    
    </div>
    
    <form action="<?= base_url('')?>" method="POST">
    <div class="form-group">
            <label for="exampleFormControlInput1">Jumlah</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah" readonly value="<?=$hasil;
            ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>    <!-- Divider -->
</div>


     
