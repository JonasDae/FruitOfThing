#define PIN_LED			2000
#define LED_MASK_SIZE	8-1		// 1 byte

void led_blink(char mask)
{
	for(int i=LED_MASK_SIZE;i>=0;i--)
	{
		if(mask & (1 << i))
		{
			digitalWrite(PIN_LED, HIGH);
			delay(250);
			digitalWrite(PIN_LED, LOW);
			delay(250);
		}
		else
			delay(500);
	}
}

void setup()
{
    Serial.begin(9600);
    delay(10000);
	pinMode(PIN_LED, OUTPU);
}

void loop() {
	led_blink(0xff);
	delay(1000);
}
