#include <SD.h>

#define SD_CHIPSELECT 4

void sd_init(String filename)
{
	
  if(!SD.begin(SD_CHIPSELECT))
      Serial.println("SD ERROR");
  else
  {
	  if(!SD.exists(filename)) {
		File datafile = SD.open(filename, FILE_WRITE);
		if(datafile) {
		  datafile.println("STARTDATA");
		  datafile.close();
		}
	  }
  }
}
void sd_write(String filename, String data)
{
  File datafile = SD.open(filename, FILE_WRITE);
  if(datafile) {
    datafile.println(data);
    datafile.close();
  }
}

void setup() {
	sd_init("testlog.txt");
}

int i = 0;
void loop() {
	String data = "DATA HERE: " + i;
	sd_write("testlog.txt", data);
	i++;
	delay(1000);
}
