/*
  Analog Input

  Demonstrates analog input by reading an analog sensor on analog pin 0 and
  turning on and off a light emitting diode(LED) connected to digital pin 13.
  The amount of time the LED will be on and off depends on the value obtained
  by analogRead().

  The circuit:
  - potentiometer
    center pin of the potentiometer to the analog input 0
    one side pin (either one) to ground
    the other side pin to +5V
  - LED
    anode (long leg) attached to digital output 13
    cathode (short leg) attached to ground

  - Note: because most Arduinos have a built-in LED attached to pin 13 on the
    board, the LED is optional.

  created by David Cuartielles
  modified 30 Aug 2011
  By Tom Igoe

  This example code is in the public domain.

  http://www.arduino.cc/en/Tutorial/AnalogInput
*/

#define ANALOG_RESOLUTION   4096.0

int PIN_BATTERY = A4;    // select the input pin for the potentiometer
int batteryAnalog = 0;  // variable to store the value coming from the sensor
float BATTERY_MIN = 2.6; //11.8V after stepdown 
float BATTERY_MAX = 3.3; //13.8V after stepdown 
float batteryValue = 0;
int battery = 0;



float readBattery(){
  Serial.println();
  // read the value from the sensor:
  batteryAnalog = analogRead(PIN_BATTERY);
  batteryValue = batteryAnalog * (3.3 / ANALOG_RESOLUTION); 
  battery = ((batteryValue-BATTERY_MIN)/(BATTERY_MAX-BATTERY_MIN))*100;
}

void printBattery(){
  Serial.println("\nVin\tAnalog\t%");
  Serial.println("-------------------------------------------------------------");
  Serial.print(batteryAnalog);
  Serial.print("V\t");
  Serial.print(batteryValue);
  Serial.print("\t");
  Serial.print(battery);
  Serial.print("%");
}
void setup() {
  
  Serial.begin(9600);
  // Setting analog read resolution to 12 bit 
   analogReadResolution(12);
  
}

void loop() {
  
  readBattery();
  printBattery();
 
}
