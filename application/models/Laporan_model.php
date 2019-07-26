<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model 
{
    public function getLaporanLaba()
    {
        $query = "SELECT SUM(laba.total) as Total, laba.date_created,laba.tgl_laporan, valas.valas
        FROM laba JOIN valas
        ON laba.id_valas = valas.Id_valas
        GROUP BY  laba.tgl_laporan";
        return $this->db->query($query)->result_array();
    }

    public function labaRugi()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $valas = $this->input->post('valas');
        $tanggal = $this->input->post('tanggal');                

        $queryTotalStock = "SELECT nr * stock_akhir AS total FROM stock WHERE id_valas = '$valas' AND status = 1 ORDER BY date_created DESC, time_created DESC";
        $dataStock['data'] = $this->db->query($queryTotalStock)->row();                
        $dts = $dataStock['data'];
        $Total = $dts->total;         

        $queryTotalJual = "SELECT SUM(total) TTL FROM transaksi WHERE date_created='$tanggal' AND trx = 2 AND id_valas = '$valas' AND status = 1";
        $dataJual['data'] = $this->db->query($queryTotalJual)->row();                
        $dtj = $dataJual['data'];
        $jual = $dtj->TTL;       

        $queryTotalVoidJual = "SELECT SUM(total) TTL FROM transaksi WHERE date_created='$tanggal' AND trx = 4 AND id_valas = '$valas' AND status = 1";
        $dataVoidJual['data'] = $this->db->query($queryTotalVoidJual)->row();                
        $dtvj = $dataVoidJual['data'];
        $voidjual = $dtvj->TTL;       
        
        $TY['data'] = $this->db->query("SELECT nr * jumlah AS total FROM stock WHERE id_valas = '$valas' AND date_created != '$tanggal' AND status = 1 ORDER BY date_created DESC, time_created DESC ")->row_array();
        $dataTY = $TY['data'];
        $totalY = $dataTY['total'];
        

        if ($totalY == '') {
            # code...
            $TY['data'] = $this->db->query("SELECT nr * jumlah AS total FROM stock WHERE id_valas = '$valas'  AND status = 1 AND trx = 0 ORDER BY time_created DESC")->row_array();
            $dataTY = $TY['data'];
            $totalYR = $dataTY['total'];

            $result = $totalYR;
        } else {
            # code...
            $result = $totalY;
        }

        $reportResult['dataReport'] = $this->db->query("SELECT * FROM `laba` WHERE id_valas = '$valas' AND tgl_laporan = '$tanggal'")->row_array();
        $dataReport = $reportResult['dataReport'];
        $lapLaba = $dataReport['id_valas'];

        $totalBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$tanggal' AND trx = 1 AND id_valas = '$valas' AND status = 1")->row_array();
        $dataTotalBeli = $totalBeli['data'];
        $totalPembelian = $dataTotalBeli['TBeli'];

        $totalVoidBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$tanggal' AND trx = 3 AND id_valas = '$valas' AND status = 1")->row_array();
        $dataTotalVoidBeli = $totalVoidBeli['data'];
        $totalVoidPembelian = $dataTotalVoidBeli['TBeli'];
        

        if ($totalPembelian == '' && $jual == '') {
            $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
            Data Tidak Ada!
            </div>');
            redirect('laporan/labarugi'); 
        } elseif ($lapLaba == '' ) {
            # code...
            $x = $result + $totalPembelian + $totalVoidPembelian;

            $hitungLaba = ($jual + $voidjual + $Total) - $x;                       

            if ($hitungLaba <= 0) {
                # code...
                $laba['hasil'] = '0';
                $laba['valas'] = $valas = $this->input->post('valas');
            } else {
                # code...
                $laba['hasil'] = round($hitungLaba);
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
            'tgl_laporan' => $tanggal,
            'date_created' => date('Y-m-d')
        ];
        $this->db->insert('laba',$data);        
    }

    public function detailLaporan($id)
    {
        $query = "SELECT laba.*, valas.valas
        FROM laba JOIN valas
        ON laba.id_valas = valas.Id_valas
        WHERE laba.tgl_laporan = '$id' ";
        return $this->db->query($query)->result_array();       
    }

    public function hapusLaporan($id)
    {
        $this->db->where('tgl_laporan', $id);
        $this->db->delete('laba');
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