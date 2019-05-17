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
        $this->form_validation->set_rules('customer','Customer','required');
        $this->form_validation->set_rules('valas','Valas','required');
        $this->form_validation->set_rules('rate_valas','Rate Valas','required');
        $this->form_validation->set_rules('jumlah','Jumlah','required');
        $this->form_validation->set_rules('total','Total','required');       

        $data['valas'] = $this->db->get('valas')->result_array();        

        $data['penjualan'] = $this->Transaksi_model->getTransaksiPenjualan();

        $data['stock'] = $this->Admin_model->getStock();
        
        
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
            $this->Transaksi_model->prosesTransaksiPenjualan();
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

        $data['pembelian'] = $this->Transaksi_model->getTransaksiPembelian();

        
        $data['stock'] = $this->Admin_model->getStock();

        $data['title'] = 'Transaksi Pembelian';
        
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('transaksi/pembelian',$data);
            $this->load->view('templates/footer');
        } else {
            # code...
            $this->Transaksi_model->prosesTransaksiPembelian();
        }        
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
            $kd_trx = $this->input->post('kode');
            $valas = $this->input->post('valas');
            $rate_valas = $this->input->post('rate_valas');
            (int)$jumlah = $this->input->post('jumlah');
            $total = $this->input->post('total');
            $hasil = [ 
                'kd_trx' => $kd_trx,
                'id_valas' => $valas,
                'rate_valas' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $total,
                'trx' => 1
            ];
            $hariini = date("Y-m-d");           

            $queryStock = "SELECT * FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created DESC, time_created DESC";
            $dataStock['data'] = $this->db->query($queryStock)->row();                
            $stock = $dataStock['data'];
            $stockAwal = $stock->stock_akhir;
            $sisaStock = $stockAwal + $jumlah;

            $totalBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$hariini' AND trx = 1 AND id_valas = '$valas' AND status = 1 ")->row();
            $dataTotalBeli = $totalBeli['data'];
            $totalPembelian = $dataTotalBeli->TBeli;
            $jumlahPembelian = $dataTotalBeli->JBeli;

            //Olah New Rate
            
            
            $SSY['data'] = $this->db->query("SELECT stock_akhir FROM stock WHERE id_valas = '$valas' AND status = 1 AND date_created != '$hariini' ORDER BY date_created DESC, time_created DESC")->row_array();
            $dataSSY = $SSY['data'];
            $stockSSY = $dataSSY['stock_akhir'];            

            $TY['data'] = $this->db->query("SELECT * FROM stock WHERE id_valas = '$valas'  AND status = 1 AND date_created != '$hariini'  ORDER BY date_created DESC, time_created DESC LIMIT 1")->row_array();
            $dataTY = $TY['data'];
            $totalY = $dataTY['total'];

            if ($totalY == '') {
                # code...
                $SSY['data'] = $this->db->query("SELECT stock_akhir FROM stock WHERE id_valas = '$valas' AND status = 1 AND trx = 0 ORDER BY time_created DESC")->row_array();
                $dataSSY = $SSY['data'];
                $stockSSYR = $dataSSY['stock_akhir'];            

                $TY['data'] = $this->db->query("SELECT * FROM stock WHERE id_valas = '$valas'  AND status = 1 AND trx = 0 ORDER BY time_created DESC")->row_array();
                $dataTY = $TY['data'];
                $totalYR = $dataTY['total'];

                $resultTotal = $totalYR;
                $resultStock = $stockSSYR;
            } else {
                # code...
                $resultTotal = $totalY;
                $resultStock = $stockSSY;
            }           

            $x = $resultTotal + $totalPembelian + $total;
            $y = $resultStock + $jumlahPembelian + $jumlah;

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
                'stock_akhir' => $sisaStock 
            ];
            

            $this->db->insert('temp_stock',$stockValas);

            $this->db->insert('temp_transaksi',$hasil);
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
            $kd_trx = $this->input->post('kode');
            $valas = $this->input->post('valas');
            $rate_valas = $this->input->post('rate_valas');
            (int)$jumlah = $this->input->post('jumlah');
            $total = $this->input->post('total');

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
            
            // Insert ke database temp_transaksi
            $hasil = [ 
                'kd_trx' => $kd_trx,
                'id_valas' => $valas,
                'rate_valas' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $total,
                'trx' => 2
            ];

            // insert ke database stock
            $stockValas = [
                'nr' => $newRate,
                'id_valas' => $valas,
                'stock_awal' => $stockAwal,
                'kd_trx' => $kd_trx,
                'trx' => 2,
                'rate' => $rate_valas,
                'jumlah' => $jumlah,
                'total' => $totalX,
                'stock_akhir' => $sisaStock 
            ];

                $this->db->insert('temp_stock',$stockValas);

                $this->db->insert('temp_transaksi',$hasil);
                $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
                Data Tersimpan!
            </div>');
                redirect('transaksi/jual'); 

            
            } else {
                # code...
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Stock Kurang !
          </div>');
            redirect('transaksi/jual');
            }                
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
            $kode = $this->input->post('kode');
            $customer = $this->input->post('customer');
            $hariini = date('Y-m-d');
            $waktu = date('H:i:s');

            
            $queryTemp = $this->db->query("SELECT * FROM temp_transaksi WHERE kd_trx = '$kd_trx' AND trx = 2")->result_array();

            foreach ($queryTemp as $row) {
                # code...

               $result = [
                'kd_trx' => $kode,
                'customer' => $customer,
                'trx' => 2,
                'id_valas' => $row['id_valas'],
                'rate_valas' => $row['rate_valas'],
                'jumlah' => $row['jumlah'],
                'total' => $row['total'],
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
               ];
               $this->db->insert('transaksi',$result);

               $this->db->where('kd_trx',$kd_trx);
               $this->db->delete('temp_transaksi');

            }

            $queryTempStock = $this->db->query("SELECT * FROM temp_stock WHERE kd_trx = '$kd_trx' AND trx = 2")->result_array();

               foreach ($queryTempStock as $row) {
                # code...

               $tempStock = [
                'id_valas' => $row['id_valas'],
                'nr' => $row['nr'],
                'stock_awal' => $row['stock_awal'],
                'kd_trx' => $kode,
                'trx' => 2,                
                'rate' => $row['rate'],
                'jumlah' => $row['jumlah'],
                'total' => $row['total'],
                'stock_akhir' => $row['stock_akhir'],
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
               ];
               $this->db->insert('stock',$tempStock);
               $this->db->where('kd_trx',$kd_trx);
               $this->db->delete('temp_stock');
            }
            
           
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
            redirect('transaksi/penjualan'); 

            
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
            $kode = $this->input->post('kode');
            $customer = $this->input->post('customer');
            $hariini = date('Y-m-d');
            $waktu = date('H:i:s');

            
            $queryTemp = $this->db->query("SELECT * FROM temp_transaksi WHERE kd_trx = '$kd_trx' AND trx = 1")->result_array();

            foreach ($queryTemp as $row) {
                # code...

               $result = [
                'kd_trx' => $kode,
                'customer' => $customer,
                'trx' => 1,
                'id_valas' => $row['id_valas'],
                'rate_valas' => $row['rate_valas'],
                'jumlah' => $row['jumlah'],
                'total' => $row['total'],
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
               ];
               $this->db->insert('transaksi',$result);

               $this->db->where('kd_trx',$kd_trx);
               $this->db->delete('temp_transaksi');

            }

            $queryTempStock = $this->db->query("SELECT * FROM temp_stock WHERE kd_trx = '$kd_trx' AND trx = 1")->result_array();

               foreach ($queryTempStock as $row) {
                # code...

               $tempStock = [
                'id_valas' => $row['id_valas'],
                'nr' => $row['nr'],
                'stock_awal' => $row['stock_awal'],
                'kd_trx' => $kode,
                'trx' => 1,                
                'rate' => $row['rate'],
                'jumlah' => $row['jumlah'],
                'total' => $row['total'],
                'stock_akhir' => $row['stock_akhir'],
                'date_created' => date('Y-m-d'),
                'time_created' => date('H:i:s'),
                'status' => 1
               ];
               $this->db->insert('stock',$tempStock);
               $this->db->where('kd_trx',$kd_trx);
               $this->db->delete('temp_stock');
            }
            
           
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

        $query = "SELECT transaksi.*, valas.*
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        WHERE transaksi.kd_trx = '$id'";

        $queryINV = "SELECT *,SUM(total) AS TTL FROM transaksi WHERE kd_trx = '$id' ";
        
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
