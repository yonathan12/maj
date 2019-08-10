<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Void_model extends CI_Model 
{
    public function search_kdTrx($kd_trx){
        $this->db->where('trx',1);
        $this->db->like('kd_trx', $kd_trx , 'both');
        $this->db->order_by('kd_trx', 'ASC');
        $this->db->limit(10);
        return $this->db->get('transaksi')->result();
    }

    public function search_kdTrxJual($kd_trx){
        $this->db->where('trx',2);
        $this->db->like('kd_trx', $kd_trx , 'both');
        $this->db->order_by('kd_trx', 'ASC');
        $this->db->limit(10);
        return $this->db->get('transaksi')->result();
    }

    public function getVoidPembelian()
    {
        $tanggal = date('Y-m-d');
        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,SUM(transaksi.total) AS Total,transaksi.date_created, valas.valas, valas.Id_valas
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        WHERE transaksi.status= 1 AND transaksi.trx = 3  AND transaksi.date_created = '$tanggal'
        GROUP BY transaksi.kd_trx
        ORDER BY transaksi.date_created DESC, transaksi.Id DESC
        ";
        return $this->db->query($query)->result_array();
    }

    public function prosesVoidPembelian()
    {
        $hariini = date('Y-m-d');
        $waktu = date('H:i:s');  
        $kd_trx = $this->input->post('kode');
        $customer = $this->input->post('kd_trx');
        $valas = $this->input->post('valas');
        $rate_valas = $this->input->post('rate_valas');
        (int)$jumlah = $this->input->post('jumlah');
        (int)$total = $this->input->post('total');
        $hasil = [ 
            'kd_trx' => $kd_trx,
            'customer' => $customer,
            'id_valas' => $valas,
            'rate_valas' => $rate_valas,
            'jumlah' => $jumlah,
            'total' => $total,
            'trx' => 3,
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s'),
            'status' => 1
        ];        

        $queryStock = "SELECT SUM(stock_akhir - stock_awal) AS stock_akhir FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created DESC, time_created DESC";
        $dataStock['data'] = $this->db->query($queryStock)->row();                
        $stock = $dataStock['data'];
        $stockAwal = $stock->stock_akhir;
        $sisaStock = $stockAwal + $jumlah;

        $totalBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$hariini' AND id_valas = '$valas' AND status = 1 AND trx = 1 OR trx = 3")->row();
        $dataTotalBeli = $totalBeli['data'];
        $totalPembelian = $dataTotalBeli->TBeli;
        $jumlahPembelian = $dataTotalBeli->JBeli;
        
        //Olah New Rate                        
        $SSY['data'] = $this->db->query("SELECT SUM(stock_akhir - stock_awal) AS stock_akhir FROM stock WHERE id_valas = '$valas' AND status = 1 AND date_created != '$hariini' ORDER BY date_created DESC, time_created DESC")->row_array();
        $dataSSY = $SSY['data'];
        $stockSSY = $dataSSY['stock_akhir'];            

        $TY['data'] = $this->db->query("SELECT total FROM stock WHERE id_valas = '$valas'  AND status = 1 AND date_created != '$hariini'  ORDER BY date_created DESC, time_created DESC LIMIT 1")->row_array();
        $dataTY = $TY['data'];
        $totalY = $dataTY['total'];

        if ($totalY == '') {
            # code...
            $SSY['data'] = $this->db->query("SELECT SUM(stock_akhir - stock_awal) AS stock_akhir FROM stock WHERE id_valas = '$valas' AND status = 1 AND trx = 0 ORDER BY time_created DESC")->row_array();
            $dataSSY = $SSY['data'];
            $stockSSYR = $dataSSY['stock_akhir'];            

            $TY['data'] = $this->db->query("SELECT total FROM stock WHERE id_valas = '$valas'  AND status = 1 AND trx = 0 ORDER BY time_created DESC")->row_array();
            $dataTY = $TY['data'];
            $totalYR = $dataTY['total'];

            $resultTotal = $totalYR;
            $resultStock = $stockSSYR;
        } else {
            # code...
            $resultTotal = $totalY;
            $resultStock = $stockSSY;
        }           

        $x = $resultTotal + $totalPembelian + $total;
        $y = $resultStock + $jumlahPembelian + $jumlah;

        $Rate = $x / $y;         
        $newRate = $Rate;

        $totalStock = $newRate * $sisaStock;

        // insert ke database stock
        $stockValas = [
            'nr' => $newRate,
            'id_valas' => $valas,
            'stock_awal' => $stockAwal,
            'kd_trx' => $kd_trx,
            'trx' => 3,
            'rate' => $rate_valas,
            'jumlah' => $jumlah,
            'total' => $totalStock,
            'stock_akhir' => $sisaStock,
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s'),
            'status' => 1
        ];

        $this->db->insert('stock',$stockValas);

        $this->db->insert('transaksi',$hasil);
    }
    
    public function getVoidPenjualan()
    {
        $tanggal = date('Y-m-d');
        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,SUM(transaksi.total) AS Total,transaksi.date_created, valas.valas, valas.Id_valas
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        WHERE transaksi.status= 1 AND transaksi.trx = 4  AND transaksi.date_created = '$tanggal'
        GROUP BY transaksi.kd_trx
        ORDER BY transaksi.date_created DESC, transaksi.Id DESC
        ";
        return $this->db->query($query)->result_array();
    }

    public function prosesVoidPenjualan()
    {
            $hariini = date('Y-m-d');
            $waktu = date('H:i:s');  
            $kd_trx = $this->input->post('kode');
            $customer = $this->input->post('kd_trx');
            $valas = $this->input->post('valas');
            $rate_valas = $this->input->post('rate_valas');
            (int)$jumlah = $this->input->post('jumlah');
            (int)$total = $this->input->post('total');

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
            
            // Insert ke database temp_transaksi
            $hasil = [ 
                'kd_trx' => $kd_trx,
                'customer' => $customer,
                'id_valas' => $valas,
                'rate_valas' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $total,
                'trx' => 3,
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 3
            ];

            // insert ke database stock
            $stockValas = [
            'nr' => $newRate,
            'id_valas' => $valas,
            'stock_awal' => $stockAwal,
            'kd_trx' => $kd_trx,
            'trx' => 4,
            'rate' => $rate_valas,
            'jumlah' => $jumlah,
            'total' => $totalX,
            'stock_akhir' => $sisaStock,
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s'),
            'status' => 1
            ];

                $this->db->insert('stock',$stockValas);

                $this->db->insert('transaksi',$hasil);
                $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
                Data Tersimpan!
            </div>');
                redirect('void/penjualan'); 

            
            } else {
                # code...
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Stock Kurang !
          </div>');
            redirect('void/jual');
            }                
    }
}