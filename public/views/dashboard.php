<?php
// Verificação adicional de segurança
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: /public/');
    exit;
}

// Obter o nome do utilizador
$username = $_SESSION['user'] ?? 'Utilizador';
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Parking Dashboard</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../css/global.css">
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
        <div class="position-sticky pt-3">
          <div class="text-center mb-4">
            <h5 class="text-white">Smart Parking</h5>
            <hr class="bg-light">
          </div>
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="#dashboard" data-bs-toggle="tab">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#sensors" data-bs-toggle="tab">
                <i class="bi bi-broadcast me-2"></i>Sensors
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#actuators" data-bs-toggle="tab">
                <i class="bi bi-toggles me-2"></i>Actuators
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#logs" data-bs-toggle="tab">
                <i class="bi bi-list-ul me-2"></i>Activity Logs
</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#settings" data-bs-toggle="tab">
                <i class="bi bi-gear me-2"></i>Settings
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Main Content -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Smart Parking Dashboard</h1>
          <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
              <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshBtn">
                <i class="bi bi-arrow-clockwise"></i> Refresh
              </button>
              <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#simulateModal">
                <i class="bi bi-play-fill"></i> Simulate
              </button>
            </div>
          </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
          <!-- Dashboard Tab -->
          <div class="tab-pane fade show active" id="dashboard">
            <!-- Status Overview Cards -->
            <div class="row mb-4">
              <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white h-100">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="card-title">Available Spaces</h6>
                        <h2 id="availableSpaces">24</h2>
                      </div>
                      <i class="bi bi-car-front fs-1"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="card bg-success text-white h-100">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="card-title">Active Sensors</h6>
                        <h2 id="activeSensors">32</h2>
                      </div>
                      <i class="bi bi-broadcast fs-1"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark h-100">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="card-title">Actuators</h6>
                        <h2 id="totalActuators">8</h2>
                      </div>
                      <i class="bi bi-toggles fs-1"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white h-100">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="card-title">Alerts</h6>
                        <h2 id="activeAlerts">2</h2>
                      </div>
                      <i class="bi bi-exclamation-triangle fs-1"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Parking Map -->
            <div class="card mb-4">
              <div class="card-header">
                <h5 class="card-title mb-0">Parking Map</h5>
              </div>
              <div class="card-body">
                <div class="parking-map" id="parkingMap">
                  <!-- Parking spaces will be generated by JavaScript -->
                </div>
              </div>
            </div>

            <!-- Quick Actions and Recent Activity -->
            <div class="row">
              <div class="col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                  </div>
                  <div class="card-body">
                    <div class="d-grid gap-2">
                      <button class="btn btn-primary" type="button" id="openEntranceGate">
                        <i class="bi bi-door-open me-2"></i>Open Entrance Gate
</button>
                      <button class="btn btn-primary" type="button" id="openExitGate">
                        <i class="bi bi-door-open me-2"></i>Open Exit Gate
</button>
                      <button class="btn btn-warning" type="button" id="toggleLights">
                        <i class="bi bi-lightbulb me-2"></i>Toggle Lights
</button>
                      <button class="btn btn-danger" type="button" id="emergencyMode">
                        <i class="bi bi-exclamation-triangle me-2"></i>Emergency Mode
</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="recentActivity">
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-car-front me-2 text-primary"></i>Vehicle entered at spot A12</span>
                        <span class="badge bg-primary rounded-pill">2m ago</span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-car-front me-2 text-danger"></i>Vehicle exited from spot B05</span>
                        <span class="badge bg-primary rounded-pill">15m ago</span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Sensor S22 needs maintenance</span>
                        <span class="badge bg-warning rounded-pill">1h ago</span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-toggles me-2 text-success"></i>Entrance gate opened manually</span>
                        <span class="badge bg-primary rounded-pill">3h ago</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Sensors Tab -->
          <div class="tab-pane fade" id="sensors">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h3>Sensors Management</h3>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSensorModal">
                <i class="bi bi-plus-circle me-2"></i>Add New Sensor
</button>
            </div>

            <!-- Sensors Filter -->
            <div class="card mb-4">
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-4">
                      <label for="sensorSearch"></label><input type="text" class="form-control" id="sensorSearch" placeholder="Search sensors...">
                  </div>
                  <div class="col-md-3">
                      <label for="sensorTypeFilter"></label><select class="form-select" id="sensorTypeFilter">
                      <option value="">All Types</option>
                      <option value="presence">Presence</option>
                      <option value="proximity">Proximity</option>
                      <option value="temperature">Temperature</option>
                      <option value="humidity">Humidity</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <select class="form-select" id="sensorStatusFilter">
                      <option value="">All Status</option>
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                      <option value="maintenance">Maintenance</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <button class="btn btn-secondary w-100" id="resetSensorFilters">Reset</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sensors Table -->
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover" id="sensorsTable">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Last Reading</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Sensor rows will be generated by JavaScript -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Actuators Tab -->
          <div class="tab-pane fade" id="actuators">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h3>Actuators Management</h3>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addActuatorModal">
                <i class="bi bi-plus-circle me-2"></i>Add New Actuator
</button>
            </div>

            <!-- Actuators Cards -->
            <div class="row" id="actuatorsContainer">
              <!-- Actuator cards will be generated by JavaScript -->
              <div class="col-md-4 mb-4">
                <div class="card h-100">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Entrance Gate</h5>
                    <span class="badge bg-success">Active</span>
                  </div>
                  <div class="card-body">
                    <p><strong>Type:</strong> Gate</p>
                    <p><strong>Location:</strong> Main Entrance</p>
                    <p><strong>Status:</strong> <span class="text-success">Closed</span></p>
                    <div class="d-grid gap-2">
                      <button class="btn btn-primary actuator-control" data-id="1" data-action="open">
                        <i class="bi bi-door-open me-2"></i>Open
                      </button>
                      <button class="btn btn-secondary actuator-control" data-id="1" data-action="close">
                        <i class="bi bi-door-closed me-2"></i>Close
                      </button>
                    </div>
                  </div>
                  <div class="card-footer d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editActuatorModal" data-id="1">
                      <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-actuator" data-id="1">
                      <i class="bi bi-trash"></i> Delete
                    </button>
                  </div>
                </div>
              </div>

              <div class="col-md-4 mb-4">
                <div class="card h-100">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Exit Gate</h5>
                    <span class="badge bg-success">Active</span>
                  </div>
                  <div class="card-body">
                    <p><strong>Type:</strong> Gate</p>
                    <p><strong>Location:</strong> Main Exit</p>
                    <p><strong>Status:</strong> <span class="text-success">Closed</span></p>
                    <div class="d-grid gap-2">
                      <button class="btn btn-primary actuator-control" data-id="2" data-action="open">
                        <i class="bi bi-door-open me-2"></i>Open
                      </button>
                      <button class="btn btn-secondary actuator-control" data-id="2" data-action="close">
                        <i class="bi bi-door-closed me-2"></i>Close
                      </button>
                    </div>
                  </div>
                  <div class="card-footer d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editActuatorModal" data-id="2">
                      <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-actuator" data-id="2">
                      <i class="bi bi-trash"></i> Delete
                    </button>
                  </div>
                </div>
              </div>

              <div class="col-md-4 mb-4">
                <div class="card h-100">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Zone A Lights</h5>
                    <span class="badge bg-success">Active</span>
                  </div>
                  <div class="card-body">
                    <p><strong>Type:</strong> Lighting</p>
                    <p><strong>Location:</strong> Zone A</p>
                    <p><strong>Status:</strong> <span class="text-warning">On</span></p>
                    <div class="d-grid gap-2">
                      <button class="btn btn-warning actuator-control" data-id="3" data-action="on">
                        <i class="bi bi-lightbulb me-2"></i>Turn On
</button>
                      <button class="btn btn-secondary actuator-control" data-id="3" data-action="off">
                        <i class="bi bi-lightbulb-off me-2"></i>Turn Off
</button>
                    </div>
                  </div>
                  <div class="card-footer d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editActuatorModal" data-id="3">
                      <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-actuator" data-id="3">
                      <i class="bi bi-trash"></i> Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Logs Tab -->
          <div class="tab-pane fade" id="logs">
            <h3 class="mb-4">Activity Logs</h3>

            <!-- Logs Filter -->
            <div class="card mb-4">
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-3">
                    <input type="text" class="form-control" id="logSearch" placeholder="Search logs...">
                  </div>
                  <div class="col-md-3">
                    <select class="form-select" id="logTypeFilter">
                      <option value="">All Types</option>
                      <option value="sensor">Sensor</option>
                      <option value="actuator">Actuator</option>
                      <option value="system">System</option>
                      <option value="user">User</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <input type="date" class="form-control" id="logDateFilter">
                  </div>
                  <div class="col-md-3">
                    <button class="btn btn-secondary w-100" id="resetLogFilters">Reset</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Logs Table -->
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="logsTable">
                    <thead>
                      <tr>
                        <th>Timestamp</th>
                        <th>Type</th>
                        <th>Device</th>
                        <th>Event</th>
                        <th>Details</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Log entries will be generated by JavaScript -->
                      <tr>
                        <td>2023-09-15 08:32:15</td>
                        <td><span class="badge bg-primary">Sensor</span></td>
                        <td>S001</td>
                        <td>Status Change</td>
                        <td>Parking spot A12 is now occupied</td>
                      </tr>
                      <tr>
                        <td>2023-09-15 08:30:05</td>
                        <td><span class="badge bg-success">Actuator</span></td>
                        <td>A001</td>
                        <td>Action</td>
                        <td>Entrance gate opened automatically</td>
                      </tr>
                      <tr>
                        <td>2023-09-15 08:15:22</td>
                        <td><span class="badge bg-warning">System</span></td>
                        <td>System</td>
                        <td>Alert</td>
                        <td>Sensor S022 reporting intermittent connection</td>
                      </tr>
                      <tr>
                        <td>2023-09-15 08:00:00</td>
                        <td><span class="badge bg-info">User</span></td>
                        <td>Admin</td>
                        <td>Login</td>
                        <td>Administrator logged in to the system</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Settings Tab -->
          <div class="tab-pane fade" id="settings">
            <h3 class="mb-4">System Settings</h3>

            <div class="row">
              <div class="col-md-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title mb-0">General Settings</h5>
                  </div>
                  <div class="card-body">
                    <form id="generalSettingsForm">
                      <div class="mb-3">
                        <label for="parkingName" class="form-label">Parking Name</label>
                        <input type="text" class="form-control" id="parkingName" value="Smart City Parking">
                      </div>
                      <div class="mb-3">
                        <label for="totalSpaces" class="form-label">Total Parking Spaces</label>
                        <input type="number" class="form-control" id="totalSpaces" value="40">
                      </div>
                      <div class="mb-3">
                        <label for="refreshInterval" class="form-label">Data Refresh Interval (seconds)</label>
                        <input type="number" class="form-control" id="refreshInterval" value="30">
                      </div>
                      <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="enableNotifications" checked>
                        <label class="form-check-label" for="enableNotifications">Enable Notifications</label>
                      </div>
                      <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                  </div>
                </div>
              </div>

              <div class="col-md-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title mb-0">System Maintenance</h5>
                  </div>
                  <div class="card-body">
                    <div class="d-grid gap-3">
                      <button class="btn btn-outline-primary" id="backupBtn">
                        <i class="bi bi-download me-2"></i>Backup System Data
</button>
                      <button class="btn btn-outline-warning" id="resetBtn">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset System Counters
</button>
                      <button class="btn btn-outline-danger" id="clearLogsBtn">
                        <i class="bi bi-trash me-2"></i>Clear Activity Logs
</button>
                    </div>
                    <hr>
                    <div class="mb-3">
                      <label for="importData" class="form-label">Import Data</label>
                      <input class="form-control" type="file" id="importData">
                    </div>
                    <button class="btn btn-secondary" id="importBtn">
                      <i class="bi bi-upload me-2"></i>Import
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modals -->
  <!-- Add Sensor Modal -->
  <div class="modal fade" id="addSensorModal" tabindex="-1" aria-labelledby="addSensorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addSensorModalLabel">Add New Sensor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addSensorForm">
            <div class="mb-3">
              <label for="sensorName" class="form-label">Sensor Name</label>
              <input type="text" class="form-control" id="sensorName" required>
            </div>
            <div class="mb-3">
              <label for="sensorType" class="form-label">Type</label>
              <select class="form-select" id="sensorType" required>
                <option value="">Select Type</option>
                <option value="presence">Presence</option>
                <option value="proximity">Proximity</option>
                <option value="temperature">Temperature</option>
                <option value="humidity">Humidity</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="sensorLocation" class="form-label">Location</label>
              <input type="text" class="form-control" id="sensorLocation" required>
            </div>
            <div class="mb-3">
              <label for="sensorStatus" class="form-label">Status</label>
              <select class="form-select" id="sensorStatus" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="maintenance">Maintenance</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="saveSensorBtn">Save Sensor</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Actuator Modal -->
  <div class="modal fade" id="addActuatorModal" tabindex="-1" aria-labelledby="addActuatorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addActuatorModalLabel">Add New Actuator</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addActuatorForm">
            <div class="mb-3">
              <label for="actuatorName" class="form-label">Actuator Name</label>
              <input type="text" class="form-control" id="actuatorName" required>
            </div>
            <div class="mb-3">
              <label for="actuatorType" class="form-label">Type</label>
              <select class="form-select" id="actuatorType" required>
                <option value="">Select Type</option>
                <option value="gate">Gate</option>
                <option value="barrier">Barrier</option>
                <option value="lighting">Lighting</option>
                <option value="display">Display</option>
                <option value="alarm">Alarm</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="actuatorLocation" class="form-label">Location</label>
              <input type="text" class="form-control" id="actuatorLocation" required>
            </div>
            <div class="mb-3">
              <label for="actuatorStatus" class="form-label">Status</label>
              <select class="form-select" id="actuatorStatus" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="maintenance">Maintenance</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="saveActuatorBtn">Save Actuator</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Actuator Modal -->
  <div class="modal fade" id="editActuatorModal" tabindex="-1" aria-labelledby="editActuatorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editActuatorModalLabel">Edit Actuator</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editActuatorForm">
            <input type="hidden" id="editActuatorId">
            <div class="mb-3">
              <label for="editActuatorName" class="form-label">Actuator Name</label>
              <input type="text" class="form-control" id="editActuatorName" required>
            </div>
            <div class="mb-3">
              <label for="editActuatorType" class="form-label">Type</label>
              <select class="form-select" id="editActuatorType" required>
                <option value="">Select Type</option>
                <option value="gate">Gate</option>
                <option value="barrier">Barrier</option>
                <option value="lighting">Lighting</option>
                <option value="display">Display</option>
                <option value="alarm">Alarm</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="editActuatorLocation" class="form-label">Location</label>
              <input type="text" class="form-control" id="editActuatorLocation" required>
            </div>
            <div class="mb-3">
              <label for="editActuatorStatus" class="form-label">Status</label>
              <select class="form-select" id="editActuatorStatus" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="maintenance">Maintenance</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="updateActuatorBtn">Update Actuator</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Simulate Modal -->
  <div class="modal fade" id="simulateModal" tabindex="-1" aria-labelledby="simulateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="simulateModalLabel">Simulate Events</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="simulationType" class="form-label">Event Type</label>
            <select class="form-select" id="simulationType">
              <option value="carEnter">Car Entering</option>
              <option value="carExit">Car Exiting</option>
              <option value="sensorFailure">Sensor Failure</option>
              <option value="powerOutage">Power Outage</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="simulationLocation" class="form-label">Location</label>
            <select class="form-select" id="simulationLocation">
              <option value="zoneA">Zone A</option>
              <option value="zoneB">Zone B</option>
              <option value="entrance">Entrance</option>
              <option value="exit">Exit</option>
            </select>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="simulationRepeat">
            <label class="form-check-label" for="simulationRepeat">
Repeat simulation (every 10 seconds)
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="startSimulationBtn">Start Simulation</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Notifications -->
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <strong class="me-auto" id="toastTitle">Notification</strong>
        <small id="toastTime">Just now</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body" id="toastMessage">
    Action completed successfully.
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="js/dashboard.js"></script>
  <script src="js/sensors.js"></script>
  <script src="js/actuators.js"></script>
</body>
</html>

