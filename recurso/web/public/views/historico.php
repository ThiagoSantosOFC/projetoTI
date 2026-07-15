<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico - IoT Monitor</title>
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
                <a class="nav-link" href="/dashboard" data-section="painel">
                    <i class="bi bi-speedometer2"></i> Painel
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/dashboard/historico" data-section="historico">
                    <i class="bi bi-clock-history"></i> Histórico
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
        <button class="btn btn-dark d-md-none mb-3" onclick="document.getElementById('sidebar').classList.toggle('show')">
            <i class="bi bi-list"></i> Menu
        </button>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"><i class="bi bi-clock-history"></i> Histórico Detalhado</h3>
        </div>

        <!-- Filters -->
        <div class="history-filters">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Recurso</label>
                    <select class="form-select" id="resource-select" onchange="loadData()">
                        <option value="">Selecione um recurso</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Limite</label>
                    <select class="form-select" id="limit-select" onchange="loadData()">
                        <option value="20">20 entradas</option>
                        <option value="50" selected>50 entradas</option>
                        <option value="100">100 entradas</option>
                        <option value="500">500 entradas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Auto-refresh</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="auto-refresh-toggle" checked>
                        <label class="form-check-label">Ativo</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger w-100" onclick="limparHist()">
                        <i class="bi bi-trash"></i> Limpar
                    </button>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="chart-container mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-graph-up"></i> Gráfico de Leituras</h5>
            <canvas id="historyChart" height="250"></canvas>
        </div>

        <!-- Table -->
        <div class="card-dashboard p-3">
            <div class="table-responsive">
                <table class="table log-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Data / Hora</th>
                            <th>Evento</th>
                        </tr>
                    </thead>
                    <tbody id="history-table-body">
                        <tr>
                            <td colspan="3" class="text-center text-muted">Selecione um recurso para ver o histórico</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/app.js"></script>
    <script>
    var refreshTimer = null;

    function loadResourcesForSelect() {
        loadResources().then(function(resources) {
            var select = document.getElementById('resource-select');
            select.innerHTML = '<option value="">Selecione um recurso</option>' +
                resources.map(function(r) {
                    return '<option value="' + r.id + '">' + escapeHtml(r.nome || r.id) + '</option>';
                }).join('');
        });
    }

    function loadData() {
        var id = document.getElementById('resource-select').value;
        var limit = document.getElementById('limit-select').value;
        var tbody = document.getElementById('history-table-body');

        if (!id) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Selecione um recurso</td></tr>';
            drawHistoryChart([]);
            return;
        }

        loadHistorico(id, limit).then(function(log) {
            if (!log || log.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Sem histórico disponível</td></tr>';
                drawHistoryChart([]);
                return;
            }

            // Parse log entries
            var entries = log.map(function(line) {
                var hora = line.substring(0, 19);
                var evento = line.substring(20);
                return { hora: hora, evento: evento };
            });

            // Reverse so newest first
            entries.reverse();

            tbody.innerHTML = entries.map(function(e, idx) {
                return '<tr>' +
                    '<td>' + (idx + 1) + '</td>' +
                    '<td>' + escapeHtml(e.hora) + '</td>' +
                    '<td>' + escapeHtml(e.evento) + '</td>' +
                    '</tr>';
            }).join('');

            drawHistoryChart(entries);
        }).catch(function() {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Erro ao carregar histórico</td></tr>';
        });
    }

    function drawHistoryChart(entries) {
        if (!entries || entries.length < 2) {
            var canvas = document.getElementById('historyChart');
            var ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            return;
        }

        // Extract value changes from log entries
        var valueEntries = [];
        entries.forEach(function(e) {
            var match = e.evento.match(/[Vv]alor[\s:"]*([\d.]+)/);
            if (match) {
                valueEntries.push({ hora: e.hora.substring(11, 16), valor: parseFloat(match[1]) });
            }
        });

        if (valueEntries.length < 2) {
            var canvas = document.getElementById('historyChart');
            var ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            return;
        }

        valueEntries.reverse();
        var labels = valueEntries.map(function(v) { return v.hora; });
        var values = valueEntries.map(function(v) { return v.valor; });

        drawLineChart('historyChart', labels, values, 'Valores', '#198754');
    }

    function limparHist() {
        var id = document.getElementById('resource-select').value;
        if (!id) { showToast('Selecione um recurso', 'warning'); return; }
        if (!confirm('Tem a certeza que deseja limpar todo o histórico deste recurso?')) return;

        limparHistorico(id).then(function() {
            showToast('Histórico limpo com sucesso', 'success');
            loadData();
        }).catch(function() {
            showToast('Erro ao limpar histórico', 'danger');
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Auto-refresh toggle
    document.getElementById('auto-refresh-toggle').addEventListener('change', function() {
        if (this.checked) {
            refreshTimer = setInterval(loadData, 5000);
            showToast('Auto-refresh ativado', 'success');
        } else {
            if (refreshTimer) {
                clearInterval(refreshTimer);
                refreshTimer = null;
            }
            showToast('Auto-refresh desativado', 'info');
        }
    });

    // Init
    document.addEventListener('DOMContentLoaded', function() {
        loadResourcesForSelect();
        refreshTimer = setInterval(loadData, 5000);
    });
    </script>
</body>
</html>
