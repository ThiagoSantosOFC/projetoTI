#include <Arduino.h>
#include "config.h"
#include "lib/wifi_manager.h"
#include "lib/scheduler.h"
#include "lib/http_client.h"
#include "lib/resource.h"
#include "lib/devices/led.h"
#include "lib/devices/buzzer.h"
#include "lib/devices/hcsr04.h"
#include "lib/devices/button.h"
#include "lib/devices/dht22.h"
#include "lib/devices/servo.h"

static Resource sensor_ldr;
static Resource sensor_dist;
static Resource sensor_dht;
static Resource act_led_verde;
static Resource act_led_vermelho;
static Resource act_buzzer;

static LED led_verde;
static LED led_vermelho;
static Buzzer buzzer;
static HCSR04 hcsr04;
static Button btn;
static DHT22 dht22;

static unsigned long last_led_blink = 0;
static bool led_builtin_state = false;

void task_read_sensors() {
    Serial.println("[TASK] Reading sensors...");

    int ldr_raw = analogRead(SENSOR_1_PIN);
    float ldr_voltage = (ldr_raw / 4095.0f) * 3.3f;
    char ldr_buf[16];
    dtostrf(ldr_voltage, 1, 2, ldr_buf);
    resource_set_value(&sensor_ldr, ldr_buf);

    float dist = hcsr04_read_cm(&hcsr04);
    if (dist >= 0) {
        char dist_buf[16];
        dtostrf(dist, 1, 1, dist_buf);
        resource_set_value(&sensor_dist, dist_buf);
    }

    if (dht22_read(&dht22)) {
        char temp_buf[16], hum_buf[16];
        dtostrf(dht22.temperatura, 1, 1, temp_buf);
        dtostrf(dht22.humidade, 1, 1, hum_buf);
        resource_set_value(&sensor_dht, temp_buf);
    }

    char url[128];
    char body[32];

    snprintf(url, sizeof(url), "%s/resources/%s/value", API_BASE_URL, sensor_ldr.id);
    snprintf(body, sizeof(body), "valor=%s", sensor_ldr.valor);
    int code = http_patch(url, API_TOKEN, body);
    Serial.printf("  LDR PATCH -> %d\n", code);

    snprintf(url, sizeof(url), "%s/resources/%s/value", API_BASE_URL, sensor_dist.id);
    snprintf(body, sizeof(body), "valor=%s", sensor_dist.valor);
    code = http_patch(url, API_TOKEN, body);
    Serial.printf("  DIST PATCH -> %d\n", code);

    snprintf(url, sizeof(url), "%s/resources/%s/value", API_BASE_URL, sensor_dht.id);
    snprintf(body, sizeof(body), "valor=%s", sensor_dht.valor);
    code = http_patch(url, API_TOKEN, body);
    Serial.printf("  DHT PATCH -> %d\n", code);
}

void task_check_actuators() {
    Serial.println("[TASK] Checking actuators...");

    const char* act_ids[] = { act_led_verde.id, act_led_vermelho.id, act_buzzer.id };
    LED* leds[] = { &led_verde, &led_vermelho, NULL };
    int pins[] = { -1, -1, ACT_BUZZER };

    for (int i = 0; i < 3; i++) {
        char url[128];
        snprintf(url, sizeof(url), "%s/resources/%s/command", API_BASE_URL, act_ids[i]);

        int code = http_get(url, API_TOKEN);
        if (code == 200) {
            if (i == 0) {
                led_on(&led_verde);
                resource_set_state(&act_led_verde, ON);
            } else if (i == 1) {
                led_off(&led_vermelho);
                resource_set_state(&act_led_vermelho, OFF);
            } else if (i == 2) {
                buzzer_beep(&buzzer, 200);
            }
        } else {
            if (i == 0) led_off(&led_verde);
            if (i == 1) led_on(&led_vermelho);
            if (i == 2) buzzer_off(&buzzer);
        }
    }
}

void setup() {
    Serial.begin(115200);
    delay(1000);
    Serial.println("\n=== MCU Firmware v1.0 ===");

    wifi_init(WIFI_SSID, WIFI_PASS);

    sensor_ldr = resource_create("sensor_ldr", SENSOR, SENSOR_1_PIN);
    sensor_dist = resource_create("sensor_distancia", SENSOR, SENSOR_2_PIN);
    sensor_dht = resource_create("sensor_temp", SENSOR, dht22.pin);
    act_led_verde = resource_create("act_led_verde", ACTUATOR, ACT_LED_VERDE);
    act_led_vermelho = resource_create("act_led_vermelho", ACTUATOR, ACT_LED_VERMELHO);
    act_buzzer = resource_create("act_buzzer", ACTUATOR, ACT_BUZZER);

    led_verde = led_init(ACT_LED_VERDE);
    led_vermelho = led_init(ACT_LED_VERMELHO);
    buzzer = buzzer_init(ACT_BUZZER);
    hcsr04 = hcsr04_init(SENSOR_2_PIN, SENSOR_2_ECHO);
    btn = button_init(0);
    dht22 = dht22_init(4);

    pinMode(LED_BUILTIN, OUTPUT);

    scheduler_init();
    scheduler_every(INTERVALO_LEITURA, task_read_sensors);
    scheduler_every(INTERVALO_ATUADORES, task_check_actuators);

    Serial.println("Setup complete. Entering loop.");
}

void loop() {
    wifi_ensure_connected();

    unsigned long now = millis();
    if (now - last_led_blink >= 1000) {
        last_led_blink = now;
        led_builtin_state = !led_builtin_state;
        digitalWrite(LED_BUILTIN, led_builtin_state);
    }

    if (button_is_pressed(&btn)) {
        Serial.println("Button pressed!");
        buzzer_beep(&buzzer, 100);
        delay(300);
    }

    scheduler_run();
}
