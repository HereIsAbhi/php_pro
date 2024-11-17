<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php"); // Redirect to login if not logged in
  exit;
}
$user_id = $_SESSION['user_id'];

// Fetch user details from the database
include 'db.php'; // Database connection file
include 'create_tables.php';
$query = $conn->prepare("SELECT firstname,lastname, email, phone FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
$query->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile - AI Dashboard</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    rel="stylesheet" />
  <link href="assets/css/styles.css" rel="stylesheet" />
  <link href="assets/css/profile.css" rel="stylesheet" />
</head>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
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

    <!-- Page Content -->
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
              <a
                class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown">
                <i class="bi bi-person-circle fs-5"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="profile.php">Profile</a>
                </li>
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

      <!-- Profile Content -->
      <div class="profile-container">
        <div class="profile-header">
          <div class="profile-cover"></div>
          <div class="profile-avatar">
            <img
              src="https://via.placeholder.com/150"
              alt="Profile Picture"
              class="avatar" />
            <button class="btn btn-light btn-sm change-avatar">
              <i class="bi bi-camera"></i>
            </button>
          </div>
        </div>

        <div class="profile-content">
          <div class="row">
            <div class="col-md-4">
              <div class="card profile-card">
                <div class="card-body">
                  <h5 class="card-title">Personal Information</h5>
                  <div class="profile-info">
                    <p><i class="bi bi-person"></i> <?php echo $user['firstname'] . " " . $user['lastname']; ?></p>
                    <p><i class="bi bi-envelope"></i> <?php echo $user['email']; ?></p>
                    <p><i class="bi bi-telephone"></i> <?php echo $user['phone']; ?></p>
                  </div>
                  <button
                    class="btn btn-primary w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#editProfileModal">
                    Edit Profile
                  </button>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Account Statistics</h5>
                  <div class="row stats">
                    <div class="col-md-4">
                      <div class="stat-item">
                        <i class="bi bi-chat-dots"></i>
                        <h3>1,234</h3>
                        <p>Chat Sessions</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="stat-item">
                        <i class="bi bi-clock-history"></i>
                        <h3>56h</h3>
                        <p>Total Time</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="stat-item">
                        <i class="bi bi-star"></i>
                        <h3>4.8</h3>
                        <p>Average Rating</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card mt-4">
                <div class="card-body">
                  <h5 class="card-title">Recent Activity</h5>
                  <div class="activity-list">
                    <div class="activity-item">
                      <i class="bi bi-chat-text"></i>
                      <div class="activity-details">
                        <h6>Chat Session</h6>
                        <p>Completed a 30-minute chat session</p>
                        <small>2 hours ago</small>
                      </div>
                    </div>
                    <div class="activity-item">
                      <i class="bi bi-gear"></i>
                      <div class="activity-details">
                        <h6>Settings Updated</h6>
                        <p>Changed notification preferences</p>
                        <small>Yesterday</small>
                      </div>
                    </div>
                    <div class="activity-item">
                      <i class="bi bi-person"></i>
                      <div class="activity-details">
                        <h6>Profile Updated</h6>
                        <p>Updated profile information</p>
                        <small>3 days ago</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  </div>


  <!-- Edit Profile Modal -->
  <div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Profile</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <form id="profileForm" method="POST" action="update_profile.php">
            <div class="mb-3">
              <label class="form-label">First Name</label>
              <input type="text" class="form-control" name="firstname" value="<?php echo $user['firstname']; ?>" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-control" name="lastname" value="<?php echo $user['lastname']; ?>" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input
                type="email"
                class="form-control"
                name="email"
                value="<?php echo $user['email']; ?>"
                required />
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input
                type="tel"
                class="form-control"
                name="phone"
                value="<?php echo $user['phone']; ?>"
                required />
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal">
            Cancel
          </button>
          <button type="submit" form="profileForm" class="btn btn-primary">
            Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/profile.js"></script>
</body>

</html>