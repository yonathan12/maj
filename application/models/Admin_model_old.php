<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model 
{
    public function getStock()
    {
            $queryDataStock = "SELECT stock.*,SUM(stock.stock_akhir - stock.stock_awal) AS sa, valas.*
            FROM stock JOIN valas
            WHERE stock.id_valas = valas.Id_valas AND stock.status = 1
            GROUP BY stock.id_valas ORDER BY stock.time_created";
            return $this->db->query($queryDataStock)->result_array();
    }

    public function addStock()
    {
        $valas = $this->input->post('valas');
        $addStock = $this->input->post('stock');
        $rate = $this->input->post('rate');
        $total = $this->input->post('total');
        $hariini = date('Y-m-d');
        $kdAddStock = $this->input->post('kdaddstock');

        $queryStock = "SELECT * FROM stock WHERE id_valas = '$valas' ORDER BY stock.time_created DESC  ";
        $dataStock['data'] = $this->db->query($queryStock)->row();                
        $stock = $dataStock['data'];
        $stockAwal = $stock->stock_akhir;
        $stockAkhir = $stockAwal + $addStock;

        if ($stockAwal != 0) {
            $queryStock = "SELECT SUM(stock_akhir - stock_awal) AS stock_akhir FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created DESC, time_created DESC";
        $dataStock['data'] = $this->db->query($queryStock)->row();                
        $stock = $dataStock['data'];
        $stockAwal = $stock->stock_akhir;
        $sisaStock = $stockAwal + $addStock;

        $totalBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$hariini' AND id_valas = '$valas' AND status = 1 AND trx = 1")->row();
        $dataTotalBeli = $totalBeli['data'];
        $totalTransaksiPembelian = $dataTotalBeli->TBeli;
        $jumlahTransaksiPembelian = $dataTotalBeli->JBeli;

        $totalBeliVoid['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$hariini' AND id_valas = '$valas' AND status = 1 AND trx = 3")->row();
        $dataTotalBeliVoid = $totalBeliVoid['data'];
        $totalPembelianVoid = $dataTotalBeliVoid->TBeli;
        $jumlahPembelianVoid = $dataTotalBeliVoid->JBeli;

        $totalPembelian = $totalTransaksiPembelian + $totalPembelianVoid;
        $jumlahPembelian = $jumlahTransaksiPembelian + $jumlahPembelianVoid;
        
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
        $y = $resultStock + $jumlahPembelian + $addStock;

        $Rate = $x / $y;         
        $newRate = $Rate;

        $totalStock = $newRate * $sisaStock;

        // insert ke database stock
        $stockValas = [
            'nr' => $newRate,
            'id_valas' => $valas,
            'stock_awal' => $stockAwal,
            'kd_trx' => $kdAddStock,
            'trx' => 5,
            'rate' => $rate,
            'jumlah' => $addStock,
            'total' => $totalStock,
            'stock_akhir' => $sisaStock,
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s'),
            'status' => 1
        ];

        $hasil = [ 
            'kd_trx' => $kdAddStock,
            'id_valas' => $valas,
            'rate_valas' => $rate,
            'jumlah' => $addStock,
            'total' => $total,
            'trx' => 5,
            'date_created' => date('Y-m-d'),
            'time_created' => date('H:i:s'),
            'status' => 1
        ];
        
        $this->db->insert('stock',$stockValas);
        $this->db->insert('transaksi',$hasil);

        } else {
            $data = [
                'id_valas' => $valas,
                'nr' => $rate,
                'stock_awal' => $stockAwal,
                'kd_trx' => $kdAddStock,
                'trx' => 0,
                'stock_akhir' => $stockAkhir,
                'jumlah' => $addStock,
                'total' => $total,
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
            ];
            $this->db->insert('stock',$data);
        }               
    }
    
    public function hapusUser($id)
    {
        $data = array
        (
            'is_active' => 0
        );

        $this->db->where('Id',$id);
        $this->db->update('user',$data);
    }

    public function simpanRole()
    {
        $role = $this->input->post('role');
        $data = [
            'role' => $role
        ];
        $this->db->insert('user_role',$data);
        redirect('admin/role');
    }
    
    public function deleteRole($id)
    {
        $this->db->where('Id',$id);
        $this->db->delete('user_role');
        redirect('admin/role');
    }
}