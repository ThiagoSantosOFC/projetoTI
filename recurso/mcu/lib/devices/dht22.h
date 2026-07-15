#ifndef DHT22_H
#define DHT22_H

#include <Arduino.h>

typedef struct {
    int pin;
    float temperatura;
    float humidade;
} DHT22;

DHT22 dht22_init(int pin);
bool dht22_read(DHT22* dht);

#endif
