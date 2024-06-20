<?php
include "db.php";

// Validate the 'id' parameter
$patient_id = isset($_GET["id"]) ? intval($_GET["id"]) : null;

// Fetch patient records
function getPatientRecords($conn, $patient_id) {
    $sql = "SELECT id, record_text, date FROM patient_records WHERE patient_id = ?";
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

// Fetch doctor responses
function getDoctorResponses($conn, $patient_id) {
    $sql = "SELECT dr.record_id, dr.record_response, d.first_name, d.last_name 
            FROM doctor_responses dr
            JOIN doctors d ON dr.doctor_id = d.id
            WHERE dr.patient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $responses = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $responses[] = $row;
        }
    }
    return $responses;
}

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

// Fetch doctor profile
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

// Get patient records and doctor responses if patient ID is set
$patient_records = [];
$doctor_responses = [];

if ($patient_id) {
    $patient_records = getPatientRecords($conn, $patient_id);
    $doctor_responses = getDoctorResponses($conn, $patient_id);
}

// Handle new record submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['record_text']) && isset($_POST['doctor_id']) && isset($_POST['patient_id'])) {
    $record_text = $_POST['record_text'];
    $doctor_id = intval($_POST['doctor_id']);
    $patient_id = intval($_POST['patient_id']);
    $stmt = $conn->prepare("INSERT INTO patient_records (patient_id, record_text, doctor_id, date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("isi", $patient_id, $record_text, $doctor_id);
    $stmt->execute();
    header("Location: user-page.php?id=$patient_id");
    exit;
}

// Get all doctors and selected doctor profile
$doctors = getDoctors($conn);
$selected_doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : null;
$doctor_profile = null;

if ($selected_doctor_id) {
    $doctor_profile = getDoctorProfile($conn, $selected_doctor_id);
}
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
    <title>MED-CONNECT - user</title>
</head>
<body>
    <div class="page">
        <div class="main-content">
            <div class="main-box">
                <div class="box-field" style="height:100%; width:100%">
                    <div class="box" style="width: 400px; height:500px">
                        <div class="box-title" style="margin-bottom: 24px;">Mani ieraksti</div>
                        <?php if (!empty($patient_records)): ?>
                            <?php foreach ($patient_records as $record): ?>
                                <div class="info-box">
                                    <div><?php echo htmlspecialchars($record['record_text']); ?></div>
                                    <div class="info-box-date"><?php echo htmlspecialchars($record['date']); ?></div>
                                <?php foreach ($doctor_responses as $response): ?>
                                    <?php if ($response['record_id'] == $record['id']): ?>
                                        <div class="info-box-response">
                                            <div class="info-box-title">
                                                Atbilde no <?php echo htmlspecialchars($response['first_name'] . ' ' . $response['last_name']); ?>
                                            </div>
                                            <div><?php echo htmlspecialchars($response['record_response']); ?></div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Šim pacientam ieraksti vel nav izveidoti.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="box" style="width: 350px; height:500px">
                        <div class="box-title">Veikt jaunu ierakstu</div>
                        <form class="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="form-fieldset">
                                <div>
                                    <p>Ziņojums</p>
                                </div>
                                <div class="form-group">
                                    <select class="form-select" name="doctor_id">
                                        <option value="">Izvēlies ārstu</option>
                                        <?php foreach ($doctors as $doctor): ?>
                                            <option value="<?php echo $doctor['id']; ?>"><?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-textarea" name="record_text" placeholder="Informācija par veselības stāvokli"></textarea>
                                </div>
                                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                                <button class="main-button" type="submit">Nosūtīt ziņojumu</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="box" style="width: 400px; height:500px">
                        <div class="box-title">Visi ārsti</div>
                        <div class="form-group">
                            <form method="GET" action="">
                                <input type="hidden" name="id" value="<?php echo $patient_id; ?>">
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
                                <div><?php echo "E-pasts: " . htmlspecialchars($doctor_profile['email']); ?></div>
                                <div><?php echo "Tel.nr.: " . htmlspecialchars($doctor_profile['phone']); ?></div>
                                <div><?php echo "Specialitāte: " . htmlspecialchars($doctor_profile['occupation']); ?></div>
                            </div>
                        <?php else: ?>
                            <p>Šim ārstam profila informācija nav atrasta.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div> <!-- main box -->

            <footer class="center-horizontal" style="display: flex; flex-direction: row; justify-content: end;">
                <button class="red-button" onclick="document.location.href='log-out.php?id=<?php echo $patient_id; ?>';">Iziet</button>
            </footer>
        </div> <!-- main content -->
    </div> <!-- page -->
</body>
</html>

