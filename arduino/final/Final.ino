// watermark initialisation
#define pinHumGnd 7
#define pinHumPwr 6
#define pinHumAnalog A2
#define pinVinAnalog A3

// dendrometer initialisation
#define dendroAnalogIn A1 // input pin for the Thermistor

// thermistor initialisation
#define thermPower 6 // switching power for thermistor
#define thermAnalogIn A4 // input pin for the Thermistor

//Watermark variables:
float resWatermark = 7760.0; //Ohm R;
float arduino_resolution = 1023.0;
int total_iterations = 10;
float watermark_1_cb = 0.0;
float watermark_1_cb_med = 0.0;
float watermark_1_cb_instant = 0.0;
float watermark_1_per = 0;
float watermark_1_per_med = 0.0;
float watermark_1_per_instant = 0.0;
float soil_temperature = 6.0;
float average_v_in = 0.0;
float average_v_out = 0.0;
float val = 0.0;
float val_vin = 0.0;
float v_in = 0.0;
float v_out = 0.0;
float rwm;

// Dendrometer variables:
int adcValDendro = 0;  // variable to store the value coming from the sensor
float distDendro;
float divider = 100000;

//read sensor functions
float readWatermark(){

  digitalWrite(pinHumPwr, HIGH);
  digitalWrite(pinHumGnd, LOW);
  delay(1000);
  digitalWrite(pinHumPwr, LOW);
  digitalWrite(pinHumGnd, LOW);

  average_v_in = 0;
  average_v_out = 0;

  for (int i = 0; i < total_iterations; i++)
  {
    digitalWrite(pinHumPwr, HIGH);
    digitalWrite(pinHumGnd, LOW);
    delayMicroseconds(49);
    //delay(0.09);
    val = analogRead(pinHumAnalog); //Read Sensor Pin A2
    val_vin = analogRead(pinVinAnalog); //Read Sensor Pin A3
    digitalWrite(pinHumPwr, LOW);
    digitalWrite(pinHumGnd, LOW);

    v_in =  val_vin * (3.3 / arduino_resolution);
    v_out = val * (v_in / arduino_resolution);
    average_v_in += v_in;
    average_v_out += v_out;
  }

  average_v_out = average_v_out / total_iterations;
  average_v_in = average_v_in / total_iterations;

  rwm = ((average_v_in * resWatermark)/average_v_out)-resWatermark;
  //float rwm = (average_v_out * resWatermark) / (average_v_in - average_v_out);

  if (rwm <= 500) {
    watermark_1_cb_instant = 0;
  } else if (rwm > 500 && rwm <= 1000) {
    watermark_1_cb_instant = -20 * ((rwm / 1000.0) * (1.00 + 0.018 * (soil_temperature - 24)) - 0.55);
  } else if (rwm > 1000 && rwm <= 8000) {
    watermark_1_cb_instant = (-3.213 * (rwm / 1000.0) - 4.093) / (1.0 - 0.009733 * (rwm / 1000.0) - 0.01205 * soil_temperature);
  } else if (rwm > 8000) {
    watermark_1_cb_instant = -2.246 - 5.239 * (rwm / 1000.00) * (1.0 + 0.018 * (soil_temperature - 24.00)) - 0.06756 * (rwm / 1000.00) * (rwm / 1000.00) * ((1.00 + 0.018 * (soil_temperature - 24.00)) * (1.00 + 0.018 * (soil_temperature - 24.00)));
  }

  //map(value, fromLow, fromHigh, toLow, toHigh)
  watermark_1_per_instant = map(watermark_1_cb_instant, -200, 0, 100, 0);  //Convert to Percentage
  //http://www.omafra.gov.on.ca/english/engineer/facts/11-037f4.gif

  digitalWrite(pinHumPwr, LOW);
  digitalWrite(pinHumGnd, LOW);
  
}

float readDendro(){
  adcValDendro = analogRead(dendroAnalogIn); // read dendrometer input
  delay(500);
  distDendro = (adcValDendro * 366 /divider); // calculate dendrometer distance in mm
  return distDendro;
}

// debug print functions
void printWatermark(){
  Serial.println("\nVin\tVout\tAnalog\tRwm\tcb\t%water\ttemp");
  Serial.println("-------------------------------------------------------------");
  Serial.print(average_v_in);
  Serial.print("\t");
  Serial.print(average_v_out);
  Serial.print("\t");
  Serial.print(val);
  Serial.print("\t");
  Serial.print((int)rwm);
  Serial.print("\t");
  Serial.print(watermark_1_cb_instant);
  Serial.print("\t");
  Serial.print(watermark_1_per_instant);
  Serial.print("%\t");
  Serial.print(soil_temperature);
  Serial.println("C");
}
void printDendro() {
  Serial.print ("Distance: "); 
  Serial.print (distDendro,2); 
  Serial.println(" mm");
  //Serial.println ();
  //Serial.print ("ADC: ");
  //Serial.println (adcValDendro);
  //Serial.print ("Distance: "); 
  //Serial.print (distDendro,2); 
  //Serial.println(" mm");
}

void printAll() {
	printWatermark();
	printDendro();
}



void setup() {

  Serial.begin(9600);

// watermark setup
  pinMode(pinHumPwr, OUTPUT);
  pinMode(pinHumGnd, OUTPUT);
  pinMode(pinHumAnalog, INPUT);
  pinMode(pinVinAnalog, INPUT);
  digitalWrite(pinHumPwr, LOW);
  digitalWrite(pinHumGnd, LOW);

// dendrometer setup
   // Setting analog read resolution to 12 bit 
   analogReadResolution(12);
}

// Arduino loop
void loop() {

  readWatermark();
  distDendro = readDendro();

  printAll();
  
}

// ============================================================================= //
/*
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
*/
