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
IPAddress ntp_server(192, 13, 23, 5);
byte ntp_packet_buffer[NTP_PACKET_SIZE];


GSM gsm;
GPRS gprs;
GSMSSLClient client_gsm;
HttpClient client_http = HttpClient(client_gsm, GPRS_SERVER, GPRS_PORT);
void setup(){}

void send_ntp_packet(IPAddress &adr)
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

	Udp.beginPacket(adr, UDP_PORT_REMOTE);
	Udp.write(ntp_packet_buffer, NTP_PACKET_SIZE);
	Udp.endPacket();
}
int parse_ntp_packet() {
  if(Udp.parsePacket()) {
	Udp.read(ntp_packet_buffer, NTP_PACKET_SIZE);
	unsigned long word_high = word(ntp_packet_buffer[40], ntp_packet_buffer[41]);
	unsigned long word_low = word(ntp_packet_buffer[42], ntp_packet_buffer[43]);

	unsigned long time_NTP = word_high << 16 | word_low;

	unsigned long time_unix = time_NTP - UNIX_TIME_OFFSET
	time_unix = time_unit + UTC_OFFSET;
	rtc.setEpoch(time_unix);
  }
  else
  	return -1;
}

String get_ntp_time() {
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

  Udp.begin(UDP_PORT_LOCAL);
  send_ntp_packet(&ntp_server);
  delay(3000);
  parse_ntp_packet();

  gsm.shutdown();
}

void loop() {
	client_gsm.stop();
}
