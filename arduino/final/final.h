
//read sensor functions
float readWatermark();
float readDendro();
float readTemp();
void readSHT();

// json functions
String build_json();

// GPRS functions
void json_push(String data);

// debug print functions
void printWatermark();
void printDendro();
void printTemp();
void printSHT();
void printAll();

void led_blink(char mask);
