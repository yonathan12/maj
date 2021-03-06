<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <strong>Invoice </strong><?= $inv['kd_trx'] ?></strong>
            <span class="float-right"> <strong>Tanggal </strong><?= date("d-m-Y"); ?></span>
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
                    <div>Nama : <?= $inv['nama']; ?></div>
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
                        ?>
                            <tr>
                                <td class="center"><?= $no++; ?></td>
                                <td class="left strong"><?= $value['valas']; ?></td>
                                <td class="left"><?= $value['description']; ?></td>
                                <td class="right"><?= "Rp " . number_format($value['rate_valas']); ?></td>
                                <td class="center"><?= number_format($value['jumlah']); ?></td>
                                <td class="right"><?= "Rp " . number_format($value['total']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="center"></th>
                            <th></th>
                            <th></th>
                            <th class="right"></th>
                            <th class="center">Jumlah</th>
                            <th class="right"><?= "Rp " . number_format($inv['TTL']); ?></th>
                        </tr>
                    </tfoot>
                    <tbody>
                </table>
                <table class="table table-borderless">
                <thead>
                    <tr>
                        <th class="center" width="455px">Dibuat Oleh</th>
                        <th class="center" width="325px">Disetujui Oleh</th>
                        <th class="center">Diterima Oleh</th>
                        <th class="center">&nbsp;</th>
                        <th class="right">&nbsp;</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="center"><?= $inv['user_id_created'] ?></th>
                        <th>&nbsp;</th>
                        <th><?= $inv['nama']; ?></th>
                        <th class="right">&nbsp;</th>
                        <th class="center">&nbsp;</th>
                        <th class="right">&nbsp;</th>
                    </tr>
                </tfoot>
                <tfoot>
                    <tr>
                    <tr>
                        <th class="center"></th>
                    </tr>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>
</div>
</div>