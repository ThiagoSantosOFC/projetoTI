#include "http_client.h"
#include <WiFiClient.h>
#include <HTTPClient.h>

static const int HTTP_TIMEOUT = 5000;

static int _request(const char* method, const char* url, const char* token, const char* body) {
    if (WiFi.status() != WL_CONNECTED) return -1;

    HTTPClient http;
    http.begin(url);
    http.setTimeout(HTTP_TIMEOUT);
    http.addHeader("Authorization", token);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int httpCode = -1;
    if (strcmp(method, "GET") == 0) {
        httpCode = http.GET();
    } else if (strcmp(method, "POST") == 0) {
        httpCode = http.POST(body);
    } else if (strcmp(method, "PATCH") == 0) {
        httpCode = http.PATCH(body);
    }

    http.end();
    return httpCode;
}

int http_patch(const char* url, const char* token, const char* body) {
    return _request("PATCH", url, token, body);
}

int http_get(const char* url, const char* token) {
    return _request("GET", url, token, NULL);
}

int http_post(const char* url, const char* token, const char* body) {
    return _request("POST", url, token, body);
}
