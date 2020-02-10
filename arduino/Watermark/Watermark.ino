
  // watermark initialisation
  #define pinHumGnd 7
  #define pinHumPwr 6
  #define pinHumAnalog A2
  #define pinVinAnalog A3
  //settings:
  float resWatermark = 7760.0; //Ohm R;
  float arduino_resolution = 1023.0;
  int total_iterations = 10;
  float watermark_1_cb = 0.0;
  float watermark_1_cb_med = 0.0;
  float watermark_1_cb_instant = 0.0;
  float watermark_1_per = 0;
  float watermark_1_per_med = 0.0;
  float watermark_1_per_instant = 0.0;
  float soil_temperature = 6.0;
  float average_v_in = 0.0;
  float average_v_out = 0.0;
  float val = 0.0;
  float val_vin = 0.0;
  float v_in = 0.0;
  float v_out = 0.0;
  float rwm;

float readWatermark(){

  digitalWrite(pinHumPwr, HIGH);
  digitalWrite(pinHumGnd, LOW);
  delay(1000);
  digitalWrite(pinHumPwr, LOW);
  digitalWrite(pinHumGnd, LOW);

  average_v_in = 0;
  average_v_out = 0;

  for (int i = 0; i < total_iterations; i++)
  {
    digitalWrite(pinHumPwr, HIGH);
    digitalWrite(pinHumGnd, LOW);
    delayMicroseconds(49);
    //delay(0.09);
    val = analogRead(pinHumAnalog); //Read Sensor Pin A2
    val_vin = analogRead(pinVinAnalog); //Read Sensor Pin A3
    digitalWrite(pinHumPwr, LOW);
    digitalWrite(pinHumGnd, LOW);

    v_in =  val_vin * (3.3 / arduino_resolution);
    v_out = val * (v_in / arduino_resolution);
    average_v_in += v_in;
    average_v_out += v_out;
  }

  average_v_out = average_v_out / total_iterations;
  average_v_in = average_v_in / total_iterations;

  rwm = ((average_v_in * resWatermark)/average_v_out)-resWatermark;
  //float rwm = (average_v_out * resWatermark) / (average_v_in - average_v_out);

  if (rwm <= 500) {
    watermark_1_cb_instant = 0;
  } else if (rwm > 500 && rwm <= 1000) {
    watermark_1_cb_instant = -20 * ((rwm / 1000.0) * (1.00 + 0.018 * (soil_temperature - 24)) - 0.55);
  } else if (rwm > 1000 && rwm <= 8000) {
    watermark_1_cb_instant = (-3.213 * (rwm / 1000.0) - 4.093) / (1.0 - 0.009733 * (rwm / 1000.0) - 0.01205 * soil_temperature);
  } else if (rwm > 8000) {
    watermark_1_cb_instant = -2.246 - 5.239 * (rwm / 1000.00) * (1.0 + 0.018 * (soil_temperature - 24.00)) - 0.06756 * (rwm / 1000.00) * (rwm / 1000.00) * ((1.00 + 0.018 * (soil_temperature - 24.00)) * (1.00 + 0.018 * (soil_temperature - 24.00)));
  }

  //map(value, fromLow, fromHigh, toLow, toHigh)
  watermark_1_per_instant = map(watermark_1_cb_instant, -200, 0, 100, 0);  //Convert to Percentage
  //http://www.omafra.gov.on.ca/english/engineer/facts/11-037f4.gif

  digitalWrite(pinHumPwr, LOW);
  digitalWrite(pinHumGnd, LOW);
  
}

void printAll(){

  Serial.println("\nVin\tVout\tAnalog\tRwm\tcb\t%water\ttemp");
  Serial.println("-------------------------------------------------------------");
  Serial.print(average_v_in);
  Serial.print("\t");
  Serial.print(average_v_out);
  Serial.print("\t");
  Serial.print(val);
  Serial.print("\t");
  Serial.print((int)rwm);
  Serial.print("\t");
  Serial.print(watermark_1_cb_instant);
  Serial.print("\t");
  Serial.print(watermark_1_per_instant);
  Serial.print("%\t");
  Serial.print(soil_temperature);
  Serial.println("C");
  
}

void setup() {

  Serial.begin(9600);

  // watermark setup
  pinMode(pinHumPwr, OUTPUT);
  pinMode(pinHumGnd, OUTPUT);
  pinMode(pinHumAnalog, INPUT);
  pinMode(pinVinAnalog, INPUT);
  digitalWrite(pinHumPwr, LOW);
  digitalWrite(pinHumGnd, LOW);

}

void loop() {

  readWatermark();

  printAll();
  
}
