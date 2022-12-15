<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/datatables.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/datatables.min.js"></script>
<!-- <body style="background-color:lightgrey;"> -->
<H1>Toko Ko Achonk</H1>

Tanggal : <input type="date" id="tanggal" value="<?=$_GET["tanggal"]?>">

<?php

$dbName = "C:\work\koachonk\data_MDA_A.mdb";
$pdo = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=".$dbName.";Uid=; Pwd=;");
$stmt = $pdo->prepare('SELECT * FROM Transaksi WHERE TransactionDate = ? ORDER BY TransactionDate DESC');
if(isset($_GET["tanggal"])){
  $stmt->execute([$_GET["tanggal"]]);
}else{
  $tanggal = date("Y-m-d");
  $stmt->execute([$tanggal]);
}
$data = [];
while ($row = $stmt->fetch())
{
  // echo $row["namabarang"]." ".$row["unitjual"]." ".$row["modalbeli"]." ".$row["Hargajual"]." ".$row["modalbelipokok"]." ".$row["hargajual2"]." ".$row["unitjual1"]." ".$row["TransactionDate"];
  // echo "<br>";
  if(key_exists($row["namabarang"], $data)){
    $data[$row["namabarang"]]["qty"] += (int) $row["unitjual"];
  }else{
    $data[$row["namabarang"]] = [
      "hargabeli" => (int) $row["modalbeli"],
      "hargajual" => (int) $row["Hargajual"],
      "qty" =>(int) $row["unitjual"],
    ];
  }
}
?>
<table id="table" class="table table-striped table-border" style="width:100%"">
<thead>
  <tr>
    <th>Nama Barang</th>
    <th>Harga Beli </th>
    <th>Harga Jual</th>
    <th>Quantity </th>
    <th>Total Beli </th>
    <th>Total Jual</th>
    <th>Profit</th>
  </tr>
</thead>
<tbody>
  <?php
  $total = [
    "beli" => 0,
    "jual" => 0,
    "profit" =>0
  ];
  foreach ($data as $key => $value){
    echo "<tr>
      <td>".$key."</td>
      <td>".$value["hargabeli"]."</td>
      <td>".$value["hargajual"]."</td>
      <td>".$value["qty"]."</td>
      <td>".$value["hargabeli"]*$value["qty"]."</td>
      <td>".$value["hargajual"]*$value["qty"]."</td>
      <td>".($value["hargajual"] - $value["hargabeli"])*$value["qty"]."</td>
      </tr>";
      $total["beli"] += $value["qty"]*$value["hargabeli"];
      $total["jual"] += $value["qty"]*$value["hargajual"];
      $total["profit"] += ($value["hargajual"] - $value["hargabeli"])*$value["qty"];
  }
  ?>
</tbody>
</table>
<table>
  <tr>
<?php
echo "<td>Total Modal</td> <td>:</td><td>". number_format($total["beli"], 2)."</td></tr>";
echo "<td>Total Jual</td> <td>:</td><td>". number_format($total["jual"], 2)."</td></tr>";
echo "<td>Total Profit</td> <td>:</td><td>". number_format($total["profit"], 2)."</td></tr>";
?>
</table>
<script>
$(document).ready(function () {
    $('#table').DataTable({
      columns:[
        null,
        null,
        {
          render: $.fn.dataTable.render.number(',', '.', 0, ''),
        },  
        {
          render: $.fn.dataTable.render.number(',', '.', 0, ''),
        },  
        {
          render: $.fn.dataTable.render.number(',', '.', 0, ''),
        },  
        {
          render: $.fn.dataTable.render.number(',', '.', 0, ''),
        },  
        {
          render: $.fn.dataTable.render.number(',', '.', 0, ''),
        },  
      ]
    });

});
$('#tanggal').change(function() {
  window.location.href += "?tanggal="+$(this).val();
});
</script>
