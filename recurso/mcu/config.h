#ifndef CONFIG_H
#define CONFIG_H

#define WIFI_SSID "YOUR_SSID"
#define WIFI_PASS "YOUR_PASSWORD"
#define API_BASE_URL "http://192.168.1.100:8000/api"
#define API_TOKEN "mcu_token_456"

#define INTERVALO_LEITURA 10000    // ms
#define INTERVALO_ATUADORES 5000   // ms

// Mapeamento de sensores
#define SENSOR_1_PIN 34   // ADC1_CH6 - LDR/sensor analógico
#define SENSOR_2_PIN 5    // GPIO5 - HC-SR04 trigger
#define SENSOR_2_ECHO 18  // GPIO18 - HC-SR04 echo

// Mapeamento de atuadores
#define ACT_LED_VERDE 19  // GPIO19
#define ACT_LED_VERMELHO 21  // GPIO21
#define ACT_BUZZER 22     // GPIO22

#endif
