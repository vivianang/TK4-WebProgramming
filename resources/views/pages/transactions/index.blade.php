@extends('adminlte::page')

@section('title', 'laporan penjualan dari setiap item barang yang dijual dan keuntungan yang didapatkan')

@section('content_header')
    <h1>Manage Transactions</h1>
@stop

@section('content')
    
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <table id="table_id" class="table table-striped display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product Name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total Price</th>
                            <th>Tanggal Transaksi</th>
                            <th>Customer Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $koneksi        = mysqli_connect("localhost", "root", "", "tp4");

                            $select         = mysqli_query($koneksi, "SELECT i.name as 'ProductName', ts.qty, ts.price, ts.total_price, ts.updated_at, u.name FROM `transaction_details` ts inner join transactions t on t.id = ts.transaction_id INNER join items i on i.id = ts.item_id inner join customers c on c.id = t.customer_id inner join users u on u.id = c.user_id");

                            $no = 1;

                            //melakukan perualangan data dengan while
                            while($data= mysqli_fetch_array($select)){
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $data['ProductName']; ?></td>
                            <td><?php echo $data['qty']; ?></td>
                            <td><?php echo $data['price']; ?></td>
                            <td><?php echo $data['total_price']; ?></td>
                            <td><?php echo $data['updated_at']; ?></td>
                            <td><?php echo $data['name']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>


    </div>

<div class="card">
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
@stop

@section('css')
@stop

@section('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js">
    </script>

    <script>
        $(document).ready(function () {
            $('#table_id').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            })
        });

    </script>
@stop