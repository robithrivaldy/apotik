
<center>
<br>
<h1>LAPORAN STOCK OPANAME {{ $apotik->name }} HALAMAN OBAT</h1><br>
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
            <th><b>NAMA OBAT</b></th>
            <th><b>SATUAN</b></th>
            <th><b>STOCK AWAL (PEMBELIAN STOCK)</b></th>
            <th><b>STOCK FISIK</b></th>
            <th><b>SELISIH</b></th>
            <th><b>EXPIRED DATE</b></th>
            <th><b>NO BATCH</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $value)

        <tr>
            <td  align="left" valign="top" >{{ $loop->iteration }}</td>
            <td  align="left" valign="top" >{{ $value->masterObat->name }}</td>
            <td  align="left" valign="top" >{{ $value->masterObat->satuan->name }}</td>
            <td  align="left" valign="top" >{{ $value->pembelian_stock }}</td>
            <td  align="left" valign="top" ></td>
            <td  align="left" valign="top" ></td>
            <td  align="left" valign="top" >{{ date('d M Y', strtotime($value->tgl_expired))}}</td>
            <td  align="left" valign="top" >{{ $value->no_batch }}</td>
        </tr>
        <tr>
            <td colspan="10"><br></td>
        </tr>

        @endforeach

    </tbody>
</table>
