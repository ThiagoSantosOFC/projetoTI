#ifndef HCSR04_H
#define HCSR04_H

#include <Arduino.h>

typedef struct {
    int trigger_pin;
    int echo_pin;
} HCSR04;

HCSR04 hcsr04_init(int trigger, int echo);
float hcsr04_read_cm(HCSR04* sensor);

#endif
