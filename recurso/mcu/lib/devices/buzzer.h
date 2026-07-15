#ifndef BUZZER_H
#define BUZZER_H

#include <Arduino.h>

typedef struct {
    int pin;
} Buzzer;

Buzzer buzzer_init(int pin);
void buzzer_on(Buzzer* b);
void buzzer_off(Buzzer* b);
void buzzer_beep(Buzzer* b, int duracao_ms);

#endif
