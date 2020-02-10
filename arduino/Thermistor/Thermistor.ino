// thermistor initialisation
#define thermPower 6 // switching power for thermistor
#define thermAnalogIn A4 // input pin for the Thermistor
float adcValTherm = 0;
float adcMax = 1023.0;
float voltMax = 3300;
float resTherm = 24900;
float varResTherm = 0; 
float tempTherm = 0; 
float voltTherm = 0;  // variable to store the value coming from the sensor

float readThermistor(){

  digitalWrite(thermPower,HIGH); // power up thermistor
  delay(500);
  adcValTherm = analogRead(thermAnalogIn);  // read thermistor input
  digitalWrite(thermPower,LOW); // power down thermistor
  //Serial.print("ADC In: ");
  //Serial.println(adcValue);
  voltTherm = adcValTherm * (voltMax / adcMax); // calculate thermistor voltage in mV
  //Serial.print("Voltage In: "); 
  //Serial.print(voltMax); 
  //Serial.println(" mV");
  //Serial.print("Static Resistance: ");
  //Serial.print(resTherm);
  //Serial.println(" Ω");
  //Serial.print("Thermistor Voltage: ");
  //Serial.print(voltTherm);
  //Serial.println(" mV");
  //varResTherm = ((voltMax * resTherm)/voltTherm)-resTherm;
  varResTherm = resTherm * ((voltMax/voltTherm)-1); // calculate thermistor resistance
  tempTherm = (1/(0.001129241 + (0.0002341077 * log(varResTherm)) + (0.00000008775468 * pow(log(varResTherm), 3.0))))-273.15; // calculate temperature in °C
  //Serial.print("Temperature: ");
  //Serial.print(tempTherm);
  //Serial.println(" °C");
  //Serial.println();
  delay(1000);

  return tempTherm; 
  
}

void printAll(){

  Serial.print ("Temperature thermistor: "); 
  Serial.print(tempTherm);
  Serial.println(" °C");
  
}

void setup() {
  Serial.begin(9600);

  // thermistor setup
  pinMode(thermPower,OUTPUT);
}
  
void loop() {

  tempTherm = readThermistor();

  printAll();
  
}
