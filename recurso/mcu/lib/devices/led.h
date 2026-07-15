#ifndef LED_H
#define LED_H

#include <Arduino.h>

typedef struct {
    int pin;
    bool estado;
} LED;

LED led_init(int pin);
void led_on(LED* led);
void led_off(LED* led);
void led_toggle(LED* led);

#endif
