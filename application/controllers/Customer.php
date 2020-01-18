<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['kode'] = $this->Kode_model->get_kodeCustomer();
        $data['customer'] = $this->db->get('customer')->result_array();       
        
        $data['title'] = 'Customer';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('customer/index',$data);
        $this->load->view('templates/footer');
    }

   public function get_data()
    {
        $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

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
            <a href="customer/detail/'.$field->Id.'" class="btn btn-success">Detail</a> 
            <a href="#" data-toggle="modal" data-target="#modal_edit'.$field->Id.'" class="btn btn-primary">Edit</a>';  
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
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('nama','Nama','required|trim');

        if ($this->form_validation->run()== FALSE) {
            $data['title'] = 'Customer';
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('customer/addcustomer',$data);
            $this->load->view('templates/footer');
        } else {
            $checkNik = $this->db->get_where('customer',['no_ktp' => $this->input->post('ktp')])->result_array()[0]['no_ktp'];
            if($checkNik){
                $this->session->set_flashdata('message1','Data Nasabah Sudah Terdaftar');
                redirect('customer');
            }else{
                $this->Customer_model->addCustomer();
                $this->session->set_flashdata('message','Menambahkan Data Nasabah');
                redirect('customer');
            }
        }              
    }

    public function detail($Id)
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $query = "SELECT *, YEAR(curdate()) - YEAR(tgl_lahir) AS umur FROM `customer` WHERE Id = $Id";
        $data['detail'] = $this->db->query($query)->row_array();

        $data['title'] = 'Customer';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar',$data);
        $this->load->view('templates/topbar',$data);
        $this->load->view('customer/detail',$data);
        $this->load->view('templates/footer');
    }

    function delete()  
    {    $Id = $this->input->post('Id');         
        $this->Customer_model->delete_data($Id);
        $this->session->set_flashdata('message',
            '<div class="alert alert-success" role="alert">
            Customer Deleted!
            </div>');
        redirect('customer');             
    }

    public function edit($Id)
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
        $data['customer'] = $this->db->get_where('customer',['Id' => $Id])->row_array();       

        $this->Customer_model->editCustomer();
        $this->session->set_flashdata('message','Mengubah Data Nasabah!');
        redirect('customer');
    }

    function upload() {
        
        if(isset($_FILES["file"]["name"]))
        {	
            $id_daftar_detail=$this->Kode_model->get_kodeCustomer();
            $daftarid_array = substr($id_daftar_detail,-6);
            
            $path = $_FILES["file"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            foreach($object->getWorksheetIterator() as $worksheet)
            {                
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $endid = 0;
                for($row=2; $row<=$highestRow; $row++)
                {
                    $id_daftar = 'CST'.str_pad(($daftarid_array+$endid),6, "0", STR_PAD_LEFT);
                    $endid++;
                                
                    $data []= array(                                                     
                                "kd_cst" => $id_daftar,
                                "nama" => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                                "tempat_lahir" => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                "tgl_lahir" => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                                "alamat" => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                                "no_ktp" => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
                                "no_npwp" => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                                "email" => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                                "telp" => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),
                                "pekerjaan" => $worksheet->getCellByColumnAndRow(8, $row)->getValue(),
                                "kewarganegaraan" => $worksheet->getCellByColumnAndRow(9, $row)->getValue(),
                                "tipe_nasabah" => $worksheet->getCellByColumnAndRow(10, $row)->getValue(),
                                "status" => 1,
                                "user_id_created" => $this->session->userdata('id'),
                                "date_created" => date("Y-m-d")
                            );
                }
                    $this->Customer_model->uploadCustomer($data);
            }
            $this->session->set_flashdata('message','Berhasil Mengimport Data Nasabah!');
            redirect(base_url('customer'));
        }
    }

    public function export()
    {
        $object = new PHPExcel();

        $table_columns = array(
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
            "Tanggal Dibuat",
            "Tipe Nasabah"
        );

        $column = 0;
        for ($col='A'; $col !=='L' ; $col++) { 
            $object->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        $object->getActiveSheet()->mergeCells('A1:K1');
        $object->getActiveSheet()->mergeCells('A2:K2');
        $object->getActiveSheet()->getStyle("A1:A2")->getFont()->setSize(14);
 

        $object->getActiveSheet()->getStyle( "A1" )->getFont()->setBold( true );
        $object->getActiveSheet()->getStyle( "A2" )->getFont()->setBold( true );

        $object->getActiveSheet()->setCellValueByColumnAndRow("A1", 1, "Data Customer");
        $object->getActiveSheet()->setCellValueByColumnAndRow("A2", 2, "PT Muchad Artha Jaya");

        $object->getActiveSheet()->getStyle('A4:K4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F28A8C'); 
        
        $row_judul = 4;

        foreach($table_columns as $field)
        {
        $object->getActiveSheet()->setCellValueByColumnAndRow($column, $row_judul, $field);
        $column++;
        }

        $data = $this->Customer_model->exportData();
        $excel_row = 5;
        foreach ($data as $row) {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->kd_cst);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->nama);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->tempat_lahir);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->tgl_lahir);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->alamat);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->no_ktp);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->no_npwp);
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->email);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->telp);
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->pekerjaan);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->kewarganegaraan);
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->date_created);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->tipe_nasabah);

            $excel_row++;
        }
        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Export Data Customer.xls"');
        $object_writer->save('php://output');
    }
}