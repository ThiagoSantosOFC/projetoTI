# Checklist para o Exame de Recurso

## ☐ Servidor PHP a correr

- [ ] Apache/XAMPP instalado e a funcionar
- [ ] PHP 7.4+ com extensões: `curl`, `json`, `session`, `openssl`
- [ ] Servidor acessível via `http://localhost/`
- [ ] Servidor acessível via IP da rede: `http://192.168.x.x/`
- [ ] Logs do Apache sem erros: `sudo tail -f /var/log/apache2/error.log`

**Testar:**
```bash
php -v
sudo systemctl status apache2
curl http://localhost/
```

## ☐ API responde (testar com curl)

- [ ] `GET /api/recursos.php` → JSON com lista de recursos (ou array vazio)
- [ ] `GET /api/state.php?id=teste` → 404 ou texto simples
- [ ] `POST /api/update.php` com JSON → 200 OK
- [ ] `POST /api/login.php` → 200 ou 401

**Testar:**
```bash
curl http://localhost/api/recursos.php
curl -X POST http://localhost/api/update.php \
  -H "Content-Type: application/json" \
  -d '{"recurso":"teste","valor":42}'
```

## ☐ Login funciona

- [ ] `POST /api/login.php` com credenciais corretas → 200
- [ ] `POST /api/login.php` com credenciais erradas → 401
- [ ] Sessão PHP criada após login
- [ ] Endpoints protegidos bloqueiam sem sessão
- [ ] Logout termina sessão

## ☐ Dashboard mostra recursos

- [ ] Página HTML carrega no browser
- [ ] Lista de recursos visível (obtida via fetch/ajax)
- [ ] Valores atualizam automaticamente (setInterval)
- [ ] Design responsivo (CSS)
- [ ] HTML válido (validator.w3.org)

## ☐ SBC envia leituras (ver log.txt)

- [ ] Script Python corre sem erros
- [ ] `POST /api/update.php` executado periodicamente
- [ ] `log.txt` tem entradas com timestamp e valor
- [ ] `state.txt` tem o valor mais recente
- [ ] Dashboard mostra valores atualizados

**Testar:**
```bash
cat /var/www/html/data/resources/temp1/log.txt
cat /var/www/html/data/resources/temp1/state.txt
```

## ☐ MCU envia leituras

- [ ] MCU (ESP32/Arduino) compila sem erros
- [ ] WiFi conecta (ESP32)
- [ ] POST para API feito com sucesso
- [ ] Leituras aparecem no log.txt
- [ ] Serial monitor mostra debug

## ☐ Atuadores respondem a comando do dashboard

- [ ] Botão no dashboard envia POST para `/api/command.php`
- [ ] `commands.txt` contém o comando
- [ ] SBC lê comando via polling
- [ ] SBC reencaminha para MCU via Serial (ou atua diretamente)
- [ ] Atuador (LED, buzzer, relé) responde
- [ ] `commands.txt` é limpo após execução

**Testar:**
```bash
curl -X POST http://localhost/api/command.php \
  -H "Content-Type: application/json" \
  -d '{"id":"led1","comando":"LED:ON"}'
curl http://localhost/api/commands.php?id=led1
```

## ☐ Webcam captura e envia

- [ ] Script de captura funcional (fswebcam, motion, etc.)
- [ ] Imagem enviada para `/api/webcam.php`
- [ ] `imagem.txt` tem o caminho da imagem
- [ ] Imagem visível no dashboard

## ☐ Histórico funcional

- [ ] `log.txt` acumula leituras ao longo do tempo
- [ ] Dashboard mostra histórico (tabela ou gráfico)
- [ ] GET `/api/log.php?id=X` retorna JSON com histórico
- [ ] Gráfico Chart.js atualiza com novos dados

## ☐ Código comentado

- [ ] Ficheiros `.py` têm comentários explicativos
- [ ] Ficheiros `.c` / `.cpp` têm comentários
- [ ] Ficheiros `.php` têm comentários
- [ ] HTML tem comentários nas secções principais
- [ ] Comentários em português (PT-PT)

## ☐ HTML/CSS válido

- [ ] HTML passa no [validador W3C](https://validator.w3.org/)
- [ ] CSS passa no [validador W3C CSS](https://jigsaw.w3.org/css-validator/)
- [ ] Sem erros de validação (avisos são aceitáveis)
- [ ] PDF da validação guardado para entregar

## ☐ ZIP preparado

- [ ] Projeto todo organizado em pastas
- [ ] Ficheiros de documentação incluídos
- [ ] ZIP com nome próprio: `ProjetoIoT_Recurso_TU_NOME.zip`
- [ ] ZIP testado (extrair e verificar se está tudo)
- [ ] Estrutura de pastas limpa (sem `__pycache__`, `.log`, etc.)

---

## Checklist Rápido de Hardware (Exame)

- [ ] Breadboard montada com componentes
- [ ] Jumpers organizados por cores
- [ ] Resistores de pull-up/pull-down quando necessário
- [ ] Divisor de tensão no HC-SR04 (se ESP32)
- [ ] Alimentação correta (3.3V vs 5V)
- [ ] SBC ligado e a correr script
- [ ] MCU ligado e com código carregado
- [ ] Router/switch a fornecer rede

## Estrutura Final Esperada

```
projeto/
├── api/
│   ├── recursos.php
│   ├── update.php
│   ├── state.php
│   ├── log.php
│   ├── command.php
│   ├── commands.php
│   ├── clear_command.php
│   ├── login.php
│   ├── logout.php
│   ├── webcam.php
│   ├── register.php
│   ├── auth_middleware.php
│   └── token_middleware.php
├── data/
│   └── resources/
│       ├── temp1/
│       │   ├── config.txt
│       │   ├── log.txt
│       │   └── state.txt
│       └── led1/
│           ├── config.txt
│           ├── commands.txt
│           └── state.txt
├── dashboard/
│   └── index.html
├── sbc/
│   ├── main.py
│   ├── config.py
│   └── lib/
│       └── devices/
│           ├── led.py
│           ├── dht22.py
│           ├── hcsr04.py
│           ├── buzzer.py
│           ├── servo.py
│           ├── relay.py
│           ├── pir.py
│           ├── ldr.py
│           └── button.py
├── mcu/
│   ├── main.c (ou .ino)
│   └── config.h
├── docs/
│   └── ... (documentação criada neste guia)
├── utilizadores.txt
└── tokens.txt
```
