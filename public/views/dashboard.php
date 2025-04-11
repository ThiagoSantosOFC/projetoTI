<?php
// Verificação adicional de segurança
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: /');
    exit;
}

// Obter o nome do utilizador
$username = $_SESSION['user'] ?? 'Utilizador';

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Estacionamento Inteligente</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/css/dashboard.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4">
                    <h5 class="text-white">Estacionamento Inteligente</h5>
                    <hr class="bg-light">
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#dashboard" data-bs-toggle="tab">
                            <i class="bi bi-speedometer2 me-2"></i>Painel Principal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sensors" data-bs-toggle="tab">
                            <i class="bi bi-broadcast me-2"></i>Sensores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#actuators" data-bs-toggle="tab">
                            <i class="bi bi-toggles me-2"></i>Atuadores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#logs" data-bs-toggle="tab">
                            <i class="bi bi-list-ul me-2"></i>Registos de Atividade
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#settings" data-bs-toggle="tab">
                            <i class="bi bi-gear me-2"></i>Configurações
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Painel de Estacionamento Inteligente</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshBtn">
                            <i class="bi bi-arrow-clockwise"></i> Atualizar
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#simulateModal">
                            <i class="bi bi-play-fill"></i> Simular
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
                                            <h6 class="card-title">Lugares Disponíveis</h6>
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
                                            <h6 class="card-title">Sensores Ativos</h6>
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
                                            <h6 class="card-title">Atuadores</h6>
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
                                            <h6 class="card-title">Alertas</h6>
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
                            <h5 class="card-title mb-0">Mapa do Estacionamento</h5>
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
                                    <h5 class="card-title mb-0">Ações Rápidas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" type="button" id="openEntranceGate">
                                            <i class="bi bi-door-open me-2"></i>Abrir Cancela de Entrada
                                        </button>
                                        <button class="btn btn-primary" type="button" id="openExitGate">
                                            <i class="bi bi-door-open me-2"></i>Abrir Cancela de Saída
                                        </button>
                                        <button class="btn btn-warning" type="button" id="toggleLights">
                                            <i class="bi bi-lightbulb me-2"></i>Alternar Iluminação
                                        </button>
                                        <button class="btn btn-danger" type="button" id="emergencyMode">
                                            <i class="bi bi-exclamation-triangle me-2"></i>Modo de Emergência
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Atividade Recente</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush" id="recentActivity">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="bi bi-car-front me-2 text-primary"></i>Veículo entrou no lugar A12</span>
                                            <span class="badge bg-primary rounded-pill">2m atrás</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="bi bi-car-front me-2 text-danger"></i>Veículo saiu do lugar B05</span>
                                            <span class="badge bg-primary rounded-pill">15m atrás</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Sensor S22 necessita manutenção</span>
                                            <span class="badge bg-warning rounded-pill">1h atrás</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="bi bi-toggles me-2 text-success"></i>Cancela de entrada aberta manualmente</span>
                                            <span class="badge bg-primary rounded-pill">3h atrás</span>
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
                        <h3>Gestão de Sensores</h3>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSensorModal">
                            <i class="bi bi-plus-circle me-2"></i>Adicionar Novo Sensor
                        </button>
                    </div>

                    <!-- Sensors Filter -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="sensorSearch"></label><input type="text" class="form-control" id="sensorSearch" placeholder="Pesquisar sensores...">
                                </div>
                                <div class="col-md-3">
                                    <label for="sensorTypeFilter"></label><select class="form-select" id="sensorTypeFilter">
                                        <option value="">Todos os Tipos</option>
                                        <option value="presenca">Presença</option>
                                        <option value="proximidade">Proximidade</option>
                                        <option value="temperatura">Temperatura</option>
                                        <option value="humidade">Humidade</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="sensorStatusFilter"></label><select class="form-select" id="sensorStatusFilter">
                                        <option value="">Todos os Estados</option>
                                        <option value="ativo">Ativo</option>
                                        <option value="inativo">Inativo</option>
                                        <option value="manutencao">Manutenção</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary w-100" id="resetSensorFilters">Limpar</button>
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
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Localização</th>
                                        <th>Estado</th>
                                        <th>Última Leitura</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Sensor rows will be generated by JavaScript -->
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">A carregar...</span>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actuators Tab -->
                <div class="tab-pane fade" id="actuators">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3>Gestão de Atuadores</h3>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addActuatorModal">
                            <i class="bi bi-plus-circle me-2"></i>Adicionar Novo Atuador
                        </button>
                    </div>

                    <!-- Actuators Cards -->
                    <div class="row" id="actuatorsContainer">
                        <!-- Actuator cards will be generated by JavaScript -->
                        <div class="col-12 text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">A carregar...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logs Tab -->
                <div class="tab-pane fade" id="logs">
                    <h3 class="mb-4">Registos de Atividade</h3>

                    <!-- Logs Filter -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="logSearch"></label><input type="text" class="form-control" id="logSearch" placeholder="Pesquisar registos...">
                                </div>
                                <div class="col-md-3">
                                    <label for="logTypeFilter"></label><select class="form-select" id="logTypeFilter">
                                        <option value="">Todos os Tipos</option>
                                        <option value="sensor">Sensor</option>
                                        <option value="atuador">Atuador</option>
                                        <option value="sistema">Sistema</option>
                                        <option value="utilizador">Utilizador</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="logDateFilter"></label><input type="date" class="form-control" id="logDateFilter">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-secondary w-100" id="resetLogFilters">Limpar</button>
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
                                        <th>Data/Hora</th>
                                        <th>Tipo</th>
                                        <th>Dispositivo</th>
                                        <th>Evento</th>
                                        <th>Detalhes</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Log entries will be generated by JavaScript -->
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">A carregar...</span>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div class="tab-pane fade" id="settings">
                    <h3 class="mb-4">Configurações do Sistema</h3>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Configurações Gerais</h5>
                                </div>
                                <div class="card-body">
                                    <form id="generalSettingsForm">
                                        <div class="mb-3">
                                            <label for="parkingName" class="form-label">Nome do Estacionamento</label>
                                            <input type="text" class="form-control" id="parkingName" value="Estacionamento Smart City">
                                        </div>
                                        <div class="mb-3">
                                            <label for="totalSpaces" class="form-label">Total de Lugares</label>
                                            <input type="number" class="form-control" id="totalSpaces" value="40">
                                        </div>
                                        <div class="mb-3">
                                            <label for="refreshInterval" class="form-label">Intervalo de Atualização (segundos)</label>
                                            <input type="number" class="form-control" id="refreshInterval" value="30">
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="enableNotifications" checked>
                                            <label class="form-check-label" for="enableNotifications">Ativar Notificações</label>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Guardar Configurações</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Manutenção do Sistema</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-3">
                                        <button class="btn btn-outline-primary" id="backupBtn">
                                            <i class="bi bi-download me-2"></i>Cópia de Segurança
                                        </button>
                                        <button class="btn btn-outline-warning" id="resetBtn">
                                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reiniciar Contadores
                                        </button>
                                        <button class="btn btn-outline-danger" id="clearLogsBtn">
                                            <i class="bi bi-trash me-2"></i>Limpar Registos
                                        </button>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <label for="importData" class="form-label">Importar Dados</label>
                                        <input class="form-control" type="file" id="importData">
                                    </div>
                                    <button class="btn btn-secondary" id="importBtn">
                                        <i class="bi bi-upload me-2"></i>Importar
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
                <h5 class="modal-title" id="addSensorModalLabel">Adicionar Novo Sensor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="addSensorForm">
                    <div class="mb-3">
                        <label for="sensorName" class="form-label">Nome do Sensor</label>
                        <input type="text" class="form-control" id="sensorName" required>
                    </div>
                    <div class="mb-3">
                        <label for="sensorType" class="form-label">Tipo</label>
                        <select class="form-select" id="sensorType" required>
                            <option value="">Selecionar Tipo</option>
                            <option value="presenca">Presença</option>
                            <option value="proximidade">Proximidade</option>
                            <option value="temperatura">Temperatura</option>
                            <option value="humidade">Humidade</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sensorLocation" class="form-label">Localização</label>
                        <input type="text" class="form-control" id="sensorLocation" required>
                    </div>
                    <div class="mb-3">
                        <label for="sensorStatus" class="form-label">Estado</label>
                        <select class="form-select" id="sensorStatus" required>
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                            <option value="manutencao">Manutenção</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveSensorBtn">Guardar Sensor</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Actuator Modal -->
<div class="modal fade" id="addActuatorModal" tabindex="-1" aria-labelledby="addActuatorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addActuatorModalLabel">Adicionar Novo Atuador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="addActuatorForm">
                    <div class="mb-3">
                        <label for="actuatorName" class="form-label">Nome do Atuador</label>
                        <input type="text" class="form-control" id="actuatorName" required>
                    </div>
                    <div class="mb-3">
                        <label for="actuatorType" class="form-label">Tipo</label>
                        <select class="form-select" id="actuatorType" required>
                            <option value="">Selecionar Tipo</option>
                            <option value="cancela">Cancela</option>
                            <option value="barreira">Barreira</option>
                            <option value="iluminacao">Iluminação</option>
                            <option value="display">Display</option>
                            <option value="alarme">Alarme</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="actuatorLocation" class="form-label">Localização</label>
                        <input type="text" class="form-control" id="actuatorLocation" required>
                    </div>
                    <div class="mb-3">
                        <label for="actuatorStatus" class="form-label">Estado</label>
                        <select class="form-select" id="actuatorStatus" required>
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                            <option value="manutencao">Manutenção</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveActuatorBtn">Guardar Atuador</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Actuator Modal -->
<div class="modal fade" id="editActuatorModal" tabindex="-1" aria-labelledby="editActuatorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editActuatorModalLabel">Editar Atuador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="editActuatorForm">
                    <input type="hidden" id="editActuatorId">
                    <div class="mb-3">
                        <label for="editActuatorName" class="form-label">Nome do Atuador</label>
                        <input type="text" class="form-control" id="editActuatorName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editActuatorType" class="form-label">Tipo</label>
                        <select class="form-select" id="editActuatorType" required>
                            <option value="">Selecionar Tipo</option>
                            <option value="cancela">Cancela</option>
                            <option value="barreira">Barreira</option>
                            <option value="iluminacao">Iluminação</option>
                            <option value="display">Display</option>
                            <option value="alarme">Alarme</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editActuatorLocation" class="form-label">Localização</label>
                        <input type="text" class="form-control" id="editActuatorLocation" required>
                    </div>
                    <div class="mb-3">
                        <label for="editActuatorStatus" class="form-label">Estado</label>
                        <select class="form-select" id="editActuatorStatus" required>
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                            <option value="manutencao">Manutenção</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="updateActuatorBtn">Atualizar Atuador</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Sensor Modal -->
<div class="modal fade" id="editSensorModal" tabindex="-1" aria-labelledby="editSensorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSensorModalLabel">Editar Sensor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="editSensorForm">
                    <input type="hidden" id="editSensorId">
                    <div class="mb-3">
                        <label for="editSensorName" class="form-label">Nome do Sensor</label>
                        <input type="text" class="form-control" id="editSensorName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSensorType" class="form-label">Tipo</label>
                        <select class="form-select" id="editSensorType" required>
                            <option value="">Selecionar Tipo</option>
                            <option value="presenca">Presença</option>
                            <option value="proximidade">Proximidade</option>
                            <option value="temperatura">Temperatura</option>
                            <option value="humidade">Humidade</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editSensorLocation" class="form-label">Localização</label>
                        <input type="text" class="form-control" id="editSensorLocation" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSensorStatus" class="form-label">Estado</label>
                        <select class="form-select" id="editSensorStatus" required>
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                            <option value="manutencao">Manutenção</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="updateSensorBtn">Atualizar Sensor</button>
            </div>
        </div>
    </div>
</div>

<!-- Simulate Modal -->
<div class="modal fade" id="simulateModal" tabindex="-1" aria-labelledby="simulateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="simulateModalLabel">Simular Eventos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="simulationType" class="form-label">Tipo de Evento</label>
                    <select class="form-select" id="simulationType">
                        <option value="carEnter">Entrada de Veículo</option>
                        <option value="carExit">Saída de Veículo</option>
                        <option value="sensorFailure">Falha de Sensor</option>
                        <option value="powerOutage">Falha de Energia</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="simulationLocation" class="form-label">Localização</label>
                    <select class="form-select" id="simulationLocation">
                        <option value="zoneA">Zona A</option>
                        <option value="zoneB">Zona B</option>
                        <option value="entrance">Entrada</option>
                        <option value="exit">Saída</option>
                    </select>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="simulationRepeat">
                    <label class="form-check-label" for="simulationRepeat">
                        Repetir simulação (a cada 10 segundos)
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="startSimulationBtn">Iniciar Simulação</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toastTitle">Notificação</strong>
            <small id="toastTime">Agora</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fechar"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            Ação concluída com sucesso.
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->

</body>
</html>


