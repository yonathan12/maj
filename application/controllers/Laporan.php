<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('Pdf');
    }

    public function labarugi()
    {
            $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

            $data['valas'] = $this->db->get('valas')->result_array();

            $query = "SELECT SUM(laba.total) as Total, laba.date_created, valas.valas
            FROM laba JOIN valas
            ON laba.id_valas = valas.Id_valas
            GROUP BY  laba.date_created";
            $data['laba'] = $this->db->query($query)->result_array();

            $this->form_validation->set_rules('valas','Valas','required');
            if ($this->form_validation->run() == FALSE ) {
                # code...
                $data['title'] = 'Laba Rugi';
                $this->load->view('templates/header',$data);
                $this->load->view('templates/sidebar',$data);
                $this->load->view('templates/topbar',$data);
                $this->load->view('laporan/labarugi',$data);
                $this->load->view('templates/footer');
            } else {
                # code...

                $valas = $this->input->post('valas');
                $hariini = date("Y-m-d");

                $queryTotalStock = "SELECT total FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created DESC, time_created DESC";
                $dataStock['data'] = $this->db->query($queryTotalStock)->row();                
                $dts = $dataStock['data'];
                $Total = $dts->total;

                $queryTotalJual = "SELECT SUM(total) TTL FROM transaksi WHERE date_created='$hariini' AND trx = 2 AND id_valas = '$valas' AND status = 1";
                $dataJual['data'] = $this->db->query($queryTotalJual)->row();                
                $dtj = $dataJual['data'];
                $jual = $dtj->TTL;

                $TY['data'] = $this->db->query("SELECT total FROM stock WHERE id_valas = '$valas' AND date_created != '$hariini' AND status = 1 ORDER BY date_created DESC, time_created DESC ")->row();
                $dataTY = $TY['data'];
                $totalY = $dataTY->total;

                $totalBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$hariini' AND trx = 1 AND id_valas = '$valas' AND status = 1")->row();
                $dataTotalBeli = $totalBeli['data'];
                $totalPembelian = $dataTotalBeli->TBeli;

                $x = $totalY + $totalPembelian;

                $hitungLaba = ($jual + $Total) - $x;

                if ($hitungLaba <= 0) {
                    # code...
                    $laba['hasil'] = '0';
                    $laba['valas'] = $valas = $this->input->post('valas');
                } else {
                    # code...
                    $laba['hasil'] = $hitungLaba;
                    $laba['valas'] = $valas = $this->input->post('valas');
                }               

                $data['title'] = 'Laba Rugi';
                $this->load->view('templates/header',$data);
                $this->load->view('templates/sidebar',$data);
                $this->load->view('templates/topbar',$data);
                $this->load->view('laporan/hasil',$laba);
                $this->load->view('templates/footer');
            }
        }

    public function laporanlaba()
    {
        $valas = $this->input->post('valas');
        $total = $this->input->post('total');

        $data = 
        [
            'id_valas' => $valas,
            'total' => $total,
            'date_created' => date('Y-m-d')
        ];
        $this->db->insert('laba',$data);

        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Tersimpan!
          </div>');
        redirect('laporan/labarugi'); 
    }
    public function hapus($date_created)
    {
        $this->db->where('date_created', $date_created);
        $this->db->delete('laba');

        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Dihapus!
          </div>');
        redirect('laporan/labarugi'); 
    }
}