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

    public function penjualan()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['penjualan'] = $this->Laporan_model->lapPenjualan();

        $data['title'] = 'Laporan Penjualan';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('laporan/lap_penjualan',$data);
        $this->load->view('templates/footer');
    }

    public function detailPenjualan()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['penjualan'] = $this->Laporan_model->d_LapPenjualan();

        $data['title'] = 'Laporan Penjualan';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('laporan/d_lapPenjualan',$data);
        $this->load->view('templates/footer');
    }

    public function pembelian()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['pembelian'] = $this->Laporan_model->lapPembelian();

        $data['title'] = 'Laporan Pembelian';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('laporan/lap_pembelian',$data);
        $this->load->view('templates/footer');
    }

    public function detailPembelian()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['pembelian'] = $this->Laporan_model->d_LapPembelian();

        $data['title'] = 'Laporan Pembelian';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('laporan/d_lapPembelian',$data);
        $this->load->view('templates/footer');
    }

    public function laporanlaba()
    {
        $this->Laporan_model->simpanLaporan();
        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
        redirect('laporan/labarugi'); 
    }
    
    public function hapus($date_created)
    {
        $this->Laporan_model->hapusLaporan($date_created);
        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Dihapus!
          </div>');
        redirect('laporan/labarugi'); 
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