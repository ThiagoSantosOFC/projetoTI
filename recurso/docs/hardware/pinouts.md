# Pinouts — GPIO

## Raspberry Pi GPIO (40-pin Header)

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 700 450" font-family="Consolas, monospace">
  <rect width="700" height="450" fill="#f8fafc" rx="8"/>

  <text x="350" y="30" text-anchor="middle" font-weight="bold" font-size="16" fill="#1e293b">Raspberry Pi — GPIO 40-pin Header</text>

  <!-- Pin header representation -->
  <rect x="150" y="50" width="400" height="380" rx="6" fill="#1e293b"/>

  <!-- Pin rows (40 pins) -->
  <!-- Format: [x, y, pin#, name, color] -->
  <g font-size="9">
    <!-- Row 1 -->
    <rect x="170" y="70" width="36" height="18" rx="2" fill="#fef2f2"/><text x="178" y="83" fill="#dc2626">1</text>
    <text x="210" y="83" fill="white">3.3V</text>
    <rect x="340" y="70" width="36" height="18" rx="2" fill="#f0fdf4"/><text x="348" y="83" fill="#16a34a">2</text>
    <text x="380" y="83" fill="white">5V</text>

    <!-- Row 2 -->
    <rect x="170" y="95" width="36" height="18" rx="2" fill="#f0fdf4"/><text x="178" y="108" fill="#16a34a">3</text>
    <text x="210" y="108" fill="white">GPIO 2 (SDA)</text>
    <rect x="340" y="95" width="36" height="18" rx="2" fill="#f0fdf4"/><text x="348" y="108" fill="#16a34a">4</text>
    <text x="380" y="108" fill="white">5V</text>

    <!-- Row 3 -->
    <rect x="170" y="120" width="36" height="18" rx="2" fill="#fef2f2"/><text x="178" y="133" fill="#dc2626">5</text>
    <text x="210" y="133" fill="white">GPIO 3 (SCL)</text>
    <rect x="340" y="120" width="36" height="18" rx="2" fill="#1e293b"/><text x="348" y="133" fill="#94a3b8">6</text>
    <text x="380" y="133" fill="white">GND</text>

    <!-- Row 4 -->
    <rect x="170" y="145" width="36" height="18" rx="2" fill="#fef2f2"/><text x="178" y="158" fill="#dc2626">7</text>
    <text x="210" y="158" fill="white">GPIO 4</text>
    <rect x="340" y="145" width="36" height="18" rx="2" fill="#1e293b"/><text x="348" y="158" fill="#94a3b8">8</text>
    <text x="380" y="158" fill="white">GPIO 14 (TXD)</text>

    <!-- Row 5 -->
    <rect x="170" y="170" width="36" height="18" rx="2" fill="#1e293b"/><text x="178" y="183" fill="#94a3b8">9</text>
    <text x="210" y="183" fill="white">GND</text>
    <rect x="340" y="170" width="36" height="18" rx="2" fill="#1e293b"/><text x="348" y="183" fill="#94a3b8">10</text>
    <text x="380" y="183" fill="white">GPIO 15 (RXD)</text>

    <!-- Row 6 -->
    <rect x="170" y="195" width="36" height="18" rx="2" fill="#fef2f2"/><text x="178" y="208" fill="#dc2626">11</text>
    <text x="210" y="208" fill="white">GPIO 17</text>
    <rect x="340" y="195" width="36" height="18" rx="2" fill="#1e293b"/><text x="348" y="208" fill="#94a3b8">12</text>
    <text x="380" y="208" fill="white">GPIO 18 (PWM)</text>

    <!-- Row 7 -->
    <rect x="170" y="220" width="36" height="18" rx="2" fill="#fef2f2"/><text x="178" y="233" fill="#dc2626">13</text>
    <text x="210" y="233" fill="white">GPIO 27</text>
    <rect x="340" y="220" width="36" height="18" rx="2" fill="#1e293b"/><text x="348" y="233" fill="#94a3b8">14</text>
    <text x="380" y="233" fill="white">GND</text>

    <!-- Row 8 -->
    <rect x="170" y="245" width="36" height="18" rx="2" fill="#fef2f2"/><text x="178" y="258" fill="#dc2626">15</text>
    <text x="210" y="258" fill="white">GPIO 22</text>
    <rect x="340" y="245" width="36" height="18" rx="2" fill="#1e293b"/><text x="348" y="258" fill="#94a3b8">16</text>
    <text x="380" y="258" fill="white">GPIO 23</text>

    <!-- Row 9 -->
    <rect x="170" y="270" width="36" height="18" rx="2" fill="#fef2f2"/><text x="178" y="283" fill="#dc2626">17</text>
    <text x="210" y="283" fill="white">3.3V</text>
    <rect x="340" y="270" width="36" height="18" rx="2" fill="#1e293b"/><text x="348" y="283" fill="#94a3b8">18</text>
    <text x="380" y="283" fill="white">GPIO 24</text>

    <!-- Row 10 -->
    <rect x="170" y="295" width="36" height="18" rx="2" fill="#fef2f2"/><text x="178" y="308" fill="#dc2626">19</text>
    <text x="210" y="308" fill="white">GPIO 10 (MOSI)</text>
    <rect x="340" y="295" width="36" height="18" rx="2" fill="#1e293b"/><text x="348" y="308" fill="#94a3b8">20</text>
    <text x="380" y="308" fill="white">GND</text>

    <!-- Row 11 -->
    <rect x="170" y="320" width="36" height="18" rx="2" fill="#fef2f2"/><text x="178" y="333" fill="#dc2626">21</text>
    <text x="210" y="333" fill="white">GPIO 9 (MISO)</text>
    <rect x="340" y="320" width="36" height="18" rx="2" fill="#1e293b"/><text x="348" y="333" fill="#94a3b8">22</text>
    <text x="380" y="333" fill="white">GPIO 25</text>

    <!-- Row 12 -->
    <rect x="170" y="345" width="36" height="18" rx="2" fill="#fef2f2"/><text x="178" y="358" fill="#dc2626">23</text>
    <text x="210" y="358" fill="white">GPIO 11 (SCLK)</text>
    <rect x="340" y="345" width="36" height="18" rx="2" fill="#1e293b"/><text x="348" y="358" fill="#94a3b8">24</text>
    <text x="380" y="358" fill="white">GPIO 8 (CE0)</text>

    <!-- Row 13 -->
    <rect x="170" y="370" width="36" height="18" rx="2" fill="#fef2f2"/><text x="178" y="383" fill="#dc2626">25</text>
    <text x="210" y="383" fill="white">GND</text>
    <rect x="340" y="370" width="36" height="18" rx="2" fill="#1e293b"/><text x="348" y="383" fill="#94a3b8">26</text>
    <text x="380" y="383" fill="white">GPIO 7 (CE1)</text>

    <!-- Row 14 -->
    <rect x="170" y="395" width="36" height="18" rx="2" fill="#e0f2fe"/><text x="178" y="408" fill="#2563eb">27</text>
    <text x="210" y="408" fill="white">ID_SD</text>
    <rect x="340" y="395" width="36" height="18" rx="2" fill="#e0f2fe"/><text x="348" y="408" fill="#2563eb">28</text>
    <text x="380" y="408" fill="white">ID_SC</text>
  </g>

  <!-- Legend -->
  <rect x="30" y="50" width="110" height="170" rx="6" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="1"/>
  <text x="40" y="68" font-weight="bold" font-size="10" fill="#475569">Legenda:</text>
  <rect x="40" y="78" width="14" height="14" rx="3" fill="#fef2f2" stroke="#fca5a5"/>
  <text x="58" y="90" font-size="9" fill="#64748b">3.3V / Alimentação</text>
  <rect x="40" y="98" width="14" height="14" rx="3" fill="#f0fdf4" stroke="#86efac"/>
  <text x="58" y="110" font-size="9" fill="#64748b">5V</text>
  <rect x="40" y="118" width="14" height="14" rx="3" fill="#1e293b"/>
  <text x="58" y="130" font-size="9" fill="#64748b">GND</text>
  <rect x="40" y="138" width="14" height="14" rx="3" fill="#fef2f2" stroke="#fca5a5"/>
  <text x="58" y="150" font-size="9" fill="#64748b">GPIO (lado ímpar)</text>
  <rect x="40" y="158" width="14" height="14" rx="3" fill="#1e293b"/>
  <text x="58" y="170" font-size="9" fill="#64748b">GPIO (lado par)</text>
  <rect x="40" y="178" width="14" height="14" rx="3" fill="#e0f2fe" stroke="#60a5fa"/>
  <text x="58" y="190" font-size="9" fill="#64748b">ID EEPROM</text>

  <!-- Pinos importantes -->
  <text x="25" y="250" font-weight="bold" font-size="11" fill="#475569">Pinos mais usados:</text>
  <text x="25" y="268" font-size="10" fill="#64748b">GPIO 4  (pin 7)  → DHT22, sensores</text>
  <text x="25" y="284" font-size="10" fill="#64748b">GPIO 17 (pin 11) → LED, botões</text>
  <text x="25" y="300" font-size="10" fill="#64748b">GPIO 18 (pin 12) → PWM (servo, LED)</text>
  <text x="25" y="316" font-size="10" fill="#64748b">GPIO 14 (pin 8)  → UART TX</text>
  <text x="25" y="332" font-size="10" fill="#64748b">GPIO 15 (pin 10) → UART RX</text>
  <text x="25" y="348" font-size="10" fill="#64748b">GPIO 27 (pin 13) → LED, botões</text>
  <text x="25" y="364" font-size="10" fill="#64748b">GPIO 22 (pin 15) → Buzzer, relé</text>
  <text x="25" y="380" font-size="10" fill="#64748b">GPIO 23 (pin 16) → LED, dados</text>
</svg>
```

## ESP32 (38-pin)

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 700 400" font-family="Consolas, monospace">
  <rect width="700" height="400" fill="#f8fafc" rx="8"/>

  <text x="350" y="30" text-anchor="middle" font-weight="bold" font-size="16" fill="#1e293b">ESP32 — 38-pin GPIO (DevKit V1)</text>

  <!-- ESP32 body -->
  <rect x="100" y="50" width="500" height="300" rx="8" fill="#1e3a5f"/>

  <g font-size="9">
    <!-- Left side pins -->
    <rect x="-10" y="70" width="100" height="20" rx="3" fill="#fef2f2"/>
    <text x="5" y="84" fill="#dc2626">3.3V</text>
    <text x="105" y="84" fill="white">EN</text>

    <rect x="-10" y="100" width="100" height="20" rx="3" fill="#fef2f2"/>
    <text x="5" y="114" fill="#dc2626">GPIO 36</text>
    <text x="105" y="114" fill="white">VP (ADC)</text>

    <rect x="-10" y="130" width="100" height="20" rx="3" fill="#fef2f2"/>
    <text x="5" y="144" fill="#dc2626">GPIO 39</text>
    <text x="105" y="144" fill="white">VN (ADC)</text>

    <rect x="-10" y="160" width="100" height="20" rx="3" fill="#fef2f2"/>
    <text x="5" y="174" fill="#dc2626">GPIO 34</text>
    <text x="105" y="174" fill="white">ADC</text>

    <rect x="-10" y="190" width="100" height="20" rx="3" fill="#fef2f2"/>
    <text x="5" y="204" fill="#dc2626">GPIO 35</text>
    <text x="105" y="204" fill="white">ADC</text>

    <rect x="-10" y="220" width="100" height="20" rx="3" fill="#fef2f2"/>
    <text x="5" y="234" fill="#dc2626">GPIO 32</text>
    <text x="105" y="234" fill="white">PWM / Touch</text>

    <rect x="-10" y="250" width="100" height="20" rx="3" fill="#fef2f2"/>
    <text x="5" y="264" fill="#dc2626">GPIO 33</text>
    <text x="105" y="264" fill="white">PWM / Touch</text>

    <rect x="-10" y="280" width="100" height="20" rx="3" fill="#fef2f2"/>
    <text x="5" y="294" fill="#dc2626">GPIO 25</text>
    <text x="105" y="294" fill="white">DAC / PWM</text>

    <rect x="-10" y="310" width="100" height="20" rx="3" fill="#fef2f2"/>
    <text x="5" y="324" fill="#dc2626">GPIO 26</text>
    <text x="105" y="324" fill="white">DAC / PWM</text>

    <!-- Right side pins -->
    <rect x="610" y="70" width="100" height="20" rx="3" fill="#f0fdf4"/>
    <text x="620" y="84" fill="#16a34a">GND</text>
    <text x="590" y="84" fill="white" text-anchor="end">GND</text>

    <rect x="610" y="100" width="100" height="20" rx="3" fill="#f0fdf4"/>
    <text x="620" y="114" fill="#16a34a">GPIO 23</text>
    <text x="590" y="114" fill="white" text-anchor="end">MOSI</text>

    <rect x="610" y="130" width="100" height="20" rx="3" fill="#f0fdf4"/>
    <text x="620" y="144" fill="#16a34a">GPIO 22</text>
    <text x="590" y="144" fill="white" text-anchor="end">SCL</text>

    <rect x="610" y="160" width="100" height="20" rx="3" fill="#f0fdf4"/>
    <text x="620" y="174" fill="#16a34a">GPIO 21</text>
    <text x="590" y="174" fill="white" text-anchor="end">SDA</text>

    <rect x="610" y="190" width="100" height="20" rx="3" fill="#f0fdf4"/>
    <text x="620" y="204" fill="#16a34a">GPIO 19</text>
    <text x="590" y="204" fill="white" text-anchor="end">MISO</text>

    <rect x="610" y="220" width="100" height="20" rx="3" fill="#f0fdf4"/>
    <text x="620" y="234" fill="#16a34a">GPIO 18</text>
    <text x="590" y="234" fill="white" text-anchor="end">SCLK</text>

    <rect x="610" y="250" width="100" height="20" rx="3" fill="#f0fdf4"/>
    <text x="620" y="264" fill="#16a34a">GPIO 5</text>
    <text x="590" y="264" fill="white" text-anchor="end">PWM</text>

    <rect x="610" y="280" width="100" height="20" rx="3" fill="#f0fdf4"/>
    <text x="620" y="294" fill="#16a34a">GPIO 17</text>
    <text x="590" y="294" fill="white" text-anchor="end">TX</text>

    <rect x="610" y="310" width="100" height="20" rx="3" fill="#f0fdf4"/>
    <text x="620" y="324" fill="#16a34a">GPIO 16</text>
    <text x="590" y="324" fill="white" text-anchor="end">RX</text>
  </g>

  <!-- Bottom pins -->
  <g font-size="9">
    <rect x="105" y="360" width="70" height="20" rx="3" fill="#fef2f2"/>
    <text x="115" y="374" fill="#dc2626">GPIO 27</text>

    <rect x="185" y="360" width="70" height="20" rx="3" fill="#fef2f2"/>
    <text x="195" y="374" fill="#dc2626">GPIO 14</text>

    <rect x="265" y="360" width="70" height="20" rx="3" fill="#fef2f2"/>
    <text x="275" y="374" fill="#dc2626">GPIO 12</text>

    <rect x="345" y="360" width="70" height="20" rx="3" fill="#fef2f2"/>
    <text x="355" y="374" fill="#dc2626">GPIO 13</text>

    <rect x="425" y="360" width="70" height="20" rx="3" fill="#f0fdf4"/>
    <text x="435" y="374" fill="#16a34a">GND</text>

    <rect x="505" y="360" width="70" height="20" rx="3" fill="#fef2f2"/>
    <text x="515" y="374" fill="#dc2626">GPIO 15</text>
  </g>

  <text x="350" y="280" text-anchor="middle" font-size="10" fill="#60a5fa">ESP32 DevKit V1</text>

  <!-- Legend -->
  <rect x="30" y="50" width="70" height="100" rx="4" fill="#f1f5f9" stroke="#cbd5e1"/>
  <text x="40" y="66" font-size="9" fill="#475569">🔴 Esq</text>
  <text x="40" y="80" font-size="9" fill="#475569">🟢 Dir</text>
  <text x="40" y="94" font-size="9" fill="#475569">⬇ Fundo</text>

  <!-- Important pins -->
  <text x="25" y="170" font-weight="bold" font-size="10" fill="#475569">Pinos Importantes:</text>
  <text x="25" y="186" font-size="9" fill="#64748b">GPIO 4 → DHT22</text>
  <text x="25" y="200" font-size="9" fill="#64748b">GPIO 5 → Servo PWM</text>
  <text x="25" y="214" font-size="9" fill="#64748b">GPIO 18 → HC-SR04 Echo</text>
  <text x="25" y="228" font-size="9" fill="#64748b">GPIO 19 → HC-SR04 Trig</text>
  <text x="25" y="242" font-size="9" fill="#64748b">GPIO 25 → LED</text>
  <text x="25" y="256" font-size="9" fill="#64748b">GPIO 32 → Buzzer</text>
  <text x="25" y="270" font-size="9" fill="#64748b">GPIO 33 → LDR (ADC)</text>
  <text x="25" y="284" font-size="9" fill="#64748b">GPIO 34 → PIR</text>
  <text x="25" y="298" font-size="9" fill="#64748b">3.3V e GND para tudo</text>
</svg>
```

## Arduino MKR1000

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 700 350" font-family="Consolas, monospace">
  <rect width="700" height="350" fill="#f8fafc" rx="8"/>

  <text x="350" y="30" text-anchor="middle" font-weight="bold" font-size="16" fill="#1e293b">Arduino MKR1000</text>

  <!-- Arduino board -->
  <rect x="100" y="50" width="500" height="250" rx="10" fill="#0078b4"/>

  <!-- Pin headers left -->
  <g font-size="9" fill="#1e293b">
    <rect x="70" y="65" width="30" height="18" rx="2" fill="white"/><text x="78" y="78">D0</text>
    <rect x="70" y="88" width="30" height="18" rx="2" fill="white"/><text x="78" y="101">D1</text>
    <rect x="70" y="111" width="30" height="18" rx="2" fill="white"/><text x="78" y="124">D2</text>
    <rect x="70" y="134" width="30" height="18" rx="2" fill="white"/><text x="78" y="147">D3</text>
    <rect x="70" y="157" width="30" height="18" rx="2" fill="white"/><text x="78" y="170">D4</text>
    <rect x="70" y="180" width="30" height="18" rx="2" fill="white"/><text x="78" y="193">D5</text>
    <rect x="70" y="203" width="30" height="18" rx="2" fill="white"/><text x="78" y="216">D6</text>
    <rect x="70" y="226" width="30" height="18" rx="2" fill="white"/><text x="78" y="239">D7</text>
    <rect x="70" y="249" width="30" height="18" rx="2" fill="white"/><text x="78" y="262">D8</text>
    <rect x="70" y="272" width="30" height="18" rx="2" fill="white"/><text x="78" y="285">D9</text>
  </g>

  <!-- Pin headers right -->
  <g font-size="9" fill="#1e293b">
    <rect x="600" y="65" width="30" height="18" rx="2" fill="white"/><text x="608" y="78">D10</text>
    <rect x="600" y="88" width="30" height="18" rx="2" fill="white"/><text x="608" y="101">D11</text>
    <rect x="600" y="111" width="30" height="18" rx="2" fill="white"/><text x="608" y="124">D12</text>
    <rect x="600" y="134" width="30" height="18" rx="2" fill="white"/><text x="608" y="147">D13</text>
    <rect x="600" y="157" width="30" height="18" rx="2" fill="white"/><text x="608" y="170">A0</text>
    <rect x="600" y="180" width="30" height="18" rx="2" fill="white"/><text x="608" y="193">A1</text>
    <rect x="600" y="203" width="30" height="18" rx="2" fill="white"/><text x="608" y="216">A2</text>
    <rect x="600" y="226" width="30" height="18" rx="2" fill="white"/><text x="608" y="239">A3</text>
    <rect x="600" y="249" width="30" height="18" rx="2" fill="white"/><text x="608" y="262">A4</text>
    <rect x="600" y="272" width="30" height="18" rx="2" fill="white"/><text x="608" y="285">A5</text>
  </g>

  <!-- Center labels -->
  <text x="350" y="100" text-anchor="middle" font-size="14" font-weight="bold" fill="white">MKR1000</text>
  <text x="350" y="120" text-anchor="middle" font-size="10" fill="#e0f2fe">SAMD21 Cortex-M0 + WiFi</text>

  <!-- WiFi icon -->
  <text x="350" y="160" text-anchor="middle" font-size="24" fill="white">📶</text>

  <text x="350" y="200" text-anchor="middle" font-size="10" fill="#e0f2fe">Alimentação: 5V (USB) ou 3.7V LiPo</text>
  <text x="350" y="218" text-anchor="middle" font-size="10" fill="#e0f2fe">Tensão I/O: 3.3V</text>
  <text x="350" y="236" text-anchor="middle" font-size="10" fill="#e0f2fe">Pinos digitais: 0-13 (PWM: 5,6,8,9,10)</text>
  <text x="350" y="254" text-anchor="middle" font-size="10" fill="#e0f2fe">Entradas analógicas: A0-A5</text>
  <text x="350" y="272" text-anchor="middle" font-size="10" fill="#e0f2fe">WiFi: U-Blox NINA-W10 (2.4GHz)</text>

  <!-- Legend -->
  <rect x="20" y="60" width="40" height="60" rx="3" fill="#f1f5f9" stroke="#cbd5e1"/>
  <text x="25" y="76" font-size="8" fill="#475569">D=Digital</text>
  <text x="25" y="90" font-size="8" fill="#475569">A=Analog</text>
  <text x="25" y="104" font-size="8" fill="#475569">~=PWM</text>
</svg>
```
