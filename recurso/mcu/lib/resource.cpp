#include "resource.h"

Resource resource_create(const char* id, ResourceType tipo, int pin) {
    Resource r;
    strncpy(r.id, id, sizeof(r.id) - 1);
    r.id[sizeof(r.id) - 1] = '\0';
    r.nome[0] = '\0';
    r.tipo = tipo;
    r.estado = OFF;
    r.valor[0] = '\0';
    r.unidade[0] = '\0';
    r.pin = pin;
    return r;
}

void resource_set_value(Resource* r, const char* valor) {
    strncpy(r->valor, valor, sizeof(r->valor) - 1);
    r->valor[sizeof(r->valor) - 1] = '\0';
}

void resource_set_state(Resource* r, ResourceState state) {
    r->estado = state;
}
