<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to login page if not logged in
  header("Location: login_signup.php");
  exit();
}

// Access user data from session
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];
$user_phone = $_SESSION['user_phone'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AI Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="assets/css/styles.css" rel="stylesheet" />
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
            <input type="text" class="form-control" placeholder="Search..." />
          </div>
          <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle fs-5"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="profile.php">Profile</a>
                </li>
                <li>
                  <a class="dropdown-item" href="settings.php">Settings</a>
                </li>
                <li>
                  <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

      <div class="chat-container">
        <div class="chat-messages" id="chatMessages">
          <div class="message ai-message">
            <div class="message-content">
              <i class="bi bi-robot"></i>
              <div class="message-text">
                Hello! How can I assist you today?
              </div>
            </div>
          </div>
          <div class="message user-message">
            <div class="message-content">
              <i class="bi bi-person"></i>
              <div class="message-text">
                Hi! Can you help me with some information?
              </div>
            </div>
          </div>
        </div>
        <div class="chat-input-container">
          <div class="input-group">
            <textarea class="form-control" placeholder="Type your message here..." rows="1"></textarea>
            <button class="btn btn-primary">
              <i class="bi bi-send"></i>
            </button>
          </div>
        </div>
      </div>

      <section id="features" class="features-section">
        <div class="container">
          <div class="section-title text-center mb-5">
            <h2>Our Features</h2>
            <p>Experience the power of AI-driven healthcare assistance</p>
          </div>

          <div class="row justify-content-center">
            <div class="col-md-8">
              <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" data-bs-toggle="tab" href="#chat-assistant">
                    <i class="bi bi-chat-dots me-2"></i>Smart Chat Assistant
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-bs-toggle="tab" href="#intelligent-responses">
                    <i class="bi bi-brain me-2"></i>Intelligent Responses
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-bs-toggle="tab" href="#data-analysis">
                    <i class="bi bi-graph-up me-2"></i>Data Analysis
                  </a>
                </li>
              </ul>

              <div class="tab-content">
                <div class="tab-pane fade show active" id="chat-assistant">
                  <div class="feature-content">
                    <h3>Smart Chat Assistant</h3>
                    <p class="text-muted">
                      Experience natural conversations with our AI-powered
                      chat assistant
                    </p>
                    <ul class="feature-list">
                      <li>
                        <i class="bi bi-check2-circle"></i> Natural language
                        processing for human-like interactions
                      </li>
                      <li>
                        <i class="bi bi-check2-circle"></i> 24/7 availability
                        for instant responses
                      </li>
                      <li>
                        <i class="bi bi-check2-circle"></i> Context-aware
                        conversations for better understanding
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="tab-pane fade" id="intelligent-responses">
                  <div class="feature-content">
                    <h3>Intelligent Responses</h3>
                    <p class="text-muted">
                      Get accurate and contextual responses powered by
                      advanced AI
                    </p>
                    <ul class="feature-list">
                      <li>
                        <i class="bi bi-check2-circle"></i> Machine
                        learning-based response generation
                      </li>
                      <li>
                        <i class="bi bi-check2-circle"></i> Adaptive learning
                        from user interactions
                      </li>
                      <li>
                        <i class="bi bi-check2-circle"></i> Multi-language
                        support for global accessibility
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="tab-pane fade" id="data-analysis">
                  <div class="feature-content">
                    <h3>Data Analysis</h3>
                    <p class="text-muted">
                      Leverage powerful analytics for deeper insights
                    </p>
                    <ul class="feature-list">
                      <li>
                        <i class="bi bi-check2-circle"></i> Real-time data
                        processing and analysis
                      </li>
                      <li>
                        <i class="bi bi-check2-circle"></i> Customizable
                        analytics dashboard
                      </li>
                      <li>
                        <i class="bi bi-check2-circle"></i> Trend
                        identification and reporting
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/chat.js"></script>
</body>

</html>