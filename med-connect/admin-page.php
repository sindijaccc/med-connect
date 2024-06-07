<?php
include "db.php";
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <title>MED-CONNECT - admin</title>
</head>
<body>
    <div class="page">
        <div class="main-content">
            
            <div class="main-box">
                <div class="box-field" style="height:100%; width:100%">
                        <div class="box" style="width: 350px; height:500px">
                            <div class="box-title">Visi pacienti</div>
                            <?php
                                // Fetch all patients
                                function getPatients($conn) {
                                    $sql = "SELECT id, first_name, last_name FROM patients";
                                    $result = $conn->query($sql);
                                    $patients = [];
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $patients[] = $row;
                                        }
                                    }
                                    return $patients;
                                }

                                // Fetch patient records
                                function getPatientRecords($conn, $patient_id) {
                                    $sql ="SELECT id, record_text, date
                                            FROM patient_records
                                            WHERE patient_id = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("i", $patient_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $records = [];
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $records[] = $row;
                                        }
                                    }
                                    return $records;
                                }

                                // Get all patients
                                $patients = getPatients($conn);

                                // If a patient is selected, get their records
                                $selected_patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : null;
                                $patient_records = [];
                                if ($selected_patient_id) {
                                    $patient_records = getPatientRecords($conn, $selected_patient_id);
                                }

                                ?>

                                <div class="form-group">
                                    <form method="GET" action="">
                                        <select class="form-select" name="patient_id" onchange="this.form.submit()">
                                            <option value="">Izvēlieties lietotāju</option>
                                            <?php foreach ($patients as $patient): ?>
                                                <option value="<?php echo $patient['id']; ?>" <?php echo ($selected_patient_id == $patient['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </div>

                        <?php if (!empty($patient_records)): ?>
                            <?php foreach ($patient_records as $record): ?>
                                <div class="info-box">
                                    <div>
                                        <?php echo htmlspecialchars($record['record_text']); ?>
                                    </div>
                                    <div class="info-box-date">
                                        <?php echo htmlspecialchars($record['date']); ?>
                                    </div>                                    
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Šim pacientam ieraksti vel nav izveidoti.</p>
                        <?php endif; ?>
                    </div>

                            

                        <div class="box" style="width: 350px; height:500px;">
                        <div class="box-title">Visi ārsti</div>
                        <?php
                            // Fetch all doctors
                            function getDoctors($conn) {
                                $sql = "SELECT id, first_name, last_name, email, phone FROM doctors";
                                $result = $conn->query($sql);
                                $doctors = [];
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $doctors[] = $row;
                                    }
                                }
                                return $doctors;
                            }

                            // Fetch doctor profiles
                            function getDoctorProfile($conn, $doctor_id) {
                                $sql = "SELECT id, first_name, last_name, email, phone, occupation FROM doctors WHERE id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $doctor_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $profile = null;
                                if ($result->num_rows > 0) {
                                    $profile = $result->fetch_assoc();
                                }
                                return $profile;
                            }

                            // Get all doctors
                            $doctors = getDoctors($conn);

                            // If a doctor is selected, get their profile
                            $selected_doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : null;
                            $doctor_profile = null;
                            if ($selected_doctor_id) {
                                $doctor_profile = getDoctorProfile($conn, $selected_doctor_id);
                            }
                        ?>

                        <div class="form-group">
                            <form method="GET" action="">
                                <select class="form-select" name="doctor_id" onchange="this.form.submit()">
                                    <option value="">Izvēlieties ārstu</option>
                                    <?php foreach ($doctors as $doctor): ?>
                                        <option value="<?php echo $doctor['id']; ?>" <?php echo ($selected_doctor_id == $doctor['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>

                        <?php if ($doctor_profile): ?>
                            <div class="info-box" style="flex-direction:column;">
                                <div class="info-box-title">
                                    <?php echo htmlspecialchars($doctor_profile['first_name'] . ' ' . $doctor_profile['last_name']); ?>
                                </div>
                                <div>
                                    <?php echo "E-pasts: " . htmlspecialchars($doctor_profile['email']); ?>
                                </div>
                                <div>
                                    <?php echo "Tel.nr.: " . htmlspecialchars($doctor_profile['phone']); ?>
                                </div>
                                <div>
                                    <?php echo "Specialitāte: " . htmlspecialchars($doctor_profile['occupation']); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <p>Šim ārstam profila informācija nav atrasta.</p>
                        <?php endif; ?>
                        </div>


                            <div class="box" style="width: 350px; height:500px">
                                <div class="box-title" style="margin-bottom: 0;">Pievienot lietotāju</div>
                                <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="form-fieldset">
                                            
                                        <div class="form-group">
                                        <select class="form-select" style="margin-top: 5px;" name="user_type">
                                            <option value="pacients">Pacients</option> <!-- table - patients -->
                                            <option value="arsts">Ārsts</option> <!-- table - doctors -->
                                        </select>
                                        </div>

                                <div class="form-group">
                                                <input class="form-input2" type="text" name="first_name" id="first_name" placeholder="Lietotāja vārds">
                                            </div>

                                            <div class="form-group">
                                                <input class="form-input2" type="text" name="last_name" id="last_name" placeholder="Lietotāja uzvārds">
                                            </div>

                                            <div class="form-group">
                                                <input class="form-input2" type="email" name="email" id="email" placeholder="Lietotāja e-pasts">
                                            </div>

                                            <div class="form-group">
                                                <input class="form-input2" type="tel" name="phone" id="phone" placeholder="Lietotāja telefona numurs">
                                            </div>

                                            <div class="form-group">
                                                <input class="form-input2" type="text" name="user_code" id="user_code" placeholder="Lietotāja personas kods">
                                            </div>

                                            <div class="form-group">
                                                <input class="form-input2" type="password" name="password" id="password" placeholder="Lietotāja piekļuves parole">
                                            </div>

                                            <div class="form-group">
                                                <textarea class="form-textarea" name="med_history" placeholder="Ievadiet nozīmīgu info šeit..." style="height: 50px;"></textarea>
                                            </div>

                                            <button class="main-button" type="submit" name="submit" style="margin-top: 0;">Pievienot lietotāju</button>
                                        </div>
                                        <?php
                                        // Function to handle form submission
                                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
                                            $user_type = $_POST["user_type"];
                                            $first_name = $_POST["first_name"];
                                            $last_name = $_POST["last_name"];
                                            $email = $_POST["email"];
                                            $phone = $_POST["phone"];
                                            $user_code = $_POST["user_code"];
                                            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                                            $medical_info = $_POST["med_history"];

                                            // Prepared statement to insert data into the appropriate table
                                            if ($user_type === "pacients") {
                                                $sql = $conn->prepare("INSERT INTO patients (first_name, last_name, email, phone, user_code, password, med_history) VALUES (?, ?, ?, ?, ?, ?, ?)");
                                            } elseif ($user_type === "arsts") {
                                                $sql = $conn->prepare("INSERT INTO doctors (first_name, last_name, email, phone, user_code, password, occupation) VALUES (?, ?, ?, ?, ?, ?, ?)");
                                            }

                                            if ($sql) {
                                                $sql->bind_param("sssssss", $first_name, $last_name, $email, $phone, $user_code, $password, $medical_info);
                                                if ($sql->execute()) {
                                                    echo "Lietotājs pievienots!";
                                                } else {
                                                    echo "Kļūda: " . $sql->error;
                                                }
                                                $sql->close();
                                            } else {
                                                echo "Kļūda sagatavojot pieprasījumu: " . $conn->error;
                                            }
                                        }?>
                                </form>
                            </div>
                    
                </div>

            </div> <!-- /* main box -->

            <footer class="center-horizontal" style="display: flex; flex-direction: row; justify-content: end;">
                <button class="red-button" onclick="document.location.href='log-out.php?id<?php echo "0"?>';">Iziet</button>
            </footer>

        </div> <!-- /* main content -->
    </div> <!-- /* page -->

</body>
</html>   
