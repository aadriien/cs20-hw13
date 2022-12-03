<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Jade Delight</title>    
</head>


<body>
    <?php 
    // establish connection info
    $server = "sql306.epizy.com";
    $userid = "epiz_27902925";
    $pw = "gAQ91hkC5G";
    $db = "epiz_27902925_JadeDelight";

    // make connection
    $conn = new mysqli($server, $userid, $pw);

    // check successful connection
    if ($conn->connect_error) {
        die("Connection failed:" .$conn->connect_error);
    }    
    
    // select database
    $conn->select_db($db);
    
    // run a query
    $sql = "SELECT * FROM Products";    
    $result = $conn->query($sql);
    
    $name = array();
    $price = array();
    
    // get results
    if ($result->num_rows > 0) {
                
        while ($row = $result->fetch_assoc()) {            
            $name[] = $row["name"];
            $price[] = $row["price"];
        }
    }
    else {
        echo "No results found";
    }

    // close connection
    $conn->close();
    ?>
    

    <script language="javascript">   
        // create array to hold item information
        var itemNames = <?php echo json_encode($name); ?>;
        var itemPrices = <?php echo json_encode($price); ?>;
        
        function MenuItem(name, cost) {
        	this.name = name;
        	this.cost = cost;
        }
        
        // store item names & their costs
        menuItems = new Array(   
            new MenuItem(itemNames[0], itemPrices[0]),
            new MenuItem(itemNames[1], itemPrices[1]),
            new MenuItem(itemNames[2], itemPrices[2]),
            new MenuItem(itemNames[3], itemPrices[3]),
            new MenuItem(itemNames[4], itemPrices[4]),
        );
        
        // construct drop down choices (within table)
        function makeSelect(name, minRange, maxRange) {
        	var t= "";
        	t = "<select name='" + name + "' size='1'>";
        	for (j=minRange; j<=maxRange; j++)
        	   t += "<option>" + j + "</option>";
        	t+= "</select>"; 
        	return t;
        }

        function validate() {
            // confirm that the customer provided their last name & phone
            var surname = document.form1.lname.value;
            var phone = document.form1.phone.value;
            
            if (surname == "" || phone == "") {
                alert("Please ensure all required fields have been filled in.");
                return false;
            }
            
            var valid = phone.search(/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im);
            if (valid <= -1) {
                alert("Please enter a valid phone number.");
                return false;
            }
            
            // confirm that street & city have been specified for delivery
            if (document.form1.p_or_d.value == 'delivery') {
                var street = document.form1.street.value;
                var city = document.form1.city.value;
                
                if (street == "" || city == "") {
                    alert("Please specify both street and city for delivery.");
                    return false;
                }
            }  
            
            // confirm that at least 1 item has been ordered
            if ((document.getElementsByName("cost")[0].value == 0) && 
                (document.getElementsByName("cost")[1].value == 0) &&
                (document.getElementsByName("cost")[2].value == 0) &&
                (document.getElementsByName("cost")[3].value == 0) &&
                (document.getElementsByName("cost")[4].value == 0)) {
                    alert("Please order a minimum of one item.");
                    return false;
            }                        
            return true;
        }

        function getValue(val) {
            // hide street & city fields if customer is picking up order
            if (val.value == "pickup") {
                document.getElementById("textboxes").style.display = 'none';
            }
            else {
                document.getElementById("textboxes").style.display = 'block';
            }
        }

        function updateText() {
            // identify the dropdown quantity for selected item
            var totalItems = this.selectedIndex;
            var itemName = this.name;
            var itemNum = itemName.substring(4);
            
            // update the corresponding cost textbox
            var itemsCost = totalItems * menuItems[itemNum].cost;    
            document.getElementsByName("cost")[itemNum].value = itemsCost.toFixed(2);
            
            // update the subtotal textbox by checking each item's count
            var subtotal = 0;
            for (i = 0; i < menuItems.length; i++) {
                if (document.getElementsByTagName("select")[i].value != 0) {
                    itemTotal = parseFloat(document.getElementsByName("cost")[i].value);
                    subtotal += itemTotal;
                }
            }
            document.getElementById("subtotal").value = subtotal;
            
            // calculate & update tax 
            var tax = 0.0625;
            var taxTotal = tax * subtotal;
            document.getElementById("tax").value = taxTotal.toFixed(2);
            
            // calculate & update total cost of order
            var total = subtotal * (1 + tax);
            document.getElementById("total").value = total.toFixed(2);    
        }

        window.onload = function() {
            // detect any instance of dropdown quantities changing
            for (var i = 0; i < menuItems.length; i++) {
                document.getElementsByName("quan" + i)[0].onchange = updateText;
                document.getElementsByName("cost")[i].value = 0.00;
            }
            // initialize subtotal, tax, total to $0
            document.getElementById("subtotal").value = 0.00;
            document.getElementById("tax").value = 0.00;
            document.getElementById("total").value = 0.00;
        }
    </script>
    
    
    <h1>Jade Delight</h1>
    <!-- validate all necessary fields on submit -->
    <form name="form1" method="post" onsubmit="return validate()" action="newJadePage.php">
        <input type="hidden" name="secretChange"/>
        
        <script language="javascript">
            document.form1.secretChange.value = "nothingHere";
        
        </script>    
        

    <p>First Name: <input type="text"  name='fname' /></p>
    <p>Last Name*:  <input type="text"  name='lname' /></p>

    <div id="textboxes" style="display: none">
        <p>Street: <input type="text"  name='street' /></p>
        <p>City: <input type="text"  name='city' /></p>
    </div>

    <p>Phone*: <input type="text"  name='phone' /></p>
    <p> 
    	<input type="radio"  name="p_or_d" value = "pickup" onChange="getValue(this)" checked="checked"/>Pickup  
    	<input type="radio"  name='p_or_d' value = 'delivery' onChange="getValue(this)"/>
    	Delivery
    </p>


    <table border="0" cellpadding="3">
         <tr>
             <th>Select Item</th>
             <th>Item Name</th>
             <th>Cost Each</th>
             <th>Total Cost</th>
         </tr>
        
        <script language="javascript">

            var s = "";
            for (i = 0; i < menuItems.length; i++)
            {
                s += "<tr><td>";
                s += makeSelect("quan" + i, 0, 10);
            	s += "</td><td>" + menuItems[i].name + "</td>";
            	s += "<td> $ " + menuItems[i].cost + "</td>";
            	s += "<td>$<input type='text' name='cost'/></td></tr>";
            }
            document.writeln(s);
        </script>

    </table>
    
    
    <p>Subtotal: 
        $<input type="text"  name='subtotal' id="subtotal" />
    </p>
    <p>Mass tax 6.25%:
        $ <input type="text"  name='tax' id="tax" />
    </p>
    <p>Total: 
        $ <input type="text"  name='total' id="total" />
    </p>

    <input type = "submit" value = "Submit Order"/>
    </form>
        
</body>

</html>




