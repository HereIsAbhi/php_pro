<?php
include 'db.php';
include 'create_tables.php'; // Include the table creation script
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login_signup.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Handle new goal submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_goal'])) {
  $goal_type = $_POST['goal_type'];
  $goal_title = $_POST['goal_title'];
  $target = $_POST['target'];
  $timeline = $_POST['timeline'];
  $reminders = isset($_POST['reminders']) ? implode(", ", $_POST['reminders']) : '';
  $notes = $_POST['notes'];

  $stmt = $conn->prepare("INSERT INTO health_goals (user_id, goal_type, goal_title, target, timeline, reminders, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("issssss", $user_id, $goal_type, $goal_title, $target, $timeline, $reminders, $notes);

  if ($stmt->execute()) {
    header("Location: goals.php");
    exit();
  } else {
    $error = "Error creating goal: " . $stmt->error;
  }

  $stmt->close();
}

// Handle goal deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_goal'])) {
  $goal_id = $_POST['goal_id'];

  $stmt = $conn->prepare("DELETE FROM health_goals WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $goal_id, $user_id);

  if ($stmt->execute()) {
    header("Location: goals.php");
    exit();
  } else {
    $error = "Error deleting goal: " . $stmt->error;
  }

  $stmt->close();
}

// Handle goal editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_goal'])) {
  $goal_id = $_POST['goal_id'];
  $goal_type = $_POST['goal_type'];
  $goal_title = $_POST['goal_title'];
  $target = $_POST['target'];
  $timeline = $_POST['timeline'];
  $reminders = isset($_POST['reminders']) ? implode(", ", $_POST['reminders']) : '';
  $notes = $_POST['notes'];

  $stmt = $conn->prepare("UPDATE health_goals SET goal_type = ?, goal_title = ?, target = ?, timeline = ?, reminders = ?, notes = ? WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ssssssii", $goal_type, $goal_title, $target, $timeline, $reminders, $notes, $goal_id, $user_id);

  if ($stmt->execute()) {
    header("Location: goals.php");
    exit();
  } else {
    $error = "Error updating goal: " . $stmt->error;
  }

  $stmt->close();
}

// Fetch user's existing goals
$stmt = $conn->prepare("SELECT * FROM health_goals WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$goals = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Health Goals - AI Dashboard</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    rel="stylesheet" />
  <link href="assets/css/styles.css" rel="stylesheet" />
  <link href="assets/css/goals.css" rel="stylesheet" />
</head>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar" class="active">
      <div class="sidebar-header">
        <h3>AI Dashboard</h3>
      </div>
      <ul class="list-unstyled components">
        <li><a href="dashboard.php"><i class="bi bi-chat-dots-fill me-2"></i>Chat</a></li>
        <li><a href="dashboard.php#features"><i class="bi bi-grid-fill me-2"></i>Features</a></li>
        <li><a href="profile.php"><i class="bi bi-person-fill me-2"></i>Profile</a></li>
        <li><a href="settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
        <li><a href="appointments.php"><i class="bi bi-calendar-check me-2"></i>Appointments</a></li>
        <li><a href="reports.php"><i class="bi bi-file-medical me-2"></i>Reports</a></li>
        <li class="active"><a href="goals.php"><i class="bi bi-trophy me-2"></i>Health Goals</a></li>
      </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="bi bi-list"></i>
          </button>
          <div class="ms-auto d-flex align-items-center gap-2">
            <button
              class="btn btn-primary"
              data-bs-toggle="modal"
              data-bs-target="#addGoalModal">
              <i class="bi bi-plus-lg me-2"></i>Add Goal
            </button>
            <div class="dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown">
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

      <!-- Goals List -->

      <div class="container mt-4">
        <div class="row">
          <div class="col-lg-8">
            <h3>Your Goals</h3>
            <div class="list-group">
              <?php foreach ($goals as $goal): ?>
                <div class="list-group-item">
                  <h5><?= htmlspecialchars($goal['goal_title']) ?></h5>
                  <p><strong>Target:</strong> <?= htmlspecialchars($goal['target']) ?></p>
                  <p><strong>Timeline:</strong> <?= htmlspecialchars($goal['timeline']) ?></p>
                  <p><strong>Progress:</strong> <?= $goal['progress'] ?>%</p>

                  <!-- Edit Button -->
                  <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editGoalModal" onclick="loadGoal(<?= $goal['id'] ?>, '<?= htmlspecialchars($goal['goal_type']) ?>', '<?= htmlspecialchars($goal['goal_title']) ?>', '<?= htmlspecialchars($goal['target']) ?>', '<?= htmlspecialchars($goal['timeline']) ?>', '<?= htmlspecialchars($goal['reminders']) ?>', '<?= htmlspecialchars($goal['notes']) ?>')">Edit</button>

                  <!-- Delete Button -->
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="goal_id" value="<?= $goal['id'] ?>">
                    <button type="submit" name="delete_goal" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this goal?')">Delete</button>
                  </form>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit Goal Modal -->
      <div class="modal fade" id="editGoalModal" tabindex="-1" aria-labelledby="editGoalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="post">
              <div class="modal-header">
                <h5 class="modal-title" id="editGoalModalLabel">Edit Goal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="goal_id" id="editGoalId">
                <div class="mb-3">
                  <label class="form-label">Goal Type</label>
                  <select name="goal_type" id="editGoalType" class="form-select" required>
                    <option>Weight Management</option>
                    <option>Exercise</option>
                    <option>Diet</option>
                    <option>Mental Health</option>
                    <option>Sleep</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Goal Title</label>
                  <input type="text" name="goal_title" id="editGoalTitle" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Target</label>
                  <input type="text" name="target" id="editTarget" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Timeline</label>
                  <input type="date" name="timeline" id="editTimeline" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Reminders</label>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="reminders[]" value="Daily">
                    <label class="form-check-label">Daily</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="reminders[]" value="Weekly">
                    <label class="form-check-label">Weekly</label>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Notes</label>
                  <textarea name="notes" id="editNotes" class="form-control"></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="edit_goal" class="btn btn-primary">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <script>
        function loadGoal(id, type, title, target, timeline, reminders, notes) {
          document.getElementById('editGoalId').value = id;
          document.getElementById('editGoalType').value = type;
          document.getElementById('editGoalTitle').value = title;
          document.getElementById('editTarget').value = target;
          document.getElementById('editTimeline').value = timeline;
          document.getElementById('editNotes').value = notes;

          // Set reminders checkboxes
          const reminderList = reminders.split(", ");
          document.querySelectorAll('input[name="reminders[]"]').forEach(input => {
            input.checked = reminderList.includes(input.value);
          });
        }
      </script>


      <!-- Add Goal Modal -->
      <div class="modal fade" id="addGoalModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="post">
              <div class="modal-header">
                <h5 class="modal-title">Add New Goal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Goal Type</label>
                  <select name="goal_type" class="form-select" required>
                    <option>Weight Management</option>
                    <option>Exercise</option>
                    <option>Diet</option>
                    <option>Mental Health</option>
                    <option>Sleep</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Goal Title</label>
                  <input type="text" name="goal_title" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Target</label>
                  <input type="text" name="target" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Timeline</label>
                  <input type="date" name="timeline" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Reminders</label>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="reminders[]" value="Daily">
                    <label class="form-check-label">Daily</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="reminders[]" value="Weekly">
                    <label class="form-check-label">Weekly</label>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Notes</label>
                  <textarea name="notes" class="form-control"></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="add_goal" class="btn btn-primary">Add Goal</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/goals.js"></script>
</body>

</html>