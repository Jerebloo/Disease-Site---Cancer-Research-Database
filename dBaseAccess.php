<?PHP

$servername = "localhost";
$username = "root";
$password = "magician";
$database = "mirnabkp";

// Create connection
$mirnabDb = new mysqli($servername, $username, $password, $database);
// Check connection
if ($mirnabDb->connect_error) {
    die("Connection failed: " . $mirnabDb->connect_error);
} 
//echo "Connected successfully";

/*
$db_connect = mysqli_connect($servername, $username, $password, $database);

//* check connection
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
*/


?>
