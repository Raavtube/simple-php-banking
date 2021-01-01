<?php
$servername = "localhost";
$username = "root";
$password = "MYSQLraavid2";
$dbname = "php_bank";

function console_log($output, $with_script_tags = true) {
  $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
  if ($with_script_tags) {
      $js_code = '<script>' . $js_code . '</script>';
  }
  echo $js_code;
}

function display_data($data) {
    $output = '<table>';
    foreach($data as $key => $var) {
        $output .= '<tr>';
        foreach($var as $k => $v) {
          
            if ($key === 0) {
                if (strpos($k, 'id') !== false) {
                  $placeholder = 0;  
                }else{
                  $output .= '<td><strong>' . $k . '</strong></td>';
                }
                
            } else {
              if (strpos($k, 'id') !== false) {
                $placeholder = 0;  
              }else{
                $output .= '<td>' . $v . '</td>';
              }
                
            }
        }
        $output .= '</tr>';
    }
    $output .= '</table>';
    return $output;
}

$servername = "localhost";
$username = "root";
$password = "MYSQLraavid2";
$dbname = "php_bank";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$result = mysqli_query($conn,"SELECT * FROM main_bank2");
//$tableresult = display_data($result);

$sql = "SELECT balance from main_bank2 where id = 1"; // SQL with parameters
$stmt = $conn->prepare($sql); 
$stmt->bind_param("i", $redirectID);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
while ($row = $result->fetch_assoc()) {
    $currentBal = $row['balance'];
}


$conn->close(); //Make sure to close out the database connection

// -------------------------Newest at the front-------------------------


$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "SELECT count(1) FROM main_bank2";
$stmt = $conn->prepare($sql); 
$stmt->bind_param("i", $redirectID);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
while ($row = $result->fetch_assoc()) {
    $rows_count = $row['count(1)'];
}
console_log("Rows: " . $rows_count);


// Table Formatting

$output = '<table>';
$output .= '<tr><td><strong>Balance</strong></td><td><strong>Reason</strong></td></tr>';
$count_x = 0;
for ($count = 0; $count < $rows_count; $count++) {
  console_log("Count: " . $count_x);
  $rowToFetch = $rows_count - $count_x;
  console_log("Row Number: " . $rowToFetch);
  $sql = "SELECT balance, reason from main_bank2 where id = $rowToFetch"; // SQL with parameters
  $stmt = $conn->prepare($sql); 
  $stmt->bind_param("i", $redirectID);
  $stmt->execute();
  $result = $stmt->get_result(); // get the mysqli result
  while ($row = $result->fetch_assoc()) {
    $balance = $row['balance'];
    $reason = $row['reason'];
}

  console_log("Balance: " . $balance);
  if ($reason == "CURRENT BALANCE"){
    $placeholder = 0;
  }else{
    if (strpos($balance, "-") !== false){
      $output .= "<tr><td><span id='red'><b>$balance</b></span></td><td><span id='red'>$reason</span></td></tr>";
    }else{
      $output .= "<tr><td><span id='green'><b>$balance</b></span></td><td><span id='green'>$reason</span> </td></tr>";
    }
    
  }
  
  console_log($sql);

  $count_x = $count_x + 1;
}
$output .= "</table>";





mysqli_close($conn);




?>
<!DOCTYPE html>
<html>
<head>
<title>Vogel Banking</title>
<style>
#table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 60%;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
#red{
  color: red;
}
#green{
  color: green;
}


</style>
</head>
<body>

<center><h1>Vogel Banking</h1></center>


<center>
<h2>Current Balance: $<?php echo $currentBal; ?></h2>
<form action="getResult.php" method="post">
  <label for="amount">Enter Amount (Add a "-" sign to subtract money, add a "+" sign to add money):</label><br><hr style="height:0px; visibility:hidden;" />
  <input type="text" id="amount" name="amount" required><br><br>
  <label for="reason">Reason or Notes:</label><br><hr style="height:0px; visibility:hidden;" />
  <input type="text" id="reason" name="reason" required><br><br>
  <input type="submit" value="Submit">
  <br><br>
</form>
</center>
<center>
<div id="table"style="height:325px;overflow:auto;">
<?php echo $output; ?>
</div>
</center>
</body>
</html>
