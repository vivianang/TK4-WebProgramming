@extends('adminlte::page')

@section('title', 'Dashboard')


@section('content_header')
    <h1>Welcome, {{auth()->user()->name}}</h1>
@stop 

@section('content')
<body>
    <br>
    <h4>Grafik total item penjualan per produk</h4>
    <canvas id="myChart"></canvas>
    <?php
    $kon = mysqli_connect("localhost","root","","tp4");
    $nama_produk= "";
    $jumlah=null;
    $sql="SELECT i.name, sum(ts.qty) as 'total' FROM `transaction_details` ts inner join transactions t on t.id = ts.transaction_id   INNER join items i on i.id = ts.item_id group by i.name";
    $hasil=mysqli_query($kon,$sql);

    while ($data = mysqli_fetch_array($hasil)) {
        $produk=$data['name'];
        $nama_produk .= "'$produk'". ", ";
        $jum=$data['total'];
        $jumlah .= "$jum". ", ";

    }
    ?>

@section('js')
<script src="js/Chart.js"></script>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php echo $nama_produk; ?>],
            datasets: [{
                label:'Data Penjualan Per item ',
                backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)','rgb(175, 238, 239)'],
                borderColor: ['rgb(255, 99, 132)'],
                data: [<?php echo $jumlah; ?>]
            }]
        },

        // Configuration options go here
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
</script>
@stop

</body>
   
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop