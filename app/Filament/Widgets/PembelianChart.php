<?php

namespace App\Filament\Widgets;

use App\Models\Obat;
use Filament\Widgets\ChartWidget;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\PenjualanItem;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
class PembelianChart extends ChartWidget
{
    use HasWidgetShield;
    protected static ?string $heading = 'Laporan Pembelian';
    // protected static ?int $sort = 1;
   protected static ?string $pollingInterval = '10s';
    protected static string $color = 'warning';
    protected function getData(): array
    {

        $data = Trend::model(Pembelian::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'Penjualan Per Bulan',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                'backgroundColor' => 'warning',
            ],
        ],
        'labels' => $data->map(fn (TrendValue$value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
