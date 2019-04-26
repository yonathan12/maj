<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Kode_model extends CI_Model{
    function __construct()
    {
        parent:: __construct();
    }

    public function get_kode()
    {
        $this->db->select('RIGHT(valas.Id_valas,3) as kode',FALSE);
        $this->db->order_by('Id_valas','DESC');
        $this->db->limit(1);

        $query = $this->db->get('valas');

        if ($query->num_rows()<> 0) {
            # code...
            $data = $query->row();
            $kode = intval($data->kode)+1;
        } else {
            # code...
            $kode = 1;
        }

        $kodeMax = str_pad($kode,3,"0",STR_PAD_LEFT);
        $kodeJadi = "VLS".$kodeMax;
        return $kodeJadi;
        
    }
    
}

?>