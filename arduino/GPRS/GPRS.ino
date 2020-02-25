#include <MKRGSM.h>
#include <ArduinoHttpClient.h>
#include <ArduinoJson.h>

#define JSON_SIZE 512

#define GSM_PIN         "6615"
#define GPRS_APN        "internet.proximus.be"
#define GPRS_LOGIN      ""
#define GPRS_PASSWORD   ""

#define GPRS_SERVER     "floriandh.sinners.be"
#define GPRS_PATH       "/pcfruit/api/measurements/create.php"
#define GPRS_PORT       443

GSM gsm;
GPRS gprs;
GSMSSLClient client_gsm;
HttpClient client_http = HttpClient(client_gsm, GPRS_SERVER, GPRS_PORT);
void setup(){}

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
  }
  else
    Serial.println("HTTPS client not connected, retrying ...");
  }
}

void loop() {

  String data = "{\"module_id\":1,\"battery_level\":69,\"measure_date\":1582197975,\"value\":[{\"sensor\":1,\"value\":0.01464},{\"sensor\":2,\"value\":20.63401},{\"sensor\":3,\"value\":3244299},{\"sensor\":4,\"value\":53.39285}]}";

  json_push(data);



  
   while (client_gsm.available() )
   {
    char c = client_gsm.read();
    Serial.print(c);
  }
  if(!client_gsm.available() && !client_gsm.connected())
  {
    client_gsm.stop();
  }
  delay(10000);
}
