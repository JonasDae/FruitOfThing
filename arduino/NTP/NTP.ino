#include <MKRGSM.h>
#include <ArduinoHttpClient.h>

#define GSM_PIN         "6615"
#define GPRS_APN        "internet.proximus.be"
#define GPRS_LOGIN      ""
#define GPRS_PASSWORD   ""

#define GPRS_SERVER     "floriandh.sinners.be"
#define GPRS_PATH       "/pcfruit/api/measurements/create.php"
#define GPRS_PORT       443

#define UDP_PORT_LOCAL		2390
#define UDP_PORT_REMOTE		123
#define NTP_PACKET_SIZE		48
#define UNIX_TIME_OFFSET 	2208988800UL
#define UTC_OFFSET			(3600UL*2UL)   // UTC +2? voor belgie

GSMUDP Udp;
IPAddress ntp_server(195, 13, 23, 5);
byte ntp_packet_buffer[NTP_PACKET_SIZE];


GSM gsm;
GPRS gprs;
void setup(){}

void send_ntp_packet(IPAddress& adr)
{
	memset(ntp_packet_buffer, 0, NTP_PACKET_SIZE);

	ntp_packet_buffer[0] = 0b11100011;	// LI, version, mode
	ntp_packet_buffer[1] = 0;			// clocktype
	ntp_packet_buffer[2] = 6;			// polling interval
	ntp_packet_buffer[3] = 0xEC;		// peer clock precision
										// 8 bytes zeroed
	ntp_packet_buffer[12] = 49;			// reference ID (4 bytes)
	ntp_packet_buffer[13] = 0x4E;
	ntp_packet_buffer[14] = 49;
	ntp_packet_buffer[15] = 52;

  Serial.println("WRITE");
	Udp.beginPacket(adr, UDP_PORT_REMOTE);
	Udp.write(ntp_packet_buffer, NTP_PACKET_SIZE);
	Udp.endPacket();
  Serial.println("WRITE DONE");
}
int parse_ntp_packet() {
  bool read_ok = false;
  while(!read_ok) {
    delay(1000);
  Serial.println("READ INIT");
  if(Udp.parsePacket()) {
    
    Serial.println("PARSE OK");
	  Udp.read(ntp_packet_buffer, NTP_PACKET_SIZE);
	  unsigned long word_high = word(ntp_packet_buffer[40], ntp_packet_buffer[41]);
	  unsigned long word_low = word(ntp_packet_buffer[42], ntp_packet_buffer[43]);

	  unsigned long time_NTP = word_high << 16 | word_low;

	  unsigned long time_unix = time_NTP - UNIX_TIME_OFFSET;
	  time_unix += UTC_OFFSET;
//	  rtc.setEpoch(time_unix);
    read_ok = true;
    Serial.println(time_unix);
  }
  else
    Serial.println("PARSE NOK");
  }
}

String get_ntp_time() {
  Serial.println("NTP START");
  boolean gsm_connected = false;
  Serial.println(gsm_connected);
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
  Serial.println("UDP_BEGIN");
  Udp.begin(UDP_PORT_LOCAL);
  send_ntp_packet(ntp_server);
  delay(3000);
  parse_ntp_packet();
}

void loop() {
  
 boolean gsm_connected = false;
  Serial.println(gsm_connected);
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
  Serial.println(gsm.getTime());
  gsm.shutdown();
}
