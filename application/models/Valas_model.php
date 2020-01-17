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
            $description = $this->input->post('description');
            $data = [
                'kd_valas' => $kode,
                'valas' => $valas,
                'description' => $description,
                'user_id_created' => $this->session->userdata('id'),
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
            ];

            $this->db->insert('valas',$data);
            $idValas = $this->db->insert_id();

            $dataStock = [
                'id_valas' => $idValas,
                'stock_awal' => 0,
                'stock_akhir' => 0,
                'rate' => 0,
                'user_id_created' => $this->session->userdata('id'),
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
            ];

            $this->db->insert('stock',$dataStock);
    }

    public function editValas(){
        $id = $this->input->post('id');
        $valas = $this->input->post('valas');
        $description = $this->input->post('description');
        $data = [
            'valas' => $valas,
            'description' => $description,
            'user_id_update' => $this->session->userdata('id'),
            'date_update' => date('Y-m-d'),
            'time_update' => date('H:i:s')
        ];

        $this->db->where('Id',$id);
        $this->db->update('valas',$data);
    }
}