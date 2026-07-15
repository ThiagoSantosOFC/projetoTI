#include "led.h"

LED led_init(int pin) {
    LED led;
    led.pin = pin;
    led.estado = false;
    pinMode(pin, OUTPUT);
    digitalWrite(pin, LOW);
    return led;
}

void led_on(LED* led) {
    digitalWrite(led->pin, HIGH);
    led->estado = true;
}

void led_off(LED* led) {
    digitalWrite(led->pin, LOW);
    led->estado = false;
}

void led_toggle(LED* led) {
    led->estado = !led->estado;
    digitalWrite(led->pin, led->estado ? HIGH : LOW);
}
