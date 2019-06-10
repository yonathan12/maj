<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model 
{
    public function getLaporanLaba()
    {
        $query = "SELECT SUM(laba.total) as Total, laba.date_created, valas.valas
        FROM laba JOIN valas
        ON laba.id_valas = valas.Id_valas
        GROUP BY  laba.date_created";
        return $this->db->query($query)->result_array();
    }

    public function labaRugi()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $valas = $this->input->post('valas');
        $tanggal = $this->input->post('tanggal');                

        $queryTotalStock = "SELECT total FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created DESC, time_created DESC";
        $dataStock['data'] = $this->db->query($queryTotalStock)->row();                
        $dts = $dataStock['data'];
        $Total = $dts->total;                

        $queryTotalJual = "SELECT SUM(total) TTL FROM transaksi WHERE date_created='$tanggal' AND trx = 2 AND id_valas = '$valas' AND status = 1";
        $dataJual['data'] = $this->db->query($queryTotalJual)->row();                
        $dtj = $dataJual['data'];
        $jual = $dtj->TTL;

        $TY['data'] = $this->db->query("SELECT total FROM stock WHERE id_valas = '$valas' AND date_created != '$tanggal' AND status = 1 ORDER BY date_created DESC, time_created DESC ")->row_array();
        $dataTY = $TY['data'];
        $totalY = $dataTY['total'];

        if ($totalY == '') {
            # code...
            $TY['data'] = $this->db->query("SELECT * FROM stock WHERE id_valas = '$valas'  AND status = 1 AND trx = 0 ORDER BY time_created DESC")->row_array();
            $dataTY = $TY['data'];
            $totalYR = $dataTY['total'];

            $result = $totalYR;
        } else {
            # code...
            $result = $totalY;
        }

        $reportResult['dataReport'] = $this->db->query("SELECT * FROM `laba` WHERE id_valas = '$valas' AND date_created = '$tanggal'")->row_array();
        $dataReport = $reportResult['dataReport'];
        $lapLaba = $dataReport['id_valas'];

        $totalBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$tanggal' AND trx = 1 AND id_valas = '$valas' AND status = 1")->row_array();
        $dataTotalBeli = $totalBeli['data'];
        $totalPembelian = $dataTotalBeli['TBeli'];

        if ($totalPembelian == '') {
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Data Tidak Ada!
            </div>');
            redirect('laporan/labarugi'); 
        } elseif ($lapLaba == '' ) {
            # code...
            $x = $result + $totalPembelian;

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
            $data['tanggal'] = $this->input->post('tanggal');
            $this->load->view('templates/header',$data);
            $this->load->view('templates/sidebar',$data);
            $this->load->view('templates/topbar',$data);
            $this->load->view('laporan/hasil',$laba);
            $this->load->view('templates/footer');
        }else{
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Data Sudah Ada!
            </div>');
            redirect('laporan/labarugi');                     
        }      
    }

    public function simpanLaporan()
    {
        $valas = $this->input->post('valas');
        $total = $this->input->post('total');
        $tanggal = $this->input->post('tanggal');

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

    public function detailLaporan($id)
    {
        $query = "SELECT laba.*, valas.valas
        FROM laba JOIN valas
        ON laba.id_valas = valas.Id_valas
        WHERE laba.date_created = '$id' ";
        return $this->db->query($query)->result_array();       
    }

    public function hapusLaporan($date_created)
    {
        $this->db->where('date_created', $date_created);
        $this->db->delete('laba');

        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">
            Data Dihapus!
          </div>');
        redirect('laporan/labarugi'); 
    }

    public function lapPenjualan()
    {        
        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,transaksi.total,transaksi.date_created , valas.valas, customer.nama
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        JOIN customer 
        ON transaksi.customer = customer.kd_cst
        WHERE transaksi.trx = 2 AND transaksi.status = 1 ORDER BY transaksi.date_created DESC";
        return $this->db->query($query)->result_array();
    }

    public function d_LapPenjualan()
    {
        $tanggal1 = $this->input->post('tanggal1');
        $tanggal2 = $this->input->post('tanggal2');

        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,transaksi.total,transaksi.date_created , valas.valas, customer.nama
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        JOIN customer 
        ON transaksi.customer = customer.kd_cst
        WHERE transaksi.trx = 2 AND transaksi.status = 1 AND transaksi.date_created BETWEEN '$tanggal1' AND '$tanggal2'";
        return $this->db->query($query)->result_array();
    }

    public function lapPembelian()
    {        
        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,transaksi.total,transaksi.date_created , valas.valas, customer.nama
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        JOIN customer 
        ON transaksi.customer = customer.kd_cst
        WHERE transaksi.trx = 1 AND transaksi.status = 1 ORDER BY transaksi.date_created DESC";
        return $this->db->query($query)->result_array();
    }

    public function d_LapPembelian()
    {
        $tanggal1 = $this->input->post('tanggal1');
        $tanggal2 = $this->input->post('tanggal2');

        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,transaksi.total,transaksi.date_created , valas.valas, customer.nama
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id_valas
        JOIN customer 
        ON transaksi.customer = customer.kd_cst
        WHERE transaksi.trx = 1 AND transaksi.status = 1 AND transaksi.date_created BETWEEN '$tanggal1' AND '$tanggal2'";
        return $this->db->query($query)->result_array();
    }
}