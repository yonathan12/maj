<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Laporan_model');
        $this->load->library('excel');
        $this->load->model('Customer_model');
    }

    public function labarugi()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['valas'] = $this->db->get('valas')->result_array();

        $data['laba'] = $this->Laporan_model->getLaporanLaba();

        $this->form_validation->set_rules('tanggal','Tanggal','required');
        if ($this->form_validation->run() == FALSE ) {
            $data['title'] = 'Laba Rugi';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('laporan/labarugi',$data);
            $this->load->view('templates/footer');
        } else {
            $this->Laporan_model->labaRugi();        
        }
    }

    public function penjualan()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['penjualan'] = $this->Laporan_model->lapPenjualan();

        $data['title'] = 'Laporan Penjualan';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('laporan/lap_penjualan',$data);
        $this->load->view('templates/footer');
    }

    public function detailPenjualan()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['penjualan'] = $this->Laporan_model->d_LapPenjualan();

        $data['title'] = 'Laporan Penjualan';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('laporan/d_lapPenjualan',$data);
        $this->load->view('templates/footer');
    }

    public function exportPenjualan()
    {
        $this->form_validation->set_rules('tanggal1','Tanggal Pertama','required|trim');
        $this->form_validation->set_rules('tanggal2','Tanggal Kedua','required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Tanggal Belum Dipilih!
            </div>');
            redirect('laporan/penjualan');
        } else {
        $object = new PHPExcel();

        $table_columns = array(
            "Kode Transaksi",
            "Customer",
            "Valas",
            "Rate Valas",
            "Jumlah",
            "Total",
            "Tanggal Transaksi"
        );

        $column = 0;
        for ($col='A'; $col !=='G' ; $col++) { 
            $object->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        $object->getActiveSheet()->mergeCells('A1:G1');
        $object->getActiveSheet()->mergeCells('A2:G2');
        $object->getActiveSheet()->getStyle("A1:A2")->getFont()->setSize(14);
 

        $object->getActiveSheet()->getStyle( "A1" )->getFont()->setBold( true );
        $object->getActiveSheet()->getStyle( "A2" )->getFont()->setBold( true );

        $object->getActiveSheet()->setCellValueByColumnAndRow("A1", 1, "Data Penjualan");
        $object->getActiveSheet()->setCellValueByColumnAndRow("A2", 2, "PT Muchad Artha Jaya");

        $object->getActiveSheet()->getStyle('A4:G4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F28A8C'); 
        
        $row_judul = 4;

        foreach($table_columns as $field)
        {
        $object->getActiveSheet()->setCellValueByColumnAndRow($column, $row_judul, $field);
        $column++;
        }

        $data = $this->Laporan_model->exportPenjualan();
        $excel_row = 5;
        foreach ($data as $row) {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->kd_trx);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->nama);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->valas);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->rate_valas);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->jumlah);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->total);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->date_created);            

            $excel_row++;
        }
        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Export Data Penjualan.xls"');
        $object_writer->save('php://output');
        redirect('laporan/penjualan');
        }
    }

    public function pembelian()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['pembelian'] = $this->Laporan_model->lapPembelian();

        $data['title'] = 'Laporan Pembelian';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('laporan/lap_pembelian',$data);
        $this->load->view('templates/footer');
    }

    public function detailPembelian()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $data['pembelian'] = $this->Laporan_model->d_LapPembelian();

        $data['title'] = 'Laporan Pembelian';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('laporan/d_lapPembelian',$data);
        $this->load->view('templates/footer');
    }

    public function exportPembelian()
    {
        $this->form_validation->set_rules('tanggal1','Tanggal Pertama','required|trim');
        $this->form_validation->set_rules('tanggal2','Tanggal Kedua','required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Tanggal Belum Dipilih!
            </div>');
            redirect('laporan/pembelian');
        } else {
        $object = new PHPExcel();

        $table_columns = array(
            "Kode Transaksi",
            "Customer",
            "Valas",
            "Rate Valas",
            "Jumlah",
            "Total",
            "Tanggal Transaksi"
        );

        $column = 0;
        for ($col='A'; $col !=='G' ; $col++) { 
            $object->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        $object->getActiveSheet()->mergeCells('A1:G1');
        $object->getActiveSheet()->mergeCells('A2:G2');
        $object->getActiveSheet()->getStyle("A1:A2")->getFont()->setSize(14);
 

        $object->getActiveSheet()->getStyle( "A1" )->getFont()->setBold( true );
        $object->getActiveSheet()->getStyle( "A2" )->getFont()->setBold( true );

        $object->getActiveSheet()->setCellValueByColumnAndRow("A1", 1, "Data Pembelian");
        $object->getActiveSheet()->setCellValueByColumnAndRow("A2", 2, "PT Muchad Artha Jaya");

        $object->getActiveSheet()->getStyle('A4:G4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F28A8C'); 
        
        $row_judul = 4;

        foreach($table_columns as $field)
        {
        $object->getActiveSheet()->setCellValueByColumnAndRow($column, $row_judul, $field);
        $column++;
        }

        $data = $this->Laporan_model->exportPembelian();
        $excel_row = 5;
        foreach ($data as $row) {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->kd_trx);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->nama);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->valas);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->rate_valas);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->jumlah);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->total);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->date_created);            

            $excel_row++;
        }
        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Export Data Pembelian.xls"');
        $object_writer->save('php://output');
        redirect('laporan/pembelian');
        }
    }

    public function laporanlaba()
    {
        $this->Laporan_model->simpanLaporan();
        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
        redirect('laporan/labarugi'); 
    }
    
    public function hapus($id)
    {
        $this->Laporan_model->hapusLaporan($id);
        $this->session->set_flashdata('message','Menghapus Laporan');
        redirect('laporan/labarugi'); 
    }

    public function detail($id)
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['detail'] = $this->Laporan_model->detailLaporan($id);
        $data['title'] = 'Laba Rugi';

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('laporan/detail',$data);
        $this->load->view('templates/footer');
    }
}