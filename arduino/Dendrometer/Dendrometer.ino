// dendrometer initialisation
#define dendroAnalogIn A1 // input pin for the dendrometer
int adcValDendro = 0;  // variable to store the value coming from the sensor
float distDendro;
float divider = 100000;

float readDendro(){

  adcValDendro = analogRead(dendroAnalogIn); // read dendrometer input
  delay(500);
  distDendro = (adcValDendro * 366 /divider); // calculate dendrometer distance in mm
  //Serial.println ();
  //Serial.print ("ADC: ");
  //Serial.println (adcValDendro);
  //Serial.print ("Distance: "); 
  //Serial.print (distDendro,2); 
  //Serial.println(" mm");

  return distDendro;
  
}

void printAll(){

  Serial.print ("Distance: "); 
  Serial.print (distDendro,2); 
  Serial.println(" mm");
  
}

void setup() {
   Serial.begin(9600);
   /* Setting analog read resolution to 12 bit */
   analogReadResolution(12);
}

void loop() {
  
  distDendro = readDendro();

  printAll();

}
