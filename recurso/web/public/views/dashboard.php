<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - IoT Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/dashboard.css">
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5><i class="bi bi-cpu"></i> IoT Monitor</h5>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#" data-section="painel" onclick="showSection('painel')">
                    <i class="bi bi-speedometer2"></i> Painel
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-section="sensores" onclick="showSection('sensores')">
                    <i class="bi bi-thermometer-half"></i> Sensores
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-section="atuadores" onclick="showSection('atuadores')">
                    <i class="bi bi-toggle-on"></i> Atuadores
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-section="historico" onclick="showSection('historico')">
                    <i class="bi bi-clock-history"></i> Histórico
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-section="camara" onclick="showSection('camara')">
                    <i class="bi bi-camera"></i> Câmara
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link text-danger" href="#" onclick="logout()">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Mobile toggle -->
        <button class="btn btn-dark d-md-none mb-3" onclick="document.getElementById('sidebar').classList.toggle('show')">
            <i class="bi bi-list"></i> Menu
        </button>

        <!-- ===== PAINEL ===== -->
        <div id="section-painel" class="section-panel active">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">Painel de Controlo</h3>
                <span class="text-muted small"><i class="bi bi-arrow-repeat"></i> Auto-refresh: 5s</span>
            </div>
            <div class="row g-3 mb-4" id="stat-counters">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="bi bi-boxes"></i></div>
                        <div class="stat-info">
                            <h3 id="counter-total">0</h3>
                            <p>Total Recursos</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="bi bi-activity"></i></div>
                        <div class="stat-info">
                            <h3 id="counter-sensores">0</h3>
                            <p>Sensores Ativos</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon orange"><i class="bi bi-toggle-on"></i></div>
                        <div class="stat-info">
                            <h3 id="counter-atuadores">0</h3>
                            <p>Atuadores</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
                        <div class="stat-info">
                            <h3 id="counter-alertas">0</h3>
                            <p>Alertas</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chart-container">
                <h5 class="fw-bold mb-3"><i class="bi bi-graph-up"></i> Últimas Leituras</h5>
                <canvas id="dashboardChart" height="250"></canvas>
            </div>
        </div>

        <!-- ===== SENSORES ===== -->
        <div id="section-sensores" class="section-panel">
            <h3 class="fw-bold mb-4"><i class="bi bi-thermometer-half"></i> Sensores</h3>
            <div class="resource-grid" id="sensores-grid"></div>
        </div>

        <!-- ===== ATUADORES ===== -->
        <div id="section-atuadores" class="section-panel">
            <h3 class="fw-bold mb-4"><i class="bi bi-toggle-on"></i> Atuadores</h3>
            <div class="resource-grid" id="atuadores-grid"></div>
        </div>

        <!-- ===== HISTÓRICO ===== -->
        <div id="section-historico" class="section-panel">
            <h3 class="fw-bold mb-4"><i class="bi bi-clock-history"></i> Histórico</h3>
            <div class="history-filters">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">Recurso</label>
                        <select class="form-select" id="historico-resource-select" onchange="loadHistoricoTable()">
                            <option value="">Selecione um recurso</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Limite de entradas</label>
                        <select class="form-select" id="historico-limit" onchange="loadHistoricoTable()">
                            <option value="20">20</option>
                            <option value="50" selected>50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-danger w-100" onclick="limparHistoricoHandler()">
                            <i class="bi bi-trash"></i> Limpar
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table log-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Timestamp</th>
                            <th>Evento</th>
                        </tr>
                    </thead>
                    <tbody id="historico-table-body"></tbody>
                </table>
            </div>
        </div>

        <!-- ===== CÂMARA ===== -->
        <div id="section-camara" class="section-panel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold"><i class="bi bi-camera"></i> Câmara</h3>
                <button class="btn btn-primary" onclick="refreshCamera()">
                    <i class="bi bi-arrow-repeat"></i> Atualizar
                </button>
            </div>
            <div class="camera-frame" id="camera-frame">
                <div class="placeholder" id="camera-placeholder">
                    <i class="bi bi-camera"></i>
                    <p>Nenhuma imagem disponível</p>
                </div>
                <img id="camera-image" src="" style="display:none;" alt="Última imagem">
            </div>
            <div class="chart-container mt-3">
                <h5 class="fw-bold mb-3"><i class="bi bi-images"></i> Histórico de Imagens</h5>
                <div class="table-responsive">
                    <table class="table log-table">
                        <thead>
                            <tr><th>Data/Hora</th><th>Ficheiro</th></tr>
                        </thead>
                        <tbody id="camera-historico-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/app.js"></script>
    <script>
    // ============================================================
    // Dashboard main logic
    // ============================================================
    var allResources = [];

    function refreshDashboard() {
        loadResources().then(function(resources) {
            allResources = resources;
            updateCounters(resources);
            renderSensors(resources);
            renderActuators(resources);
            updateHistoricoSelect(resources);
            updateChart(resources);
        }).catch(function(err) {
            console.error('Erro ao carregar recursos:', err);
        });
    }

    function updateCounters(resources) {
        var total = resources.length;
        var sensores = resources.filter(function(r) { return r.tipo === 'sensor'; }).length;
        var atuadores = resources.filter(function(r) { return r.tipo === 'atuador'; }).length;

        document.getElementById('counter-total').textContent = total;
        document.getElementById('counter-sensores').textContent = sensores;
        document.getElementById('counter-atuadores').textContent = atuadores;
        // Alerta simples: recursos sem valor recente (>5min)
        var now = new Date();
        var alertas = 0;
        resources.forEach(function(r) {
            if (r.hora) {
                var hora = new Date(r.hora.replace(' ', 'T'));
                if ((now - hora) > 300000) alertas++;
            }
        });
        document.getElementById('counter-alertas').textContent = alertas;
    }

    function renderSensors(resources) {
        var grid = document.getElementById('sensores-grid');
        var sensores = resources.filter(function(r) { return r.tipo === 'sensor'; });
        if (sensores.length === 0) {
            grid.innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-inbox" style="font-size:3rem"></i><p class="mt-2">Nenhum sensor registado</p></div>';
            return;
        }
        grid.innerHTML = sensores.map(function(r) {
            var isOnline = r.hora && (new Date() - new Date(r.hora.replace(' ', 'T'))) < 300000;
            return '<div class="sensor-card">' +
                '<div class="d-flex justify-content-between align-items-start">' +
                '<div class="sensor-name">' + escapeHtml(r.nome || r.id) + '</div>' +
                '<span class="status-badge ' + (isOnline ? 'online' : 'offline') + '">' + (isOnline ? 'Online' : 'Offline') + '</span>' +
                '</div>' +
                '<div class="sensor-value">' + escapeHtml(r.valor || '--') +
                (r.unidade ? ' <small>' + escapeHtml(r.unidade) + '</small>' : '') +
                '</div>' +
                '<div class="mt-2" style="font-size:0.8rem;opacity:0.8">' + (r.hora || '---') + '</div>' +
                '</div>';
        }).join('');
    }

    function renderActuators(resources) {
        var grid = document.getElementById('atuadores-grid');
        var atuadores = resources.filter(function(r) { return r.tipo === 'atuador'; });
        if (atuadores.length === 0) {
            grid.innerHTML = '<div class="text-center text-muted py-5"><i class="bi bi-inbox" style="font-size:3rem"></i><p class="mt-2">Nenhum atuador registado</p></div>';
            return;
        }
        grid.innerHTML = atuadores.map(function(r) {
            var isOn = r.valor === '1' || r.valor === 'on' || r.valor === 'true';
            return '<div class="actuator-card">' +
                '<div class="d-flex justify-content-between align-items-center">' +
                '<div class="actuator-name">' + escapeHtml(r.nome || r.id) + '</div>' +
                '<div class="form-check form-switch">' +
                '<input class="form-check-input" type="checkbox" style="transform:scale(1.3)" ' +
                (isOn ? 'checked' : '') +
                ' onchange="toggleActuator(\'' + r.id + '\', this.checked)">' +
                '</div>' +
                '</div>' +
                '<small class="text-muted">' + (r.hora || '---') + '</small>' +
                '</div>';
        }).join('');
    }

    function toggleActuator(id, state) {
        var valor = state ? '1' : '0';
        updateResource(id, valor).then(function() {
            showToast('Atuador ' + (state ? 'ligado' : 'desligado'), 'success');
            refreshDashboard();
        }).catch(function(err) {
            showToast('Erro ao controlar atuador', 'danger');
        });
    }

    function updateHistoricoSelect(resources) {
        var select = document.getElementById('historico-resource-select');
        var currentVal = select.value;
        select.innerHTML = '<option value="">Selecione um recurso</option>' +
            resources.map(function(r) {
                return '<option value="' + r.id + '">' + escapeHtml(r.nome || r.id) + '</option>';
            }).join('');
        if (currentVal) select.value = currentVal;
    }

    function loadHistoricoTable() {
        var id = document.getElementById('historico-resource-select').value;
        var limit = document.getElementById('historico-limit').value;
        var tbody = document.getElementById('historico-table-body');

        if (!id) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Selecione um recurso</td></tr>';
            return;
        }

        loadHistorico(id, limit).then(function(log) {
            if (log.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Sem histórico</td></tr>';
                return;
            }
            tbody.innerHTML = log.map(function(line, idx) {
                return '<tr><td>' + (idx + 1) + '</td><td>' + escapeHtml(line.substring(0, 19)) + '</td><td>' + escapeHtml(line.substring(20)) + '</td></tr>';
            }).join('');
        }).catch(function() {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Erro ao carregar histórico</td></tr>';
        });
    }

    function limparHistoricoHandler() {
        var id = document.getElementById('historico-resource-select').value;
        if (!id) { showToast('Selecione um recurso', 'warning'); return; }
        if (!confirm('Tem a certeza que deseja limpar o histórico?')) return;

        limparHistorico(id).then(function() {
            showToast('Histórico limpo com sucesso', 'success');
            loadHistoricoTable();
        }).catch(function() {
            showToast('Erro ao limpar histórico', 'danger');
        });
    }

    function updateChart(resources) {
        var sensores = resources.filter(function(r) { return r.tipo === 'sensor'; }).slice(0, 8);
        if (sensores.length === 0) return;

        var labels = sensores.map(function(r) { return r.nome || r.id; });
        var values = sensores.map(function(r) { return parseFloat(r.valor) || 0; });

        drawLineChart('dashboardChart', labels, values, 'Leituras', '#0d6efd');
    }

    // ============================================================
    // Camera
    // ============================================================
    function refreshCamera() {
        loadUltimaImagem().then(function(data) {
            if (data && data.url) {
                document.getElementById('camera-placeholder').style.display = 'none';
                var img = document.getElementById('camera-image');
                img.src = data.url + '?t=' + Date.now();
                img.style.display = 'block';
            }
        }).catch(function() {
            document.getElementById('camera-placeholder').style.display = 'block';
            document.getElementById('camera-image').style.display = 'none';
        });

        loadCameraHistory(20).then(function(entries) {
            var tbody = document.getElementById('camera-historico-body');
            if (entries.length === 0) {
                tbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Sem histórico</td></tr>';
                return;
            }
            tbody.innerHTML = entries.map(function(e) {
                return '<tr><td>' + escapeHtml(e.hora) + '</td><td>' + escapeHtml(e.filename) + '</td></tr>';
            }).join('');
        });
    }

    // ============================================================
    // Helpers
    // ============================================================
    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // ============================================================
    // Init
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        refreshDashboard();
        startAutoRefresh(refreshDashboard, 5000);
    });
    </script>
</body>
</html>
