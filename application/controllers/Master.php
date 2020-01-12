<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller 
{
    public function __construct(){
        parent::__construct();
        is_logged_in();
        $this->load->model('Kode_model','kode');
        $this->load->model('Valas_model');
        $this->load->model('Master_model');
    }

    public function valas(){
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $this->db->where('status',1);
        $data['valas'] = $this->db->get('valas')->result_array();
        $data['kode'] = $this->kode->get_kode();
        $this->form_validation->set_rules('valas','Valas','required|trim|is_unique[valas.valas]',[
            'is_unique' => 'This valas has been registered!']);
        $this->form_validation->set_rules('description','Description','required');
        if ($this->form_validation->run()== FALSE) {
            $data['title'] = 'Valas';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('master/valas',$data);
            $this->load->view('templates/footer');
        } else {
            $this->Valas_model->addValas();
            $this->session->set_flashdata('message','Menambahkan Valas');
            redirect('master/valas');   
        }             
    }

    public function editValas(){
        $this->Valas_model->editValas();
        $this->session->set_flashdata('message','Mengubah Valas');
        redirect('master/valas');   
    }

    public function stock(){
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = 'Stock';

        $data['kode'] = $this->kode->getKodeAddStock();
        $data['valas'] = $this->Master_model->getStock();

        $this->form_validation->set_rules('valas','Valas','required');
        $this->form_validation->set_rules('stock','Stock','required');
        $this->form_validation->set_rules('rate','Rate','required');
        $this->form_validation->set_rules('total','Total','required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('master/stock',$data);
            $this->load->view('templates/footer');
        } else {
            
            $this->Master_model->addStock();
            $this->session->set_flashdata('message','Menambahkan Stock');
            redirect('master/stock');
        }         
    }
}

    ?>