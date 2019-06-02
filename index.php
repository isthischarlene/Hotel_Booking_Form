<?php
// Start the session to save the information the user puts in
session_start();
?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Hotel Booking Form</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
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
            <h1>Book My Hotel</h1>
                <ul class="form-style-1">
                    <li><label>Full Name: <span class="required">*</span></label><input type="text" name="firstname" class="field-divided" placeholder="First" /> <input type="text" name="lastname" class="field-divided" placeholder="Last" /></li>
                    <li>
                        <label>Check-In Date: <span class="required">*</span></label>
                        <input type="date" name="datein" id="datein" min="2018-01-01" min="2020-12-31" class="field-long" required />
                    </li>
                    <li>
                        <label>Check-Out Date: <span class="required">*</span></label>
                        <input type="date" name="dateout" id="dateout" min="2018-01-01" min="2020-12-31" class="field-long" required>
                        </li>
                    <li>
                    <label>Select A Hotel: <span class="required">*</span></label>
                        <select name="hotelname" class="field-select" required>
                            <option> Select A Hotel...</option>
                            <option value="The Silo Hotel">The Silo Hotel</option>
                            <option value="Cape Grace Hotel">The Cape Grace Hotel</option>
                            <option value="Ellerman House">Ellerman House</option>
                            <option value="The Celler-Hohenhort">The Cellars-Hohenhort</option>
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
            if (isset($_POST['submit'])) {

            $datetime1 = new DateTime($_SESSION['datein']);
            $datetime2 = new DateTime($_SESSION['dateout']);
            $interval = $datetime1->diff($datetime2); 
            $daysbooked = $interval->format('%R%a');

            //switch statement to calculate the cost for the duration of the user's stay
            switch($_POST['hotelname']){
                case "The Silo Hotel":
                $value = $daysbooked * 5500;
                break;
    
                case "Cape Grace Hotel":
                $value = $daysbooked * 6600;
                break;
    
                case "Ellerman House":
                $value = $daysbooked * 7700;
                break;
    
                case "The Cellar-Hohenhort":
                $value = $daysbooked * 8800;
                break;
    
                default:
                return "Invalid Booking. Please try again.";
            }            

            //display provisional booking info to user after "book" button has been pushed
            echo '<div class="provisional">';
            echo '<h2> ~ Provisional Booking Details ~ </h2>';
            echo "<strong>Name:</strong> ". $_SESSION['firstname']."<br>".
                 "<strong>Surname:</strong> ". $_SESSION['lastname']."<br>".
                 "<strong>Check-in Date:</strong> ". $_SESSION['datein']."<br>".
                 "<strong>Check-out Date:</strong> ". $_SESSION['dateout']."<br>".
                 "<strong>Hotel Name:</strong> ". $_SESSION['hotelname']."<br>".
                 "<strong>Duration of Stay:</strong> ".$interval->format('%R%a days')."<br>".
                 "<strong>Cost:</strong> R". $value."<br>";

            //mini-form (only a "confirm" button) displayed with user's provisional booking information. 
            echo '<form role="form" action="index.php " method="post"> <input name="confirm" type="submit"></form>';

            echo '</div>';

        }

            //"template" for inserting user inputs into "bookings" table when the user presses "confirm" button. 
            if (isset($_POST["confirm"])) {
                $stmt= $conn->prepare("INSERT INTO bookings (firstname, lastname, datein, dateout, hotelname) VALUES (?,?,?,?,?)");
                $stmt->bind_param('sssss', $firstname, $lastname, $datein, $dateout, $hotelname);

                //set the parameters
                $firstname = $_SESSION["firstname"];
                $lastname = $_SESSION["lastname"];
                $datein = $_SESSION["datein"];
                $dateout = $_SESSION["dateout"];
                $hotelname = $_SESSION["hotelname"];

                //execute the above statement
                $stmt->execute();

                //booking confirmation message displayed after used has clicked "confirm"
                echo '<div class="confirm-btn">';
                echo '<img src="images/green-tick.png" alt="green-tick-icon"> <p>Booking confirmed</p>';
                echo '</div>';
            }
            
            ?>
    </body>
</html>


                    