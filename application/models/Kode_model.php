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
    public function get_kodeTrx()
    {
        $q = $this->db->query("SELECT MAX(RIGHT(kd_trx,4)) AS kd_max FROM transaksi WHERE DATE(date_created)=CURDATE()");
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }else{
            $kd = "0001";
        }
        date_default_timezone_set('Asia/Jakarta');
        return date('Y-m-d')."-".$kd;

        $kodeMax = str_pad($kode,3,"0",STR_PAD_LEFT);
        $kodeJadi = "TRX".$kodeMax;
        return $kodeJadi;        
    }
    
}

?>