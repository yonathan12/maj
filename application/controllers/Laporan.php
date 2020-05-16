<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/third_party/spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Laporan_model');
        $this->load->model('Customer_model');
    }

    public function labarugi()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['valas'] = $this->db->get('valas')->result_array();

        $data['laba'] = $this->Laporan_model->getLaporanLaba();
        $data['title'] = 'Laba Rugi';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('laporan/labarugi', $data);
        $this->load->view('templates/footer');
        if ($this->input->post('tanggal'))
            $this->Laporan_model->labaRugi();
    }

    public function penjualan()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['penjualan'] = $this->Laporan_model->lapPenjualan();

        $data['title'] = 'Laporan Penjualan';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('laporan/lap_penjualan', $data);
        $this->load->view('templates/footer');
    }

    public function detailPenjualan()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['penjualan'] = $this->Laporan_model->d_LapPenjualan();

        $data['title'] = 'Laporan Penjualan';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('laporan/d_lapPenjualan', $data);
        $this->load->view('templates/footer');
    }

    public function exportPenjualan()
    {
        $this->form_validation->set_rules('tanggal1', 'Tanggal Pertama', 'required|trim');
        $this->form_validation->set_rules('tanggal2', 'Tanggal Kedua', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message1', 'Tanggal Belum Dipilih!');
            redirect('laporan/penjualan');
        } else {
            $data = $this->Laporan_model->exportPenjualan();
            foreach ($data as $row) {
                $rows[] = [
                    $row->kd_trx, $row->nama,
                    $row->valas, $row->rate_valas,
                    $row->jumlah, $row->total, $row->date_created
                ];
            }

            $writer = WriterFactory::create(Type::XLSX);
            $writer->openToBrowser("Export Data Penjualan.xlsx");

            $header = [
                'PT Muchad Artha Jaya'
            ];
            $sub_header = array(
                "Kode Transaksi",
                "Customer",
                "Valas",
                "Rate Valas",
                "Jumlah",
                "Total",
                "Tanggal Transaksi"
            );
            $writer->addRow($header);
            $writer->addRow($sub_header);
            $writer->addRows($rows);

            $writer->close();
        }
    }

    public function pembelian()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['pembelian'] = $this->Laporan_model->lapPembelian();

        $data['title'] = 'Laporan Pembelian';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('laporan/lap_pembelian', $data);
        $this->load->view('templates/footer');
    }

    public function detailPembelian()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['pembelian'] = $this->Laporan_model->d_LapPembelian();

        $data['title'] = 'Laporan Pembelian';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('laporan/d_lapPembelian', $data);
        $this->load->view('templates/footer');
    }

    public function exportPembelian()
    {
        $this->form_validation->set_rules('tanggal1', 'Tanggal Pertama', 'required|trim');
        $this->form_validation->set_rules('tanggal2', 'Tanggal Kedua', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message1', 'Tanggal Belum Dipilih!');
            redirect('laporan/pembelian');
        } else {
            $data = $this->Laporan_model->exportPembelian();
            foreach ($data as $row) {
                $rows[] = [
                    $row->kd_trx, $row->nama,
                    $row->valas, $row->rate_valas,
                    $row->jumlah, $row->total, $row->date_created
                ];
            }

            $writer = WriterFactory::create(Type::XLSX);
            $writer->openToBrowser("Export Data Pembelian.xlsx");

            $header = [
                'PT Muchad Artha Jaya'
            ];
            $sub_header = array(
                "Kode Transaksi",
                "Customer",
                "Valas",
                "Rate Valas",
                "Jumlah",
                "Total",
                "Tanggal Transaksi"
            );
            $writer->addRow($header);
            $writer->addRow($sub_header);
            $writer->addRows($rows);

            $writer->close();
        }
    }

    public function exportLabarugi()
    {
        $this->form_validation->set_rules('tanggal1', 'Tanggal Pertama', 'required|trim');
        $this->form_validation->set_rules('tanggal2', 'Tanggal Kedua', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message1', 'Tanggal Belum Dipilih!');
            redirect('laporan/labarugi');
        } else {
            $data_laba_rugi = $this->Laporan_model->exportlabaRugi();
            foreach ($data_laba_rugi as $row) {
                $rows[] = [
                    $row->valas, $row->description,
                    $row->total, $row->tgl_laporan,
                    $row->date_created, $row->nama
                ];
            }

            $header = [
                'PT Muchad Artha Jaya'
            ];

            $sub_header = array(
                "Valas",
                "Deskripsi",
                "Total",
                "Tanggal Laporan",
                "Tanggal Dibuat",
                "Dibuat Oleh"
            );

            $sub_header_penjualan = array(
                "Kode Transaksi",
                "Customer",
                "Valas",
                "Rate Valas",
                "Jumlah",
                "Total",
                "Tanggal Transaksi"
            );

            $sub_header_pembelian = array(
                "Kode Transaksi",
                "Customer",
                "Valas",
                "Rate Valas",
                "Jumlah",
                "Total",
                "Tanggal Transaksi"
            );

            $data_penjualan = $this->Laporan_model->exportPenjualan();
            foreach ($data_penjualan as $row) {
                $rows_penjualan[] = [
                    $row->kd_trx, $row->nama,
                    $row->valas, $row->rate_valas,
                    $row->jumlah, $row->total, $row->date_created
                ];
            }

            $data_pembelian = $this->Laporan_model->exportPembelian();
            foreach ($data_pembelian as $row) {
                $rows_pembelian[] = [
                    $row->kd_trx, $row->nama,
                    $row->valas, $row->rate_valas,
                    $row->jumlah, $row->total, $row->date_created
                ];
            }

            $writer = WriterFactory::create(Type::XLSX);
            $writer->openToBrowser("Export Data Laba Rugi.xlsx");

            $writer->getCurrentSheet()->setName('Laporan Laba Rugi');
            $writer->addRow($header);
            $writer->addRow($sub_header);
            $writer->addRows($rows);

            $writer->addNewSheetAndMakeItCurrent()->setName('Laporan Penjualan');
            $writer->addRow($header);
            $writer->addRow($sub_header_penjualan);
            $writer->addRows($rows_penjualan);

            $writer->addNewSheetAndMakeItCurrent()->setName('Laporan Pembelian');
            $writer->addRow($header);
            $writer->addRow($sub_header_pembelian);
            $writer->addRows($rows_pembelian);

            $writer->close();
        }
    }

    public function laporanlaba()
    {
        $this->Laporan_model->simpanLaporan();
        $this->session->set_flashdata('message', 'Data Tersimpan!');
        redirect('laporan/labarugi');
    }

    public function hapus($id)
    {
        $this->Laporan_model->hapusLaporan($id);
        $this->session->set_flashdata('message', 'Menghapus Laporan');
        redirect('laporan/labarugi');
    }

    public function detail($id)
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['detail'] = $this->Laporan_model->detailLaporan($id);
        $data['title'] = 'Laba Rugi';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('laporan/detail', $data);
        $this->load->view('templates/footer');
    }

    public function sipesat()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = 'Laporan Sipesat';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('laporan/sipesat', $data);
        $this->load->view('templates/footer');
    }

    public function exportSipesat()
    {
        $data = $this->Laporan_model->exportSipesat();
        foreach ($data as $row) {
            $rows[] = [
                $row->kd_cst, $row->nama,
                $row->tempat_lahir, $row->tgl_lahir,
                $row->alamat, $row->no_ktp
            ];
        }

        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToBrowser("Data Sipesat.xlsx");

        $header = [
            'PT Muchad Artha Jaya'
        ];
        $sub_header = array(
            "No Nasabah",
            "Nama Nasabah",
            "Tempat Lahir",
            "Tanggal Lahir",
            "Alamat",
            "No KTP / No NPWP"
        );
        $writer->addRow($header);
        $writer->addRow($sub_header);
        $writer->addRows($rows);

        $writer->close();
    }
}
