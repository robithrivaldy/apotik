
<center>
<br>
<h1>LAPORAN OBAT EXPIRED {{ $apotik->name }} </h1><br>
<b>{{ $apotik->address }}</b> <br>
<br><br>
<b>Obat Expired Mendatang</b>
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
            <th><b>PT</b></th>
            <th><b>STOCK</b></th>
            <th><b>TANGGAL EXPIRED</b></th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $value)

        <tr>
            <td  align="left" valign="top" >{{ $loop->iteration }}</td>
            <td  align="left" valign="top" >{{ $value->masterObat->name }}</td>
            <td  align="left" valign="top" >{{ $value->masterObat->satuan->name }}</td>
            <td  align="left" valign="top" >{{ $value->masterObat->pt->name }}</td>
            <td  align="left" valign="top" >{{ $value->stock}}</td>
            <td  align="left" valign="top" >{{ date('d, M Y', strtotime($value->tgl_expired)) }}</td>
        </tr>
        <tr>
            <td colspan="10"><br></td>
        </tr>

        @endforeach

    </tbody>
</table>
