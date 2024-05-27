<?php
session_start();
include 'db.php'; //Add database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $user_code = $_POST['user_code'];
    $password = $_POST['password'];

    // Prepare and execute the query for patients
    $stmt = $conn->prepare("SELECT * FROM patients WHERE user_code = ? AND password = ?");
    $stmt->bind_param("ss", $user_code, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Patient found, redirect to user-page.php
        $_SESSION['user_code'] = $user_code;
        $_SESSION['password'] = $password;
        $_SESSION['id'] = $result->fetch_assoc()['id']; // Assuming 'id' is the column name for patient ID
        header("Location: user-page.php?id=".$_SESSION['id']);
        exit();
    }

    // Prepare and execute the query for doctors
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE user_code = ? AND password = ?");
    $stmt->bind_param("ss", $user_code, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Doctor found, redirect to doctor-page.php
        $_SESSION['user_code'] = $user_code;
        $_SESSION['password'] = $password;
        $_SESSION['id'] = $result->fetch_assoc()['id']; // Assuming 'id' is the column name for doctor ID
        header("Location: doctor-page.php?id=".$_SESSION['id']);
        exit();
    }
    
    if ($user_code == "admin" && $password == "123456789") {                 
        header("Location: admin-page.php");
        exit();
    }

    // If no match found
    header("location: ../med-connect/index.php?error=Invalid credentials");
    exit();
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
                        <p style="font-size: 13px; color:#ffffff">Lai piekļūtu rīkam nepieciešams pierakstīties ar personas kodu un paroli kas tika piešķirta iepriekš.</p>
                    </div>

                    <div class="form-group">
                        <input class="form-input"  id="user_code" type="text" name="user_code" placeholder="Personas kods" required>
                    </div>
                    <div class="form-group">
                        <input class="form-input"  id="password" type="password" name="password" placeholder="Parole" required>
                    </div>

                    
                    <button class="large-button" type="submit" name="submit">Pierakstīties</button>
               </div>
            </form>
        </div>
    </div>

</body>
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
</html> 
