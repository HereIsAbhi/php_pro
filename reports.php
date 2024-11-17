<?php
// Start the session to manage user data
session_start();

include 'db.php';
include 'create_tables.php'; // Include the table creation script

// If the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Handling file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['report_file'])) {
    $report_type = $_POST['report_type'];
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $notes = $_POST['notes'];

    $file_name = $_FILES['report_file']['name'];
    $file_tmp_name = $_FILES['report_file']['tmp_name'];
    $file_size = $_FILES['report_file']['size'];
    $file_error = $_FILES['report_file']['error'];

    // Validate file upload
    if ($file_error === 0) {
        // Set a target path for the file
        $target_dir = "uploads/reports/";
        $target_file = $target_dir . basename($file_name);

        // Move the file to the server directory
        if (move_uploaded_file($file_tmp_name, $target_file)) {
            // Insert data into the database, including the user_id from the session
            $stmt = $conn->prepare("INSERT INTO reports (user_id, file_type, doctor, date, file_path, notes) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $user_id, $report_type, $doctor, $date, $target_file, $notes);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Error with file upload.";
    }
}

// Fetch reports from the database
$sql = "SELECT * FROM reports ORDER BY upload_date DESC";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Medical Reports - AI Dashboard</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="assets/css/reports.css" rel="stylesheet" />
  </head>
  <body>
    <div class="wrapper">
      <nav id="sidebar" class="active">
        <div class="sidebar-header">
          <h3>AI Dashboard</h3>
        </div>
        <ul class="list-unstyled components">
          <li class="active">
            <a href="dashboard.php"><i class="bi bi-chat-dots-fill me-2"></i>Chat</a>
          </li>
          <li>
            <a href="dashboard.php#features"><i class="bi bi-grid-fill me-2"></i>Features</a>
          </li>
          <li>
            <a href="profile.php"><i class="bi bi-person-fill me-2"></i>Profile</a>
          </li>
          <li>
            <a href="settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a>
          </li>
          <li>
            <a href="appointments.php"><i class="bi bi-calendar-check me-2"></i>Appointments</a>
          </li>
          <li>
            <a href="reports.php"><i class="bi bi-file-medical me-2"></i>Reports</a>
          </li>
          <li>
            <a href="goals.php"><i class="bi bi-trophy me-2"></i>Health Goals</a>
          </li>
        </ul>
      </nav>

      <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn btn-primary">
              <i class="bi bi-list"></i>
            </button>
            <div class="ms-2">
              <input type="text" class="form-control" placeholder="Search reports..." />
            </div>
            <div class="ms-auto d-flex align-items-center gap-2">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadReportModal">
                <i class="bi bi-upload me-2"></i>Upload Report
              </button>
              <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                  <i class="bi bi-person-circle fs-5"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                  <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                  <li><hr class="dropdown-divider" /></li>
                  <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
              </div>
            </div>
          </div>
        </nav>

        <div class="reports-container">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Medical Reports & Prescriptions</h4>
                    <div class="btn-group">
                      <button class="btn btn-outline-primary active">All</button>
                      <button class="btn btn-outline-primary">Reports</button>
                      <button class="btn btn-outline-primary">Prescriptions</button>
                    </div>
                  </div>
                  <div class="reports-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                      <div class="report-item">
                        <div class="report-icon">
                          <i class="bi bi-file-earmark-medical"></i>
                        </div>
                        <div class="report-details">
                          <h5><?= htmlspecialchars($row['file_type']) ?></h5>
                          <p><?= htmlspecialchars($row['doctor']) ?></p>
                          <small><?= htmlspecialchars($row['date']) ?></small>
                        </div>
                        <div class="report-actions">
                          <a href="<?= htmlspecialchars($row['file_path']) ?>" class="btn btn-sm btn-primary" download>
                            <i class="bi bi-download"></i> Download
                          </a>
                          <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> View
                          </button>
                        </div>
                      </div>
                    <?php endwhile; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Upload Report Modal -->
        <div class="modal fade" id="uploadReportModal" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Upload Medical Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <form action="reports.php" method="POST" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label class="form-label">Report Type</label>
                    <select name="report_type" class="form-select">
                      <option>Medical Report</option>
                      <option>Prescription</option>
                      <option>Lab Test</option>
                      <option>X-Ray/Scan</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Doctor/Healthcare Provider</label>
                    <input name="doctor" type="text" class="form-control" />
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input name="date" type="date" class="form-control" />
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Upload File</label>
                    <input name="report_file" type="file" class="form-control" />
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Notes (optional)</label>
                    <textarea name="notes" class="form-control"></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary w-100">Upload Report</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/reports.js"></script>
  </body>
</html>
