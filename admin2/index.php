<?php

session_start();

include "db_conn.php";


if (isset($_POST['register'])) {// mag check if na click ang "register" button
    
    function validate($data) {   // Function to validate input para ma ensure  na ang data na i-input ni user through html is valid and safe for processing
        $data = trim($data); // this line is gina ensure sa code na wlay unintended spaces.
        $data = stripslashes($data); // Remove backslashes para sa security reason.
        $data = htmlspecialchars($data); // converter, this line of code is para ma convert ang mga special characters like "<, >, &," to their html forms
        return $data;
    }
    $fname = validate($_POST['fname']); // gina  Validate and sanitize ang first name input same sa ibang inputs.
    $mname = validate($_POST['mname']); 
    $lname = validate($_POST['lname']);  
    $email = validate($_POST['email']); 
    $pass = validate($_POST['password']); 
    $status = validate($_POST['status']); 
    $username = validate($_POST['username']); 
    $Active = validate($_POST['Active']); 

    if (empty($fname) || empty($lname) || empty($email) || empty($pass) || empty($status) || empty($username) || empty($Active)) { // gina Check if any required fields are empty
        $_SESSION['message'] = "All fields are required"; // kung naay empy, mag ingon ug error message
        $_SESSION['alert_type'] = "error"; // Set error alert type
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check if email is in a valid format
        $_SESSION['message'] = "Invalid email format"; // Set error message
        $_SESSION['alert_type'] = "error"; // Set error alert type
    } else {
        $email_check_stmt = $conn->prepare("SELECT * FROM user WHERE email=?"); // Prepare SQL statement to check if email is already registered
        $email_check_stmt->bind_param("s", $email); // Bind parameters to the prepared statement
        $email_check_stmt->execute(); // Execute the prepared statement
        $email_check_result = $email_check_stmt->get_result(); // Get the result set from the executed statement
        if ($email_check_result->num_rows > 0) { // Check if email is already registered
            $_SESSION['message'] = "Email already registered"; // Set error message
            $_SESSION['alert_type'] = "error"; // Set error alert type
        } else {
            $stmt = $conn->prepare("INSERT INTO user (username, password, First_name, Middle_name, Lastname, Email, Status, Active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"); // Prepare SQL statement to insert user data into the database
            $stmt->bind_param("ssssssss", $username, $pass, $fname, $mname, $lname, $email, $status, $Active); // Bind parameters to the prepared statement
            $stmt->execute(); // Execute the prepared statement to insert data
            $_SESSION['message'] = "Registration successful"; 
            $_SESSION['alert_type'] = "success"; 
        }
    }
    header("Location: index.php"); // Redirect back to the registration page after processing the form
    exit(); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <!-- Including Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="Stylesheet.css" rel="stylesheet">
</head>
<body>
    <!-- Container for the registration form -->
    <div class="container">
        <div class="register-container">
            <h2>Sign Up</h2>
            <!-- Displaying alert message if session variable is set -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['alert_type']; ?>">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    unset($_SESSION['alert_type']);
                    ?>
                </div>
            <?php endif; ?>
            <!-- Registration form -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!-- Input field for first name -->
                <input type="text" name="fname" class="form-control" placeholder="First Name" required><br>
                <!-- Input field for middle name -->
                <input type="text" name="mname" class="form-control" placeholder="Middle Name"><br>
                <!-- Input field for last name -->
                <input type="text" name="lname" class="form-control" placeholder="Last Name" required><br>
                <!-- Input field for email -->
                <input type="email" name="email" class="form-control" placeholder="Email" required><br>
                <!-- Input field for username -->
                <input type="text" name="username" class="form-control" placeholder="Username" required><br>
                <!-- Input field for password -->
                <input type="password" name="password" class="form-control" placeholder="Password" required><br>
                <!-- Input field for Active -->
                <input type="text" name="Active" class="form-control" placeholder="Active" required><br>
                <!-- Label for status dropdown -->
                <label>Status</label><br>
                
                <select name="status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                    <option value="widowed">Widowed</option>
                    <option value="others">Others</option>
                </select><br>
                <!-- Submit button for registration -->
                <button type="submit" name="register" class="btn btn-primary">Sign Up</button>
            </form>
            <!-- Link to login page -->
            <p>Already have an account? <a href="Loginform.php">Log In</a></p>
        </div>
    </div>
</body>
</html>
