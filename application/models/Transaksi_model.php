<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model 
{
    public function getTransaksiPenjualan()
    {
        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,SUM(transaksi.total) AS Total,transaksi.jumlah,transaksi.total,transaksi.date_created, valas.valas, valas.Id_valas
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        WHERE transaksi.status = 1 AND transaksi.trx = 2 
        GROUP BY transaksi.kd_trx
        ORDER BY transaksi.date_created DESC, transaksi.Id DESC";
        return $this->db->query($query)->result_array();
    }

    public function prosesTransaksiPenjualan()
    {
            $customer = $this->input->post('customer');
            $rate_valas = $this->input->post('rate_valas');
            $valas = $this->input->post('valas');
            $jumlah = $this->input->post('jumlah');
            $total = $this->input->post('total');
            $kd_trx = $this->input->post('kode');

            $queryStock = "SELECT * FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created DESC, time_created DESC  ";
            $dataStock['data'] = $this->db->query($queryStock)->row();                
            $stock = $dataStock['data'];
            $stockAwal = $stock->stock_akhir;

            // get new rate
            $queryRate = "SELECT nr FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created ASC, time_created DESC";
            $dataRate['data'] = $this->db->query($queryStock)->row();                
            $rate = $dataRate['data'];
            $newRate = $rate->nr;

            

            if ($jumlah <= $stockAwal) {
                # code...
            $sisaStock = $stockAwal - $jumlah;
            $totalX = $newRate * $sisaStock;
            
            // insert ke database transaksi
            $data = [ 
                'customer' => $customer,
                'id_valas' => $valas,
                'trx' => 2, //trx out
                'kd_trx' => $kd_trx,
                'rate_valas' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $total, 
                'status' => 1,
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s')
            ];

            // insert ke database stock
            $stockValas = [
                'nr' => $newRate,
                'id_valas' => $valas,
                'stock_awal' => $stockAwal,
                'trx' => 2,
                'kd_trx' => $kd_trx,
                'rate' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $totalX,
                'stock_akhir' => $sisaStock ,
                'date_created' => date('Y-m-d') ,
                'time_created' => date('H:i:s'),
                'status' => 1
            ];

            $this->db->insert('stock',$stockValas);
            $this->db->insert('transaksi',$data);
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('transaksi/penjualan');  

            
            } else {
                # code...
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Stock Kurang !
          </div>');
            redirect('transaksi/penjualan');
            }         
    }

    public function getTransaksiPembelian()
    {
        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,SUM(transaksi.total) AS Total,transaksi.date_created, valas.valas, valas.Id_valas
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        WHERE transaksi.status= 1 AND transaksi.trx = 1 
        GROUP BY transaksi.kd_trx
        ORDER BY transaksi.date_created DESC, transaksi.Id DESC
        ";
        return $this->db->query($query)->result_array();
    }

    public function prosesTransaksiPembelian()
    {
        $kd_trx = $this->input->post('kode');
            $customer = $this->input->post('customer');
            $valas = $this->input->post('valas');
            $rate_valas = $this->input->post('rate_valas');
            (int)$jumlah = $this->input->post('jumlah');
            $total = $this->input->post('total');
            $hasil = [ 
                'kd_trx' => $kd_trx,
                'customer' => $customer,
                'trx' => 1,
                'id_valas' => $valas,
                'rate_valas' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $total,
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
            ];

            $queryStock = "SELECT * FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created DESC, time_created DESC";
            $dataStock['data'] = $this->db->query($queryStock)->row();                
            $stock = $dataStock['data'];
            $stockAwal = $stock->stock_akhir;
            $sisaStock = $stockAwal + $jumlah;

            //Olah New Rate
            $hariini = date("Y-m-d");

            $SSY['data'] = $this->db->query("SELECT stock_akhir FROM stock WHERE id_valas = '$valas' AND status = 1 AND date_created != '$hariini' ORDER BY date_created DESC, time_created DESC")->row();
            $dataSSY = $SSY['data'];
            $stockSSY = $dataSSY->stock_akhir;

            $TY['data'] = $this->db->query("SELECT * FROM stock WHERE id_valas = '$valas'  AND status = 1 AND date_created != '$hariini'  ORDER BY date_created DESC, time_created DESC LIMIT 1")->row();
            $dataTY = $TY['data'];
            $totalY = $dataTY->total;

            $totalBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$hariini' AND trx = 1 AND id_valas = '$valas' AND status = 1 ")->row();
            $dataTotalBeli = $totalBeli['data'];
            $totalPembelian = $dataTotalBeli->TBeli;
            $jumlahPembelian = $dataTotalBeli->JBeli;

            $x = $totalY + $totalPembelian + $total;
            $y = $stockSSY + $jumlahPembelian + $jumlah;

            $Rate = $x / $y;         
            $newRate = round($Rate);

            $totalStock = $newRate * $sisaStock;

            // insert ke database stock
            $stockValas = [
                'nr' => $newRate,
                'id_valas' => $valas,
                'stock_awal' => $stockAwal,
                'kd_trx' => $kd_trx,
                'trx' => 1,
                'rate' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $totalStock,
                'stock_akhir' => $sisaStock ,
                'date_created' => date('Y-m-d') ,
                'time_created' => date('H:i:s'),
                'status' => 1
            ];
            

            $this->db->insert('transaksi',$hasil);
            $this->db->insert('stock',$stockValas);

            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('transaksi/pembelian'); 
    }
}