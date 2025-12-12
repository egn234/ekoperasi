<?php

namespace App\Controllers\Ketua\ReportManagement;

require_once ROOTPATH.'vendor/autoload.php';

use App\Controllers\Ketua\ReportManagement\BaseReportController;
use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_monthly_report;
use App\Models\M_param;
use App\Models\M_cicilan;
use App\Models\M_pinjaman;
use App\Models\M_asuransi;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

/**
 * ReportExport Controller
 * Handles all report export operations for Ketua role
 */
class ReportExport extends BaseReportController
{
    /**
     * Export monthly loan deduction report (CSV)
     */
    public function print_potongan_pinjaman()
    {
        $m_monthly_report = model(M_monthly_report::class);
        $m_user = model(M_user::class);
        $idreportm = request()->getPost('idreportm');

        if($idreportm == 0){
            $this->sendAlert('Belum memilih bulan laporan', 'warning');
            return redirect()->back();
        }
        
        $report_data = $m_monthly_report->where('idreportm', $idreportm)->get()->getResult()[0];
        $endDate = $report_data->created;
        $startDate = $m_monthly_report->getPrevMonth($idreportm)[0]->created;
        $instansi = request()->getPost('instansi');

        if ($instansi == '0') {
            $user_list = $m_user->where('flag', 1)
                ->where('idgroup', 4)
                ->get()
                ->getResult();
        }else{
            $user_list = $m_user->where('instansi', $instansi)
                ->where('flag', 1)
                ->where('idgroup', 4)
                ->get()
                ->getResult();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Potongan Pinjaman');

        // Header kolom
        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('D1:D2');
        $sheet->mergeCells('E1:E2');
        $sheet->mergeCells('F1:I1');
        $sheet->mergeCells('J1:J2');
        $sheet->mergeCells('K1:K2');
        $sheet->mergeCells('L1:L2');
        $sheet->mergeCells('M1:N1');
        $sheet->setCellValue('A1', 'NO.');
        $sheet->setCellValue('B1', 'NAMA');
        $sheet->setCellValue('C1', 'NIK');
        $sheet->setCellValue('D1', 'SIMPANAN WAJIB + POKOK');
        $sheet->setCellValue('E1', 'SIMPANAN MANASUKA');
        $sheet->setCellValue('F1', 'PINJAMAN');
        $sheet->setCellValue('F2', 'PINJAMAN POKOK');
        $sheet->setCellValue('G2', 'BUNGA PINJAMAN');
        $sheet->setCellValue('H2', 'PROVISI');
        $sheet->setCellValue('I2', 'JUMLAH');
        $sheet->setCellValue('J1', 'ASURANSI');
        $sheet->setCellValue('K1', 'TOTAL POTONGAN');
        $sheet->setCellValue('L1', 'CICILAN PINJAMAN UANG');
        $sheet->setCellValue('M1', 'CICILAN PINJAMAN UANG');
        $sheet->setCellValue('L2', 'JML');
        $sheet->setCellValue('M2', 'KE');
        $c = 1;
        $cellNumber = 3;

        foreach ($user_list as $a) {
            $cicilan = $m_monthly_report->getHitunganPinjaman($a->iduser, $startDate, $endDate);
            $pinjaman = $m_monthly_report->getPinjamanAktifByAnggota($a->iduser, $startDate, $endDate);

            if ($pinjaman) {
                $count_cicilan = $m_monthly_report->countCicilanByPinjaman($pinjaman[0]->idpinjaman, $startDate, $endDate)[0]->hitung;
                
                // Get asuransi data
                $m_asuransi = model(M_asuransi::class);
                $asuransi_data = $m_asuransi->getAsuransiByIdPinjaman($pinjaman[0]->idpinjaman);
                $total_asuransi = 0;
                foreach ($asuransi_data as $asuransi) {
                    $total_asuransi += $asuransi->nilai_asuransi;
                }
            }else{
                $count_cicilan = " - ";
                $total_asuransi = 0;
            }
            
            $data_pokok_wajib = $m_monthly_report->getSumSimpanan1($a->iduser, $startDate, $endDate)[0]->nominal;  
            $data_manasuka = $m_monthly_report->getSumSimpanan2($a->iduser, $startDate, $endDate)[0]->nominal;

            $pokok_wajib = ($data_pokok_wajib)?$data_pokok_wajib:0;
            $manasuka = ($data_manasuka)?$data_manasuka:0;

            $p_pokok = ($cicilan)?$cicilan[0]->nominal:0;
            $p_bunga = ($cicilan)?$cicilan[0]->bunga:0;
            $p_provisi = ($cicilan)?$cicilan[0]->provisi:0;

            $total_potongan = ($pokok_wajib + $manasuka + $p_pokok + $p_bunga + $p_provisi + $total_asuransi);

            $sheet->setCellValue('A'.$cellNumber, $c);
            $sheet->setCellValue('B'.$cellNumber, $a->nama_lengkap);
            $sheet->setCellValue('C'.$cellNumber, "'".$a->nik);
            $sheet->setCellValue('D'.$cellNumber, $pokok_wajib);
            $sheet->setCellValue('E'.$cellNumber, $manasuka);
            $sheet->setCellValue('F'.$cellNumber, $p_pokok);
            $sheet->setCellValue('G'.$cellNumber, $p_bunga);
            $sheet->setCellValue('H'.$cellNumber, $p_provisi);
            $sheet->setCellValue('I'.$cellNumber, ($p_pokok + $p_bunga + $p_provisi));
            $sheet->setCellValue('J'.$cellNumber, $total_asuransi);
            $sheet->setCellValue('K'.$cellNumber, $total_potongan);
            $sheet->setCellValue('L'.$cellNumber, ($pinjaman)?$pinjaman[0]->angsuran_bulanan:' - ');
            $sheet->setCellValue('M'.$cellNumber, $count_cicilan);
            $c++;
            $cellNumber++;
        }
        
        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Csv($spreadsheet);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="cutoff_'.$instansi.'_'.$report_data->created.'.csv"');
        $writer->save('php://output');
        exit;
    }

    /**
     * Export account statement per year (Excel)
     */
    public function print_rekening_koran()
    {
        $m_param = model(M_param::class);
        $m_deposit = model(M_deposit::class);
        $m_pinjaman = model(M_pinjaman::class);
        $m_cicilan = model(M_cicilan::class);
        $m_user = model(M_user::class);

        $tahun = request()->getPost('tahun');
        
        if ($tahun == '0') {
            $this->sendAlert('Belum memilih tahun laporan', 'warning');
            return redirect()->back();
        }

        $setDay = $m_param->where('idparameter', 8)->get()->getResult()[0]->nilai + 1;
        $user_list = $m_user->where('flag', '1')
            ->where('idgroup', 4)
            ->get()
            ->getResult();
        
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Bookman Old Style');
        
        foreach($user_list as $member){
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($member->nama_lengkap);
            
            $sheet->setCellValue('A1', 'DAFTAR SIMPAN PINJAM ANGGOTA KOPERASI "GIAT"');
            $sheet->setCellValue('A2', 'UNIVERSITAS TELKOM BANDUNG');
            $sheet->setCellValue('A3', 'Nama');
            $sheet->setCellValue('B3', ': '.$member->nama_lengkap);
            $sheet->setCellValue('A4', 'Karyawan');
            $sheet->setCellValue('B4', ': '.$member->instansi);
            
            $sheet->setCellValue('A6', 'TANGGAL');
            $sheet->setCellValue('B6', 'URAIAN');
            $sheet->setCellValue('C6', 'SIMPANAN');
            $sheet->setCellValue('C7', 'POKOK');
            $sheet->setCellValue('D7', 'WAJIB');
            $sheet->setCellValue('E7', 'MANASUKA');
            $sheet->setCellValue('F6', 'JUMLAH SIMPANAN');
            $sheet->setCellValue('G6', 'PINJAMAN');
            $sheet->setCellValue('G7', 'JUMLAH PINJAMAN');
            $sheet->setCellValue('H7', 'PEMBAYARAN');
            $sheet->setCellValue('H8', 'POKOK');
            $sheet->setCellValue('I8', 'JASA');
            $sheet->setCellValue('J8', 'PROVISI');
            $sheet->setCellValue('K8', 'PENALTY');
            $sheet->setCellValue('L6', 'SALDO PINJAMAN');
            $sheet->setCellValue('M6', 'KETERANGAN');
            $sheet->setCellValue('M7', 'Jumlah Cicilan');
            $sheet->setCellValue('N7', 'Sisa Cicilan');

            $sheet->setCellValue('A9', 'Saldo Per 01 Januari '.$tahun);

            $sheet->setCellValue('A10', 'January');
            $sheet->setCellValue('A11', 'February');
            $sheet->setCellValue('A12', 'March');
            $sheet->setCellValue('A13', 'April');
            $sheet->setCellValue('A14', 'May');
            $sheet->setCellValue('A15', 'June');
            $sheet->setCellValue('A16', 'July');
            $sheet->setCellValue('A17', 'August');
            $sheet->setCellValue('A18', 'September');
            $sheet->setCellValue('A19', 'October');
            $sheet->setCellValue('A20', 'November');
            $sheet->setCellValue('A21', 'December');

            $sheet->setCellValue('B10', "Potongan Koperasi Jan'".substr($tahun,2));
            $sheet->setCellValue('B11', "Potongan Koperasi Feb'".substr($tahun,2));
            $sheet->setCellValue('B12', "Potongan Koperasi Mar'".substr($tahun,2));
            $sheet->setCellValue('B13', "Potongan Koperasi Apr'".substr($tahun,2));
            $sheet->setCellValue('B14', "Potongan Koperasi May'".substr($tahun,2));
            $sheet->setCellValue('B15', "Potongan Koperasi Jun'".substr($tahun,2));
            $sheet->setCellValue('B16', "Potongan Koperasi Jul'".substr($tahun,2));
            $sheet->setCellValue('B17', "Potongan Koperasi Aug'".substr($tahun,2));
            $sheet->setCellValue('B18', "Potongan Koperasi Sep'".substr($tahun,2));
            $sheet->setCellValue('B19', "Potongan Koperasi Oct'".substr($tahun,2));
            $sheet->setCellValue('B20', "Potongan Koperasi Nov'".substr($tahun,2));
            $sheet->setCellValue('B21', "Potongan Koperasi Dec'".substr($tahun,2));

            $currentYear = date('Y-m-d', strtotime('1 January '.$tahun));
            $lastYear = date('Y-m-d', strtotime($currentYear.' -1 year'));
            
            // SALDO SIMPANAN 1 JANUARI
            $SaldoPokokTahunan = $m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
                ->where("date_created BETWEEN '".$lastYear."' AND '".$currentYear."'")
                ->where('status', 'diterima')
                ->where('jenis_deposit', 'pokok')
                ->where('idanggota', $member->iduser)
                ->get()->getResult()[0]->saldo;

            $SaldoWajibTahunan = $m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
                ->where("date_created BETWEEN '".$lastYear."' AND '".$currentYear."'")
                ->where('status', 'diterima')
                ->where('jenis_deposit', 'wajib')
                ->where('idanggota', $member->iduser)
                ->get()->getResult()[0]->saldo;

            $SaldoManasukaTahunan = $m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
                ->where("date_created BETWEEN '".$lastYear."' AND '".$currentYear."'")
                ->where('status', 'diterima')
                ->whereIn('jenis_deposit', ['manasuka', 'manasuka free'])
                ->where('idanggota', $member->iduser)
                ->get()->getResult()[0]->saldo;

            $sheet->setCellValue('C9', ($SaldoPokokTahunan)?$SaldoPokokTahunan:'0');
            $sheet->setCellValue('D9', ($SaldoWajibTahunan)?$SaldoWajibTahunan:'0');
            $sheet->setCellValue('E9', ($SaldoManasukaTahunan)?$SaldoManasukaTahunan:'0');
            $sheet->setCellValue('F9', '=SUM(C9:E9)');

            // JUMLAH PINJAMAN PER 1 JANUARI
            $pinjamanTahunan = $m_pinjaman->getPinjamanTahunan($member->iduser, $lastYear, $currentYear);
            
            $sheet->setCellValue('G9', ($pinjamanTahunan)?$pinjamanTahunan[0]->jumlah_pinjaman:'0');
            $sheet->setCellValue('L9', '=G9-SUM(H9:K9)');
            $sheet->setCellValue('M9', ($pinjamanTahunan)?$pinjamanTahunan[0]->jumlah_cicilan:'0');
            $sheet->setCellValue('N9', ($pinjamanTahunan)?$pinjamanTahunan[0]->hitungan_cicilan:'0');

            // KOLOM PINJAMAN
            foreach(range('C', 'E') as $column){
                $row = 10;
                
                if ($column == 'C') {
                    $jenis_deposit = ['pokok'];
                } elseif ($column == 'D') {
                    $jenis_deposit = ['wajib'];
                } elseif ($column == 'E') {
                    $jenis_deposit = ['manasuka', 'manasuka free'];
                }

                for ($i = 1; $i < 13; ++$i) {
                    $endDate = $tahun.'-'.$i.'-'.$setDay;
                    $startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));

                    $saldo = $m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
                        ->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                        ->where('status', 'diterima')
                        ->whereIn('jenis_deposit', $jenis_deposit)
                        ->where('idanggota', $member->iduser)
                        ->get()->getResult();

                    $sheet->setCellValue($column.$row, ($saldo)?$saldo[0]->saldo:'');
                    $row++;
                }
            }
        
            // SUM JUMLAH SIMPANAN
            for ($i = 10; $i < 22; ++$i) {
                $prev_row = $i - 1;	
                $sheet->setCellValue("F".$i, '=F'.$prev_row.'+SUM(C'.$i.':E'.$i.')');
            }

            // JUMLAH PINJAMAN
            $row = 10;
            for ($i = 1; $i < 13; ++$i) {
                $endDate = $tahun.'-'.$i.'-'.$setDay;
                $startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));

                $jum_pinjaman = $m_pinjaman->select("SUM(nominal) as nominal, angsuran_bulanan, DATE(CONCAT(YEAR('".$startDate."'),'-',(bln_perdana - 1),'-',tanggal_bayar)) as tgl")
                    ->where('status BETWEEN 4 AND 5')
                    ->where("DATE(CONCAT(YEAR('".$startDate."'),'-',bln_perdana-1,'-',tanggal_bayar)) BETWEEN '".$startDate."' AND '".$endDate."'")
                    ->where('idanggota', $member->iduser)
                    ->groupBy('tgl')
                    ->get()->getResult();

                $sheet->setCellValue('G'.$row, ($jum_pinjaman)?$jum_pinjaman[0]->nominal:'0');
                $sheet->setCellValue('L'.$row, '=IFERROR(L'.($row-1).'+G'.$row.'-H'.$row.', L'.($row-1).')');
                $sheet->setCellValue('M'.$row, ($jum_pinjaman)?$jum_pinjaman[0]->angsuran_bulanan:'=M'.($row-1));
                $row++;
            }

            // CICILAN BULANAN
            foreach(range('H', 'J') as $column){
                $row = 10;

                if ($column == 'H') {
                    $tipe = 'nominal';
                } elseif ($column == 'I') {
                    $tipe = 'bunga';
                } elseif ($column == 'J') {
                    $tipe = 'provisi';
                }

                for ($i = 1; $i < 13; $i++) {					
                    $endDate = $tahun.'-'.$i.'-'.$setDay;
                    $startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));

                    $saldo = $m_cicilan->select('ROUND(SUM(tb_cicilan.'.$tipe.')) AS saldo')
                        ->join('tb_pinjaman', 'tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman')
                        ->where("tb_cicilan.date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                        ->where('idanggota', $member->iduser)
                        ->get()->getResult();

                    $sheet->setCellValue($column.$row, ($saldo)?$saldo[0]->saldo:'0');
                    $row++;
                }
            }

            // SISA CICILAN
            $pinjaman_aktif = $m_pinjaman->where('status', '4')
                ->where('idanggota', $member->iduser)
                ->get()->getResult();

            $row = 10;
            if ($pinjaman_aktif) {
                for ($i = 1; $i < 13; ++$i) {
                    $startDate = date('Y-m-d', strtotime($pinjaman_aktif[0]->date_updated));
                    $endDate = $tahun.'-'.$i.'-'.$setDay;

                    $cicilan = $m_cicilan->select('COUNT(idcicilan) AS hitung')
                        ->join('tb_pinjaman', 'tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman')
                        ->where("tb_cicilan.date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                        ->where('idanggota', $member->iduser)
                        ->get()->getResult();
    
                    $sheet->setCellValue("N".$row, "=M".$row."-".$cicilan[0]->hitung);
                    $row++;
                }
            }

            // SUM SEMUA
            $sheet->setCellValue("A24", "SALDO PER 31 DECEMBER ".$tahun);
            $sheet->setCellValue("C24", "=SUM(C9:C23)");
            $sheet->setCellValue("D24", "=SUM(D9:D23)");
            $sheet->setCellValue("E24", "=SUM(E9:E23)");
            $sheet->setCellValue("F24", "=SUM(F9:F23)");
            $sheet->setCellValue("G24", "=SUM(G9:G23)");
            $sheet->setCellValue("H24", "=SUM(H9:H23)");
            $sheet->setCellValue("I24", "=SUM(I9:I23)");
            $sheet->setCellValue("J24", "=SUM(J9:J23)");
            $sheet->setCellValue("K24", "=SUM(K9:K23)");
            $sheet->setCellValue("L24", "=G24-H24");

            // STYLING
            $defStyle = [
                'alignment' => [
                    'wrapText' => true
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];

            $sheet->getStyle('A6:N24')->applyFromArray($defStyle);
            $sheet->getStyle('A6:N8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle('A6:N8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A6:N8')->getFont()->setBold(true);
            $sheet->getStyle('A24:N24')->getFont()->setBold(true);

            foreach (range('B', 'N') as $col) {
               $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $sheet->mergeCells('A6:A8');
            $sheet->mergeCells('B6:B8');
            $sheet->mergeCells('C6:E6');
            $sheet->mergeCells('C7:C8');
            $sheet->mergeCells('D7:D8');
            $sheet->mergeCells('E7:E8');
            $sheet->mergeCells('F6:F8');
            $sheet->mergeCells('G6:K6');
            $sheet->mergeCells('G7:G8');
            $sheet->mergeCells('H7:K7');
            $sheet->mergeCells('L6:L8');
            $sheet->mergeCells('M6:N6');
            $sheet->mergeCells('M7:M8');
            $sheet->mergeCells('N7:N8');
            $sheet->mergeCells('A9:B9');
            $sheet->mergeCells('A24:B24');
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="REKENING KORAN PER TAHUN '.$tahun.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    /**
     * Print annual summary report (Excel export)
     */
    public function print_rekap_tahunan()
    {
        $m_user = model(M_user::class);
        $tahun = request()->getPost('tahun');

        if ($tahun == '0') {
            $this->sendAlert('Belum memilih tahun laporan', 'warning');
            return redirect()->back();
        }

        $endDate = $tahun.'-'.date('m-d', strtotime("+1 day"));
        $startDate = date('Y-m-d', strtotime('-1 year', strtotime($endDate)));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Tahun '.$tahun);

        // Header kolom
        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('A1', 'NO. URUT');

        $sheet->mergeCells('B1:B2');
        $sheet->setCellValue('B1', 'NAMA ANGGOTA KOPERASI');

        $sheet->mergeCells('C1:E1');
        $sheet->setCellValue('C1', 'SIMPANAN');
        $sheet->setCellValue('C2', 'POKOK');
        $sheet->setCellValue('D2', 'WAJIB');
        $sheet->setCellValue('E2', 'MANASUKA');

        $sheet->mergeCells('F1:F2');
        $sheet->setCellValue('F1', strtoupper('SALDO SIMPANAN PER ' . date('d F Y')));

        $sheet->mergeCells('G1:H1');
        $sheet->setCellValue('G1', 'PINJAMAN');
        $sheet->setCellValue('G2', 'JUMLAH PINJAMAN');
        $sheet->setCellValue('H2', 'PEMBAYARAN POKOK');

        $sheet->mergeCells('I1:I2');
        $sheet->setCellValue('I1', strtoupper('SALDO PINJAMAN PER ' . date('d F Y')));

        $sheet->mergeCells('J1:K1');
        $sheet->setCellValue('J1', 'KETERANGAN');
        $sheet->setCellValue('J2', 'JUMLAH CICILAN');
        $sheet->setCellValue('K2', 'SISA CICILAN');

        $sheet->mergeCells('L1:L2');
        $sheet->setCellValue('L1', 'NOMOR ANGGOTA');

        // Styling header
        $sheet->getStyle('A1:L2')->getFont()->setBold(true);
        $sheet->getStyle('A1:L2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:L2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $list_pegawai = [
            'BUT' => $m_user->where('flag', 1)->where('instansi', 'YPT')->where('idgroup', 4)->get()->getResult(),
            'GIAT' => $m_user->where('flag', 1)->where('instansi', 'GIAT')->where('idgroup', 4)->get()->getResult(),
            'Telkom' => $m_user->where('flag', 1)->where('instansi', 'Telkom')->where('idgroup', 4)->get()->getResult(),
            'Trengginas Jaya' => $m_user->where('flag', 1)->where('instansi', 'Trengginas Jaya')->where('idgroup', 4)->get()->getResult(),
            'Telkom University' => $m_user->where('flag', 1)->where('instansi', 'Telkom University')->where('idgroup', 4)->get()->getResult(),
            'YPT' => $m_user->where('flag', 1)->where('instansi', 'YPT')->where('idgroup', 4)->get()->getResult()
        ];

        $row = 3;
        $number = 1;

        $m_monthly_report = model(M_monthly_report::class);
        $m_deposit = model(M_deposit::class);
        $m_cicilan = model(M_cicilan::class);

        foreach ($list_pegawai as $instansi => $pegawai_list) {
            // Baris pembatas instansi
            $sheet->mergeCells('A' . $row . ':L' . $row);
            $sheet->setCellValue('A' . $row, strtoupper('DAFTAR ' . $instansi));
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $row++;
            
            foreach ($pegawai_list as $index => $pegawai) {
                // Perhitungan Data
                $cicilan = $m_monthly_report->getHitunganPinjaman2($pegawai->iduser, $startDate, $endDate);
                $pinjaman = $m_monthly_report->getPinjamanAktifByAnggota($pegawai->iduser, $startDate, $endDate);
                
                if ($pinjaman){
                    $count_cicilan = $m_monthly_report->countCicilanByPinjaman($pinjaman[0]->idpinjaman, $startDate, $endDate)[0]->hitung;
                } else {
                    $count_cicilan = " - ";
                }

                $simpanan_pokok = $m_deposit->select("SUM(cash_in)-SUM(cash_out) AS nominal")
                    ->where('status', 'diterima')
                    ->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                    ->where('jenis_deposit', 'pokok')
                    ->where('idanggota', $pegawai->iduser)
                    ->get()
                    ->getResult()[0]
                    ->nominal;

                $simpanan_wajib = $m_deposit->select("SUM(cash_in)-SUM(cash_out) AS nominal")
                    ->where('status', 'diterima')
                    ->whereNotIn('deskripsi', ['saldo wajib'])
                    ->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                    ->where('jenis_deposit', 'wajib')
                    ->where('idanggota', $pegawai->iduser)
                    ->get()
                    ->getResult()[0]
                    ->nominal;

                $simpanan_manasuka = $m_deposit->select("SUM(cash_in)-SUM(cash_out) AS nominal")
                    ->where('status', 'diterima')
                    ->whereNotIn('deskripsi', ['saldo manasuka'])
                    ->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                    ->whereIn('jenis_deposit', ['manasuka', 'manasuka free'])
                    ->where('idanggota', $pegawai->iduser)
                    ->get()
                    ->getResult()[0]
                    ->nominal;

                if($pinjaman){
                    $cicilan_dalam = $m_cicilan->select("SUM(nominal) as nominal")
                        ->where('idpinjaman', $pinjaman[0]->idpinjaman)
                        ->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                        ->get()->getResult()[0]
                        ->nominal;

                    $cicilan_luar = $m_cicilan->select("SUM(nominal) as nominal")
                        ->where('idpinjaman', $pinjaman[0]->idpinjaman)
                        ->where("date_created < '".$startDate."'")
                        ->get()->getResult()[0]
                        ->nominal;

                    $jumlah_pinjaman = $pinjaman[0]->nominal - $cicilan_luar;
                } else {
                    $cicilan_dalam = 0;
                    $cicilan_luar = 0;
                    $jumlah_pinjaman = 0;
                }
                
                $manasuka = $m_monthly_report->getSumSimpanan2($pegawai->iduser, $startDate, $endDate)[0]->nominal;  
                $p_pokok = ($cicilan)?$cicilan[0]->nominal:0;

                // Populate data
                $sheet->setCellValue('A'.$row, $number);
                $sheet->setCellValue('B'.$row, $pegawai->nama_lengkap);
                $sheet->setCellValue('C'.$row, $simpanan_pokok);
                $sheet->setCellValue('D'.$row, $simpanan_wajib);
                $sheet->setCellValue('E'.$row, $simpanan_manasuka);
                $sheet->setCellValue('F'.$row, ($simpanan_manasuka+$simpanan_wajib+$simpanan_pokok));
                $sheet->setCellValue('G'.$row, $jumlah_pinjaman);
                $sheet->setCellValue('H'.$row, $cicilan_dalam);
                $sheet->setCellValue('I'.$row, $jumlah_pinjaman-$cicilan_dalam);
                $sheet->setCellValue('J'.$row, ($pinjaman)?$pinjaman[0]->angsuran_bulanan:' - ');
                $sheet->setCellValue('K'.$row, ($count_cicilan != " - ")?$pinjaman[0]->angsuran_bulanan - $count_cicilan:' - ');
                $sheet->setCellValue('L'.$row, $pegawai->username);

                $row++;
                $number++;
            }
        }
        
        // Format currency
        $sheet->getStyle('C4:F' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('G4:H' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('I4:I' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
       
        // Set borders
        $sheet->getStyle('A1:L' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Auto size columns
        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Export
        $writer = new Xlsx($spreadsheet);
        $filename = 'REKAP_TAHUNAN_' . $tahun . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
