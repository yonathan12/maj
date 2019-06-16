<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller 
{
    public function __construct()
    {
     parent::__construct(); 
     is_logged_in();  
     $this->load->model('Customer_model');
     $this->load->model('Kode_model');
    }

    public function index()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        
        $data['title'] = 'Customer';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('customer/index',$data);
        $this->load->view('templates/footer');
    }

   public function get_data()
    {
        $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $list = $this->Customer_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
			$no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nama;
            $row[] = $field->no_ktp;
			$row[] = $field->telp;
            $row[] = '
            <a href="customer/detail/'.$field->Id.'" class="btn btn-success">Detail</a> <a href="customer/edit/'.$field->Id.'" class="btn btn-primary">Edit</a>
            <button type="button" name="delete" id="'.$field->Id.'" class="btn btn-danger btn-xs delete">Delete</button> ';  
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Customer_model->count_all(),
            "recordsFiltered" => $this->Customer_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    public function addCustomer()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['kode'] = $this->Kode_model->get_kodeCustomer();

        $this->form_validation->set_rules('nama','Nama','required|trim');
        $this->form_validation->set_rules('tempat_lhr','Tempat Lahir','required|trim');
        $this->form_validation->set_rules('tgl_lhr','Tanggal Lahir','required|trim');
        $this->form_validation->set_rules('ktp','No KTP','required|trim|numeric');
        $this->form_validation->set_rules('npwp','No NPWP','trim|numeric');
        $this->form_validation->set_rules('alamat','Alamat','required|trim');        
        $this->form_validation->set_rules('pekerjaan','Pekerjaan','required|trim');
        $this->form_validation->set_rules('kewarganegaraan','Kewarganegaraan','required|trim');
        $this->form_validation->set_rules('telp','Telp','required|trim|numeric');

        if ($this->form_validation->run()== FALSE) {
            # code...
            $data['title'] = 'Customer';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('customer/addcustomer',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $this->Customer_model->addCustomer();
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Customer Added!
            </div>');
            redirect('customer');
        }              
    }

    public function detail($Id)
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $query = "SELECT *, YEAR(curdate()) - YEAR(tgl_lahir) AS umur FROM `customer` WHERE Id = $Id";
        $data['detail'] = $this->db->query($query)->row_array();

        $data['title'] = 'Customer';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('customer/detail',$data);
        $this->load->view('templates/footer');
    }

    function delete()  
    {    $Id = $this->input->post('Id');         
        $this->Customer_model->delete_data($Id);
        $this->session->set_flashdata('message',
            '<div class="alert alert-success" role="alert">
            Customer Deleted!
            </div>');
        redirect('customer');             
    }

    function edit($Id)
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['customer'] = $this->db->get_where('customer',['Id' => $Id])->row_array();       

        $this->form_validation->set_rules('nama','Nama','required|trim');
        $this->form_validation->set_rules('tempat_lhr','Tempat Lahir','required|trim');
        $this->form_validation->set_rules('tgl_lhr','Tanggal Lahir','required|trim');
        $this->form_validation->set_rules('ktp','No KTP','required|trim|numeric');
        $this->form_validation->set_rules('npwp','No NPWP','trim|numeric');
        $this->form_validation->set_rules('alamat','Alamat','required|trim');        
        $this->form_validation->set_rules('pekerjaan','Pekerjaan','required|trim');
        $this->form_validation->set_rules('kewarganegaraan','Kewarganegaraan','required|trim');
        $this->form_validation->set_rules('telp','Telp','required|trim|numeric');

        if ($this->form_validation->run()== FALSE) {
            # code...
            $data['title'] = 'Customer';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('customer/editCustomer',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $this->Customer_model->editCustomer();
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Customer Edited!
            </div>');
            redirect('customer');
        }              
    }
}