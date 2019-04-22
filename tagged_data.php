<?php 
	error_reporting(E_ALL);
ini_set('display_errors', 1);

	$username="root";
	$password="password";
	$host="localhost";
	$db="showmethemoney";
	
	$connection = new PDO("mysql:dbname=$db;host=$host",$username,$password);
	
	//get chart data
	$tags = "select dateCleared,detail,sum(amount) as total,tag from transactions,keyword_tag where detail like CONCAT('%',keyword_tag.keyword,'%') group by tag;";
	$prepare = $connection->prepare($tags);
	$prepare->execute();
	$chartdata = $prepare->fetchall(PDO::FETCH_ASSOC);
	
	
	//compile data
	//$sql = "select dateCleared,detail,amount,tag from transactions";
	$sql = "select dateCleared,detail,amount,tag from transactions,keyword_tag where detail like CONCAT('%',keyword_tag.keyword,'%');";
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
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
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
        }
    }
});
</script>

<?php
	
	/*table output*/
	echo "<table>".PHP_EOL;
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
