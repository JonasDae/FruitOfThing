#include <SD.h>

#define SD_CHIPSELECT 4

void setup() {
  Serial.begin(9600);
  Serial.println("TEST1");
  delay(5000);
  Serial.println("TEST2");
  if(!SD.begin(SD_CHIPSELECT))
  {
    while(1) {
      Serial.println("SD ERROR");
      delay(1000);
    }
  }
  else
    Serial.println("SD GUD");
  if(!SD.exists("testlog.txt")) {
    Serial.println("SD MADE");
    File datafile = SD.open("testlog.txt", FILE_WRITE);
    if(datafile) {
      datafile.println("TESTERINOS");
      datafile.close();
    }
    else
      Serial.println("NO FILE MADE");
  }

}

void loop() {
  // put your main code here, to run repeatedly:

}
