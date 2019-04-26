<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Kode_model','kode');
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

    public function valas()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        
        $this->db->where('status',1);
        $data['valas'] = $this->db->get('valas')->result_array();

        $data['kode'] = $this->kode->get_kode();

        $this->form_validation->set_rules('valas','Valas','required|trim|is_unique[valas.valas]',[
            'is_unique' => 'This valas has been registered!']);

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
            $valas = $this->input->post('valas');
            $kode = $this->input->post('kode');
            $data = [
                'Id_valas' => $kode,
                'valas' => $valas,
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
            ];

            $dataStock = [
                'id_valas' => $kode,
                'stock_awal' => 0,
                'stock_akhir' => 0,
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
            ];

            $this->db->insert('valas',$data);
            $this->db->insert('stock',$dataStock);

            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
                    New Valas Added!
                  </div>');
                    redirect('admin/valas');   
        }             
    }

    public function user()
    {
            $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
            $data['title'] = 'All User';
            $data['AllData'] = $this->db->get('user')->result_array();
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('admin/user',$data);
            $this->load->view('templates/footer');
    }

    public function stock()
    {
            $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
            $data['title'] = 'Stock';

            // $data['valas'] = $this->db->get('valas')->result_array();

            $queryDataStock = "SELECT stock.*,SUM(stock.stock_akhir - stock.stock_awal) AS sa, valas.*
            FROM stock JOIN valas
            WHERE stock.id_valas = valas.Id_valas
            GROUP BY stock.id_valas ORDER BY stock.time_created";
            $data['valas'] = $this->db->query($queryDataStock)->result_array();


            $this->form_validation->set_rules('valas','Valas','required');
            $this->form_validation->set_rules('stock','Stock','required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('templates/header',$data);
                $this->load->view('templates/sidebar',$data);
                $this->load->view('templates/topbar',$data);
                $this->load->view('admin/stock',$data);
                $this->load->view('templates/footer');
            } else {
                # code...
                $valas = $this->input->post('valas');
                $addStock = $this->input->post('stock');

                $queryStock = "SELECT * FROM stock WHERE id_valas = '$valas' ORDER BY stock.time_created DESC  ";
                $dataStock['data'] = $this->db->query($queryStock)->row();                
                $stock = $dataStock['data'];
                $stockAwal = $stock->stock_akhir;
                $stockAkhir = $stockAwal + $addStock;
                
                $data = [
                    'id_valas' => $valas,
                    'stock_awal' => $stockAwal,
                    'trx_in' => $addStock,
                    'stock_akhir' => $stockAkhir,
                    'date_created' => date('Y-m-d'),
                    'time_created' => date('H:i:s'),
                    'status' => 1
                ];
                $this->db->insert('stock',$data);

                $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
                Stock Added!
                </div>');
                redirect('admin/stock');
            }
            


            
    }
}
?>