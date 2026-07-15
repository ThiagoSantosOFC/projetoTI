#include "dht22.h"

// Bit-bang DHT22 protocol (single-wire)
// No external library dependency

static bool _wait_for_level(int pin, int level, unsigned long timeout_us) {
    unsigned long start = micros();
    while (digitalRead(pin) != level) {
        if (micros() - start > timeout_us) return false;
    }
    return true;
}

bool dht22_read(DHT22* dht) {
    uint8_t data[5] = {0, 0, 0, 0, 0};

    pinMode(dht->pin, OUTPUT);
    digitalWrite(dht->pin, LOW);
    delay(1);                     // host pull low > 1ms
    pinMode(dht->pin, INPUT_PULLUP);
    delayMicroseconds(30);        // host pull up 20-40us

    if (!_wait_for_level(dht->pin, LOW, 200)) return false;
    if (!_wait_for_level(dht->pin, HIGH, 200)) return false;
    if (!_wait_for_level(dht->pin, LOW, 200)) return false;

    for (int i = 0; i < 40; i++) {
        if (!_wait_for_level(dht->pin, HIGH, 100)) return false;
        unsigned long t = micros();
        if (!_wait_for_level(dht->pin, LOW, 150)) return false;
        unsigned long duration = micros() - t;

        data[i / 8] <<= 1;
        if (duration > 50) {
            data[i / 8] |= 1;
        }
    }

    uint8_t checksum = data[0] + data[1] + data[2] + data[3];
    if (checksum != data[4]) return false;

    int16_t raw_temp = ((int16_t)data[2] << 8) | data[3];
    dht->temperatura = raw_temp / 10.0f;

    uint16_t raw_hum = ((uint16_t)data[0] << 8) | data[1];
    dht->humidade = raw_hum / 10.0f;

    return true;
}

DHT22 dht22_init(int pin) {
    DHT22 dht;
    dht.pin = pin;
    dht.temperatura = 0.0;
    dht.humidade = 0.0;
    pinMode(pin, INPUT_PULLUP);
    return dht;
}
