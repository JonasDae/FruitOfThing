#include <ArduinoJson.h>


/*
JSON layout:

{
  "module": "5",
  "date": "2020-04-22 13:25:00",
  "data": [
      {"sensor":"1", "value":"55",},
      {"sensor":"2", "value":"20",},
      {"sensor":"3", "value":"50",},
  ],
}
*/



#define MODULE_NAME "logger 123"
#define JSON_SIZE 200
String build_json()
{
  String out = "";
  StaticJsonDocument<JSON_SIZE> doc;

  doc["module_id"] = 1;
  doc["battery_level"] = 69;
  doc["module_sensor_id"] = 3;
  doc["value"] = 66;
  doc["measure_date"] = "2069-01-30 10:20:20";

  JsonArray data_arr = doc.createNestedArray("data");
  JsonObject sensordata = data_arr.createNestedObject();
  sensordata["sensor"] = 1;
  sensordata["data"] = 55;  
  sensordata = data_arr.createNestedObject();
  sensordata["sensor"] = 2;
  sensordata["data"] = 25;

  serializeJson(doc, out);
  return out;
}

void setup() {
  // put your setup code here, to run once:

}

void loop() {
  // put your main code here, to run repeatedly:

  String json = build_json();

  Serial.println(json);

}
