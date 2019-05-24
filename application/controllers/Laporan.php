<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('Pdf');
        $this->load->model('Laporan_model');
    }

    public function labarugi()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['valas'] = $this->db->get('valas')->result_array();

        $data['laba'] = $this->Laporan_model->getLaporanLaba();

        $this->form_validation->set_rules('valas','Valas','required');
        if ($this->form_validation->run() == FALSE ) {
            # code...
            $data['title'] = 'Laba Rugi';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('laporan/labarugi',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $this->Laporan_model->labaRugi();        
        }
    }

    public function laporanlaba()
    {
        $this->Laporan_model->simpanLaporan();
    }
    
    public function hapus($date_created)
    {
        $this->Laporan_model->hapusLaporan();
    }

    public function detail($id)
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['detail'] = $this->Laporan_model->detailLaporan($id);
        $data['title'] = 'Laba Rugi';

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('laporan/detail',$data);
        $this->load->view('templates/footer');
    }
}