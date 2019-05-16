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

                $queryStock = "SELECT * FROM stock WHERE id_valas = '$valas' ORDER BY stock.time_created DESC  ";
                $dataStock['data'] = $this->db->query($queryStock)->row();                
                $stock = $dataStock['data'];
                $stockAwal = $stock->stock_akhir;
                $stockAkhir = $stockAwal + $addStock;
                
                $data = [
                    'id_valas' => $valas,
                    'nr' => $rate,
                    'stock_awal' => $stockAwal,
                    'trx' => 1,
                    'stock_akhir' => $stockAkhir,
                    'jumlah' => $addStock,
                    'total' => $total,
                    'date_created' => date('Y-m-d'),
                    'time_created' => date('H:i:s'),
                    'status' => 1
                ];
                $this->db->insert('stock',$data);
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

}