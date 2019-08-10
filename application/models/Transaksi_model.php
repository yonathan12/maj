<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model 
{
    public function getTransaksiPenjualan()
    {
        $tanggal = date('Y-m-d');
        $query = "SELECT transaksi.kd_trx,transaksi.rate_valas,SUM(transaksi.total) AS Total,transaksi.jumlah,transaksi.total,transaksi.date_created, valas.valas, valas.Id_valas, customer.nama as customer
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        JOIN customer 
        ON transaksi.customer = customer.kd_cst
        WHERE transaksi.status = 1 AND transaksi.trx = 2 AND transaksi.date_created = '$tanggal'
        GROUP BY transaksi.kd_trx
        ORDER BY transaksi.date_created DESC, transaksi.Id DESC";
        return $this->db->query($query)->result_array();
    }

    public function tempJual()
    {
        $kd_trx = $this->input->post('kode');
            $valas = $this->input->post('valas');
            $rate_valas = $this->input->post('rate_valas');
            (int)$jumlah = $this->input->post('jumlah');
            $total = $this->input->post('total');

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
                'id_valas' => $valas,
                'rate_valas' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $total,
                'trx' => 2
            ];

            // insert ke database stock
            $stockValas = [
                'nr' => $newRate,
                'id_valas' => $valas,
                'stock_awal' => $stockAwal,
                'kd_trx' => $kd_trx,
                'trx' => 2,
                'rate' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $totalX,
                'stock_akhir' => $sisaStock 
            ];

                $this->db->insert('temp_stock',$stockValas);

                $this->db->insert('temp_transaksi',$hasil);
                $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
                Data Tersimpan!
            </div>');
                redirect('transaksi/jual'); 

            
            } else {
                # code...
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Stock Kurang !
          </div>');
            redirect('transaksi/jual');
            }                
    }

    public function prosesTransaksiPenjualan()
    {
        $kd_trx = $this->session->userdata('email');
        $kode = $this->input->post('kode');
        $customer = substr($this->input->post('customer'),-9);
        $hariini = date('Y-m-d');
        $waktu = date('H:i:s');
        
        $cekCustomer['data'] = $this->db->query("SELECT kd_cst FROM customer WHERE kd_cst = '$customer'")->row_array();
        $cekKode = $cekCustomer['data']['kd_cst'];

        if ($cekKode == null) {
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Customer Belum Terdaftar !
            </div>');
            redirect('transaksi/jual');
        } else {
            $queryTemp = $this->db->query("SELECT * FROM temp_transaksi WHERE kd_trx = '$kd_trx' AND trx = 2")->result_array();

            foreach ($queryTemp as $row) {
                # code...
            $result = [
                'kd_trx' => $kode,
                'customer' => $customer,
                'trx' => 2,
                'id_valas' => $row['id_valas'],
                'rate_valas' => $row['rate_valas'],
                'jumlah' => $row['jumlah'],
                'total' => $row['total'],
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
            ];
            $this->db->insert('transaksi',$result);

            $this->db->where('kd_trx',$kd_trx);
            $this->db->delete('temp_transaksi');

        }

        $queryTempStock = $this->db->query("SELECT * FROM temp_stock WHERE kd_trx = '$kd_trx' AND trx = 2")->result_array();

           foreach ($queryTempStock as $row) {
            # code...
           $tempStock = [
            'id_valas' => $row['id_valas'],
            'nr' => $row['nr'],
            'stock_awal' => $row['stock_awal'],
            'kd_trx' => $kode,
            'trx' => 2,                
            'rate' => $row['rate'],
            'jumlah' => $row['jumlah'],
            'total' => $row['total'],
            'stock_akhir' => $row['stock_akhir'],
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s'),
            'status' => 1
           ];
           $this->db->insert('stock',$tempStock);
           $this->db->where('kd_trx',$kd_trx);
           $this->db->delete('temp_stock');
        }          
        }
    }

    public function getTransaksiPembelian()
    {
        $tanggal = date('Y-m-d');
        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,SUM(transaksi.total) AS Total,transaksi.date_created, valas.valas, valas.Id_valas, customer.nama
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        JOIN customer
       	ON transaksi.customer = customer.kd_cst
        WHERE transaksi.status= 1 AND transaksi.trx = 1  AND transaksi.date_created = '$tanggal'
        GROUP BY transaksi.kd_trx
        ORDER BY transaksi.date_created DESC, transaksi.Id DESC
        ";
        return $this->db->query($query)->result_array();
    }

    public function tempBeli()
    {
        $kd_trx = $this->input->post('kode');
        $valas = $this->input->post('valas');
        $rate_valas = $this->input->post('rate_valas');
        (int)$jumlah = $this->input->post('jumlah');
        $total = $this->input->post('total');

        $hasil = [ 
            'kd_trx' => $kd_trx,
            'id_valas' => $valas,
            'rate_valas' => $rate_valas,
            'jumlah' => $jumlah,
            'total' => $total,
            'trx' => 1
        ];
        
        $hariini = date("Y-m-d");           

        $queryStock = "SELECT SUM(stock_akhir - stock_awal) AS stock_akhir FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created DESC, time_created DESC";
        $dataStock['data'] = $this->db->query($queryStock)->row();                
        $stock = $dataStock['data'];
        $stockAwal = $stock->stock_akhir;
        $sisaStock = $stockAwal + $jumlah;

        $totalBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$hariini' AND trx = 1 AND id_valas = '$valas' AND status = 1 ")->row();
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
            'trx' => 1,
            'rate' => $rate_valas,
            'jumlah' => $jumlah,
            'total' => $totalStock,
            'stock_akhir' => $sisaStock 
        ];

        $this->db->insert('temp_stock',$stockValas);

        $this->db->insert('temp_transaksi',$hasil);
    }  

    public function prosesTransaksiPembelian()
    {   
        $kd_trx = $this->session->userdata('email');
        $kode = $this->input->post('kode');
        $customer = substr($this->input->post('customer'),-9);
        $hariini = date('Y-m-d');
        $waktu = date('H:i:s');
        
        $cekCustomer['data'] = $this->db->query("SELECT kd_cst FROM customer WHERE kd_cst = '$customer'")->row_array();
        $cekKode = $cekCustomer['data']['kd_cst'];

        if ($cekKode == null) {
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Customer Belum Terdaftar !
            </div>');
            redirect('transaksi/jual');
        } else {
            $queryTemp = $this->db->query("SELECT * FROM temp_transaksi WHERE kd_trx = '$kd_trx' AND trx = 1")->result_array();
       
            foreach ($queryTemp as $row) {
                # code...
               $result = [
                'kd_trx' => $kode,
                'customer' => $customer,
                'trx' => 1,
                'id_valas' => $row['id_valas'],
                'rate_valas' => $row['rate_valas'],
                'jumlah' => $row['jumlah'],
                'total' => $row['total'],
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
               ];
             
               $this->db->insert('transaksi',$result);
    
               $this->db->where('kd_trx',$kd_trx);
               $this->db->delete('temp_transaksi');
            }
    
            $queryTempStock = $this->db->query("SELECT * FROM temp_stock WHERE kd_trx = '$kd_trx' AND trx = 1")->result_array();
    
               foreach ($queryTempStock as $row) {
                # code...
               $tempStock = [
                'id_valas' => $row['id_valas'],
                'nr' => $row['nr'],
                'stock_awal' => $row['stock_awal'],
                'kd_trx' => $kode,
                'trx' => 1,                
                'rate' => $row['rate'],
                'jumlah' => $row['jumlah'],
                'total' => $row['total'],
                'stock_akhir' => $row['stock_akhir'],
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
               ];
               
               $this->db->insert('stock',$tempStock);
               $this->db->where('kd_trx',$kd_trx);
               $this->db->delete('temp_stock');
            }
        }          
    }

    function search_blog($customer){
        $this->db->like('nama', $customer , 'both');
        $this->db->order_by('nama', 'ASC');
        $this->db->limit(10);
        return $this->db->get('customer')->result();
    }
}