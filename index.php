<?php
// Start the session to save the information the user puts in
session_start();
?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Hotel Booking Form</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <?php
            //Connect the connect
            require_once "connect.php";

            //The query to create "bookings" table skeleton in "hotels" database
            $sql = "CREATE TABLE IF NOT EXISTS bookings (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(50),
            lastname VARCHAR(50),
            hotelname VARCHAR(50),
            datein VARCHAR(30),
            dateout VARCHAR(30),
            booked INT(4))";

            
            $conn ->query($sql);
            //echo an error if there is an error
            echo $conn->error;

        ?>

        <!--Form for users to fill in their details to book a hotel-->
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"> 
            <h1>Book A Hotel</h1>
                <ul class="form-style-1">
                    <li><label>Full Name: <span class="required">*</span></label><input type="text" name="firstname" class="field-divided" placeholder="First" /> <input type="text" name="lastname" class="field-divided" placeholder="Last" /></li>
                    <li>
                        <label>Arrival Date: <span class="required">*</span></label>
                        <input type="date" name="datein" id="datein" min="2018-01-01" min="2020-12-31" class="field-long" required />
                    </li>
                    <li>
                        <label>Departure Date: <span class="required">*</span></label>
                        <input type="date" name="dateout" id="dateout" min="2018-01-01" min="2020-12-31" class="field-long" required>
                        </li>
                    <li>
                    <label>Select A Hotel: <span class="required">*</span></label>
                        <select name="hotelname" class="field-select" required>
                            <option> Select A Hotel...</option>
                            <option value="Silo">The Silo Hotel</option>
                            <option value="Grace">The Cape Grace Hotel</option>
                            <option value="Ellerman">Ellerman House</option>
                            <option value="Cellers">The Cellars-Hohenhort</option>
                        </select>
                    </li>
                    <li>
                        <input type="submit" name="submit" value="Book" />
                    </li>
                </ul>
        </form>
        <?php
            //assigning the information that the user inputs to session variables so that the information is stored
            if (isset($_POST['submit'])) {
                $_SESSION['firstname'] = $_POST['firstname'];
                $_SESSION['lastname'] = $_POST['lastname'];
                $_SESSION['hotelname'] = $_POST['hotelname'];
                $_SESSION['datein'] = $_POST['datein'];
                $_SESSION['dateout'] = $_POST['dateout'];
            }

            //calculate duration of user's stay at selected hotel
            $datetime1 = new DateTime($_SESSION['datein']);
            $datetime2 = new DateTime($_SESSION['dateout']);
            $interval = $datetime1->diff($datetime2); 

            $daysbooked = $interval->format('%R%a');
            //echo $daysbooked;
            //$value;

            //switch statement to calculate the cost for the duration of the user's stay
            switch($_POST['hotelname']){
                case "Silo":
                $value = $daysbooked * 5500;
                break;
    
                case "Grace":
                $value = $daysbooked * 6600;
                break;
    
                case "Ellerman":
                $value = $daysbooked * 7700;
                break;
    
                case "Cellars":
                $value = $daysbooked * 8800;
                break;
    
                default:
                return "Invalid Booking. Please try again.";
            }

            //display booking info to user after "book" button has been pushed
            echo "<br> 
                First Name: ". $_SESSION['firstname']."<br>".
                "Surname: ". $_SESSION['lastname']."<br>".
                "Start Date: ". $_SESSION['datein']."<br>".
                "End Date: ". $_SESSION['dateout']."<br>".
                "Hotel Name: ". $_SESSION['hotelname']."<br>".
                $interval->format('%R%a days')."<br>"."Cost: R". $value;

            //mini-form (only a "confirm" button) displayed with user's provisional booking information. 
            echo '<form method="post" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>
                <input type="submit" name="confirm" value="Confirm">
                </form>';

            //"template" for inserting user inputs into "bookings" table when the user presses "confirm" button. 
            if (isset($_POST['confirm'])) {
                $stmt= $conn->prepare("INSERT INTO bookings (firstname, lastname, datein, dateout, hotelname) VALUES (?,?,?,?,?)");
                $stmt-> bind_param('sssss', $firstname, $lastname, $datein, $dateout, $hotelname);

                //set the parameters
                $firstname = $_SESSION["firstname"];
                $lastname = $_SESSION["lastname"];
                $datein = $_SESSION["datein"];
                $dateout = $_SESSION["dateout"];
                $hotelname = $_SESSION["hotelname"];

                //execute the above statement
                $stmt-> execute();
                echo "Booking confirmed";
            }
            
            ?>
    </body>
</html>


                    