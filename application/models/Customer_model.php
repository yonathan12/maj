<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model 
{
    var $table = 'customer';
    var $column_order = array(null, 'nama','alamat','telp','no_ktp');
    var $column_search = array('nama','alamat','telp','no_ktp','email');
    var $order = array('nama' => 'ASC');

    public function __construct()
    {
        parent::__construct();
    }

    private function getQuery()
    {
        $this->db->from($this->table);

     $i = 0;
  
     foreach ($this->column_search as $item) 
     {
         if($_POST['search']['value']) 
         {
              
             if($i===0) 
             {
                 $this->db->group_start(); 
                 $this->db->like($item, $_POST['search']['value']);
             }
             else
             {
                 $this->db->or_like($item, $_POST['search']['value']);
             }

             if(count($this->column_search) - 1 == $i) 
                 $this->db->group_end(); 
         }
         $i++;
     }
      
     if(isset($_POST['order'])) 
     {
         $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
     } 
     else if(isset($this->order))
     {
         $order = $this->order;
         $this->db->order_by(key($order), $order[key($order)]);
     }
    }

    function get_datatables()
    {
        $this->getQuery();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->getQuery();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function addCustomer()
    {
        $kode = $this->input->post('kode');
        $nama = $this->input->post('nama');
        $tempat_lahir = $this->input->post('tempat_lhr');
        $tgl_lhr = $this->input->post('tgl_lhr');
        $ktp = $this->input->post('ktp');
        $alamat = $this->input->post('alamat');
        $telp = $this->input->post('telp');
        $npwp = $this->input->post('npwp');
        $pekerjaan = $this->input->post('pekerjaan');
        $warganegara = $this->input->post('kewarganegaraan');
        $tgl = date('Y-m-d');

        $data = [
            'kd_cst' => $kode,
            'nama' => $nama,
            'tempat_lahir' => $tempat_lahir,
            'tgl_lahir' => $tgl_lhr,
            'alamat' => $alamat,
            'no_ktp' => $ktp,
            'no_npwp' => $npwp,
            'telp' => $telp,
            'pekerjaan' => $pekerjaan,
            'kewarganegaraan' => $warganegara,
            'status' => 1,
            'date_created' => $tgl
        ];

        $this->db->insert('customer',$data);
    }

    function delete_data($Id)  
      {  
           $this->db->where("Id", $Id);  
           $this->db->delete("customer");
      }

    function editCustomer($Id)
    {
        $Id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $tempat_lahir = $this->input->post('tempat_lhr');
        $tgl_lhr = $this->input->post('tgl_lhr');
        $ktp = $this->input->post('ktp');
        $alamat = $this->input->post('alamat');
        $telp = $this->input->post('telp');
        $npwp = $this->input->post('npwp');
        $pekerjaan = $this->input->post('pekerjaan');
        $warganegara = $this->input->post('kewarganegaraan');
        $tgl = date('Y-m-d');

        $data = [
            'nama' => $nama,
            'tempat_lahir' => $tempat_lahir,
            'tgl_lahir' => $tgl_lhr,
            'alamat' => $alamat,
            'no_ktp' => $ktp,
            'no_npwp' => $npwp,
            'telp' => $telp,
            'pekerjaan' => $pekerjaan,
            'kewarganegaraan' => $warganegara,
            'status' => 1,
            'date_changed' => $tgl
        ];

        $this->db->where('Id',$Id);
        $this->db->update('customer',$data);        
    }

    function uploadCustomer($data)
    {
        $this->db->insert_batch('customer', $data);
    }

    public function exportData()
    {
        $this->db->order_by("kd_cst","ASC");
        return $this->db->get("customer")->result();
    }
}