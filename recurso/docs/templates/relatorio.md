# Template de Relatório de Entrega

> **Disciplina:** [Nome da Disciplina]
> **Projeto:** Sistema IoT — [Nome do Projeto]
> **Autor:** [Teu Nome]
> **Data:** [Data de Entrega]

---

## 1. Introdução

### 1.1 Objetivo

[Descrição do objetivo do sistema IoT. Ex: "O presente projeto consiste no desenvolvimento de um sistema IoT para monitorização e controlo remoto de..."]

### 1.2 Âmbito

[Descrição do âmbito — o que o sistema faz, onde se aplica]

### 1.3 Tecnologias Utilizadas

| Tecnologia | Versão | Função |
|------------|--------|--------|
| PHP | 8.x | API REST e dashboard |
| Python | 3.x | Lógica do SBC |
| C/C++ (Arduino) | — | Firmware do MCU |
| Apache | 2.4 | Servidor web |
| Chart.js | 4.x | Gráficos no dashboard |

---

## 2. Arquitetura do Sistema

### 2.1 Diagrama de Arquitetura

[Inserir diagrama de arquitetura — ver docs/architecture/arquitetura.md]

### 2.2 Componentes do Sistema

| Componente | Hardware | Software | Função |
|------------|----------|----------|--------|
| Servidor Web | PC / VPS | Apache + PHP | Alojar API e dashboard |
| SBC | Raspberry Pi 3/4 | Python 3 | Leitura sensores, comunicação |
| MCU | ESP32 / Arduino MKR1000 | C/C++ | Controlo atuadores, leitura local |
| Router | WiFi 2.4 GHz | — | Rede entre dispositivos |

### 2.3 Fluxo de Dados

[Descrição dos 6 pares de comunicação obrigatórios]

1. **Python (SBC) → Atuadores MCU:** Comandos via UART Serial
2. **Python (SBC) → Dashboard:** Leituras via HTTP POST
3. **Dashboard → Atuadores MCU:** Comandos via HTTP + Serial
4. **Dashboard → Python (SBC):** Ações via HTTP polling
5. **C (MCU) → Dashboard:** Leituras diretas via HTTP
6. **C (MCU) → Python (SBC):** Dados via UART Serial

---

## 3. Componentes e Circuitos

### 3.1 Lista de Componentes

| Componente | Quantidade | Função |
|------------|-----------|--------|
| Raspberry Pi 3/4 | 1 | SBC (Single Board Computer) |
| ESP32 | 1 | MCU |
| Sensor DHT22 | 1 | Temperatura e humidade |
| Sensor HC-SR04 | 1 | Distância (ultrassom) |
| LED 5mm | 2 | Sinalização |
| Resistor 220Ω | 2 | Limitação de corrente (LEDs) |
| Resistor 10kΩ | 3 | Pull-up/pull-down |
| Buzzer 5V | 1 | Alarme sonoro |
| Transistor 2N2222 | 1 | Driver do buzzer |
| Jumpers M/M | 20 | Ligações na breadboard |
| Breadboard | 1 | Placa de montagem |
| Fonte 5V | 1 | Alimentação |

### 3.2 Circuitos Montados

[Inserir diagramas de cada circuito montado — ver docs/hardware/circuits/]

**Circuito 1: LED + Resistor**
- GPIO 18 → Resistor 220Ω → Ânodo LED → Cátodo → GND

**Circuito 2: Sensor DHT22**
- VCC → 3.3V, DATA → GPIO 4, GND → GND
- Resistor 10kΩ pull-up entre VCC e DATA

**[Adicionar mais circuitos conforme necessário]**

---

## 4. Código Desenvolvido

### 4.1 API PHP (Servidor Web)

**Ficheiros:**
- `api/recursos.php` — Listagem de recursos
- `api/update.php` — Atualização de valores
- `api/command.php` — Envio de comandos
- `api/login.php` — Autenticação

[Incluir excertos de código mais relevantes]

### 4.2 Código Python (SBC)

**Ficheiros:**
- `sbc/main.py` — Loop principal
- `sbc/config.py` — Configuração
- `sbc/lib/devices/` — Biblioteca de dispositivos

[Incluir excertos]

### 4.3 Código C (MCU)

**Ficheiros:**
- `mcu/main.c` — Código principal
- `mcu/config.h` — Configuração

[Incluir excertos]

---

## 5. Testes Realizados

### 5.1 Testes Unitários

| Teste | Resultado | Observações |
|-------|-----------|-------------|
| LED acende via comando HTTP | ✅ | Comando recebido e executado |
| DHT22 lê temperatura | ✅ | Leitura precisa ±0.5°C |
| HC-SR04 mede distância | ✅ | Precisão ±0.3cm |
| API responde a GET | ✅ | Código 200 |
| API rejeita sem token | ✅ | Código 401 |

### 5.2 Testes de Integração

| Teste | Resultado | Observações |
|-------|-----------|-------------|
| Dashboard → Atuador | ✅ | < 1s latência |
| Sensor → Dashboard | ✅ | Atualização a cada 5s |
| SBC → MCU serial | ✅ | Sem perda de dados |
| WiFi ESP32 | ✅ | Conexão estável |

### 5.3 Validação HTML/CSS

- [ ] HTML válido (validador W3C)
- [ ] CSS válido (validador W3C)
- [ ] PDF dos resultados anexo

---

## 6. Conclusão

### 6.1 Objetivos Alcançados

[Listar o que foi implementado com sucesso]

### 6.2 Dificuldades Encontradas

[Listar dificuldades e como foram resolvidas]

### 6.3 Melhorias Futuras

[Sugestões de melhoria]

---

## Anexos

- Anexo A: Diagramas de circuito
- Anexo B: Validação HTML
- Anexo C: Validação CSS
- Anexo D: Código completo (ZIP)
