<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Kode_model extends CI_Model{
    function __construct()
    {
        parent:: __construct();
    }

    public function get_kode()
    {
        $this->db->select('RIGHT(valas.kd_valas,3) as kode',FALSE);
        $this->db->order_by('kd_valas','DESC');
        $this->db->limit(1);

        $query = $this->db->get('valas');

        if ($query->num_rows()<> 0) {
            $data = $query->row();
            $kode = intval($data->kode)+1;
        } else {
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

        $kodeMax = str_pad($kd,3,"0",STR_PAD_LEFT);
        $kodeJadi = "TRX".$kodeMax;
        return $kodeJadi;        
    }

    public function get_kodeVoid()
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
        return "V-".date('Y-m-d')."-".$kd;      
    }

    public function get_kodeCustomer()
    {
        $this->db->select('RIGHT(customer.kd_cst,6) as kode',FALSE);
        $this->db->order_by('kd_cst','DESC');
        $this->db->limit(1);

        $query = $this->db->get('customer');

        if ($query->num_rows()<> 0) {
            $data = $query->row();
            $kode = intval($data->kode)+1;
        } else {
            $kode = 1;
        }

        $kodeMax = str_pad($kode,6,"0",STR_PAD_LEFT);
        $kodeJadi = "CST".$kodeMax;
        return $kodeJadi;        
    }

    public function getKodeAddStock()
    {
        $q = $this->db->query("SELECT MAX(RIGHT(kd_trx,4)) AS kd_max FROM stock WHERE DATE(date_created)=CURDATE()");
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
        return "AS-".date('Y-m-d')."-".$kd;      
    }
    
}

?>