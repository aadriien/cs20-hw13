<!DOCTYPE HTML>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Jade Delight</title> 
</head>

<body>

    <?php 
    echo "Thank you for your order.<br/><br/>";
    echo "You selected the following items: <br/>";
    
    // create arrays to store product information
    $itemsQuan = array($_POST["quan0"], $_POST["quan1"], $_POST["quan2"], 
                    $_POST["quan3"], $_POST["quan4"]);
                
    $itemsName = array("Chicken Chop Suey", "Sweet and Sour Pork", "Shrimp Lo Mein",
                        "Moo Shi Chicken", "Fried Rice");                        
    $itemsCost = array(4.5, 6.25, 5.25, 6.5, 2.35);

    // print any items that were ordered (with cost & amount)
    for ($i = 0; $i < 5; $i++) {
        if ($itemsQuan[$i] > 0) {
            echo $itemsQuan[$i], " ";
            echo $itemsName[$i], " - $";
            $cost = $itemsCost[$i] * $itemsQuan[$i];
            echo $cost, "<br/>";
        }        
    }
    
    echo "<br/><br/>";
    
    $pickdeliv = $_POST["p_or_d"];
    $date = date("H:i");
    
    // determine pickup / delivery, & print time estimate
    if ($pickdeliv == "delivery") {
        echo "Your order will be ready within 30 minutes.";
        $addtime = date("H:i", strtotime($date . "+30 minutes"));
        echo "<br/>Expected delivery time: " . $addtime;
    }
    else {
        echo "Your order will be ready within 15 minutes.";
        $addtime = date("H:i", strtotime($date . "+15 minutes"));
        echo "<br/>Expected pickup time: " . $addtime;
    }
    
    echo "<br/><br/>";
    
    // print subtotal, tax, total
    echo "Subtotal: $" . $_POST["subtotal"];
    echo "<br/>Tax: $" . $_POST["tax"];
    echo "<br/>Total: $" . $_POST["total"];
    
    // draft & send email message 
    $emailMessage = "Thank you for your order. It will be ready at " . $addtime 
    . ". Your total for today is $" . $_POST["total"];
    
    mail("customer@gmail.com", "JadeDelightService@gmail.com", $emailMessage);
    ?>
    
</body>

</html>


