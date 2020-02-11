#include <RTCZero.h>

RTCZero rtc;

void rtc_reset()
{
	rtc.setTime(0);
}

String get_date()
{
	String out = "";
	out += rtc.getYear() + "-";
	out += rtc.getMonth() + "-";
	out += rtc.getDay();
	out += " ";
	out += rtc.getHours() + ":";
	out += rtc.getMinutes() + ":";
	out += rtc.getSeconds();
}

void setup()
{
    Serial.begin(9600);
	rtc.begin();
//	rtc.setTime(hr, min, sec);
//	rtc.setDate(day, mnt, yr);
}

void loop() {
	delay(1000);
	Serial.println(get_date());
}
