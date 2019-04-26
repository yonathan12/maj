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

        $query = "SELECT transaksi.rate_valas,transaksi.hasil,transaksi.date_created,
        transaksi.rate_valas * transaksi.hasil AS JML, valas.*
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        WHERE transaksi.trx = 1 AND transaksi.status= 1";
        $data['penjualan'] = $this->db->query($query)->result_array();


        $queryDataStock =   "SELECT stock.*,SUM(stock.stock_akhir - stock.stock_awal) AS sa, valas.*
                            FROM stock JOIN valas
                            WHERE stock.id_valas = valas.Id_valas
                            GROUP BY stock.id_valas ORDER BY stock.time_created";
        $data['stock'] = $this->db->query($queryDataStock)->result_array();
        
        
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

                $queryStock = "SELECT * FROM stock WHERE id_valas = '$valas' ORDER BY stock.time_created DESC  ";
                $dataStock['data'] = $this->db->query($queryStock)->row();                
                $stock = $dataStock['data'];
                $stockAwal = $stock->stock_akhir;
                

            if ($hasil <= $stockAwal) {
                # code...
            $sisaStock = $stockAwal - $hasil;

            // insert ke database transaksi
            $data = [ 
                'id_valas' => $valas,
                'rate_valas' => $rate_valas,
                'jumlah' => $jumlah,
                'hasil' => $hasil,
                'trx' => 1, //trx out
                'status' => 1,
                'date_created' => date('Y-m-d')
            ];

            // insert ke database stock
            $stockValas = [
                'id_valas' => $valas,
                'stock_awal' => $stockAwal,
                'trx_out' => $hasil,
                'stock_akhir' => $sisaStock ,
                'date_created' => date('Y-m-d') ,
                'time_created' => date('H:i:s'),
                'status' => 1
            ];

            $this->db->insert('stock',$stockValas);
            $this->db->insert('transaksi',$data);
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('transaksi/penjualan');  

            
            } else {
                # code...
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Stock Kurang !
          </div>');
            redirect('transaksi/penjualan');
            }         
        }        
    }

    public function pembelian()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $this->form_validation->set_rules('valas','Valas','required');

        $data['valas'] = $this->db->get('valas')->result_array();        

        $query = "SELECT transaksi.rate_valas,transaksi.hasil,transaksi.date_created,
        transaksi.jumlah, valas.*
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id
        WHERE transaksi.trx = 2 AND transaksi.status= 1";
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
            $sisaStock = $sisa + $jumlah;

            $this->db->set('stock',$sisaStock);
            $this->db->where('Id',$valas);
            $this->db->update('valas');

            $this->db->insert('transaksi',$data);
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('transaksi/pembelian');                      
        }
    }
}