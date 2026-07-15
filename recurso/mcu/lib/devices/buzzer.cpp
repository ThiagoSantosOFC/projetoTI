#include "buzzer.h"

Buzzer buzzer_init(int pin) {
    Buzzer b;
    b.pin = pin;
    pinMode(pin, OUTPUT);
    digitalWrite(pin, LOW);
    return b;
}

void buzzer_on(Buzzer* b) {
    digitalWrite(b->pin, HIGH);
}

void buzzer_off(Buzzer* b) {
    digitalWrite(b->pin, LOW);
}

void buzzer_beep(Buzzer* b, int duracao_ms) {
    digitalWrite(b->pin, HIGH);
    delay(duracao_ms);
    digitalWrite(b->pin, LOW);
}
