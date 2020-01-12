<div class="container-fluid">
    <div class="col-lg-3">
        <?= $this->session->flashdata('message'); ?>
    </div>
    <?= validation_errors(); ?>
    <a href="<?= base_url(); ?>transaksi/beli" class="btn btn-primary mb-3">New Transaction</a>
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Invoice</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Date Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($pembelian as $beli) {
            ?>
                <tr>

                    <td><?php echo $no++; ?></td>
                    <td><?php echo $beli['kd_trx']; ?></td>
                    <td><?php echo $beli['nama']; ?></td>
                    <td><?php echo "IDR " . number_format($beli['Total']); ?></td>
                    <td><?= $beli['date_created']; ?></td>
                    <td>
                        <a href="<?= base_url(); ?>transaksi/printInvoice/<?= $beli['kd_trx']; ?>" class="fas fa-print"></a>
                    </td>
                </tr>
            <?php } ?>
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
            foreach ($stock as $s) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $s['valas']; ?></td>
                    <td><?php echo $s['sa']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>