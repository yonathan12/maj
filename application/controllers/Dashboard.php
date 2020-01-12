<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Dashboard_model');
    }

    public function index()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['totalPembelian'] = $this->Dashboard_model->totalPembelian();
        $data['totalPenjualan'] = $this->Dashboard_model->totalPenjualan();
        $data['totalPembatalanPembelian'] = $this->Dashboard_model->totalPembatalanPembelian();
        $data['totalPembatalanPenjualan'] = $this->Dashboard_model->totalPembatalanPenjualan();
        $data['title'] = 'Dashboard';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('dashboard/index',$data);
        $this->load->view('templates/footer');
    }
}