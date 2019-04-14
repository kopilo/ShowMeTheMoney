<?php require_once('includes/header.php'); ?>
<script src="includes/sorttable.js" type="text/javascript"></script>
<body id="transactions">
<table class="sortable">
<thead>
	<th>Date</th>
	<th>Details</th>
	<th>Amount</th>
</thead>
<tbody>
<?php
	$username="root";
	$password="password";
	$host="localhost";
	$db="showmethemoney";
	
	$connection = new PDO("mysql:dbname=$db;host=$host",$username,$password);
	$sql = "SELECT * from transactions";
	$query = $connection->prepare($sql);
	$query->execute();
	$rows = $query->fetchAll();
	foreach($rows as $row) {
		?>
			<tr>
			<td><?php echo $row["dateCleared"]; ?></td>
			<td><?php echo $row["details"];?></td>
			<td class="money"><?php echo $row["amount"];?></td>
		<?php
	}
	
?>
</tbody>
</table>
<?php require_once('includes/footer.php'); ?>
