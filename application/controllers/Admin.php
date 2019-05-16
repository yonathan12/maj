<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Kode_model','kode');
        $this->load->model('Valas_model');
        $this->load->model('Admin_model');
    }
    public function index()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        
        $data['title'] = 'Dashboard';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('admin/index',$data);
        $this->load->view('templates/footer');
    }

    public function role()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        
        $data['title'] = 'Role';

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('admin/role',$data);
        $this->load->view('templates/footer');
    }

    public function roleAccess($role_id)
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        
        $data['title'] = 'Role';

        $data['role'] = $this->db->get_where('user_role',['Id' => $role_id])->row_array();

        $this->db->where('Id !=',1);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('admin/roleaccess',$data);
        $this->load->view('templates/footer');
    }

    public function changeAccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu',$data);

        if ($result->num_rows() < 1) {
            # code...
            $this->db->insert('user_access_menu',$data);
        }else{
            $this->db->delete('user_access_menu',$data);
        }
        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Access Changed!
          </div>');
    }

    public function user()
    {
            $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
            $data['title'] = 'All User';
            $queryUser = "SELECT * FROM user ORDER BY is_active DESC";

            $data['AllData'] = $this->db->query($queryUser)->result_array();

            $this->form_validation->set_rules('name','Name','required|trim');
            $this->form_validation->set_rules('email','Email','required|trim|valid_email|is_unique[user.email]',[
                'is_unique' => 'This email has been registered!'
            ]);
            $this->form_validation->set_rules('password','Password','required|trim|min_length[3]',
            [
            'min_length' => 'Password to short!'
            ]);
            $this->form_validation->set_rules('role_id','Level','required');

            if ($this->form_validation->run()== FALSE) {
                # code...
                $this->load->view('templates/header',$data);
                $this->load->view('templates/sidebar',$data);
                $this->load->view('templates/topbar',$data);
                $this->load->view('admin/user',$data);
                $this->load->view('templates/footer');
            }else{
                $data = [
                    'name' => htmlspecialchars($this->input->post('name',true)),
                    'email' => htmlspecialchars($this->input->post('email',true)),
                    'image' => 'default.jpg',
                    'password' => password_hash($this->input->post('password'),PASSWORD_DEFAULT),
                    'role_id' => $this->input->post('role_id'),
                    'is_active' => 1,
                    'date_created' => time()
                ];
    
                $this->db->insert('user',$data);
                $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
                Your account has been created!
              </div>');
                redirect('admin/user');
            }               
    }

    public function valas()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        
        $this->db->where('status',1);
        $data['valas'] = $this->db->get('valas')->result_array();

        $data['kode'] = $this->kode->get_kode();

        $this->form_validation->set_rules('valas','Valas','required|trim|is_unique[valas.valas]',[
            'is_unique' => 'This valas has been registered!']);
        $this->form_validation->set_rules('description','Description','required');

        if ($this->form_validation->run()== FALSE) {
            # code...
            $data['title'] = 'Valas';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('admin/valas',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $this->Valas_model->addValas();

            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
                    New Valas Added!
                  </div>');
                    redirect('admin/valas');   
        }             
    }

    public function deleteValas($id)
    {
        $this->Valas_model->hapusValas($id);
        $this->session->set_flashdata('message','DiHapus');
        redirect('admin/valas');
    }    

    public function stock()
    {
            $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
            $data['title'] = 'Stock';

            // $data['valas'] = $this->db->get('valas')->result_array();
            $data['valas'] = $this->Admin_model->getStock();

            $this->form_validation->set_rules('valas','Valas','required');
            $this->form_validation->set_rules('stock','Stock','required');
            $this->form_validation->set_rules('rate','Rate','required');
            $this->form_validation->set_rules('total','Total','required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('templates/header',$data);
                $this->load->view('templates/sidebar',$data);
                $this->load->view('templates/topbar',$data);
                $this->load->view('admin/stock',$data);
                $this->load->view('templates/footer');
            } else {
                # code...
                
                $this->Admin_model->addStock();

                $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
                Stock Added!
                </div>');
                redirect('admin/stock');
            }         
    }


    public function hapusUser($id)
    {
        $this->Admin_model->hapusUser($id);

        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Account has been delete!
          </div>');
            redirect('admin/user');
    }
}
?>