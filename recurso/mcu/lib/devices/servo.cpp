#include "servo.h"
#include "driver/ledc.h"

#define SERVO_FREQ 50
#define SERVO_RES LEDC_TIMER_8_BIT
#define SERVO_CHANNEL LEDC_CHANNEL_0

static int _pin = -1;

void servo_init(int pin) {
    _pin = pin;

    ledc_timer_config_t timer = {
        .speed_mode = LEDC_HIGH_SPEED_MODE,
        .duty_resolution = SERVO_RES,
        .timer_num = LEDC_TIMER_0,
        .freq_hz = SERVO_FREQ,
        .clk_cfg = LEDC_AUTO_CLK
    };
    ledc_timer_config(&timer);

    ledc_channel_config_t channel = {
        .gpio_num = pin,
        .speed_mode = LEDC_HIGH_SPEED_MODE,
        .channel = SERVO_CHANNEL,
        .timer_sel = LEDC_TIMER_0,
        .duty = 0,
        .hpoint = 0
    };
    ledc_channel_config(&channel);
}

void servo_set_angle(int angle) {
    if (_pin < 0 || angle < 0 || angle > 180) return;
    // 0deg -> 2.5% duty (6.4), 180deg -> 12.5% duty (32)
    uint32_t duty = map(angle, 0, 180, 6, 32);
    ledc_set_duty(LEDC_HIGH_SPEED_MODE, SERVO_CHANNEL, duty);
    ledc_update_duty(LEDC_HIGH_SPEED_MODE, SERVO_CHANNEL);
}
