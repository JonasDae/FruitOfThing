#include <OneWire.h>
#include <DallasTemperature.h>
#define ONE_WIRE_BUS 2 // Data wire is plugged into port 2 on the Arduino
float tempGnd = 0; 
OneWire oneWire(ONE_WIRE_BUS); // Setup a oneWire instance to communicate with any OneWire devices (not just Maxim/Dallas temperature ICs)
DallasTemperature sensors(&oneWire); // Pass our oneWire reference to Dallas Temperature. 


float readTemp(){

  sensors.requestTemperatures(); // Send the command to get temperatures // request to all devices on the bus // call sensors.requestTemperatures() to issue a global temperature
  tempGnd = sensors.getTempCByIndex(0);
  return tempGnd; 
  
}

void printAll(){

   Serial.print("Temperature ground: ");
   Serial.print(tempGnd);
   Serial.println(" °C");
  
}
 
void setup()
{
  Serial.begin(9600);
  sensors.begin(); // Start up the library
}
 
void loop()
{ 
    tempGnd = readTemp();
    delay(1000);
    printAll();
}
