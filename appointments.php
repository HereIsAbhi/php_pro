<?php
session_start();
include('db.php');
include('create_tables.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: login_signup.php"); // Redirect if not logged in
  exit();
}

$user_id = $_SESSION['user_id'];
$success_message = $error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $doctor_name = $_POST['doctor_name'];
  $appointment_date = $_POST['appointment_date'];
  $appointment_time = $_POST['appointment_time'];
  $notes = $_POST['notes'];

  $sql_insert = "INSERT INTO appointments (user_id, doctor_name, appointment_date, appointment_time, notes)
                   VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql_insert);
  $stmt->bind_param("issss", $user_id, $doctor_name, $appointment_date, $appointment_time, $notes);

  if ($stmt->execute()) {
    header("Location: appointments.php");
  } else {
    $error_message = "Error scheduling appointment: " . $conn->error;
  }
  $stmt->close();
}

// Fetch upcoming appointments
$sql_fetch = "SELECT doctor_name, appointment_date, appointment_time, notes 
              FROM appointments 
              WHERE user_id = ? AND appointment_date >= CURDATE() 
              ORDER BY appointment_date, appointment_time ASC";
$stmt = $conn->prepare($sql_fetch);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Appointments - AI Dashboard</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    rel="stylesheet" />
  <link href="assets/css/styles.css" rel="stylesheet" />
  <link href="assets/css/appointments.css" rel="stylesheet" />
</head>

<body>
  <div class="wrapper">
    <nav id="sidebar" class="active">
      <div class="sidebar-header">
        <h3>AI Dashboard</h3>
      </div>
      <ul class="list-unstyled components">
        <li><a href="dashboard.php"><i class="bi bi-chat-dots-fill me-2"></i>Chat</a></li>
        <li><a href="dashboard.php#features"><i class="bi bi-grid-fill me-2"></i>Features</a></li>
        <li><a href="profile.php"><i class="bi bi-person-fill me-2"></i>Profile</a></li>
        <li><a href="settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
        <li class="active"><a href="appointments.php"><i class="bi bi-calendar-check me-2"></i>Appointments</a></li>
        <li><a href="reports.php"><i class="bi bi-file-medical me-2"></i>Reports</a></li>
        <li><a href="goals.php"><i class="bi bi-trophy me-2"></i>Health Goals</a></li>
      </ul>
    </nav>

    <div id="content">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="bi bi-list"></i>
          </button>
          <div class="ms-2">
            <input type="text" class="form-control" placeholder="Search..." />
          </div>
          <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle fs-5"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                <li>
                  <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

      <div class="appointments-container">
        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title mb-4">Schedule Appointment</h4>
                <form id="appointmentForm" method="POST" action="appointments.php">
                  <div class="mb-3">
                    <label class="form-label">Doctor/Physician</label>
                    <select class="form-select" name="doctor_name" required>
                      <option>Dr. Sarah Johnson - Cardiologist</option>
                      <option>Dr. Michael Chen - General Physician</option>
                      <option>Dr. Emily Brown - Neurologist</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="appointment_date" required />
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Time Slot</label>
                    <select class="form-select" name="appointment_time" required>
                      <option>09:00:00</option>
                      <option>10:00:00</option>
                      <option>11:00:00</option>
                      <option>14:00:00</option>
                      <option>15:00:00</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Reason for Visit</label>
                    <textarea class="form-control" rows="3" name="notes"></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Schedule Appointment</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title mb-4">Upcoming Appointments</h4>
                <div class="upcoming-appointments">
                  <?php if (empty($appointments)) { ?>
                    <p>No upcoming appointments.</p>
                  <?php } else { ?>
                    <?php foreach ($appointments as $appointment) { ?>
                      <div class="appointment-item">
                        <div class="appointment-date">
                          <span class="day"><?= date('d', strtotime($appointment['appointment_date'])) ?></span>
                          <span class="month"><?= date('M', strtotime($appointment['appointment_date'])) ?></span>
                        </div>
                        <div class="appointment-details">
                          <h6><?= htmlspecialchars($appointment['doctor_name']) ?></h6>
                          <p><?= htmlspecialchars($appointment['notes']) ?></p>
                          <small><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></small>
                        </div>
                      </div>
                    <?php } ?>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/appointments.js"></script>
</body>

</html>