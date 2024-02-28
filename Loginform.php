<?php
session_start(); // Start the session to use session variables
include "db_conn.php"; // Connect to the database

if (isset($_POST['login'])) { // Check if the login form is submitted
    $email = $_POST['email']; // Get the email from the form
    $pass = $_POST['password']; // Get the password from the form
    $sql = "SELECT * FROM user WHERE email=? AND password=?"; // Query to retrieve the user from the database based on email and password
    $stmt = $conn->prepare($sql); // Prepare the statement for querying
    $stmt->bind_param("ss", $email, $pass); // Set the parameters for the statement
    $stmt->execute(); // Execute the prepared statement
    $result = $stmt->get_result(); // Get the result of the query

    if ($result->num_rows === 1) { // If one user is retrieved from the query
        $row = $result->fetch_assoc(); // Get the row from the result set
        $_SESSION['user_id'] = $row['user_id']; // Set the user ID in the session variable
        $_SESSION['fname'] = $row['First_name']; // Set the first name in the session variable
        $_SESSION['lname'] = $row['Lastname']; // Set the last name in the session variable
        $_SESSION['email'] = $row['email']; // Set the email in the session variable
        $_SESSION['message'] = "Login successful"; // Set the message in the session variable
        $_SESSION['alert_type'] = "success"; // Set the alert type in the session variable
        header("Location: welcome.php"); // Redirect to welcome.php
        exit(); 
    } else { // If no user is retrieved from the query
        $_SESSION['message'] = "Incorrect email or password"; // Set the message in the session variable
        $_SESSION['alert_type'] = "error"; // Set the alert type in the session variable
        header("Location: Loginform.php"); // Redirect to login.php
        exit(); 
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <!-- Linking Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Linking your custom CSS file -->
    <link href="Stylesheet.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>Log In</h2>
            <?php if (isset($_SESSION['message'])): ?>
                <!-- Displaying alert message if session variable is set -->
                <div class="alert alert-<?php echo $_SESSION['alert_type']; ?>">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']); // Unsetting session variable after displaying
                    unset($_SESSION['alert_type']); // Unsetting session variable after displaying
                    ?>
                </div>
            <?php endif; ?>
            <!-- Login form -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!-- Input field for email -->
                <input type="email" name="email" class="form-control" placeholder="Email" required><br>
                <!-- Input field for password -->
                <input type="password" name="password" class="form-control" placeholder="Password" required><br>
                <!-- Submit button for login -->
                <button type="submit" name="login" class="btn btn-primary">Log In</button>
            </form>
            <!-- Link to registration page -->
            <p>Don't have an account? <a href="index.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>

