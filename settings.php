<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings - AI Dashboard</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="assets/css/settings.css" rel="stylesheet" />
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
            <a href="dashboard.php"
              ><i class="bi bi-chat-dots-fill me-2"></i>Chat</a
            >
          </li>
          <li>
            <a href="dashboard.php#features"
              ><i class="bi bi-grid-fill me-2"></i>Features</a
            >
          </li>
          <li>
            <a href="profile.php"
              ><i class="bi bi-person-fill me-2"></i>Profile</a
            >
          </li>
          <li>
            <a href="settings.php"
              ><i class="bi bi-gear-fill me-2"></i>Settings</a
            >
          </li>
          <li>
            <a href="appointments.php"
              ><i class="bi bi-calendar-check me-2"></i>Appointments</a
            >
          </li>
          <li>
            <a href="reports.php"
              ><i class="bi bi-file-medical me-2"></i>Reports</a
            >
          </li>
          <li>
            <a href="goals.php"
              ><i class="bi bi-trophy me-2"></i>Health Goals</a
            >
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
                  data-bs-toggle="dropdown"
                >
                  <i class="bi bi-person-circle fs-5"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="profile.php">Profile</a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="settings.php">Settings</a>
                  </li>
                  <li><hr class="dropdown-divider" /></li>
                  <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
              </div>
            </div>
          </div>
        </nav>

        <!-- Settings Content -->
        <div class="settings-container">
          <div class="row">
            <div class="col-md-3">
              <div class="settings-nav">
                <div class="list-group">
                  <a
                    href="#ai-settings"
                    class="list-group-item list-group-item-action active"
                    data-bs-toggle="list"
                  >
                    <i class="bi bi-robot me-2"></i>AI Assistant
                  </a>
                  <a
                    href="#notification-settings"
                    class="list-group-item list-group-item-action"
                    data-bs-toggle="list"
                  >
                    <i class="bi bi-bell me-2"></i>Notifications
                  </a>
                  <a
                    href="#privacy-settings"
                    class="list-group-item list-group-item-action"
                    data-bs-toggle="list"
                  >
                    <i class="bi bi-shield-lock me-2"></i>Privacy
                  </a>
                  <a
                    href="#account-settings"
                    class="list-group-item list-group-item-action"
                    data-bs-toggle="list"
                  >
                    <i class="bi bi-person-gear me-2"></i>Account
                  </a>
                  <a
                    href="#appearance-settings"
                    class="list-group-item list-group-item-action"
                    data-bs-toggle="list"
                  >
                    <i class="bi bi-palette me-2"></i>Appearance
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-9">
              <div class="tab-content">
                <!-- AI Assistant Settings -->
                <div class="tab-pane fade show active" id="ai-settings">
                  <div class="settings-section">
                    <h3>AI Assistant Settings</h3>
                    <div class="card">
                      <div class="card-body">
                        <div class="setting-item">
                          <div
                            class="d-flex justify-content-between align-items-center mb-3"
                          >
                            <div>
                              <h5>Response Style</h5>
                              <p class="text-muted mb-0">
                                Choose how the AI assistant communicates
                              </p>
                            </div>
                            <select class="form-select" style="width: auto">
                              <option>Professional</option>
                              <option>Casual</option>
                              <option>Technical</option>
                            </select>
                          </div>
                        </div>
                        <div class="setting-item">
                          <div
                            class="d-flex justify-content-between align-items-center mb-3"
                          >
                            <div>
                              <h5>Language Model</h5>
                              <p class="text-muted mb-0">
                                Select the AI model for responses
                              </p>
                            </div>
                            <select class="form-select" style="width: auto">
                              <option>GPT-4</option>
                              <option>GPT-3.5</option>
                              <option>Custom Model</option>
                            </select>
                          </div>
                        </div>
                        <div class="setting-item">
                          <div class="form-check form-switch">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="contextMemory"
                              checked
                            />
                            <label class="form-check-label" for="contextMemory">
                              <h5 class="mb-0">Context Memory</h5>
                              <p class="text-muted mb-0">
                                Remember conversation context
                              </p>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Notification Settings -->
                <div class="tab-pane fade" id="notification-settings">
                  <div class="settings-section">
                    <h3>Notification Settings</h3>
                    <div class="card">
                      <div class="card-body">
                        <div class="setting-item">
                          <div class="form-check form-switch">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="emailNotifications"
                              checked
                            />
                            <label
                              class="form-check-label"
                              for="emailNotifications"
                            >
                              <h5 class="mb-0">Email Notifications</h5>
                              <p class="text-muted mb-0">
                                Receive updates via email
                              </p>
                            </label>
                          </div>
                        </div>
                        <div class="setting-item">
                          <div class="form-check form-switch">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="pushNotifications"
                              checked
                            />
                            <label
                              class="form-check-label"
                              for="pushNotifications"
                            >
                              <h5 class="mb-0">Push Notifications</h5>
                              <p class="text-muted mb-0">
                                Receive browser notifications
                              </p>
                            </label>
                          </div>
                        </div>
                        <div class="setting-item">
                          <div class="form-check form-switch">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="soundNotifications"
                            />
                            <label
                              class="form-check-label"
                              for="soundNotifications"
                            >
                              <h5 class="mb-0">Sound Notifications</h5>
                              <p class="text-muted mb-0">
                                Play sound for new messages
                              </p>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Privacy Settings -->
                <div class="tab-pane fade" id="privacy-settings">
                  <div class="settings-section">
                    <h3>Privacy Settings</h3>
                    <div class="card">
                      <div class="card-body">
                        <div class="setting-item">
                          <div class="form-check form-switch">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="dataCollection"
                              checked
                            />
                            <label
                              class="form-check-label"
                              for="dataCollection"
                            >
                              <h5 class="mb-0">Data Collection</h5>
                              <p class="text-muted mb-0">
                                Allow data collection for improving service
                              </p>
                            </label>
                          </div>
                        </div>
                        <div class="setting-item">
                          <div class="form-check form-switch">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="chatHistory"
                              checked
                            />
                            <label class="form-check-label" for="chatHistory">
                              <h5 class="mb-0">Chat History</h5>
                              <p class="text-muted mb-0">Save chat history</p>
                            </label>
                          </div>
                        </div>
                        <div class="setting-item">
                          <button class="btn btn-danger" id="clearData">
                            Clear All Data
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Account Settings -->
                <div class="tab-pane fade" id="account-settings">
                  <div class="settings-section">
                    <h3>Account Settings</h3>
                    <div class="card">
                      <div class="card-body">
                        <div class="setting-item">
                          <h5>Change Password</h5>
                          <form id="passwordForm">
                            <div class="mb-3">
                              <label class="form-label">Current Password</label>
                              <input type="password" class="form-control" />
                            </div>
                            <div class="mb-3">
                              <label class="form-label">New Password</label>
                              <input type="password" class="form-control" />
                            </div>
                            <div class="mb-3">
                              <label class="form-label"
                                >Confirm New Password</label
                              >
                              <input type="password" class="form-control" />
                            </div>
                            <button type="submit" class="btn btn-primary">
                              Update Password
                            </button>
                          </form>
                        </div>
                        <div class="setting-item mt-4">
                          <h5>Two-Factor Authentication</h5>
                          <div class="form-check form-switch">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="twoFactor"
                            />
                            <label class="form-check-label" for="twoFactor"
                              >Enable 2FA</label
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Appearance Settings -->
                <div class="tab-pane fade" id="appearance-settings">
                  <div class="settings-section">
                    <h3>Appearance Settings</h3>
                    <div class="card">
                      <div class="card-body">
                        <div class="setting-item">
                          <h5>Theme</h5>
                          <div class="theme-options">
                            <div class="form-check">
                              <input
                                class="form-check-input"
                                type="radio"
                                name="theme"
                                id="lightTheme"
                                checked
                              />
                              <label class="form-check-label" for="lightTheme"
                                >Light</label
                              >
                            </div>
                            <div class="form-check">
                              <input
                                class="form-check-input"
                                type="radio"
                                name="theme"
                                id="darkTheme"
                              />
                              <label class="form-check-label" for="darkTheme"
                                >Dark</label
                              >
                            </div>
                            <div class="form-check">
                              <input
                                class="form-check-input"
                                type="radio"
                                name="theme"
                                id="systemTheme"
                              />
                              <label class="form-check-label" for="systemTheme"
                                >System Default</label
                              >
                            </div>
                          </div>
                        </div>
                        <div class="setting-item">
                          <h5>Font Size</h5>
                          <input
                            type="range"
                            class="form-range"
                            min="12"
                            max="20"
                            step="1"
                            id="fontSize"
                          />
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/settings.js"></script>
  </body>
</html>
