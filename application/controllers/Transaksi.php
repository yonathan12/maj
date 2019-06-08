<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('Pdf');
        $this->load->model('Kode_model','kode');
        $this->load->model('Admin_model');
        $this->load->model('Transaksi_model');
    }

    public function penjualan()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['kode'] = $this->kode->get_kodeTrx();
        $data['title'] = 'Transaksi Penjualan';
        
        $data['valas'] = $this->db->get('valas')->result_array();        

        $data['penjualan'] = $this->Transaksi_model->getTransaksiPenjualan();

        $data['stock'] = $this->Admin_model->getStock();
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('transaksi/penjualan',$data);
        $this->load->view('templates/footer');
    }

    public function pembelian()
    {
        $data['kode'] = $this->kode->get_kodeTrx();

        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['valas'] = $this->db->get('valas')->result_array();        

        $data['pembelian'] = $this->Transaksi_model->getTransaksiPembelian();
        
        $data['stock'] = $this->Admin_model->getStock();

        $data['title'] = 'Transaksi Pembelian';        
        
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('transaksi/pembelian',$data);
        $this->load->view('templates/footer');
    }

    public function beli()
    {
        $data['title'] = 'Transaksi Pembelian';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['kode'] = $this->kode->get_kodeTrx();
        $data['valas'] = $this->db->get('valas')->result_array();

        $kd_trx = $this->session->userdata('email');
        $tempTransaksi = "SELECT temp_transaksi.*, valas.* 
        FROM temp_transaksi JOIN valas
        ON temp_transaksi.id_valas = valas.Id_valas
        WHERE kd_trx = '$kd_trx' AND temp_transaksi.trx = 1";
        
        $data['temp'] = $this->db->query($tempTransaksi)->result_array();
        
        $this->form_validation->set_rules('valas','Valas','required|trim|is_unique[temp_transaksi.id_valas]',[
            'is_unique' => 'This valas has been registered!']);
        $this->form_validation->set_rules('rate_valas','Rate Valas','required');
        $this->form_validation->set_rules('jumlah','Jumlah','required');
        $this->form_validation->set_rules('total','Total','required'); 

        if ($this->form_validation->run() == FALSE) {
            # code...
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('transaksi/beli',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $this->Transaksi_model->tempBeli();
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('transaksi/beli'); 
        }      
    }

    public function jual()
    {
        $data['title'] = 'Transaksi Penjualan';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['kode'] = $this->kode->get_kodeTrx();
        $data['valas'] = $this->db->get('valas')->result_array();

        $kd_trx = $this->session->userdata('email');
        $tempTransaksi = "SELECT temp_transaksi.*, valas.* 
        FROM temp_transaksi JOIN valas
        ON temp_transaksi.id_valas = valas.Id_valas
        WHERE kd_trx = '$kd_trx' AND temp_transaksi.trx = 2";
        
        $data['temp'] = $this->db->query($tempTransaksi)->result_array();

        
        $this->form_validation->set_rules('valas','Valas','required|trim|is_unique[temp_transaksi.id_valas]',[
            'is_unique' => 'This valas has been registered!']);
        $this->form_validation->set_rules('rate_valas','Rate Valas','required');
        $this->form_validation->set_rules('jumlah','Jumlah','required');
        $this->form_validation->set_rules('total','Total','required');            

        if ($this->form_validation->run() == FALSE) {
            # code...
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('transaksi/jual',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $this->Transaksi_model->tempJual();
        }      
    }

    public function prosesPenjualan()
    {
        $data['title'] = 'Transaksi Penjualan';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['kode'] = $this->kode->get_kodeTrx();
        $data['valas'] = $this->db->get('valas')->result_array();

        $kd_trx = $this->session->userdata('email');
        $tempTransaksi = "SELECT temp_transaksi.*, valas.* 
        FROM temp_transaksi JOIN valas
        ON temp_transaksi.id_valas = valas.Id_valas
        WHERE kd_trx = '$kd_trx' AND temp_transaksi.trx = 2 ";
        
        $data['temp'] = $this->db->query($tempTransaksi)->result_array();

        $this->form_validation->set_rules('customer','Customer','required');

        if ($this->form_validation->run() == FALSE) {
            # code...
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('transaksi/jual',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
           $this->Transaksi_model->prosesTransaksiPenjualan();
           
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('transaksi/penjualan');             
        }
        
    }

    public function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Transaksi_model->search_blog($_GET['term']);
            if (count($result) > 0) {
            foreach ($result as $row)
                $arr_result[] = $row->nama.' - '.$row->kd_cst;
                echo json_encode($arr_result);
            }
        }
    }

    public function prosesPembelian()
    {
        $data['title'] = 'Transaksi Pembelian';
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['kode'] = $this->kode->get_kodeTrx();
        $data['valas'] = $this->db->get('valas')->result_array();

        $kd_trx = $this->session->userdata('email');
        $tempTransaksi = "SELECT temp_transaksi.*, valas.* 
        FROM temp_transaksi JOIN valas
        ON temp_transaksi.id_valas = valas.Id_valas
        WHERE kd_trx = '$kd_trx'";
        
        $data['temp'] = $this->db->query($tempTransaksi)->result_array();

        $this->form_validation->set_rules('customer','Customer','required');

        if ($this->form_validation->run() == FALSE) {
            # code...
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('transaksi/beli',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $this->Transaksi_model->prosesTransaksiPembelian();
            
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('transaksi/pembelian');             
        }
        
    }

    public function tempHapusBeli($id)
    {
        $kd_trx = $this->session->userdata('email');

        $this->db->where('kd_trx',$kd_trx);
        $this->db->where('id_valas',$id);
        $this->db->delete('temp_transaksi');

        $this->db->where('kd_trx',$kd_trx);
        $this->db->where('id_valas',$id);
        $this->db->delete('temp_stock');

        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Dihapus!
          </div>');
            redirect('transaksi/beli'); 
    }

    public function tempHapusJual($id)
    {
        $kd_trx = $this->session->userdata('email');

        $this->db->where('kd_trx',$kd_trx);
        $this->db->where('id_valas',$id);
        $this->db->delete('temp_transaksi');

        $this->db->where('kd_trx',$kd_trx);
        $this->db->where('id_valas',$id);
        $this->db->delete('temp_stock');

        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Dihapus!
          </div>');
            redirect('transaksi/jual'); 
    }

    public function hapusBeli($id)
    {
        $data = array
        (
            'status' => 0
        );

        $this->db->where('kd_trx',$id);
        $this->db->update('transaksi',$data);

        $this->db->where('kd_trx',$id);
        $this->db->update('stock',$data);

        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Dihapus!
          </div>');
            redirect('transaksi/pembelian'); 
    }

    public function hapusJual($id)
    {
        $data = array
        (
            'status' => 0
        );

        $this->db->where('kd_trx',$id);
        $this->db->update('transaksi',$data);

        $this->db->where('kd_trx',$id);
        $this->db->update('stock',$data);

        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Dihapus!
          </div>');
            redirect('transaksi/penjualan'); 
    }

    public function printInvoice($id)
    {
     
        $data['title'] = 'Invoice';

        $query = "SELECT transaksi.*, valas.*,customer.*
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        JOIN customer
        ON transaksi.customer = customer.kd_cst
        WHERE transaksi.kd_trx = '$id'";

        $queryINV = "SELECT transaksi.*,SUM(transaksi.total) AS TTL, customer.nama
        FROM transaksi JOIN customer
        ON transaksi.customer = customer.kd_cst
        WHERE kd_trx = '$id' ";
        
        $trx['data'] = $this->db->query($query)->result_array();
        $trx['inv'] = $this->db->query($queryINV)->row_array();

        $this->load->view('templates/header',$data);
        $this->load->view('transaksi/print',$trx);

    }

    
    
    function cetak($id){
        $pdf = new FPDF('l','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',16);
        // mencetak string 
        $pdf->Cell(190,7,'PT Muchad Artha Jaya',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,7,'Blok S',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(40,6,'Customer',1,0);
        $pdf->Cell(20,6,'Valas',1,0);
        $pdf->Cell(35,6,'Jumlah',1,0);
        $pdf->Cell(37,6,'Hasil',1,0);
        $pdf->Cell(45,6,'Tanggal Dibuat',1,1);
        $pdf->SetFont('Arial','',10);
        
        $query = "SELECT transaksi.customer,transaksi.rate_valas,transaksi.jumlah,transaksi.total,transaksi.date_created, valas.valas, valas.Id_valas
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        WHERE transaksi.kd_trx = '$id'";
        
        $trx['data'] = $this->db->query($query)->row();
        $print = $trx['data'];
        
        $pdf->Cell(40,6,$print->customer,1,0);
        $pdf->Cell(20,6,$print->valas,1,0);
        $pdf->Cell(35,6,$print->jumlah,1,0);
        $pdf->Cell(37,6,$print->total,1,0);
        $pdf->Cell(45,6,$print->date_created,1,1); 

        $pdf->Output();
    }
}
