#include <MKRGSM.h>
#include <ArduinoHttpClient.h>
#include <ArduinoJson.h>

#define JSON_SIZE 300

#define GSM_PIN         "6615"
#define GPRS_APN        "internet.proximus.be"
#define GPRS_LOGIN      ""
#define GPRS_PASSWORD   ""

#define GPRS_SERVER     "pcfruit-dashboard.epizy.com"
#define GPRS_PATH       "/api/measurements/create.php"
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
  
  String data = "{\"module_id\":1,\"battery_level\":69,\"measure_date\":\"2020-01-30 10:20:20\",\"data\":[{\"sensor\":1,\"data\":0.00366},{\"sensor\":2,\"data\":22.12139},{\"sensor\":3,\"data\":34},{\"sensor\":4,\"data\":38.32303}]}";

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
  delay(10000);
}
