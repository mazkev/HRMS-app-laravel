<?php

namespace App\Services;

class TaxBpjsCalculator
{
    /**
     * Calculate PPh 21 using TER (Tarif Efektif Rata-Rata 2024)
     */
    public static function calculatePph21(float $grossIncome, string $ptkpStatus = 'TK/0'): float
    {
        $ptkpStatus = strtoupper(trim($ptkpStatus));

        // Determine TER Category
        // Kategori A: TK/0, TK/1, K/0
        // Kategori B: TK/2, TK/3, K/1, K/2
        // Kategori C: K/3
        $rate = 0.0;

        if (in_array($ptkpStatus, ['TK/0', 'TK/1', 'K/0'])) {
            // TER Kategori A
            if ($grossIncome <= 5400000) $rate = 0.00;
            elseif ($grossIncome <= 5650000) $rate = 0.0025;
            elseif ($grossIncome <= 5950000) $rate = 0.005;
            elseif ($grossIncome <= 6300000) $rate = 0.0075;
            elseif ($grossIncome <= 6750000) $rate = 0.01;
            elseif ($grossIncome <= 7500000) $rate = 0.0125;
            elseif ($grossIncome <= 8550000) $rate = 0.015;
            elseif ($grossIncome <= 9650000) $rate = 0.0175;
            elseif ($grossIncome <= 10050000) $rate = 0.02;
            elseif ($grossIncome <= 10350000) $rate = 0.0225;
            elseif ($grossIncome <= 10700000) $rate = 0.025;
            elseif ($grossIncome <= 11050000) $rate = 0.03;
            elseif ($grossIncome <= 11600000) $rate = 0.035;
            elseif ($grossIncome <= 12500000) $rate = 0.04;
            elseif ($grossIncome <= 13750000) $rate = 0.05;
            elseif ($grossIncome <= 15100000) $rate = 0.06;
            elseif ($grossIncome <= 16950000) $rate = 0.07;
            elseif ($grossIncome <= 19750000) $rate = 0.08;
            elseif ($grossIncome <= 24100000) $rate = 0.09;
            elseif ($grossIncome <= 26450000) $rate = 0.10;
            else $rate = 0.12;
        } elseif (in_array($ptkpStatus, ['TK/2', 'TK/3', 'K/1', 'K/2'])) {
            // TER Kategori B
            if ($grossIncome <= 6200000) $rate = 0.00;
            elseif ($grossIncome <= 6500000) $rate = 0.0025;
            elseif ($grossIncome <= 6850000) $rate = 0.005;
            elseif ($grossIncome <= 7300000) $rate = 0.0075;
            elseif ($grossIncome <= 9200000) $rate = 0.01;
            elseif ($grossIncome <= 10750000) $rate = 0.015;
            elseif ($grossIncome <= 12550000) $rate = 0.025;
            elseif ($grossIncome <= 14950000) $rate = 0.04;
            elseif ($grossIncome <= 17600000) $rate = 0.06;
            elseif ($grossIncome <= 21450000) $rate = 0.08;
            else $rate = 0.10;
        } else {
            // TER Kategori C (K/3)
            if ($grossIncome <= 6600000) $rate = 0.00;
            elseif ($grossIncome <= 6950000) $rate = 0.0025;
            elseif ($grossIncome <= 7350000) $rate = 0.005;
            elseif ($grossIncome <= 7800000) $rate = 0.0075;
            elseif ($grossIncome <= 8850000) $rate = 0.01;
            elseif ($grossIncome <= 12200000) $rate = 0.02;
            elseif ($grossIncome <= 15600000) $rate = 0.04;
            elseif ($grossIncome <= 20150000) $rate = 0.07;
            else $rate = 0.09;
        }

        return round($grossIncome * $rate, 2);
    }

    /**
     * Calculate BPJS Kesehatan (1% Employee Deduction, Max Cap Rp 12.000.000)
     */
    public static function calculateBpjsKesehatan(float $basicSalary): float
    {
        $cappedSalary = min($basicSalary, 12000000.0);
        return round($cappedSalary * 0.01, 2);
    }

    /**
     * Calculate BPJS Ketenagakerjaan (JHT 2% + JP 1% Employee Deduction, JP Capped at Rp 10.042.300)
     */
    public static function calculateBpjsTk(float $basicSalary): float
    {
        $jht = $basicSalary * 0.02;
        $jp = min($basicSalary, 10042300.0) * 0.01;
        return round($jht + $jp, 2);
    }
}
