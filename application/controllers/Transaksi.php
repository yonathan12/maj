<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function penjualan()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $this->form_validation->set_rules('valas','Valas','required');

        $data['valas'] = $this->db->get('valas')->result_array();

        $query = "SELECT transaksi.*, valas.*
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id
        WHERE transaksi.trx = 1 
        ORDER BY transaksi.Id DESC";
        $data['penjualan'] = $this->db->query($query)->result_array();

        if ($this->form_validation->run()== FALSE) {
            # code...
            $data['title'] = 'Transaksi Penjualan';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('transaksi/penjualan',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $rate_valas = $this->input->post('rate_valas');
            $valas = $this->input->post('valas');
            $jumlah = $this->input->post('jumlah');
            $hasil = $this->input->post('hasil');

            $data = [
                'id_valas' => $valas,
                'rate_valas' => $rate_valas,
                'jumlah' => $jumlah,
                'hasil' => $hasil,
                'trx' => 1,
                'date_created' => time()
            ];

            $this->db->insert('transaksi',$data);
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('transaksi/penjualan');            
        }        
    }

    public function pembelian()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $this->form_validation->set_rules('valas','Valas','required');

        $data['valas'] = $this->db->get('valas')->result_array();        

        $query = "SELECT transaksi.rate_valas,transaksi.hasil,transaksi.date_created,
        transaksi.rate_valas * transaksi.hasil AS JML, valas.*
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id
        WHERE transaksi.trx = 2";
        $data['pembelian'] = $this->db->query($query)->result_array();

        $data['stock'] = $this->db->get('valas')->result_array();
        
        
        if ($this->form_validation->run()== FALSE) {
            # code...
            $data['title'] = 'Transaksi Pembelian';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('transaksi/pembelian',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $rate_valas = $this->input->post('rate_valas');
            $valas = $this->input->post('valas');
            $jumlah = $this->input->post('jumlah');
            $hasil = $this->input->post('hasil');

            $data = [
                'id_valas' => $valas,
                'rate_valas' => $rate_valas,
                'jumlah' => $jumlah,
                'hasil' => $hasil,
                'trx' => 2,
                'date_created' => time()
            ];

            $queryStock ="SELECT stock FROM valas WHERE Id = $valas ";
            $dataStock['data'] = $this->db->query($queryStock)->row();            
            $stock = $dataStock['data'];
            $sisa = $stock->stock;

            if ($hasil <= $sisa) {
                # code...
            $sisaStock = $sisa - $hasil;

            $this->db->set('stock',$sisaStock);
            $this->db->where('Id',$valas);
            $this->db->update('valas');

            $this->db->insert('transaksi',$data);
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('transaksi/pembelian');  
            } else {
                # code...
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Stock Kurang !
          </div>');
            redirect('transaksi/pembelian');
            }
            

                      
        }        
    }
}