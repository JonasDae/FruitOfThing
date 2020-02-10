#include "Seeed_SHT35.h"
#include <float.h>


#define SDAPIN  11		// serial data
#define SCLPIN  12		// serial clock
#define RSTPIN  13		// serial reset


SHT35 shtSensor(SCLPIN);
float tempSHT;
float humSHT;

void setup()
{
    SERIAL.begin(9600);
    if(shtSensor.init())
    {
      SERIAL.println("sensor init failed!!!");
    }
}


void readSHT()
{
    float tempSHT,humSHT;
    if(shtSensor.read_meas_data_single_shot(HIGH_REP_WITH_STRCH,&tempSHT,&humSHT) != NO_ERROR)
    {
      SERIAL.println("read temp failed!!");
	  tempSHT = FLT_MAX;
	  humSHT = FLT_MAX;
    }
}

void printSHT() {
      SERIAL.print("temperature = ");
      SERIAL.print(tempSHT);
	  SERIAL.println(" ℃ ");

      SERIAL.print("humidity = ");
      SERIAL.print(humSHT);
	  SERIAL.println(" % ");
}

void loop() {
	readSHT();
	printSHT();
}
