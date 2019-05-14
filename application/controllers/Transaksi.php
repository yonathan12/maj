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
    }

    public function penjualan()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['kode'] = $this->kode->get_kodeTrx();
        $this->form_validation->set_rules('customer','Customer','required');
        $this->form_validation->set_rules('valas','Valas','required');
        $this->form_validation->set_rules('rate_valas','Rate Valas','required');
        $this->form_validation->set_rules('jumlah','Jumlah','required');
        $this->form_validation->set_rules('total','Total','required');       

        $data['valas'] = $this->db->get('valas')->result_array();        

        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,transaksi.total,transaksi.date_created, valas.valas, valas.Id_valas
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        WHERE transaksi.status = 1 AND transaksi.trx = 2 ORDER BY transaksi.date_created DESC, transaksi.Id DESC";
        $data['penjualan'] = $this->db->query($query)->result_array();


        $queryDataStock =   "SELECT stock.*,SUM(stock.stock_akhir - stock.stock_awal) AS sa, valas.*
                            FROM stock JOIN valas
                            WHERE stock.id_valas = valas.Id_valas AND stock.status = 1
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
            $customer = $this->input->post('customer');
            $rate_valas = $this->input->post('rate_valas');
            $valas = $this->input->post('valas');
            $jumlah = $this->input->post('jumlah');
            $total = $this->input->post('total');
            $kd_trx = $this->input->post('kode');

            $queryStock = "SELECT * FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created DESC, time_created DESC  ";
            $dataStock['data'] = $this->db->query($queryStock)->row();                
            $stock = $dataStock['data'];
            $stockAwal = $stock->stock_akhir;

            // get new rate
            $queryRate = "SELECT nr FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created ASC, time_created DESC";
            $dataRate['data'] = $this->db->query($queryStock)->row();                
            $rate = $dataRate['data'];
            $newRate = $rate->nr;

            

            if ($jumlah <= $stockAwal) {
                # code...
            $sisaStock = $stockAwal - $jumlah;
            $totalX = $newRate * $sisaStock;
            
            // insert ke database transaksi
            $data = [ 
                'customer' => $customer,
                'id_valas' => $valas,
                'trx' => 2, //trx out
                'kd_trx' => $kd_trx,
                'rate_valas' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $total, 
                'status' => 1,
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s')
            ];

            // insert ke database stock
            $stockValas = [
                'nr' => $newRate,
                'id_valas' => $valas,
                'stock_awal' => $stockAwal,
                'trx' => 2,
                'kd_trx' => $kd_trx,
                'rate' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $totalX,
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
        $data['kode'] = $this->kode->get_kodeTrx();

        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('customer','Customer','required');
        $this->form_validation->set_rules('valas','Valas','required');
        $this->form_validation->set_rules('rate_valas','Rate Valas','required');
        $this->form_validation->set_rules('jumlah','Jumlah','required');
        $this->form_validation->set_rules('total','Total','required');       
        

        $data['valas'] = $this->db->get('valas')->result_array();        

        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,transaksi.total,transaksi.date_created, valas.valas, valas.Id_valas
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        WHERE transaksi.status= 1 AND transaksi.trx = 1 ORDER BY transaksi.date_created DESC, transaksi.Id DESC";
        $data['pembelian'] = $this->db->query($query)->result_array();

        $queryDataStock =   "SELECT stock.*,SUM(stock.stock_akhir - stock.stock_awal) AS sa, valas.*
                            FROM stock JOIN valas
                            WHERE stock.id_valas = valas.Id_valas AND stock.status = 1
                            GROUP BY stock.id_valas";
        $data['stock'] = $this->db->query($queryDataStock)->result_array();

        $data['title'] = 'Transaksi Pembelian';
        
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('transaksi/pembelian',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $kd_trx = $this->input->post('kode');
            $customer = $this->input->post('customer');
            $valas = $this->input->post('valas');
            $rate_valas = $this->input->post('rate_valas');
            (int)$jumlah = $this->input->post('jumlah');
            $total = $this->input->post('total');
            $hasil = [ 
                'kd_trx' => $kd_trx,
                'customer' => $customer,
                'trx' => 1,
                'id_valas' => $valas,
                'rate_valas' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $total,
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
            ];

            $queryStock = "SELECT * FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created DESC, time_created DESC";
            $dataStock['data'] = $this->db->query($queryStock)->row();                
            $stock = $dataStock['data'];
            $stockAwal = $stock->stock_akhir;
            $sisaStock = $stockAwal + $jumlah;

            //Olah New Rate
            $hariini = date("Y-m-d");

            $SSY['data'] = $this->db->query("SELECT stock_akhir FROM stock WHERE id_valas = '$valas' AND status = 1 AND date_created != '$hariini' ORDER BY date_created DESC, time_created DESC")->row();
            $dataSSY = $SSY['data'];
            $stockSSY = $dataSSY->stock_akhir;

            $TY['data'] = $this->db->query("SELECT * FROM stock WHERE id_valas = '$valas'  AND status = 1 AND date_created != '$hariini'  ORDER BY date_created DESC, time_created DESC LIMIT 1")->row();
            $dataTY = $TY['data'];
            $totalY = $dataTY->total;

            $totalBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$hariini' AND trx = 1 AND id_valas = '$valas' AND status = 1 ")->row();
            $dataTotalBeli = $totalBeli['data'];
            $totalPembelian = $dataTotalBeli->TBeli;
            $jumlahPembelian = $dataTotalBeli->JBeli;

            $x = $totalY + $totalPembelian + $total;
            $y = $stockSSY + $jumlahPembelian + $jumlah;

            $Rate = $x / $y;         
            $newRate = round($Rate);

            $totalStock = $newRate * $sisaStock;

            // insert ke database stock
            $stockValas = [
                'nr' => $newRate,
                'id_valas' => $valas,
                'stock_awal' => $stockAwal,
                'kd_trx' => $kd_trx,
                'trx' => 1,
                'rate' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $totalStock,
                'stock_akhir' => $sisaStock ,
                'date_created' => date('Y-m-d') ,
                'time_created' => date('H:i:s'),
                'status' => 1
            ];
            

            $this->db->insert('transaksi',$hasil);
            $this->db->insert('stock',$stockValas);

            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('transaksi/pembelian'); 
        }        
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

        $query = "SELECT transaksi.*, valas.*
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        WHERE transaksi.kd_trx = '$id'";
        
        $trx['data'] = $this->db->query($query)->row_array();

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