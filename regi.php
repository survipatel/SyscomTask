    <?php
    
    $firstname = $lastname = $email = $password = $confirm_password = $mob_number = "";
    $firstname_err = $lastname_err = $email_err = $password_err = $confirm_password_err = $mob_number_err = "";

    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if (empty(trim($_POST["firstname"]))) {
            $firstname_err = "Please enter your first name.";
        } else {
            $firstname = trim($_POST["firstname"]);
        }

        
        if (empty(trim($_POST["lastname"]))) {
            $lastname_err = "Please enter your last name.";
        } else {
            $lastname = trim($_POST["lastname"]);
        }

    
        if (empty(trim($_POST["email"]))) {
            $email_err = "Please enter your email address.";
        } else {
            $email = trim($_POST["email"]);
            // Check if email address is valid
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_err = "Invalid email format.";
            }
        }

        // Validate Password
        if (empty(trim($_POST["password"]))) {
            $password_err = "Please enter a password.";
        } elseif (strlen(trim($_POST["password"])) < 8) {
            $password_err = "Password must have at least 8 characters.";
        } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", trim($_POST["password"]))) {
            $password_err = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Validate Confirm Password
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm password.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "Passwords do not match.";
            }
        }

        // Validate Mobile Number
        if (empty(trim($_POST["mob_number"]))) {
            $mob_number_err = "Please enter your mobile number.";
        } elseif (!preg_match("/^[0-9]{10}$/", trim($_POST["mob_number"]))) {
            $mob_number_err = "Invalid mobile number format.";
        } else {
            $mob_number = trim($_POST["mob_number"]);
        }

        // If all validation checks pass, insert data into database
        if (empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($mob_number_err)) {
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "syscom";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

           // echo "Hashed Password: " . $hashed_password . "<br>";

            // Insert data into database
            $sql = "INSERT INTO users (firstname, lastname, email, password, mob_number)
                    VALUES ('$firstname', '$lastname', '$email', '$hashed_password', '$mob_number')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        }
    }
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <style type="text/css">
        .error{
            color: red;
        }
    </style>
</head>
<body>
    <h2>Registration Form</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <img src="img/logo.png" alt ="Site logo"><br><br>
        <label for="firstname">First Name:</label><br>
        <input type="text" id="firstname" name="firstname" required><br>
        <span class="error"><?php echo $firstname_err;?></span><br><br>
        
        <label for="lastname">Last Name:</label><br>
        <input type="text" id="lastname" name="lastname" required><br>
        <span class="error"><?php echo $lastname_err;?></span><br><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <span class="error"><?php echo $email_err;?></span><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <span class="error"><?php echo $password_err;?></span><br><br>
        
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        <span class="error"><?php echo $confirm_password_err;?></span><br><br>
        
        <label for="mob_number">Mobile Number:</label><br>
        <input type="text" id="mob_number" name="mob_number" required><br>
        <span class="error"><?php echo $mob_number_err;?></span><br><br>
        
        <input type="submit" value="Submit">
    </form>


</body>
</html>