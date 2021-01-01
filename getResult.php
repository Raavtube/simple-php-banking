<?php
    $servername = "localhost";
    $username = "root";
    $password = "MYSQLraavid2";
    $dbname = "php_bank";




    $amount = $_POST['amount'];
    $reason = $_POST['reason'];
    // This is a check to make sure that the code is correctly formatted.
    $plus_or_minus = $amount[0];
    echo $plus_or_minus . "<br><br>";
    if ($plus_or_minus == "+" || $plus_or_minus == "-") {
        $syntaxCheck = 1;
    }else{
        $syntaxCheck = 0;
    }
    if ($syntaxCheck == 1) {


        $sql = "INSERT INTO `main_bank2`(`Balance`, `Reason`) VALUES ($amount, '$reason')";

        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // This is just to push the new value up.
        mysqli_query($conn,$sql);

        // Getting the current balance so we can do the math.
        $sql = "SELECT balance from main_bank2 where id = 1"; // SQL with parameters
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("i", $redirectID);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        while ($row = $result->fetch_assoc()) {
            $currentBal = $row['balance'];
        }
        
        // Math to check balance:
        if ($amount[0] == "+") {
            $pureAmount = str_replace('+', "", $amount);
            $addValue = True;
            $temporalBalance = $currentBal + $pureAmount;
            
            // This is to help later on.
        }
        if ($amount[0] == "-") {
            $pureAmount = str_replace('-', "", $amount);
            $addValue = False;
            $temporalBalance = $currentBal - $pureAmount;
        
            
        }
        
        $newSQL = "UPDATE `main_bank2` SET `Balance` = $temporalBalance WHERE `main_bank2`.`id` = 1";
        mysqli_query($conn,$newSQL);


        $conn->close();
        header('Location: https://rserver.ml/bank');
        

    }

?>