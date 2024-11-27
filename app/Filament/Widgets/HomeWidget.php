<?php

namespace App\Filament\Widgets;

use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\PenjualanItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
class HomeWidget extends BaseWidget
{
    use HasWidgetShield;
    protected function getStats(): array
    {

            $month = date('m') - 1;

            $dari= date('Y').'/'.$month.'/31';
            $sampai= date('Y').'/'.date('m').'/31';

            $pembelian_per_bulan = Pembelian::whereBetween('tgl_faktur',[$dari,$sampai])->sum('total');
            $penjualan_per_bulan = Penjualan::whereBetween('created_at',[$dari,$sampai])->sum('total');
            $laba= PenjualanItem::with('obat')->whereBetween('created_at',[$dari,$sampai])->get();
            $total_laba = 0;
            foreach($laba as $value){
                $total_laba += $value->obat->price * ($value->obat->margin / 100);

                // $total_laba = $value->obat->sum('margin');
            }

            return [
                Stat::make("Pembelian ".date('M Y'),'Rp. '. number_format($pembelian_per_bulan))
                ->description('Total Pembelian Bulan ini')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('warning'),

                Stat::make("Penjualan  ".date('M Y'),'Rp. '. number_format($penjualan_per_bulan) )
                ->description('Total Penjualan Bulan ini')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('info'),

                Stat::make("Laba ".date('M Y'), 'Rp. '.number_format($total_laba) )
                ->description('Total Laba Bulan ini')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('success'),
            ];

    }


}
