<head>
	<script src="includes/sorttable.js"></script>
	<link href="includes/accordion.css" rel="stylesheet" type="text/css">
</head>

<?php 
	error_reporting(E_ALL);
ini_set('display_errors', 1);

	$username="root";
	$password="password";
	$host="localhost";
	$db="showmethemoney";
	
	$connection = new PDO("mysql:dbname=$db;host=$host",$username,$password);
	
	//get chart data
	$tags = "select dateCleared,detail,sum(amount) as total,tag from transaction_tags where tag <> 'INCOME' group by tag";
	$prepare = $connection->prepare($tags);
	$prepare->execute();
	$chartdata = $prepare->fetchall(PDO::FETCH_ASSOC);
	
	//get table data
	$sql = "select dateCleared,detail,amount,tag from transaction_tags where tag <> 'INCOME';";
	$prepare = $connection->prepare($sql);
	
	//if successful :: output
	if(!$prepare->execute()) die;
	$rows = $prepare->fetchall(PDO::FETCH_ASSOC);
	
	/*chart output*/
?>
<script src="includes/Chart.min.js"></script>
<canvas id="myChart" width="400" height="400"></canvas>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
ctx.canvas.width  = window.innerWidth;
  ctx.canvas.height = window.innerHeight;
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        //labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
		labels: [<?php	foreach($chartdata as $tag) {
				echo("'".$tag["tag"]."',");
			}
			?>],
        datasets: [{
            //label: 'Expenditure',
            data: [<?php	foreach($chartdata as $tag) {
				echo("'".$tag["total"]."',");
			}
			?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(75, 159, 64, 0.2)',
                'rgba(64, 64, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(75, 159, 64, 1)',
                'rgba(64, 64, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        legend: {
			display:false,
		}
    }
});
</script>


<div class="accordion">

    <div class="option">
      <input type="checkbox" id="toggle1" class="toggle" >
      <label class="title" for="toggle1">Untagged Transactions</label>
      <div class="content">

<?php
	//get untagged data
	$untagged = "select * from transactions a 
NATURAL LEFT JOIN transaction_tags b
WHERE b.id is null

order by detail;";

	$untagged = $connection->prepare($untagged);
	$untagged->execute();
	$untagged = $untagged->fetchall(PDO::FETCH_ASSOC);

	echo '<table class="sortable" id="untagged-data"><thead>';
	$head = array_keys($untagged[0]);
	foreach($head as $th){
		echo"<th>".$th."</th>";
	}
	echo "</thead><tbody>";
	foreach ($untagged as $row) {
		echo "<tr>";
		foreach($row as $key=>$value) {
			echo "<td>".$value."</td>";
		}
		echo "</tr>";
	}
	echo "</tbody></table>";
?>
</div>
    </div>

    <div class="option">
      <input type="checkbox" id="toggle2" class="toggle" />
      <label class="title" for="toggle2">Tagged Transactions</label>
      <div class="content"
<?php	
	/*table output*/
	echo "<h2>&nbsp;</h2>";
	echo "<table class='sortable'>".PHP_EOL;
	echo "<thead><th>Date</th><th>Detail</th><th>Amount</th><th>Tag</th></thead>";
	foreach($rows as $row) {
		//var_dump($row);
		echo "<tr>".PHP_EOL;
		foreach ($row as $key=>$value) {
			echo "<td>".$value."</td>";
		}
		echo "</tr>".PHP_EOL;
	}
	echo "</table>".PHP_EOL;
	
	?>
	
     </div>
    </div>
  </div>
</div>
