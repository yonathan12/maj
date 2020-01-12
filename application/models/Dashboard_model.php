<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model 
{
    public function totalPembelian()
    {
        $tanggal = date('Y-m-d');
        $query = "SELECT count(id) AS total FROM transaksi WHERE transaksi.status = 1 AND transaksi.trx = 1 AND transaksi.date_created = '$tanggal'";
        return $this->db->query($query)->result()[0]->total;
    }

    public function totalPenjualan()
    {
        $tanggal = date('Y-m-d');
        $query = "SELECT count(id) AS total FROM transaksi WHERE transaksi.status = 1 AND transaksi.trx = 2 AND transaksi.date_created = '$tanggal'";
        return $this->db->query($query)->result()[0]->total;
    }

    public function totalPembatalanPembelian()
    {
        $tanggal = date('Y-m-d');
        $query = "SELECT count(id) AS total FROM transaksi WHERE transaksi.status = 1 AND transaksi.trx = 3 AND transaksi.date_created = '$tanggal'";
        return $this->db->query($query)->result()[0]->total;
    }

    public function totalPembatalanPenjualan()
    {
        $tanggal = date('Y-m-d');
        $query = "SELECT count(id) AS total FROM transaksi WHERE transaksi.status = 1 AND transaksi.trx = 4 AND transaksi.date_created = '$tanggal'";
        return $this->db->query($query)->result()[0]->total;
    }
}