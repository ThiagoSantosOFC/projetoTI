#ifndef RESOURCE_H
#define RESOURCE_H

#include <Arduino.h>

typedef enum { SENSOR, ACTUATOR } ResourceType;
typedef enum { OFF, ON } ResourceState;

typedef struct {
    char id[32];
    char nome[64];
    ResourceType tipo;
    ResourceState estado;
    char valor[16];
    char unidade[16];
    int pin;
} Resource;

Resource resource_create(const char* id, ResourceType tipo, int pin);
void resource_set_value(Resource* r, const char* valor);
void resource_set_state(Resource* r, ResourceState state);

#endif
