<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Menu_model');
    }

    public function index()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu','Menu','required');

        if ($this->form_validation->run()== FALSE) {
            $data['title'] = 'Menu Management';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('menu/index',$data);
            $this->load->view('templates/footer');
        } else {
            $menu = $this->input->post('menu');
            $user_id = $this->input->post('user_id');
            $tgl = date('Y-m-d');
            $data = array(
                'menu' => $menu,
                'user_id_created' => $user_id,
                'date_created' => $tgl
            );
            $this->db->insert('user_menu',$data);
            $this->session->set_flashdata('message','Menu Berhasil Ditambahkan');
            redirect('menu');   
        }             
    }

    public function deleteMenu($Id)
    {
        $this->db->where('Id',$Id);
        $this->db->delete('user_menu');
        $this->session->set_flashdata('message','Menghapus Menu');
        redirect('menu');   
    }

    public function editMenu(){
        $this->Menu_model->editMenu();
        $this->session->set_flashdata('message','Mengubah Menu');
        redirect('menu');
    }

    public function submenu()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        
        $this->load->model('Menu_model');
        $data['menu'] = $this->db->get('user_menu')->result_array();
        $data['subMenu'] = $this->Menu_model->getSubMenu();

        $this->form_validation->set_rules('title','Title','required');
        $this->form_validation->set_rules('menu_id','Menu','required');
        $this->form_validation->set_rules('url','URL','required');

        if ($this->form_validation->run()== FALSE) {
            $data['title'] = 'SubMenu Management';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('menu/submenu',$data);
            $this->load->view('templates/footer');
        } else {
            $this->Menu_model->addSubmenu();
            $this->session->set_flashdata('message','Menambahkan Sub Menu');
            redirect('menu/submenu');  
        }       
            
    }

    public function editSubmenu()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        
        $this->load->model('Menu_model');
        $data['menu'] = $this->db->get('user_menu')->result_array();
        $data['subMenu'] = $this->Menu_model->getSubMenu();

        $this->form_validation->set_rules('title','Title','required');
        $this->form_validation->set_rules('menu_id','Menu','required');
        $this->form_validation->set_rules('url','URL','required');
        
        if ($this->form_validation->run()== FALSE) {
            $data['title'] = 'SubMenu Management';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('menu/submenu',$data);
            $this->load->view('templates/footer');
        } else {
            $this->Menu_model->editSub();
            $this->session->set_flashdata('message','Mengubah Sub Menu');
            redirect('menu/submenu');  
        }       
    }

    public function deleteSubmenu($Id)
    {
        $this->db->where('Id',$Id);
        $this->db->delete('user_sub_menu');
        $this->session->set_flashdata('message','Menghapus Sub Menu');
        redirect('menu/submenu');  
    }
}