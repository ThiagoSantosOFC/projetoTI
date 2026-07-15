#ifndef BUTTON_H
#define BUTTON_H

#include <Arduino.h>

typedef struct {
    int pin;
} Button;

Button button_init(int pin);
bool button_is_pressed(Button* btn);

#endif
