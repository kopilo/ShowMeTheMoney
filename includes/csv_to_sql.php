<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); ini_set('display_errors',1);
error_reporting(E_ALL);
?>
<?php /*For parsing CSV to SQL*/
if(! $_GET['csvfile']) return;

//for suncorp
$csvdata = file ($_GET['csvfile']);
$account="";
$date ="";
$amount="";
foreach($csvdata as $line) {
	$data = str_getcsv($line);
	
	//skip headers
	//parse data for suncorp
	if(count($data) < 3) {
		if(count($data) > 2) {
			$account = $data[1];
		}
		continue;
	}
	//print_r($data);
	$date = $data[0];
	$details = $data[1];
	$amount = str_replace('$','',$data[2]);
	$balance = str_replace('$','',$data[3]);
	
	insertSQL($date,$details,$amount,$balance);
}

function insertSQL($date,$details,$amount,$balance) {
	//date to YYYY-MM-DD
	$date = str_replace('/', '-', $date);
	$date = date('Y-m-d', strtotime($date));
	
	echo PHP_EOL.($date);
	echo " :: ".$details." :: ";
	echo $amount." :: ";
	echo $balance."  ";
	
	/*$database = "sqlite:/var/www/html/transactions.sqlite3";
	$connection = new PDO($database) or die("cannot open the database");
	
	//$sql = "SELECT * FROM transactions WHERE";
	$query = $connection->prepare('INSERT into transactions (dateCleared, details, amount,balance) VALUES (?, ?, ?, ?)');
	$result = $query->execute(array($date,$details,$amount,$balance));
	var_dump($result);
	*/
	
	//TODO: //check if transaction exists
	$username="root";
	$password="password";
	$host="localhost";
	$db="showmethemoney";
	
	$connection = new PDO("mysql:dbname=$db;host=$host",$username,$password);
	$sql = "INSERT INTO transactions (dateCleared, details, amount, balance) VALUES (?, ?, ?, ?)";
	$prepare = $connection->prepare($sql);
	//$result = $prepare->execute([$date, $details, $amount, $balance]);
	//var_dump($prepare->errorInfo())
	//var_dump($result);

}
