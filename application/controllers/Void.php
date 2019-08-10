<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Void extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('Pdf');
        $this->load->model('Kode_model','kode');
        $this->load->model('Admin_model');
        $this->load->model('Void_model');
    }

    public function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Void_model->search_kdTrx($_GET['term']);
            if (count($result) > 0) {
            foreach ($result as $row)
                $arr_result[] = $row->kd_trx;
                echo json_encode($arr_result);
            }
        }
    }

    public function get_autocompleteJual(){
        if (isset($_GET['term'])) {
            $result = $this->Void_model->search_kdTrxJual($_GET['term']);
            if (count($result) > 0) {
            foreach ($result as $row)
                $arr_result[] = $row->kd_trx;
                echo json_encode($arr_result);
            }
        }
    }

    public function pembelian()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['valas'] = $this->db->get('valas')->result_array();        

        $data['pembelian'] = $this->Void_model->getVoidPembelian();
                
        $data['stock'] = $this->Admin_model->getStock();

        $data['title'] = 'Void Transaksi Pembelian';        
        
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('void/pembelian',$data);
        $this->load->view('templates/footer');
    }

    public function beli()
    {
        $data['title'] = 'Void Transaksi Pembelian';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['kode'] = $this->kode->get_kodeVoid();
        $data['valas'] = $this->db->get('valas')->result_array();
        
        $this->form_validation->set_rules('valas','Valas','required|trim|is_unique[temp_transaksi.id_valas]',[
            'is_unique' => 'This valas has been Added!']);
        $this->form_validation->set_rules('rate_valas','Rate Valas','required');
        $this->form_validation->set_rules('jumlah','Jumlah','required');
        $this->form_validation->set_rules('total','Total','required'); 
        $this->form_validation->set_rules('kd_trx','Kode Transaksi','required');

        if ($this->form_validation->run() == FALSE) {
            # code...
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('void/beli',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $this->Void_model->prosesVoidPembelian();
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('void/pembelian');
        }      
    }

    public function penjualan()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['valas'] = $this->db->get('valas')->result_array();        

        $data['penjualan'] = $this->Void_model->getVoidPenjualan();
                
        $data['stock'] = $this->Admin_model->getStock();

        $data['title'] = 'Void Transaksi Penjualan';        
        
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('void/penjualan',$data);
        $this->load->view('templates/footer');
    }

    public function jual()
    {
        $data['title'] = 'Void Transaksi Penjualan';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['kode'] = $this->kode->get_kodeVoid();
        $data['valas'] = $this->db->get('valas')->result_array();
        
        $this->form_validation->set_rules('valas','Valas','required|trim|is_unique[temp_transaksi.id_valas]',[
            'is_unique' => 'This valas has been Added!']);
        $this->form_validation->set_rules('rate_valas','Rate Valas','required');
        $this->form_validation->set_rules('jumlah','Jumlah','required');
        $this->form_validation->set_rules('total','Total','required'); 
        $this->form_validation->set_rules('kd_trx','Kode Transaksi','required');

        if ($this->form_validation->run() == FALSE) {
            # code...
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('void/jual',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $this->Void_model->prosesVoidPenjualan();
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('void/penjualan'); 
        }      
    }
}