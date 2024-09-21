
<center>
<br>
<h1>LAPORAN PEMBELIAN{{ $apotik->name }} </h1><br>
<b>{{ $apotik->address }}</b> <br>
<br><br>
<b> Dari : {{ date('d, M Y', strtotime($dari)) }}</b><br>
<b> Sampai : {{ date('d, M Y', strtotime($sampai)) }}</b>


<br><br>


</center>
<table border="0" style="border-collapse: collapse;">


    <thead>
        <tr>
            <th><b>NO</b></th>
            <th><b>FAKTUR</b></th>
            <th><b>TGL FAKTUR</b></th>
            <th><b>TGL JATUH TEMPO</b></th>
            <th><b>SUPPLIER</b></th>
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
            <td  align="left" valign="top" >{{ $value->no_faktur }}</td>
            <td  align="left" valign="top" >{{ $value->tgl_faktur }}</td>
            <td  align="left" valign="top" >{{ $value->tgl_jatuh_tempo }}</td>
            <td  align="left" valign="top" >{{ $value->supplier->name }}</td>
            <td colspan="5" align="left" valign="top" >Rincian Obat</td>
        </tr>

        @foreach ($value->obat as $obat)
            <tr>
                <td colspan="5"></td>
                <td>
                    {{ $obat->masterObat->name }}
                </td>
                <td>
                    {{ $obat->masterObat->satuan->name }}
                </td>
                <td>
                   Rp {{ number_format($obat->pembelian_price,0) }}
                </td>

                <td>
                    {{ $obat->pembelian_stock }}
                </td>

                <td>
                   Rp {{ number_format($obat->pembelian_total,0) }}
                </td>
            </tr>

        @endforeach

        <tr>

            <td colspan="9" align="right">SUBTOTAL</td>
            <td > Rp {{ number_format($value->total,0) }}</td>
        </tr>
        <tr>
            <td colspan="10"><br></td>
        </tr>


        @php
        $total= $value->sum('total')
        @endphp

        @endforeach

        <br><br>
        <tr>
            <td colspan="9" align="right">GRAND TOTAL PEMBELIAN</td>
            <td > Rp {{ number_format($total,0) }}</td>
        </tr>
        <tr>
            <td colspan="10"><br></td>
        </tr>
    </tbody>
</table>
