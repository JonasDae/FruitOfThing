#include <Seeed_SHT35.h>
#include <ArduinoJson.h>
#include <MKRGSM.h>
#include <ArduinoHttpClient.h>

#include <float.h>    // for FLT_MAX in sht35

// database constants
#define SENSOR_DENDROMETER 		1
#define SENSOR_TEMPERATURE 		2
#define SENSOR_HUMIDITY_SOIL 	3
#define SENSOR_HUMIDITY_AIR		4

// watermark pins
#define PIN_WM_GND 7
#define PIN_WM_PWR 6
#define PIN_WM_HUM A2
#define PIN_WM_VIN A3

// dendrometer pins
#define PIN_DENDRO_IN A1 // input pin for the Thermistor

// thermistor pins
#define PIN_THERM_PWR 6 // switching power for thermistor
#define PIN_THERM_IN A4 // input pin for the Thermistor

// sht 35 pins
#define SDAPIN  11		// serial data
#define SCLPIN  12		// serial clock
#define RSTPIN  13		// serial reset

// json constants
#define MODULE_NAME "logger 123"
#define JSON_SIZE 200

// gprs constants
#define GSM_PIN         "6615"
#define GPRS_APN        "internet.proximus.be"
#define GPRS_LOGIN      ""
#define GPRS_PASSWORD   ""

#define GPRS_SERVER     "floriandh.sinners.be"
#define GPRS_PATH       "/pcfruit/api/measurements/create.php"
#define GPRS_PORT       443


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

// sht 35 variables
SHT35 shtSensor(SCLPIN);
float tempSHT;
float humSHT;
float wetbulbSHT;

// GPRS variables
GSM gsm;
GPRS gprs;
GSMSSLClient client_gsm;
HttpClient client_http = HttpClient(client_gsm, GPRS_SERVER, GPRS_PORT);

//read sensor functions
float readWatermark(){

  digitalWrite(PIN_WM_PWR, HIGH);
  digitalWrite(PIN_WM_GND, LOW);
  delay(1000);
  digitalWrite(PIN_WM_PWR, LOW);
  digitalWrite(PIN_WM_GND, LOW);

  average_v_in = 0;
  average_v_out = 0;

  for (int i = 0; i < total_iterations; i++)
  {
    digitalWrite(PIN_WM_PWR, HIGH);
    digitalWrite(PIN_WM_GND, LOW);
    delayMicroseconds(49);
    //delay(0.09);
    val = analogRead(PIN_WM_HUM); //Read Sensor Pin A2
    val_vin = analogRead(PIN_WM_VIN); //Read Sensor Pin A3
    digitalWrite(PIN_WM_PWR, LOW);
    digitalWrite(PIN_WM_GND, LOW);

    v_in =  val_vin * (3.3 / arduino_resolution);
    v_out = val * (v_in / arduino_resolution);
    average_v_in += v_in;
    average_v_out += v_out;
  }

  average_v_out = average_v_out / total_iterations;
  average_v_in = average_v_in / total_iterations;

  rwm = ((average_v_in * resWatermark)/average_v_out)-resWatermark;

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

  digitalWrite(PIN_WM_PWR, LOW);
  digitalWrite(PIN_WM_GND, LOW);
  
}

float readDendro(){
  adcValDendro = analogRead(PIN_DENDRO_IN); // read dendrometer input
  delay(500);
  distDendro = (adcValDendro * 366 /divider); // calculate dendrometer distance in mm
  return distDendro;
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
// json functions
String build_json()
{
  String out = "";
  StaticJsonDocument<JSON_SIZE> doc;

  doc["module_id"] = 1;
  doc["battery_level"] = 69;
  doc["measure_date"] = "2020-01-30 10:20:20";
  /*
  doc["value"] = 66;
  doc["measure_date"] = "2069-01-30 10:20:20";
  */
  JsonArray data_arr = doc.createNestedArray("data");
  JsonObject sensordata = data_arr.createNestedObject();
  sensordata["sensor"] = SENSOR_DENDROMETER;
  sensordata["data"] = distdendro;  

  sensordata = data_arr.createNestedObject();
  sensordata["sensor"] = SENSOR_TEMPERATURE;
  sensordata["data"] = tempSHT;

  sensordata = data_arr.createNestedObject();
  sensordata["sensor"] = SENSOR_HUMIDITY_AIR;
  sensordata["data"] = humSHT;

  sensordata = data_arr.createNestedObject();
  sensordata["sensor"] = SENSOR_HUMIDITY_SOIL;
  sensordata["data"] = watermark_1_per_instant;

  serializeJson(doc, out);
  return out;
}

// GPRS functions
void json_push(String data) {
  boolean gsm_connected = false;
  while(!gsm_connected)
  {
    if((gsm.begin(GSM_PIN) == GSM_READY))
    {
      Serial.println("GSM OK");
      if(gprs.attachGPRS(GPRS_APN, GPRS_LOGIN, GPRS_PASSWORD) == GPRS_READY)
      {
        Serial.println("GPRS OK");
        gsm_connected = true;
      }
      else
        Serial.println("GPRS not connected, retrying ...");
    }
    else
      Serial.println("GSM not connected, retrying ...");
  }
  
  gsm_connected = false;
  while(!gsm_connected)
  {
    Serial.println(client_gsm.connect(GPRS_SERVER, GPRS_PORT));
    if(client_gsm.connect(GPRS_SERVER, GPRS_PORT))
    {
      Serial.println("HTTPS OK");
      gsm_connected = true;
    
      client_http.beginRequest();
      client_http.post(GPRS_PATH);
      client_http.sendHeader("Content-Type", "application/json");
    
      client_http.sendHeader("Content-length", data.length());
      client_http.beginBody();
      client_http.print(data);
    
      client_http.endRequest();
// FIXME: debug this
	  client_gsm.stop();
  }
  else
    Serial.println("HTTPS client not connected, retrying ...");
  }
}
// debug print functions
void printWatermark(){
  Serial.println("\nVin\tVout\tAnalog\tRwm\tcb\t%water\ttemp");
  Serial.println("-------------------------------------------------------------");
  Serial.print(average_v_in);
  Serial.print("V\t");
  Serial.print(average_v_out);
  Serial.print("V\t");
  Serial.print(val);
  Serial.print("\t");
  Serial.print((int)rwm);
  Serial.print("OHM\t");
  Serial.print(watermark_1_cb_instant);
  Serial.print("\t");
  Serial.print(watermark_1_per_instant);
  Serial.print("%\t");
  Serial.print(soil_temperature);
  Serial.println("C");
}
void printDendro() {
  Serial.println("\ndistance");
  Serial.println("-------------------------------------------------------------");
  Serial.print (distDendro,2); 
  Serial.println("mm\t");
}
void printSHT() {
  Serial.println("\ntemp\thumid\twetbulb");
  Serial.println("-------------------------------------------------------------");
  Serial.print(tempSHT);
  Serial.println("C\t");

  Serial.print(humSHT);
  Serial.println("%\t");

  Serial.print(wetbulbSHT);
  Serial.println("C\t");
}

void printAll() {
	printWatermark();
	printDendro();
	printSHT();
}

void setup() {

  Serial.begin(9600);

// watermark setup
  pinMode(PIN_WM_PWR, OUTPUT);
  pinMode(PIN_WM_GND, OUTPUT);
  pinMode(PIN_WM_HUM, INPUT);
  pinMode(PIN_WM_VIN, INPUT);
  digitalWrite(PIN_WM_PWR, LOW);
  digitalWrite(PIN_WM_GND, LOW);

// dendrometer setup
   // Setting analog read resolution to 12 bit 
   analogReadResolution(12);

// sht 35 setup
    if(shtSensor.init())
      Serial.println("sensor init failed!!!");
}

// Arduino loop
void loop() {
/*
  readWatermark();
  distDendro = readDendro();
  
  printAll();
  
  String json = build_json();
  json_push(json);
  */
  readSHT();
  printSHT();
  delay(1000);

}

// ============================================================================= //
/*
void loop() {
  
  StaticJsonDocument<JSON_SIZE> doc;

  doc["module_id"] = 1;
  doc["battery_level"] = 69;
  doc["module_sensor_id"] = 3;
  doc["value"] = 99;
  doc["measure_date"] = "2069-11-22 10:20:20";
  String data = "";
  serializeJson(doc, data);

  json_push(data);



  
   if(client_gsm.available() )
   {
    char c = client_gsm.read();
    Serial.print(c);
  }
  if(!client_gsm.available() && !client_gsm.connected())
  {
    client_gsm.stop();
  }
  delay(999999999);
}
*/