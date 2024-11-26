#include <WiFi.h>
#include <WebServer.h>
#include <HTTPClient.h>
#include <NTPClient.h>
#include <WiFiUdp.h>
#include <time.h>
#include <ArduinoJSON.h>

WebServer server(80);

WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "pool.ntp.org", 0, 60000); // Sync every 60s

// const char* WIFI_SSID = "Arena";
// const char* WIFI_PASSWORD = "arena12024";
// const char* SERVER_URL = "http://192.168.0.100/smart-pill-box";

const char* WIFI_SSID = "Clayton_2g";
const char* WIFI_PASSWORD = "Cla44885735";
const char* SERVER_URL = "http://192.168.0.90/smart-pill-box";
const int PERSON_IN_CARE_ID = 1;
const int NUMBER_OF_SLOTS = 6;

const int BUZZER = 21;
const int SWITCH_A = 23; //top left
const int SWITCH_B = 32; //top right
const int SWITCH_C = 19; //middle left
const int SWITCH_D = 26; //middle right
const int SWITCH_E =  4; //bottom left
const int SWITCH_F = 14; //bottom right
const int SWITCHES_ARRAY[NUMBER_OF_SLOTS] = {
    SWITCH_A,
    SWITCH_B,
    SWITCH_C,
    SWITCH_D,
    SWITCH_E,
    SWITCH_F
};
const int LED_A = 22; //top left - BLUE
const int LED_B = 33; //top right - RED
const int LED_C = 18; //middle left - WHITE
const int LED_D = 27; //middle right - GREEN
const int LED_E =  2; //bottom left - PINK
const int LED_F = 13; //bottom right - YELLOW
const int LEDS_ARRAY[NUMBER_OF_SLOTS] = {
    LED_A,
    LED_B,
    LED_C,
    LED_D,
    LED_E,
    LED_F
};

char SLOTS_NAMES[NUMBER_OF_SLOTS] = {
    'A', 'B', 'C', 'D', 'E', 'F'
}

int slotNameToNum(char slot_name){
    switch (slot_name) {
        case SLOTS_NAMES[1]: return 1;
        case SLOTS_NAMES[2]: return 2;
        case SLOTS_NAMES[3]: return 3;
        case SLOTS_NAMES[4]: return 4;
        case SLOTS_NAMES[5]: return 5;
        case SLOTS_NAMES[6]: return 6;

        default: return -1;
    }
}

int slots_next_doses_timestamps[NUMBER_OF_SLOTS] = {0, 0, 0, 0, 0, 0};
bool alarms_statuses[NUMBER_OF_SLOTS] = {false, false, false, false, false , false};
int currentTime;

void setup() {
    Serial.begin(115200);

    pinMode(BUZZER, OUTPUT);
    for(int i=0; i<NUMBER_OF_SLOTS; i++){
        pinMode(SWITCHES_ARRAY[i], INPUT);
        pinMode(LEDS_ARRAY[i], OUTPUT);

        digitalWrite(LEDS_ARRAY[i], LOW);
    }

    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
    Serial.print("Conectando-se à rede WiFi");
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    Serial.println("\nConectado!");
    Serial.print("Endereço IP: ");
    Serial.println(WiFi.localIP());

    server.begin();
    timeClient.begin();
    getNextDoseTime(PERSON_IN_CARE_ID);
}

void loop() {
    server.handleClient();
    timeClient.update();

    for(int i=0; i<NUMBER_OF_SLOTS; i++){
        if(alarms_statuses[i]){ //Alarm is on
            if(digitalRead(SWITCHES_ARRAY[i])){
                Serial.println("Alarme " SLOTS_NAMES[i] " desligado.");
                digitalWrite(LEDS_ARRAY[i], LOW);
                digitalWrite(BUZZER, LOW);
                alarms_statuses[i] = false; //Turn alarm off
                takeDose();
            }
        } else {
            currentTime = timeClient.getEpochTime();
            if(slots_next_doses_timestamps[i] != 0 currentTime >= slots_next_doses_timestamps[i]){
                Serial.println("Alarme " SLOTS_NAMES[i] " ligado.");
                alarms_statuses[i] = false; //Turn alarm on
                digitalWrite(LEDS_ARRAY[i], HIGH);
                digitalWrite(BUZZER, HIGH);
            }
        }
    } 
  delay(1000);
}

void getNextDoseTime(int person_in_care_id) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(String(SERVER_URL) + "/controllers/doses.php");
    http.addHeader("Content-Type", "application/json");
    String jsonBody = "{\"doses_action\":\"get_person_next_dose\", \"params\": {\"person_in_care_id\": " + String(person_in_care_id) + "} }";
    int httpCode = http.POST(jsonBody);

    if (httpCode == 200) {
      String payload = http.getString();
      DynamicJsonDocument doc(1024);
      DeserializationError error = deserializeJson(doc, payload);

      if (error) {
        Serial.println("Failed to parse JSON");
        return;
      }

      next_dose_id = doc["DOS_id"];
      next_dose_due_timestamp = doc["due_date_timestamp"];
      Serial.println("Next dose time: " + String(doc["DOS_due_datetime"]));

    } else {
      Serial.println("Error on HTTP request: " + String(httpCode));
    }

    http.end();
  } else {
    Serial.println("Not connected to WiFi");
  }
}