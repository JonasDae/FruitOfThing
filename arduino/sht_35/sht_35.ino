#include "Seeed_SHT35.h"
#include <float.h>


#define SDAPIN  11		// serial data
#define SCLPIN  12		// serial clock
#define RSTPIN  13		// serial reset


SHT35 shtSensor(SCLPIN);
float tempSHT;
float humSHT;
float wetbulbSHT;

void setup()
{
    Serial.begin(9600);
    if(shtSensor.init())
    {
      Serial.println("sensor init failed!!!");
    }
    delay(10000);
}


void readSHT()
{
    if(shtSensor.read_meas_data_single_shot(HIGH_REP_WITH_STRCH,&tempSHT,&humSHT) != NO_ERROR)
    {
      Serial.println("read temp failed!!");
	    tempSHT = FLT_MAX;
      humSHT = FLT_MAX;
      wetbulbSHT = FLT_MAX;
    }
    else {
      wetbulbSHT = (tempSHT * atan(0.151977 * sqrt(humSHT + 8.313659))) + atan(tempSHT + humSHT) - atan(humSHT - 1.676331) + (0.00391838 * pow(sqrt(humSHT), 3.0) * atan(0.023101 * humSHT)) - 4.686035;

    }
}

void printSHT() {
      Serial.print("temperature = ");
      Serial.print(tempSHT);
	    Serial.println(" ℃ ");

      Serial.print("humidity = ");
      Serial.print(humSHT);
	    Serial.println(" % ");
    
      Serial.print("wetbulb = ");
      Serial.print(wetbulbSHT);
      Serial.println(" ℃ ");
}

void loop() {
	readSHT();
	printSHT();
 delay(1000);
}
