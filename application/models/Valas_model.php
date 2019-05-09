<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Valas_model extends CI_Model 
{

    public function hapusValas($id)
    {
        $data = array
        (
            'status' => 0
        );

        $this->db->where('Id_valas',$id);
        $this->db->update('valas',$data);

        $this->db->where('id_valas',$id);
        $this->db->update('stock',$data);
    }

    public function addValas()
    {
            $kode = $this->input->post('kode');
            $valas = $this->input->post('valas');
            $data = [
                'Id_valas' => $kode,
                'valas' => $valas,
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
            ];

            $dataStock = [
                'id_valas' => $kode,
                'stock_awal' => 0,
                'stock_akhir' => 0,
                'rate' => 0,
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
            ];

            $this->db->insert('valas',$data);
            $this->db->insert('stock',$dataStock);
    }
}