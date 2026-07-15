/**
 * IoT Resource Monitor - Application JavaScript
 */

// ============================================================
// Toast / Notification System
// ============================================================
function showToast(message, type) {
    type = type || 'info';
    var container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    var colors = {
        success: '#198754',
        danger: '#dc3545',
        warning: '#ffc107',
        info: '#0d6efd'
    };

    var toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.borderLeft = '4px solid ' + (colors[type] || colors.info);
    toast.innerHTML = '<div style="display:flex;align-items:center;justify-content:space-between">' +
        '<span>' + message + '</span>' +
        '<button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;font-size:1.2rem;cursor:pointer;opacity:0.5">&times;</button>' +
        '</div>';

    container.appendChild(toast);

    setTimeout(function () {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(function () { toast.remove(); }, 300);
    }, 4000);
}

// ============================================================
// Generic fetchAPI wrapper
// ============================================================
function fetchAPI(method, url, body) {
    var options = {
        method: method,
        headers: {}
    };

    if (body instanceof FormData) {
        // Let browser set Content-Type for FormData
        options.body = body;
    } else if (body !== undefined && body !== null) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(body);
    }

    return fetch(url, options)
        .then(function (response) {
            return response.json().then(function (data) {
                if (!response.ok) {
                    throw { status: response.status, data: data };
                }
                return data;
            });
        })
        .catch(function (err) {
            if (err && err.data) throw err;
            throw { status: 0, data: { error: 'Erro de conexão' } };
        });
}

// ============================================================
// Authentication
// ============================================================
function submitLogin(form) {
    var formData = new FormData(form);
    var data = {
        username: formData.get('username'),
        password: formData.get('password')
    };

    var btn = form.querySelector('button[type="submit"]');
    var originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'A entrar...';

    fetchAPI('POST', '/api/auth/login', data)
        .then(function (result) {
            if (result.success) {
                showToast('Login efetuado com sucesso!', 'success');
                var modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
                if (modal) modal.hide();
                setTimeout(function () { window.location.href = '/dashboard'; }, 500);
            } else {
                showToast(result.error || 'Erro no login', 'danger');
            }
        })
        .catch(function (err) {
            var msg = (err.data && err.data.error) || 'Erro no login';
            showToast(msg, 'danger');
        })
        .finally(function () {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });

    return false;
}

function logout() {
    fetchAPI('POST', '/api/auth/logout')
        .then(function () {
            window.location.href = '/';
        })
        .catch(function () {
            window.location.href = '/';
        });
}

function checkSession() {
    return fetchAPI('GET', '/api/auth/session');
}

// ============================================================
// Resources CRUD
// ============================================================
function loadResources() {
    return fetchAPI('GET', '/api/resources')
        .then(function (result) {
            return result.data || [];
        });
}

function loadResourceDetail(id) {
    return fetchAPI('GET', '/api/resources/' + encodeURIComponent(id))
        .then(function (result) {
            return result.data || null;
        });
}

function createResource(data) {
    return fetchAPI('POST', '/api/resources', data);
}

function updateResource(id, valor) {
    return fetchAPI('PATCH', '/api/resources/' + encodeURIComponent(id), { valor: valor });
}

function deleteResource(id) {
    return fetchAPI('DELETE', '/api/resources/' + encodeURIComponent(id));
}

// ============================================================
// Commands & History
// ============================================================
function sendCommand(id, comando) {
    return fetchAPI('POST', '/api/resources/' + encodeURIComponent(id) + '/comando', { comando: comando });
}

function loadHistorico(id, limit) {
    limit = limit || 100;
    var url = '/api/resources/' + encodeURIComponent(id) + '/historico?limit=' + limit;
    return fetchAPI('GET', url)
        .then(function (result) {
            return result.data || [];
        });
}

function limparHistorico(id) {
    return fetchAPI('DELETE', '/api/resources/' + encodeURIComponent(id) + '/historico');
}

// ============================================================
// Camera
// ============================================================
function loadUltimaImagem() {
    return fetchAPI('GET', '/api/camera/ultima')
        .then(function (result) {
            return result.data || null;
        });
}

function loadCameraHistory(limit) {
    limit = limit || 50;
    return fetchAPI('GET', '/api/camera/historico?limit=' + limit)
        .then(function (result) {
            return result.data || [];
        });
}

function uploadCameraImage(file) {
    var formData = new FormData();
    formData.append('imagem', file);
    return fetchAPI('POST', '/api/camera', formData);
}

// ============================================================
// Dashboard auto-refresh
// ============================================================
var refreshInterval = null;

function startAutoRefresh(callback, intervalMs) {
    intervalMs = intervalMs || 5000;
    if (refreshInterval) clearInterval(refreshInterval);
    refreshInterval = setInterval(callback, intervalMs);
}

function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
        refreshInterval = null;
    }
}

// ============================================================
// Dashboard navigation
// ============================================================
function showSection(sectionId) {
    var panels = document.querySelectorAll('.section-panel');
    for (var i = 0; i < panels.length; i++) {
        panels[i].classList.remove('active');
    }
    var target = document.getElementById('section-' + sectionId);
    if (target) {
        target.classList.add('active');
    }

    var links = document.querySelectorAll('.sidebar .nav-link');
    for (var i = 0; i < links.length; i++) {
        links[i].classList.remove('active');
        if (links[i].getAttribute('data-section') === sectionId) {
            links[i].classList.add('active');
        }
    }
}

// ============================================================
// Chart helper (basic canvas line chart)
// ============================================================
function drawLineChart(canvasId, labels, values, label, color) {
    var canvas = document.getElementById(canvasId);
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    var dpr = window.devicePixelRatio || 1;
    var rect = canvas.getBoundingClientRect();
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
    ctx.scale(dpr, dpr);
    var w = rect.width;
    var h = rect.height;
    var padding = { top: 20, right: 20, bottom: 30, left: 40 };
    var chartW = w - padding.left - padding.right;
    var chartH = h - padding.top - padding.bottom;

    ctx.clearRect(0, 0, w, h);

    if (!labels || labels.length === 0) {
        ctx.fillStyle = '#999';
        ctx.font = '14px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('Sem dados', w / 2, h / 2);
        return;
    }

    color = color || '#0d6efd';
    var minVal = Math.min.apply(null, values);
    var maxVal = Math.max.apply(null, values);
    var range = maxVal - minVal || 1;

    // Grid lines
    ctx.strokeStyle = '#f0f0f0';
    ctx.lineWidth = 1;
    for (var i = 0; i <= 4; i++) {
        var y = padding.top + (chartH / 4) * i;
        ctx.beginPath();
        ctx.moveTo(padding.left, y);
        ctx.lineTo(w - padding.right, y);
        ctx.stroke();
    }

    // Draw line
    ctx.strokeStyle = color;
    ctx.lineWidth = 2;
    ctx.beginPath();
    for (var i = 0; i < values.length; i++) {
        var x = padding.left + (chartW / (values.length - 1 || 1)) * i;
        var y = padding.top + chartH - ((values[i] - minVal) / range) * chartH;
        if (i === 0) ctx.moveTo(x, y);
        else ctx.lineTo(x, y);
    }
    ctx.stroke();

    // Draw points
    ctx.fillStyle = color;
    for (var i = 0; i < values.length; i++) {
        var x = padding.left + (chartW / (values.length - 1 || 1)) * i;
        var y = padding.top + chartH - ((values[i] - minVal) / range) * chartH;
        ctx.beginPath();
        ctx.arc(x, y, 3, 0, 2 * Math.PI);
        ctx.fill();
    }

    // X labels
    ctx.fillStyle = '#999';
    ctx.font = '10px sans-serif';
    ctx.textAlign = 'center';
    var step = Math.max(1, Math.floor(labels.length / 8));
    for (var i = 0; i < labels.length; i += step) {
        var x = padding.left + (chartW / (values.length - 1 || 1)) * i;
        ctx.fillText(labels[i], x, h - 8);
    }
}
