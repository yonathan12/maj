<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/third_party/spout/src/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Customer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Customer_model');
        $this->load->model('Kode_model');
        $this->load->library('excel');
    }

    public function index()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['kode'] = $this->Kode_model->get_kodeCustomer();

        $data['title'] = 'Customer';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('customer/index', $data);
        $this->load->view('templates/footer');
    }

    public function get_data()
    {
        $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $list = $this->Customer_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->kd_cst;
            $row[] = $field->nama;
            $row[] = $field->no_ktp;
            $row[] = $field->telp;
            $row[] = '
            <a href="customer/detail/' . $field->Id . '" class="btn btn-success">Detail</a> 
            <a href="#" data-toggle="modal" data-target="#modal_edit" id="'.$field->Id.'"
            onclick="return editData(this)" class="btn btn-primary">Edit</a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Customer_model->count_all(),
            "recordsFiltered" => $this->Customer_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function addCustomer()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Customer';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('customer/addcustomer', $data);
            $this->load->view('templates/footer');
        } else {
            $checkNik = $this->db->get_where('customer', ['no_ktp' => $this->input->post('ktp')])->result_array()[0]['no_ktp'];
            if ($checkNik) {
                $this->session->set_flashdata('message1', 'Data Nasabah Sudah Terdaftar');
                redirect('customer');
            } else {
                $this->Customer_model->addCustomer();
                $this->session->set_flashdata('message', 'Menambahkan Data Nasabah');
                redirect('customer');
            }
        }
    }

    public function detail($Id)
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $query = "SELECT *, YEAR(curdate()) - YEAR(tgl_lahir) AS umur FROM `customer` WHERE Id = $Id";
        $data['detail'] = $this->db->query($query)->row_array();

        $data['title'] = 'Customer';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('customer/detail', $data);
        $this->load->view('templates/footer');
    }

    public function getDetail($id)
    {
        header('Content-Type: application/json');
        $getData = $this->db->get_where('customer',['Id' => $id])->row_array();
        $data = ['data' => ['status' => true, 'data' => $getData]];
        echo json_encode($data);
    }

    function delete()
    {
        $Id = $this->input->post('Id');
        $this->Customer_model->delete_data($Id);
        $this->session->set_flashdata(
            'message',
            '<div class="alert alert-success" role="alert">
            Customer Deleted!
            </div>'
        );
        redirect('customer');
    }

    public function edit()
    {
        $Id = $this->input->post('id');
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['customer'] = $this->db->get_where('customer', ['Id' => $Id])->row_array();

        $this->Customer_model->editCustomer();
        $this->session->set_flashdata('message', 'Mengubah Data Nasabah!');
        redirect('customer');
    }

    function upload()
    {

        if (isset($_FILES["file"]["name"])) {
            $id_daftar_detail = $this->Kode_model->get_kodeCustomer();
            $daftarid_array = substr($id_daftar_detail, -6);

            $path = $_FILES["file"]["tmp_name"];
            $reader = ReaderFactory::create(Type::XLSX); 
            $reader->open($path); 
            $endid = 0;
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $key => $row) {
                    if ($key != 1) {
                        $id_daftar = 'CST' . str_pad(($daftarid_array + $endid), 6, "0", STR_PAD_LEFT);
                        $endid++;
                        $data[] = array(
                            "kd_cst" => $id_daftar,
                            "nama" => $row[0],
                            "tempat_lahir" => $row[1],
                            "tgl_lahir" => $row[2],
                            "alamat" => $row[3],
                            "no_ktp" => $row[4],
                            "no_npwp" => $row[5],
                            "email" => $row[6],
                            "telp" => $row[7],
                            "pekerjaan" => $row[8],
                            "kewarganegaraan" => $row[9],
                            "tipe_nasabah" => $row[10],
                            "status" => 1,
                            "user_id_created" => $this->session->userdata('id'),
                            "date_created" => date("Y-m-d")
                        );
                    }
                }
            }
            $this->Customer_model->uploadCustomer($data);
            $reader->close();
        }
         $this->session->set_flashdata('message', 'Berhasil Mengimport Data Nasabah!');
         redirect(base_url('customer'));
    }

    public function export()
    {
        $data = $this->Customer_model->exportData();
        foreach ($data as $row) {
            $rows[] = [
                $row->kd_cst, $row->nama,
                $row->tempat_lahir, $row->tgl_lahir,
                $row->alamat, $row->no_ktp,
                $row->no_npwp, $row->email,
                $row->telp, $row->pekerjaan,
                $row->kewarganegaraan, $row->tipe_nasabah,
                $row->date_created
            ];
        }

        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToBrowser("Data Customer.xlsx");

        $header = [
            'PT Muchad Artha Jaya'
        ];
        $sub_header = array(
            "Kode Customer",
            "Nama",
            "Tempat Lahir",
            "Tanggal Lahir",
            "Alamat",
            "Nomor KTP",
            "Nomor NPWP",
            "Email",
            "Telp",
            "Pekerjaan",
            "Kewarganegaraan",
            "Tipe Nasabah",
            "Tanggal Dibuat"
        );
        $writer->addRow($header);
        $writer->addRow($sub_header);
        $writer->addRows($rows);

        $writer->close();
    }
}
