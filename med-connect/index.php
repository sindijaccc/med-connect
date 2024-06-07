<?php
session_start(); // Initiates a new session or resumes an existing session
include 'db.php'; // Add database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) { // Checks if the form was submitted
    $user_code = $_POST['user_code']; // Retrieve user code from the form
    $password = $_POST['password'];	 // Retrieve password from the form

    // Prepare and execute the query for patients
    $stmt = $conn->prepare("SELECT * FROM patients WHERE user_code = ?"); // Prepare SQL statement
    $stmt->bind_param("s", $user_code); // Bind actual value to the prepared statement placeholder
    $stmt->execute(); // Execute the prepared statement
    $result = $stmt->get_result(); // Get the result set from the executed query

    if ($result->num_rows > 0) { // Check if the query result set has rows
        $row = $result->fetch_assoc(); // Fetch the resulting row as an associative array
        if (password_verify($password, $row['password'])) { // Verify the password against the hashed password
            // Patient found, redirect to user-page.php
            $_SESSION['user_code'] = $user_code; // Save user code to session variable
            $_SESSION['id'] = $row['id']; // Save user ID to session variable
            header("Location: user-page.php?id=".$_SESSION['id']); // Redirect to user-page.php
            exit(); // Ensure script stops execution after redirect
        }
    }

    // Prepare and execute the query for doctors
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE user_code = ?"); // Prepare SQL statement
    $stmt->bind_param("s", $user_code); // Bind actual value to the prepared statement placeholder
    $stmt->execute(); // Execute the prepared statement
    $result = $stmt->get_result(); // Get the result set from the executed query

    if ($result->num_rows > 0) { // Check if the query result set has rows
        $row = $result->fetch_assoc(); // Fetch the resulting row as an associative array
        if (password_verify($password, $row['password'])) { // Verify the password against the hashed password
            // Doctor found, redirect to doctor-page.php
            $_SESSION['user_code'] = $user_code; // Save user code to session variable
            $_SESSION['id'] = $row['id']; // Save user ID to session variable
            header("Location: doctor-page.php?id=".$_SESSION['id']); // Redirect to doctor-page.php
            exit(); // Ensure script stops execution after redirect
        }
    }
    
    // Check for admin credentials
    if ($user_code == "admin" && $password == "admin") { // Check if both user code and password match "admin"
        $_SESSION['id'] = 0; // Set session ID to 0
        header("Location: admin-page.php?id=".$_SESSION['id']); // Redirect to admin-page.php
        exit(); // Ensure script stops execution after redirect
    }

    // Redirect to login page with error message if credentials are invalid
    header("Location: ../med-connect/index.php?error=Invalid credentials");
    exit(); // Ensure script stops execution after redirect
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../med-connect/styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>MED-CONNECT - log in</title>
</head>
<body>
    <div class="page"> 
        <div class="container">
            <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-fieldset" style="margin: auto; padding: auto;">
                    <div class="form-title">
                        <h1>MED-CONNECT</h1>
                        <p style="font-size: 13px; color:#ffffff;">Lai piekļūtu rīkam nepieciešams pierakstīties ar personas kodu un paroli kas tika piešķirta iepriekš.</p>
                        <p style="font-size: 11px; color: #ffffff;">(Testēšanas nolūkam administratora profila informācija ir Kods - admin, parole - admin, no kura vēlāk pievienot lietotāju kontus)</p>
                    </div>
                    <div class="form-group">
                        <input class="form-input" id="user_code" type="text" name="user_code" placeholder="Personas kods" required>
                    </div>
                    <div class="form-group">
                        <input class="form-input" id="password" type="password" name="password" placeholder="Parole" required>
                    </div>
                    <button class="large-button" type="submit" name="submit">Pierakstīties</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Function to parse URL parameters
        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        };

        // Checks for error query parameter
        var error = getUrlParameter('error');
        if (error === 'Invalid credentials') {
            // Displays an alert for invalid credentials
            alert('Kļūdaina pierakstīšanās informācija.');
        }
    </script>        
</body>
</html> 
