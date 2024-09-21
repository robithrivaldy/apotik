{{-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> --}}
<!-- start invoice print -->
<html>

<head>
    <title>Invoice</title>
    <style type="text/css">
        body {

            margin: 0;
            font-size: 24px;
            line-height: 30px;

        }

        i {
            font-size: 20px;
        }
    </style>
    <script script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- <script>
        $(document).ready(function() {
            // $("#print").click();
            // myFunction();
            window.print();
            window.close();
        });
    </script> --}}
</head>

<body>
    <table cellpadding="0" cellspacing="0">
        <table>
            <tr>
                <td colspan="2" align="center"><b>{{ $data_apotik->name }}</b></td>
            </tr>

            <tr>
                <td colspan="2" align="center"><i>{{ $data_apotik->address }}</i></td>
            </tr>

            <tr>
                <td colspan="2" align="center"><i>{{ $data_apotik->phone }}</i></td>
            </tr>

            <tr>
                <td><i>Customer {{ $data_penjualan->customer }}</i> </td>
                <td align="right"># {{ $data_penjualan->id }}</td>
            </tr>
            {{-- <tr>
                <td><b>Mob.No:</b> 9726820585 </td>
                <td align="right"><b>Bill Dt.:</b> </td>
            </tr> --}}

            <tr class="heading" style="background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">
                <td>
                    PRODUK
                </td>

                <td align="right">
                    TOTAL
                </td>
            </tr>

            @foreach ($data_penjualan_item as $value)
                <tr class="itemrows">
                    <td>
                        {{ $value->obat->masterObat->name }}
                        <br>
                        <i>@ {{ $value->qty }}</i>
                        <br>
                        <i>Rp {{ number_format($value->price, 0) }}</i>
                        <br><br>

                    </td>

                    <td align="right">
                        Rp. {{ number_format($value->total, 0) }}
                    </td>
                </tr>
            @endforeach

            <tr class="subtotal">
                <td>Subtotal</td>
                <td align="right">
                    <b> Rp. {{ number_format($data_penjualan->subtotal, 0) }}</b>
                </td>
            </tr>
            <tr class="discount">
                <td>Discount</td>
                <td align="right">
                    <b> Rp. {{ number_format($data_penjualan->discount, 0) }}</b>
                </td>
            </tr>
            <tr class="total">
                <td>Total</td>
                <td align="right">
                    <b> Rp. {{ number_format($data_penjualan->total, 0) }}</b>
                </td>
            </tr>

            <tr>
                <td colspan="2" align="center"> {{ $data_apotik['keterangan'] }}</td>
            </tr>

        </table>
    </table>


</body>

</html>
