#include "button.h"

Button button_init(int pin) {
    Button btn;
    btn.pin = pin;
    pinMode(pin, INPUT_PULLUP);
    return btn;
}

bool button_is_pressed(Button* btn) {
    return digitalRead(btn->pin) == LOW;
}
