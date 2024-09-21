
<center>
<br>
<h1>LAPORAN STOCK OPANAME {{ $apotik->name }} </h1><br>
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
            <th><b>OBAT</b></th>
            <th><b>SATUAN</b></th>
            <th><b>STOCK AWAL</b></th>
            <th><b>STOCK AKHIR</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $value)

        <tr>
            <td  align="left" valign="top" >{{ $loop->iteration }}</td>
            <td  align="left" valign="top" >{{ $value->obatColumn->masterObat->name }}</td>
            <td  align="left" valign="top" >{{ $value->obatColumn->masterObat->satuan->name }}</td>
            <td  align="left" valign="top" >{{ $value->stock_awal }}</td>
            <td  align="left" valign="top" >{{ $value->stock_akhir }}</td>
        </tr>
        <tr>
            <td colspan="10"><br></td>
        </tr>

        @endforeach

    </tbody>
</table>
