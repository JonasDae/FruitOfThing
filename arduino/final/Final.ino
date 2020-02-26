#include <Seeed_SHT35.h>
#include <ArduinoJson.h>
#include <MKRGSM.h>
#include <ArduinoHttpClient.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <RTCZero.h>
#include <SD.h>

#include <float.h>    // for FLT_MAX in sht35

#define ANALOG_RESOLUTION   4096.0

// database constants
#define SENSOR_ID_1         1   // DENDRO
#define SENSOR_ID_2         2   // TEMP AIR
#define SENSOR_ID_3         3   // HUMID AIR
#define SENSOR_ID_4         4   // HUMID GND
#define SENSOR_ID_5         5   // TEMP GND

int MODULE_ID = 1;
int SENSOR_DENDROMETER 		    = 13;
int SENSOR_TEMPERATURE_SOIL   = 14;
int SENSOR_HUMIDITY_SOIL 	    = 16;
int SENSOR_HUMIDITY_AIR		    = 15;
int SENSOR_TEMPERATURE_AIR    = 99;

// final values
#define FINAL_DENDRO    0
#define FINAL_TEMP      1
#define FINAL_HUMID_GND 2
#define FINAL_HUMID_AIR 3
float finals[] = {0.f, 0.f, 0.f, 0.f};
int final_cnt = 0;

// LED defines
#define PIN_LED 		    200		// FIXME
#define LED_MASK_SIZE 	8-1   // 1 byte 0->7

#define LED_MASK_GSM		0x15		// 1 0 1 0 1
#define LED_MASK_GPRS		0x1b		// 1 1 0 1 1
#define LED_MASK_HTTP		0x17		// 1 0 1 1 1
#define LED_MASK_SHT_INIT	0x1d	// 1 1 1 0 1
#define LED_MASK_SHT_READ	0x1a	// 1 1 0 1 0

// watermark pins
#define PIN_WM_GND 7
#define PIN_WM_PWR 6
#define PIN_WM_HUM A2
#define PIN_WM_VIN A3
#define WM_NUM_ITERATION 10

// dendrometer pins
#define PIN_DENDRO_IN A1 // input pin for the dendrometer

// OneWire temperature sensor constants
#define ONE_WIRE_BUS 2

// sht 35 pins
#define SDAPIN  11		// serial data
#define SCLPIN  12		// serial clock
#define RSTPIN  13		// serial reset

// battery pins
#define PIN_BATTERY   A4
#define BATTERY_MIN   2.6f
#define BATTERY_MAX   3.3f

// json constants
#define MODULE_NAME "geuderduino"
#define JSON_SIZE 512

// gprs constants
#define GSM_PIN         "6615"
#define GPRS_APN        "internet.proximus.be"
#define GPRS_LOGIN      ""
#define GPRS_PASSWORD   ""

#define GPRS_SERVER       "floriandh.sinners.be"
#define GPRS_PATH_MEASURE       "/pcfruit/api/measurements/create.php"
#define GPRS_PATH_MODULES       "/pcfruit/api/modules/create.php"
#define GPRS_PATH_NOTIFICATION  "/pcfruit/api/modules/create.php"
#define GPRS_PORT         443

#define GSM_TRIES       10

// SD constants
#define SD_CHIPSELECT 4
#define SD_FILE_NAME "DATALOG.TXT"

//Watermark variables:
float resWatermark = 7760.0; //Ohm R;

float watermark_1_cb = 0.0;
float watermark_1_kp_instant = 0.0;
float watermark_1_per = 0;
float watermark_1_per_instant = 0.0;
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

// OneWire temperature sensor variables
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature tempSensor(&oneWire);
float tempGnd = 0.f;

// sht 35 variables
SHT35 shtSensor(SCLPIN);
float tempSHT;
float humSHT;
float wetbulbSHT;

// battery values
float batteryAnalog = 0.f;
float batteryValue = 0.f;
int battery = 0;

// GPRS variables
GSM gsm;
GPRS gprs;
GSMSSLClient client_gsm;
HttpClient client_http = HttpClient(client_gsm, GPRS_SERVER, GPRS_PORT);

// RTC variables
RTCZero rtc;

// Notification variables
String severities[] = {"success", "warning", "danger", "info"};
String checks[] = {"Watermark: ", "Dendro: ", "Temp: ", "SHT: ", "GSM: ", "GPRS: ", "HTTPS: "};
#define NOTIF_COUNT 4
String notifSeverity[NOTIF_COUNT];
String notifText[NOTIF_COUNT];
#define CHECK_SUCCESS 0
#define CHECK_WARNING 1
#define CHECK_DANGER 2
#define CHECK_INFO 3

//check system/sensors
int checkGSM(){
  
  return CHECK_SUCCESS;
  
}

int checkGPRS(){
  
  return CHECK_SUCCESS;
  
}

int checkHTTPS(){
  
  return CHECK_SUCCESS;
  
}

int checkWatermark(){
  
  return CHECK_SUCCESS;
  
}

int checkDendro(){
  
  return CHECK_SUCCESS;
  
}

int checkTemp(){
  
  return CHECK_SUCCESS;
  
}

int checkSHT(){
  
  return CHECK_SUCCESS;
  
}


void printNotif(){

  Serial.println();
  Serial.println("Notifications: ");
  
   for(int i = 0; i < NOTIF_COUNT; i++)
  {
    Serial.print(checks[i]);
    Serial.println(notifSeverity[i]);
  }

  Serial.println();

}





//read sensor functions
float readWatermark(){

  digitalWrite(PIN_WM_PWR, HIGH);
  digitalWrite(PIN_WM_GND, LOW);
  delay(1000);
  digitalWrite(PIN_WM_PWR, LOW);
  digitalWrite(PIN_WM_GND, LOW);

  average_v_in = 0;
  average_v_out = 0;

  for (int i = 0; i < WM_NUM_ITERATION; i++)
  {
    digitalWrite(PIN_WM_PWR, HIGH);
    digitalWrite(PIN_WM_GND, LOW);
    delayMicroseconds(49);
    //delay(0.09);
    val = analogRead(PIN_WM_HUM); //Read Sensor Pin A2
    val_vin = analogRead(PIN_WM_VIN); //Read Sensor Pin A3
    digitalWrite(PIN_WM_PWR, LOW);
    digitalWrite(PIN_WM_GND, LOW);

    v_in =  val_vin * (3.3 / ANALOG_RESOLUTION);
    v_out = val * (v_in / ANALOG_RESOLUTION);
    average_v_in += v_in;
    average_v_out += v_out;
  }

  average_v_out = average_v_out / WM_NUM_ITERATION;
  average_v_in = average_v_in / WM_NUM_ITERATION;

  rwm = ((average_v_in * resWatermark)/average_v_out)-resWatermark;

  if (rwm <= 550) {
    watermark_1_kp_instant = 0;
  } else if (rwm < 1000) {
    watermark_1_kp_instant = -20 * ((rwm / 1000.0) * (1.00 + 0.018 * (tempGnd- 24.f)) - 0.55);
  } else if (rwm < 8000) {
    watermark_1_kp_instant = (-3.213 * (rwm / 1000.0) - 4.093) / (1.0 - 0.009733 * (rwm / 1000.0) - 0.01205 * tempGnd);
  } else {
    watermark_1_kp_instant = -2.246 - 5.239 * (rwm / 1000.00) * (1.0 + 0.018 * (tempGnd - 24.00)) - 0.06756 * (rwm / 1000.00) * (rwm / 1000.00) * ((1.00 + 0.018 * (tempGnd - 24.00)) * (1.00 + 0.018 * (tempGnd - 24.00)));
  }

  //map(value, fromLow, fromHigh, toLow, toHigh)
  watermark_1_per_instant = map(watermark_1_kp_instant, -200, 0, 100, 0);  //Convert to Percentage
  //http://www.omafra.gov.on.ca/english/engineer/facts/11-037f4.gif

  digitalWrite(PIN_WM_PWR, LOW);
  digitalWrite(PIN_WM_GND, LOW);

  //notification
  notifSeverity[0] = severities[checkWatermark()]; 
  
}

float readDendro(){
  adcValDendro = analogRead(PIN_DENDRO_IN); // read dendrometer input
  delay(500);
  distDendro = (adcValDendro * 366 /divider); // calculate dendrometer distance in mm


  //notification
  notifSeverity[1] = severities[checkDendro()]; 

  
  return distDendro;
}

float readTemp()
{
  tempSensor.requestTemperatures();
  tempGnd = tempSensor.getTempCByIndex(0);


  //notification
  notifSeverity[2] = severities[checkTemp()]; 

  
  return tempGnd;
}

void readSHT()
{
    if(shtSensor.read_meas_data_single_shot(HIGH_REP_WITH_STRCH,&tempSHT,&humSHT) != NO_ERROR)
    {
	  tempSHT = FLT_MAX;
	  humSHT = FLT_MAX;
      wetbulbSHT = FLT_MAX;
      Serial.println("read temp failed!!");
    }
    else {
      wetbulbSHT = (tempSHT * atan(0.151977 * sqrt(humSHT + 8.313659))) + atan(tempSHT + humSHT) - atan(humSHT - 1.676331) + (0.00391838 * pow(sqrt(humSHT), 3.0) * atan(0.023101 * humSHT)) - 4.686035;

    }

  //notification
  notifSeverity[3] = severities[checkSHT()]; 

    
}
float readBattery(){
  Serial.println();
  // read the value from the sensor:
  batteryAnalog = analogRead(PIN_BATTERY);
  batteryValue = batteryAnalog * (3.3 / ANALOG_RESOLUTION); 
  battery = ((batteryValue-BATTERY_MIN)/(BATTERY_MAX-BATTERY_MIN))*100;
}


// json functions
String build_json_module()
{
  String out = "";
  StaticJsonDocument<JSON_SIZE> doc;
  doc["module_identifier"] = MODULE_NAME;
  JsonArray sensor_arr = doc.createNestedArray("sensoren");
  JsonObject sensor_id = sensor_arr.createNestedObject();
  sensor_id["id"] = SENSOR_ID_1;
  sensor_id = sensor_arr.createNestedObject();
  sensor_id["id"] = SENSOR_ID_2;
  sensor_id = sensor_arr.createNestedObject();
  sensor_id["id"] = SENSOR_ID_3;
  sensor_id = sensor_arr.createNestedObject();
  sensor_id["id"] = SENSOR_ID_4;
  sensor_id = sensor_arr.createNestedObject();
  sensor_id["id"] = SENSOR_ID_5;
  
  serializeJson(doc, out);
  return out;
}
String build_json_notification()
{
  String out = "";
  StaticJsonDocument<JSON_SIZE> doc;
  doc["module_id"] = MODULE_ID;
  doc["severity"] = "info";
  doc["message"] = "5 o clock and all OK";
  
  serializeJson(doc, out);
  return out;
}
String build_json_data()
{
  String out = ""; 
  StaticJsonDocument<JSON_SIZE> doc;

  doc["module_id"] = MODULE_ID;
  doc["battery_level"] = 69;
  doc["measure_date"] = rtc.getEpoch();  // UNIX timestamp
  
  JsonArray data_arr = doc.createNestedArray("data");
  JsonObject sensordata = data_arr.createNestedObject();
  sensordata["sensor"] = SENSOR_DENDROMETER;
  sensordata["value"] = distDendro;  

  sensordata = data_arr.createNestedObject();
  sensordata["sensor"] = SENSOR_TEMPERATURE_AIR;
  sensordata["value"] = tempSHT;
  
  sensordata = data_arr.createNestedObject();
  sensordata["sensor"] = SENSOR_TEMPERATURE_SOIL;
  sensordata["value"] = tempGnd;

  sensordata = data_arr.createNestedObject();
  sensordata["sensor"] = SENSOR_HUMIDITY_SOIL;
  sensordata["value"] = 37.37; //watermark_1_per_instant;

  sensordata = data_arr.createNestedObject();
  sensordata["sensor"] = SENSOR_HUMIDITY_AIR;
  sensordata["value"] = humSHT;

  
  serializeJson(doc, out);
  return out;
}

// GPRS functions
void gsm_enable() {
  boolean gsm_connected = false;
  int tries = 0;
  while(!gsm_connected && tries < GSM_TRIES)
  {
    tries++;
    if((gsm.begin(GSM_PIN) == GSM_READY))
    {
      Serial.println("GSM OK");
      if(gprs.attachGPRS(GPRS_APN, GPRS_LOGIN, GPRS_PASSWORD) == GPRS_READY)
      {
        Serial.println("GPRS OK");
        gsm_connected = true;
      }
      else
      {
        Serial.println("GPRS not connected, retrying ...");
      }
    }
    else
    {
      Serial.println("GSM not connected, retrying ...");
    }
  }  
}
void gsm_disable() {
  gsm.shutdown();
}
void json_push_module(String data) {
  boolean gsm_connected = false;  
  int tries = 0;
  while(!gsm_connected && tries < GSM_TRIES)
  {
    tries++;
    if(client_gsm.connect(GPRS_SERVER, GPRS_PORT))
    {
      Serial.println("HTTPS OK");
      gsm_connected = true;
    
      client_http.beginRequest();
      client_http.post(GPRS_PATH_MODULES);
      client_http.sendHeader("Content-Type", "application/json");
    
      client_http.sendHeader("Content-length", data.length());
      client_http.beginBody();
      client_http.print(data);
    
      client_http.endRequest();
    }
    else
    {
      Serial.println("HTTPS client not connected, retrying ...");
    }
  }
  if(gsm_connected) {
    int response = client_http.responseStatusCode();
    if(response == 200)
      Serial.println("status 200 OK");
     else {
      Serial.print("status: ");
      Serial.print(response);
      Serial.println(" NOK");
     }
    StaticJsonDocument<JSON_SIZE> doc;
    DeserializationError jsonerr = deserializeJson(doc, client_http.responseBody());
    if(jsonerr) {
      Serial.println(jsonerr.c_str());
      return;
    }
    MODULE_ID                 = doc["module_id"];
    SENSOR_DENDROMETER        = doc["module_sensors"][0];
    SENSOR_TEMPERATURE_SOIL   = doc["module_sensors"][1];
    SENSOR_HUMIDITY_SOIL      = doc["module_sensors"][2];
    SENSOR_HUMIDITY_AIR       = doc["module_sensors"][3];
    SENSOR_TEMPERATURE_AIR    = doc["module_sensors"][4];
  }
}
void json_push_notification(String data) {
  boolean gsm_connected = false;  
  int tries = 0;
  while(!gsm_connected && tries < GSM_TRIES)
  {
    tries++;
    if(client_gsm.connect(GPRS_SERVER, GPRS_PORT))
    {
      Serial.println("HTTPS OK");
      gsm_connected = true;
    
      client_http.beginRequest();
      client_http.post(GPRS_PATH_NOTIFICATION);
      client_http.sendHeader("Content-Type", "application/json");
    
      client_http.sendHeader("Content-length", data.length());
      client_http.beginBody();
      client_http.print(data);
    
      client_http.endRequest();

      client_gsm.stop();
    }
    else
    {
      Serial.println("HTTPS client not connected, retrying ...");
    }
  }
}
void json_push_data(String data) {
  boolean gsm_connected = false;  
  int tries = 0;
  while(!gsm_connected && tries < GSM_TRIES)
  {
    tries++;
    if(client_gsm.connect(GPRS_SERVER, GPRS_PORT))
    {
      Serial.println("HTTPS OK");
      gsm_connected = true;
    
      client_http.beginRequest();
      client_http.post(GPRS_PATH_MEASURE);
      client_http.sendHeader("Content-Type", "application/json");
    
      client_http.sendHeader("Content-length", data.length());
      client_http.beginBody();
      client_http.print(data);
    
      client_http.endRequest();

      client_gsm.stop();
    }
    else
    {
      Serial.println("HTTPS client not connected, retrying ...");
    }
  }
}
// median functions
void add_finals()
{
  finals[FINAL_DENDRO]      += distDendro;
  finals[FINAL_TEMP]        += tempSHT;
  finals[FINAL_HUMID_GND]   += watermark_1_per_instant;
  finals[FINAL_HUMID_AIR]   += humSHT;
}
void reset_finals()
{
  finals[FINAL_DENDRO]      = 0.f;
  finals[FINAL_TEMP]        = 0.f;
  finals[FINAL_HUMID_GND]   = 0.f;
  finals[FINAL_HUMID_AIR]   = 0.f;  
}
void median_finals()
{
  
}

// NTP function
int ntp_get_time()
{
  unsigned long out = gsm.getTime();
  return out;
}

// SD functions
void sd_init(String filename)
{
  
  if(!SD.begin(SD_CHIPSELECT))
      Serial.println("SD ERROR");
  else
  {
    if(!SD.exists(filename)) {
      File datafile = SD.open(filename, FILE_WRITE);
      if(datafile) {
        datafile.print("Data log: ");
        datafile.println(MODULE_NAME);
        datafile.close();
        Serial.println("FILE MADE");
      }
     else
        Serial.println("CANNOT MAKE FILE");
    }
    else
      Serial.println("FILE EXISTS");
  }
}
void sd_write(String filename, String data)
{
  File datafile = SD.open(filename, FILE_WRITE);
  if(datafile) {
    datafile.println(data);
    datafile.close();
    Serial.println("WRITE OK");
  }
  else
    Serial.println("CANNOT WRITE");
}

// debug print functions
void printWatermark(){
  Serial.println("\nVin\tVout\tAnalog\tRwm\t\tkp\t%water\ttemp");
  Serial.println("-------------------------------------------------------------");
  Serial.print(average_v_in);
  Serial.print("V\t");
  Serial.print(average_v_out);
  Serial.print("V\t");
  Serial.print(val);
  Serial.print("\t");
  Serial.print((int)rwm);
  Serial.print(" OHM\t");
  Serial.print(watermark_1_kp_instant);
  Serial.print("\t");
  Serial.print(watermark_1_per_instant);
  Serial.print("%\t");
  Serial.print(tempGnd);
  Serial.println("C");
}
void printDendro() {
  Serial.println("\ndistance");
  Serial.println("-------------------------------------------------------------");
  Serial.print (distDendro,2); 
  Serial.println("mm\t");
}
void printTemp() {
  Serial.println("\ntemp");
  Serial.println("-------------------------------------------------------------");
  Serial.print(tempGnd);
  Serial.println("C\t");
}
void printSHT() {
  Serial.println("\ntemp\thumid\twetbulb");
  Serial.println("-------------------------------------------------------------");
  Serial.print(tempSHT);
  Serial.print("C\t");

  Serial.print(humSHT);
  Serial.print("%\t");

  Serial.print(wetbulbSHT);
  Serial.println("C\t");
}
void printBattery(){
  Serial.println("\nVin\tAnalog\t%");
  Serial.println("-------------------------------------------------------------");
  Serial.print(batteryValue);
  Serial.print("V\t");
  Serial.print(batteryAnalog);
  Serial.print("\t");
  Serial.print(battery);
  Serial.println("%");
}

void printAll() {
	printWatermark();
	printDendro();
	printSHT();
	printTemp();
  printBattery();
  printNotif(); 
}

// led blink
void led_blink(char mask)
{
	for(int i=LED_MASK_SIZE;i>=0;i--)
	{
		if(mask & (1<<i))
		{
			digitalWrite(PIN_LED, HIGH);
			delay(300);
			digitalWrite(PIN_LED, LOW);
			delay(200);
		}
		else
			delay(500);
	}
}

void setup() {

  Serial.begin(9600);

// LED setup
  pinMode(PIN_LED, OUTPUT);
  digitalWrite(PIN_LED, LOW);

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
// onewire temperature sensor setup
   tempSensor.begin();
// sht 35 setup
    if(shtSensor.init())
	{
      Serial.println("sensor init failed!!!");
	}

 // rtc setup
 rtc.begin();
 // set rtc from ntp
 gsm_enable();
 rtc.setEpoch(ntp_get_time());
 String json = build_json_module();  
 json_push_module(json);
 gsm_disable();
 // SD setup
 sd_init(SD_FILE_NAME);
}
// Arduino loop
void loop() {
  String json_data;
  String json_notification;
  
  readTemp();
// WM after temperature
  readWatermark();
  readDendro();
  readSHT();
  readBattery();
  printAll();
  json_data = build_json_data();
  json_notification = build_json_notification();
  sd_write(SD_FILE_NAME, json_data);
  Serial.println(json_data);
  Serial.println(json_notification);
  /*
  gsm_enable();
  json_push_data(json_data);
  //json_push_notification(json_notification);
  gsm_disable();
  */
  delay(1000);
}
