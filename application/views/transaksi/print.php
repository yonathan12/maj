<div class="container mt-5">
  <div class="card">
<div class="card-header">
<strong>Invoice </strong><?= $inv['kd_trx']?></strong> 
  <span class="float-right"> <strong>Tanggal </strong><?= date("d-m-Y");?></span>

</div>
<div class="card-body">
<div class="row mb-4">
        <div class="col-sm-6">
        <h6 class="mb-3">PT Muchad Artha Jaya</h6>
            
            <div>Jl Senayan 49 RT 001/02, Gunung, Kebayoran Baru, Jakarta 12120
            </div>
            <div>021 7205879</div>
        </div>

        <div class="col-sm-6">
            <h6 class="mb-3">Customer:</h6>
            <div>Nama : <?= $inv['nama'];?></div>
            <div>No Phone : </div>
            <div>Alamat : </div>
        </div>
</div>

<div class="table-responsive-sm">
<table class="table table-striped">
    <thead>
        <tr>
            <th class="center">#</th>
            <th>Valas</th>
            <th>Description</th>
            <th class="right">Rate</th>
            <th class="center">Jumlah</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $no = 1;
    foreach ($data as $value) {
        # code...
    
    ?>
    <tr>
        <td class="center"><?= $no++; ?></td>
        <td class="left strong"><?= $value['valas']; ?></td>
        <td class="left"><?= $value['description']; ?></td>
        <td class="right"><?= "Rp ".number_format($value['rate_valas']); ?></td>
        <td class="center"><?= number_format($value['jumlah']); ?></td>
        <td class="right"><?= "Rp ".number_format($value['total']); ?></td>
    </tr>

    <?php } ?>
    </tbody>
</table>
</div>
<div class="row">
<div class="col-lg-4 col-sm-5">

</div>

    <div class="col-lg-12 col-sm-5 ml-auto">
    <table class="table table-clear">
        <tbody>
        <tr>

        <td class="left">
                <p>Dibuat Oleh</p>
            </td>
            <td class="left">
                <p>Di Setujui Oleh</p>
            </td>
            <td class="left">
                <p>Di Terima Oleh</p>
            </td>
        <td class="left">
            <strong>Total</strong>
            </td>
            <td class="right">
            <strong><?= "Rp ".number_format($inv['TTL']);?></strong>
            </td>
        </tr>
        </tbody>
    </table>
    </div>

</div>

</div>
</div>
</div>