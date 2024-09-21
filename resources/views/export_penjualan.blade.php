
<center>
<br>
<h1>LAPORAN PEMBELIAN {{ $apotik->name }} </h1><br>
<b>{{ $apotik->address }}</b> <br>
<br><br>
<b> Dari : {{ date('d, M Y', strtotime($dari)) }}</b><br>
<b> Sampai : {{ date('d, M Y', strtotime($sampai)) }}</b>


<br><br>

<style>
    th{
        font-weight: bold;
    }
</style>
</center>
<table style="border-collapse: collapse;">


    <thead>
        <tr>
            <th><b>NO</b></th>
            <th><b>TANGGAL</b></th>
            <th><b>OBAT</b></th>
            <th><b>SATUAN</b></th>
            <th><b>HARGA</b></th>
            <th><b>QTY</b></th>
            <th><b>TOTAL</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $value)

        <tr>
            <td  align="left" valign="top" >{{ $loop->iteration }}</td>
            <td  align="left" valign="top" >{{ date('d, M Y', strtotime($value->created_at)) }}</td>
            <td colspan="5" align="left" valign="top" >RINCIAN OBAT</td>
        </tr>

        @foreach ($value->items as $item)
            <tr>
                <td colspan="2"></td>
                <td>
                    {{ $item->obat->masterObat->name }}
                </td>
                <td>
                    {{ $item->obat->masterObat->satuan->name }}
                </td>
                <td>
                   Rp {{ number_format($item->price,0) }}
                </td>

                <td>
                    {{ $item->qty }}
                </td>

                <td>
                   Rp {{ number_format($item->total,0) }}
                </td>
            </tr>

            @php
            $total= $item->sum('total');
            // $modal = $item->obat->sum('pembelian_price');
             @endphp
        @endforeach
        <tr>
            <td colspan="6" align="right">SUB TOTAL</td>
            <td > Rp {{ number_format($value->subtotal,0) }}</td>
        </tr>


        <tr>

            <td colspan="6" align="right">DISCOUNT</td>
            <td > Rp {{ number_format($value->discount,0) }}</td>
        </tr>

        <tr>
            <td colspan="6" align="right">TOTAL</td>
            <td > Rp {{ number_format($value->total,0) }}</td>
        </tr>
        <tr>
            <td colspan="10"><br></td>
        </tr>

        @endforeach

        <br><br>
        {{-- <tr>
            <td colspan="5" align="right">TOTAL PEMBELIAN</td>
            <td > Rp {{ number_format($modal,0) }}</td>
        </tr>
        <tr>
            <td colspan="6"><br></td>
        </tr> --}}
        <tr>
            <td colspan="6" align="right">TOTAL PENJUALAN</td>
            <td > Rp {{ number_format($total,0) }}</td>
        </tr>
        <tr>
            <td colspan="6"><br></td>
        </tr>
    </tbody>
</table>