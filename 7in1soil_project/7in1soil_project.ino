#include <Arduino.h>
#include <WiFi.h>
#include <Firebase_ESP_Client.h>
#include "time.h"
#include "addons/TokenHelper.h"
#include "addons/RTDBHelper.h"
#include <Wire.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <HardwareSerial.h>
#include "DHT.h"

#define WIFI_SSID "Galaxy A20D45E"
#define WIFI_PASSWORD "twqr5172"
#define API_KEY "AIzaSyA2k14DsWZdBm72fOu-Df2tYnc7cDS08vM"
#define USER_EMAIL "user@gmail.com"
#define USER_PASSWORD "User@123"
#define DATABASE_URL "https://agrisense-e6a88-default-rtdb.firebaseio.com"

String uid, timestamp, DB_history, working_mode, db_wa_pump, db_p_pump, db_n_pump, db_mist, db_light, db_k_pump, db_air_out, db_air_in, db_plant, k_max, k_min, n_max, n_min, p_max, p_min;
struct tm timeinfo;
const char* ntpServer = "pool.ntp.org";
unsigned long lastTime = 0;
unsigned long timerDelay = 10000;
unsigned long lastTime2 = 0;
unsigned long timerDelay2 =900000;

unsigned int soilHumidity;
unsigned int soilTemperature;
unsigned int soilConductivity;
unsigned int soilPH;
unsigned int n;
unsigned int p;
unsigned int k;
int sta1;
// Define Firebase objects
FirebaseData fbdo;
FirebaseAuth auth;
FirebaseConfig config;
FirebaseJson json;

// OLED display configuration
#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
#define OLED_RESET -1 // Use -1 if no reset pin
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);

// Hardware Serial
HardwareSerial mySerial(1);

// Pin Definitions
#define RXD2 17
#define TXD2 16
#define DHTPIN 13
#define DHTTYPE DHT22

#define fanin 12
#define fanout 14
#define pump_N 27
#define pump_P 26
#define pump_K 25
#define mist 33
#define water_pump 32
#define light 23

// Sensor Data Variables
int n_R, p_R, k_R;
float ph_R, h, t;

// DHT Sensor
DHT dht(DHTPIN, DHTTYPE);


void initWiFi() {
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Connecting to WiFi ..");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print('.');
    delay(1000);
  }
  Serial.println(WiFi.localIP());
  Serial.println();
}
unsigned long getTime() {
  time_t now;
  if (!getLocalTime(&timeinfo)) {
    //Serial.println("Failed to obtain time");
    return (0);
  }
  time(&now);
  return now;
}
void setup() {
  Serial.begin(9600);
  mySerial.begin(4800, SERIAL_8N1, RXD2, TXD2);
  dht.begin();

  // Initialize pins
  pinMode(fanin, OUTPUT);
  pinMode(fanout, OUTPUT);
  pinMode(pump_N, OUTPUT);
  pinMode(pump_P, OUTPUT);
  pinMode(pump_K, OUTPUT);
  pinMode(mist, OUTPUT);
  pinMode(water_pump, OUTPUT);
  pinMode(light, OUTPUT);

  // Set initial states
  digitalWrite(fanin, LOW);
  digitalWrite(fanout, LOW);
  digitalWrite(pump_N, LOW);
  digitalWrite(pump_P, LOW);
  digitalWrite(pump_K, LOW);
  digitalWrite(mist, LOW);
  digitalWrite(water_pump, LOW);
  digitalWrite(light, HIGH);

  // Initialize OLED display
  if (!display.begin(SSD1306_SWITCHCAPVCC, 0x3C)) { // Change address if needed
    Serial.println(F("SSD1306 allocation failed"));
    for (;;); // Halt if OLED initialization fails
  }
  display.clearDisplay();
  display.setTextSize(1);
  display.setTextColor(SSD1306_WHITE);
  display.display();

  initWiFi();
  configTime(0, 0, ntpServer);

  // Assign the api key (required)
  config.api_key = API_KEY;

  // Assign the user sign in credentials
  auth.user.email = USER_EMAIL;
  auth.user.password = USER_PASSWORD;

  // Assign the RTDB URL (required)
  config.database_url = DATABASE_URL;

  Firebase.reconnectWiFi(true);
  fbdo.setResponseSize(4096);

  // Assign the callback function for the long running token generation task */
  config.token_status_callback = tokenStatusCallback; //see addons/TokenHelper.h

  // Assign the maximum retry of token generation
  config.max_token_generation_retry = 5;

  // Initialize the library with the Firebase authen and config
  Firebase.begin(&config, &auth);

  // Getting the user UID might take a few seconds
  Serial.println("Getting User UID");
  while ((auth.token.uid) == "") {
    Serial.print('.');
    delay(1000);
  }
  // Print user UID
  uid = auth.token.uid.c_str();
  Serial.print("User UID: ");
  Serial.println(uid);
}

void loop() {

  timestamp = getTime();
  timestamp = timestamp + "000";

  if ((millis() - lastTime) > timerDelay) {
    liveFeed();
    lastTime = millis();
  }
  if ((millis() - lastTime2) > timerDelay2) {
      historyData();
      lastTime2 = millis();
  }


  npkRead();
  readDHT();
  updateDisplay();
  dbDataGetting();
}

void npkRead() {
  byte queryData[] = {0x01, 0x03, 0x00, 0x00, 0x00, 0x07, 0x04, 0x08};
  byte receivedData[19];
  mySerial.write(queryData, sizeof(queryData));
  delay(1000);

  if (mySerial.available() >= sizeof(receivedData)) {
    mySerial.readBytes(receivedData, sizeof(receivedData));

    // Debug: Print raw data
    Serial.println("Raw Data:");
    for (int i = 0; i < sizeof(receivedData); i++) {
      Serial.print(receivedData[i], HEX);
      Serial.print(" ");
    }
    Serial.println();

    soilHumidity = (receivedData[3] << 8) | receivedData[4];
    soilTemperature = (receivedData[5] << 8) | receivedData[6];
    soilConductivity = (receivedData[7] << 8) | receivedData[8];
    soilPH = (receivedData[9] << 8) | receivedData[10];
    n = (receivedData[11] << 8) | receivedData[12];
    p = (receivedData[13] << 8) | receivedData[14];
    k = (receivedData[15] << 8) | receivedData[16];

    Serial.print("soilHumidity: ");
    Serial.println((float)soilHumidity / 10.0);
    soilHumidity = soilHumidity / 10.0;
    Serial.print("soilTemperature: ");
    Serial.println((float)soilTemperature / 10.0);
    soilTemperature = soilTemperature / 10.0;
    Serial.print("soilConductivity: ");
    Serial.println(soilConductivity);
    Serial.print("soilPH: ");
    ph_R = (float)soilPH / 10.0;
    Serial.println(ph_R);
    Serial.print("n: ");
    n_R = n;
    Serial.println(n);
    Serial.print("p: ");
    p_R = p;
    Serial.println(p);
    Serial.print("k: ");
    k_R = k;
    Serial.println(k);
    delay(1000);
  } else {
    Serial.println("Error: Insufficient data received.");
  }
}

void readDHT() {
  delay(2000);

  h = dht.readHumidity();
  t = dht.readTemperature();

  if (isnan(h) || isnan(t)) {
    Serial.println(F("Failed to read from DHT sensor!"));
    return;
  }

  Serial.print(F("Humidity: "));
  Serial.print(h);
  Serial.print(F("%  Temperature: "));
  Serial.print(t);
  Serial.println(F("°C "));
}

void updateDisplay() {
  display.clearDisplay();
  display.setCursor(25, 0);

  // Display NPK data
  display.println("Soil Readings");
  display.print("Nitrogen: ");
  display.print(n_R);
  display.println(" mg/kg");
  display.print("Phosphorus: ");
  display.print(p_R);
  display.println(" mg/kg");
  display.print("Potassium: ");
  display.print(k_R);
  display.println(" mg/kg");

  // Display PH data
  display.print("pH: ");
  display.println(ph_R);

  // Display DHT data
  display.print("Humidity: ");
  display.print(h);
  display.println(" %");
  display.print("Temperature: ");
  display.print(t);
  display.println(" C");

  display.display();
}
void liveFeed() {
  Firebase.RTDB.setString(&fbdo, "liveData/conductivity", soilConductivity);
  delay(200);
  Firebase.RTDB.setString(&fbdo, "liveData/humidity", h);
  delay(200);
  Firebase.RTDB.setString(&fbdo, "liveData/k", k_R);
  delay(200);
  Firebase.RTDB.setString(&fbdo, "liveData/moisture", soilHumidity);
  delay(200);
  Firebase.RTDB.setString(&fbdo, "liveData/n", n_R);
  delay(200);
  Firebase.RTDB.setString(&fbdo, "liveData/p", p_R);
  delay(200);
  Firebase.RTDB.setString(&fbdo, "liveData/pH", ph_R);
  delay(200);
  Firebase.RTDB.setString(&fbdo, "liveData/soil_temperature", soilTemperature);
  delay(200);
  Firebase.RTDB.setString(&fbdo, "liveData/temperature", t);
  delay(200);
}
void historyData() {
  DB_history = "history/" + String(timestamp);
  json.set("/conductivity", String(soilConductivity));
  json.set("/humidity", String(h));
  json.set("/k", String(k_R));
  json.set("/moisture", String(soilHumidity));
  json.set("/n", String(n_R));
  json.set("/p", String(p_R));
  json.set("/pH", String(ph_R));
  json.set("/soil_temperature", String(soilTemperature));
  json.set("/temperature", String(t));
  json.set("/timestamp", String(timestamp));
  Serial.printf("Set json... %s\n", Firebase.RTDB.setJSON(&fbdo, DB_history.c_str(), &json) ? "ok" : fbdo.errorReason().c_str());

}
void notify() {
  Firebase.RTDB.setString(&fbdo, "notification/message", "");
  delay(200);
  Firebase.RTDB.setBool(&fbdo, "notification/isNew", true);
  delay(200);
}
void fetchFirebaseString(const String &path, String &variable) {
  if (Firebase.RTDB.getString(&fbdo, path)) {
    if (fbdo.dataType() == "string") {
      variable = fbdo.stringData();
      Serial.print(path + " : ");
      Serial.println(variable);
    }
  } else {
    Serial.println(fbdo.errorReason());
  }
}

void controlDevice(const String &path, int pin) {
  String state;
  fetchFirebaseString(path, state);
  if (pin == light) {
    digitalWrite(pin, state.equals("true") ? LOW : HIGH);
  } else {
    digitalWrite(pin, state.equals("true") ? HIGH : LOW);
  }

}

void dbDataGetting() {
  fetchFirebaseString("liveData/automatic", working_mode);

  if (working_mode.equals("true")) {
    sta1 = 1;
    // Automatic mode
    fetchFirebaseString("liveData/plant", db_plant);
    if (!db_plant.isEmpty()) {
      fetchFirebaseString("plant/" + db_plant + "/k_max", k_max);
      fetchFirebaseString("plant/" + db_plant + "/k_min", k_min);
      fetchFirebaseString("plant/" + db_plant + "/n_max", n_max);
      fetchFirebaseString("plant/" + db_plant + "/n_min", n_min);
      fetchFirebaseString("plant/" + db_plant + "/p_max", p_max);
      fetchFirebaseString("plant/" + db_plant + "/p_min", p_min);

      if (k_R > k_max.toInt() && k_R < k_min.toInt()) {
        digitalWrite(pump_K, HIGH);
      } else {
        digitalWrite(pump_K, LOW);
      }

      if (n_R > n_max.toInt() && n_R < n_min.toInt()) {
        digitalWrite(pump_N, HIGH);
      } else {
        digitalWrite(pump_N, LOW);
      }

      if (p_R > p_max.toInt() && p_R < p_max.toInt()) {
        digitalWrite(pump_P, HIGH);
      } else {
        digitalWrite(pump_P, LOW);
      }
    }

    controlDevice("liveData/light", light);

    if (t > 25 && t < 30) {
      digitalWrite(fanin, LOW);
      digitalWrite(fanout, LOW);
    } else if (t < 25) {
      digitalWrite(fanin, HIGH);
    } else if (t > 30) {
      digitalWrite(fanout, HIGH);
    }

    if (h < 50) {
      digitalWrite(mist, HIGH);
    } else if (h > 80) {
      digitalWrite(mist, LOW);
    }

  } else {

    if (sta1) {
      firstTime();
      sta1 = 0;
    }
    // Manual mode
    controlDevice("liveData/air_in", fanin);
    controlDevice("liveData/air_out", fanout);
    controlDevice("liveData/k_pump", pump_K);
    controlDevice("liveData/light", light);
    controlDevice("liveData/mist", mist);
    controlDevice("liveData/n_pump", pump_N);
    controlDevice("liveData/p_pump", pump_P);
    controlDevice("liveData/water_pump", water_pump);
    controlDevice("liveData/light", light);
  }
}
void firstTime() {
  digitalWrite(fanin, LOW);
  digitalWrite(fanout, LOW);
  digitalWrite(pump_N, LOW);
  digitalWrite(pump_P, LOW);
  digitalWrite(pump_K, LOW);
  digitalWrite(mist, LOW);
  digitalWrite(water_pump, LOW);
  digitalWrite(light, HIGH);
}
