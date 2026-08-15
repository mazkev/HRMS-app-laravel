<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Export Attendance Logs to CSV
     */
    public function exportAttendanceCsv(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $attendances = Attendance::with(['user.department'])
            ->where('date', $date)
            ->orderBy('time_in')
            ->get();

        $fileName = 'rekap_absensi_' . $date . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return new StreamedResponse(function () use ($attendances) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($handle, [
                'NIK',
                'Nama Karyawan',
                'Departemen',
                'Jabatan',
                'Tanggal',
                'Jam Masuk',
                'Jam Pulang',
                'Status',
                'Jarak GPS (Meter)',
                'Dalam Radius Kantor',
                'Catatan',
            ]);

            foreach ($attendances as $att) {
                fputcsv($handle, [
                    $att->user->nik ?? '-',
                    $att->user->name ?? '-',
                    $att->user->department->name ?? '-',
                    $att->user->position ?? '-',
                    $att->date->format('Y-m-d'),
                    $att->time_in ?? '-',
                    $att->time_out ?? '-',
                    $att->status,
                    $att->distance_meters ?? '-',
                    $att->is_office_radius ? 'Ya' : 'Tidak',
                    $att->notes ?? '-',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export Payroll Summary to CSV
     */
    public function exportPayrollCsv(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $payrolls = Payroll::with(['user.department'])
            ->where('period_month', $month)
            ->get();

        $fileName = 'rekap_payroll_' . $month . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        return new StreamedResponse(function () use ($payrolls) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'NIK',
                'Nama Karyawan',
                'Departemen',
                'Periode',
                'Gaji Pokok',
                'Tunjangan',
                'Potongan Terlambat',
                'Potongan Lain',
                'Total Gaji Bersih',
                'Total Hari Hadir',
                'Total Hari Telat',
                'Status Pembayaran',
            ]);

            foreach ($payrolls as $p) {
                fputcsv($handle, [
                    $p->user->nik ?? '-',
                    $p->user->name ?? '-',
                    $p->user->department->name ?? '-',
                    $p->period_month,
                    $p->basic_salary,
                    $p->allowances,
                    $p->late_deduction,
                    $p->other_deductions,
                    $p->net_salary,
                    $p->total_present_days,
                    $p->total_late_days,
                    $p->status,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
