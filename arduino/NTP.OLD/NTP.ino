#include <MKRGSM.h>
#include <ArduinoHttpClient.h>

#define GSM_PIN         "6615"
#define GPRS_APN        "internet.proximus.be"
#define GPRS_LOGIN      ""
#define GPRS_PASSWORD   ""

#define GPRS_SERVER     "floriandh.sinners.be"
#define GPRS_PATH       "/pcfruit/api/measurements/create.php"
#define GPRS_PORT       443

#define UDP_PORT_LOCAL	2390
#define UDP_PORT_REMOTE	123
#define NTP_PACKET_SIZE	48
#define UTC_OFFSET		3600UL   // UTC +1 voor belgie

GSMUDP Udp;
IPAddress ntp_server(192, 13, 23, 5);

GSM gsm;
GPRS gprs;
GSMSSLClient client_gsm;
HttpClient client_http = HttpClient(client_gsm, GPRS_SERVER, GPRS_PORT);
void setup(){}

void send_ntp_packet(IPAddress &adr)
{
	byte ntp_packet_buffer[NTP_PACKET_SIZE];

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
	Udp.read(ntp_packet_buffer, NTP_PACKET_SIZE);
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
  if(Udp.parsePacket()) {
  }
}

void loop() {
	client_gsm.stop();
}
