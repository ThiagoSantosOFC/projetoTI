#include "hcsr04.h"

HCSR04 hcsr04_init(int trigger, int echo) {
    HCSR04 s;
    s.trigger_pin = trigger;
    s.echo_pin = echo;
    pinMode(trigger, OUTPUT);
    pinMode(echo, INPUT);
    digitalWrite(trigger, LOW);
    return s;
}

float hcsr04_read_cm(HCSR04* sensor) {
    digitalWrite(sensor->trigger_pin, LOW);
    delayMicroseconds(2);
    digitalWrite(sensor->trigger_pin, HIGH);
    delayMicroseconds(10);
    digitalWrite(sensor->trigger_pin, LOW);

    long duration = pulseIn(sensor->echo_pin, HIGH, 30000UL);
    if (duration == 0) return -1.0;

    float distance = duration * 0.034 / 2.0;
    return distance;
}
