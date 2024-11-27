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
class PenjualanChart extends ChartWidget
{
    use HasWidgetShield;
    protected static ?string $heading = 'Laporan Penjualan';
    // protected static ?int $sort = 3;
    protected static string $color = 'success';
    protected function getData(): array
    {

        $data = Trend::model(Penjualan::class)
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
                'backgroundColor' => 'info',
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
