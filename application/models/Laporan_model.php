<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model 
{
    public function getLaporanLaba()
    {
        $query = "SELECT SUM(laba.total) as Total, laba.date_created,laba.tgl_laporan, valas.valas
        FROM laba JOIN valas
        ON laba.id_valas = valas.Id
        GROUP BY  laba.tgl_laporan";
        return $this->db->query($query)->result_array();
    }

    public function labaRugi()
    {
        $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

        $valas = $this->input->post('valas');
        $tanggal = $this->input->post('tanggal');                

        $queryCekTglLap['data'] = $this->db->query("SELECT tgl_laporan FROM laba WHERE tgl_laporan = '$tanggal'")->row_array();
        $dataCekLap = $queryCekTglLap['data'];
        
        if ($dataCekLap['tgl_laporan'] == '') {

            $queryValas['data'] = $this->db->query("SELECT Id from valas ")->result_array();
            $data = $queryValas['data'];
            foreach ($data as $key => $value) {
                $v = $value['Id'];
                $queryTotalStock = "SELECT total FROM stock WHERE id_valas = '$v' AND status = 1 AND date_created = '$tanggal' ORDER BY date_created DESC, time_created DESC";
                $dataStock['data'] = $this->db->query($queryTotalStock)->row_array();                
                $dts = $dataStock['data'];
                $Total = $dts['total'];         

                $queryTotalJual = "SELECT SUM(total) TTL FROM transaksi WHERE date_created='$tanggal' AND trx = 2 AND id_valas = '$v' AND status = 1";
                $dataJual['data'] = $this->db->query($queryTotalJual)->row_array();                
                $dtj = $dataJual['data'];
                $jual = $dtj['TTL'];       

                $queryTotalVoidJual = "SELECT SUM(total) TTL FROM transaksi WHERE date_created='$tanggal' AND trx = 4 AND id_valas = '$v' AND status = 1";
                $dataVoidJual['data'] = $this->db->query($queryTotalVoidJual)->row_array();                
                $dtvj = $dataVoidJual['data'];
                $voidjual = $dtvj['TTL'];       
                
                // $TY['data'] = $this->db->query("SELECT total,date_created FROM stock WHERE id_valas = '$v' AND date_created != '$tanggal' AND status = 1 ORDER BY date_created DESC, time_created DESC")->row_array();
                // $dataTY = $TY['data'];
                // $totalY = $dataTY['total'];

                $TY['data'] = $this->db->query("SELECT total FROM stock WHERE id_valas = '$v' AND date_created = '$tanggal' AND status = 1 AND trx = 0 ORDER BY time_created DESC")->row_array();
                $dataTY = $TY['data'];
                $totalYR = $dataTY['total'];

                    if ($totalYR == '') {
                            $TY['data'] = $this->db->query("SELECT total,date_created FROM stock WHERE id_valas = '$v' AND date_created != '$tanggal' AND status = 1 ORDER BY date_created DESC, time_created DESC")->row_array();
                            $dataTY = $TY['data'];
                            $totalY = $dataTY['total'];
                            $result = $totalY;
                    } else {
                        $result = $totalYR;
                    }

                $totalBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$tanggal' AND trx = 1 or trx = 5 AND id_valas = '$v' AND status = 1")->row_array();
                $dataTotalBeli = $totalBeli['data'];
                $totalPembelian = $dataTotalBeli['TBeli'];

                $totalVoidBeli['data'] = $this->db->query("SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM transaksi WHERE date_created='$tanggal' AND trx = 3 AND id_valas = '$v' AND status = 1")->row_array();
                $dataTotalVoidBeli = $totalVoidBeli['data'];
                $totalVoidPembelian = $dataTotalVoidBeli['TBeli'];
            
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
                    $data = 
                    [
                        'id_valas' => $v,
                        'total' => $laba['hasil'],
                        'tgl_laporan' => $tanggal,
                        'date_created' => date('Y-m-d'),
                        'user_id_created' => $this->session->userdata('id')
                    ];
                    $this->db->insert('laba',$data);  
            }
            $this->session->set_flashdata('message','Menyimpan Laporan');
            redirect('laporan/labarugi'); 
        }else {
            $this->session->set_flashdata('message1','Laporan Sudah Ada');
            redirect('laporan/labarugi'); 
        }
    }

    public function detailLaporan($id)
    {
        $query = "SELECT laba.*, valas.valas
        FROM laba JOIN valas
        ON laba.id_valas = valas.Id
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
        ON transaksi.id_valas = valas.Id
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
        ON transaksi.id_valas = valas.Id
        JOIN customer 
        ON transaksi.customer = customer.kd_cst
        WHERE transaksi.trx = 2 AND transaksi.status = 1 AND transaksi.date_created BETWEEN '$tanggal1' AND '$tanggal2'";
        return $this->db->query($query)->result_array();
    }

    public function exportPenjualan()
    {
        $tanggal1 = $this->input->post('tanggal1');
        $tanggal2 = $this->input->post('tanggal2');

        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,transaksi.total,transaksi.date_created , valas.valas, customer.nama
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id
        JOIN customer 
        ON transaksi.customer = customer.kd_cst
        WHERE transaksi.trx = 2 AND transaksi.status = 1 AND transaksi.date_created BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY transaksi.date_created DESC";
        return $this->db->query($query)->result();
    }

    public function lapPembelian()
    {        
        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,transaksi.total,transaksi.date_created , valas.valas, customer.nama
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id
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
        ON transaksi.id_valas = valas.Id
        JOIN customer 
        ON transaksi.customer = customer.kd_cst
        WHERE transaksi.trx = 1 AND transaksi.status = 1 AND transaksi.date_created BETWEEN '$tanggal1' AND '$tanggal2'";
        return $this->db->query($query)->result_array();
    }

    public function exportPembelian()
    {
        $tanggal1 = $this->input->post('tanggal1');
        $tanggal2 = $this->input->post('tanggal2');

        $query = "SELECT transaksi.kd_trx,transaksi.customer,transaksi.rate_valas,transaksi.jumlah,transaksi.total,transaksi.date_created , valas.valas, customer.nama
        FROM transaksi JOIN valas
        ON transaksi.id_valas = valas.Id
        JOIN customer 
        ON transaksi.customer = customer.kd_cst
        WHERE transaksi.trx = 1 AND transaksi.status = 1 AND transaksi.date_created BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY transaksi.date_created DESC";
        return $this->db->query($query)->result();
    }

    public function exportlabaRugi()
    {
        $tanggal1 = $this->input->post('tanggal1');
        $tanggal2 = $this->input->post('tanggal2');

        $query = "SELECT b.valas, b.description, a.total, a.tgl_laporan, a.date_created, c.nama FROM laba a
        LEFT JOIN valas b
        ON a.id_valas = b.Id
        LEFT JOIN user c
        ON a.user_id_created = c.Id
        WHERE a.tgl_laporan BETWEEN '$tanggal1' AND '$tanggal2'";
        return $this->db->query($query)->result();
    }

    public function exportSipesat()
    {
        $periode = $this->input->post('periode');
        $tahun = $this->input->post('tahun');

        if($periode == "1"){
            $dateStart = $tahun.'-01-01';
            $dateEnd = $tahun.'-03-31';
        }else if($periode == "2"){
            $dateStart = $tahun.'-04-01';
            $dateEnd = $tahun.'-06-30';
        }else if($periode == "3"){
            $dateStart = $tahun.'-07-01';
            $dateEnd = $tahun.'-09-30';
        }else if($periode == "4"){
            $dateStart = $tahun.'-10-01';
            $dateEnd = $tahun.'-12-31';
        }

        $query = "
            SELECT * FROM customer WHERE date_created BETWEEN '$dateStart' AND '$dateEnd'
        ";
        return $this->db->query($query)->result();
    }
}