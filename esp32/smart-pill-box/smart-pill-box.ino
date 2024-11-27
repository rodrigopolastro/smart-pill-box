#include <WiFi.h>
#include <WebServer.h>
#include <HTTPClient.h>
#include <NTPClient.h>
#include <WiFiUdp.h>
#include <time.h>
#include <ArduinoJson.h>

const char* WIFI_SSID = "Clayton_2g";
const char* WIFI_PASSWORD = "Cla44885735";
const char* SERVER_URL = "http://192.168.0.90/smart-pill-box";
const int PERSON_IN_CARE_ID = 1;
const int QUERIES_INTERVAL_SECS = 10;
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

const char* SLOTS_NAMES[NUMBER_OF_SLOTS] = {
    "A", "B", "C", "D", "E", "F"
};
int slotNameToNum(const char* slot_name){
    for(int i=0; i<NUMBER_OF_SLOTS; i++){
        if (strcmp(SLOTS_NAMES[i], slot_name) == 0) {
            return i;
        }
    }

    return -1;
}

//TO DO: Create Structs to hold the data together
int slotsLastQueryTimes[NUMBER_OF_SLOTS] = {0, 0, 0, 0, 0, 0};
int slotsTreatmentsIds[NUMBER_OF_SLOTS] = {0, 0, 0, 0, 0, 0};
int slotsNextDosesIds[NUMBER_OF_SLOTS] = {0, 0, 0, 0, 0, 0};
int slotsNextDosesTimestamps[NUMBER_OF_SLOTS] = {0, 0, 0, 0, 0, 0};
bool alarmsStatuses[NUMBER_OF_SLOTS] = {false, false, false, false, false , false};

WebServer server(80);

WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "pool.ntp.org", 0, 60000); // Sync every 60s

void setup() {
    Serial.begin(115200);

    for(int i=0; i<NUMBER_OF_SLOTS; i++){
        pinMode(SWITCHES_ARRAY[i], INPUT_PULLDOWN);
        pinMode(LEDS_ARRAY[i], OUTPUT);
        digitalWrite(LEDS_ARRAY[i], LOW);
    }
    pinMode(BUZZER, OUTPUT);
    digitalWrite(BUZZER, LOW);

    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
    Serial.print("Conectando-se à rede WiFi");
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    Serial.println("\nConectado!");
    Serial.print("Endereço IP: ");
    Serial.println(WiFi.localIP());

    server.on("/addTreatmentToSlot", addTreatmentToSlot);

    server.begin();
    timeClient.begin();
}

void loop() {
    server.handleClient();
    timeClient.update();

    for(int i=0; i<NUMBER_OF_SLOTS; i++){
        if(slotsTreatmentsIds[i] == 0){
            continue;
        }

        if(alarmsStatuses[i]){ //Alarm is on
            digitalWrite(BUZZER, HIGH);
            if(digitalRead(SWITCHES_ARRAY[i])){
                takeDose(slotsNextDosesIds[i]);
                Serial.println("Dose do compartimento " + String(SLOTS_NAMES[i]) +  " tomada.");
                digitalWrite(LEDS_ARRAY[i], LOW);
                digitalWrite(BUZZER, LOW);
                alarmsStatuses[i] = false; //Turn alarm off
            }
        } else {
            // 10 seconds delay for querying   
            unsigned long currentMillis = millis();
            if (currentMillis - slotsLastQueryTimes[i] >= QUERIES_INTERVAL_SECS * 1000) { 
                slotsLastQueryTimes[i] = currentMillis;
                getTreatmentNextDose(slotsTreatmentsIds[i], i);
            }

            unsigned long currentTime = timeClient.getEpochTime();
            if(slotsNextDosesTimestamps[i] != 0 && currentTime >= slotsNextDosesTimestamps[i]){
                Serial.println("Alarme " + String(SLOTS_NAMES[i]) + " ligado.");
                alarmsStatuses[i] = true; //Turn alarm on
                digitalWrite(LEDS_ARRAY[i], HIGH);
                digitalWrite(BUZZER, HIGH);
                slotsNextDosesTimestamps[i] = 0;
            }
        }
    } 
}

void getTreatmentNextDose(int treatment_id, int slotNumber) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(String(SERVER_URL) + "/controllers/doses.php");
    http.addHeader("Content-Type", "application/json");
    String jsonBody = "{\"doses_action\":\"get_treatment_next_dose\", \"params\": {\"treatment_id\": " + String(treatment_id) + "} }";
    int httpCode = http.POST(jsonBody);

    if (httpCode == 200) {
        String responseText = http.getString();
        JsonDocument responseJson;
        DeserializationError error = deserializeJson(responseJson, responseText);

        if (error) {
        Serial.println("Failed to parse JSON");
        return;
        }

        slotsNextDosesIds[slotNumber] = responseJson["DOS_id"];
        slotsNextDosesTimestamps[slotNumber] = responseJson["due_date_timestamp"];
        if(responseJson["DOS_due_datetime"] == "") {
            Serial.println("No dose found for slot " + String(SLOTS_NAMES[slotNumber]));
        } else {
            Serial.println("Next dose time: " + String(responseJson["DOS_due_datetime"]) + " (" + String(responseJson["due_date_timestamp"]) + ")");
        }

    } else {
      Serial.println("Error on HTTP request: " + String(httpCode));
    }

    http.end();
  } else {
    Serial.println("Not connected to WiFi");
  }
}

void takeDose(int doseId) {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        http.begin(String(SERVER_URL) + "/controllers/doses.php");
        http.addHeader("Content-Type", "application/json");
        String jsonBody = "{\"doses_action\":\"take_dose\",\"params\":{\"dose_id\": \"" + String(doseId) + "\" }}";

        int httpCode = http.POST(jsonBody);
        http.end();
    } else {
        Serial.println("Not connected to WiFi");
    }
}

void addTreatmentToSlot() {
    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.sendHeader("Access-Control-Allow-Methods", "POST, GET, OPTIONS");
    server.sendHeader("Access-Control-Allow-Headers", "Content-Type");
    if (server.method() == HTTP_OPTIONS) {
        server.send(200, "text/plain", "");
        return;
    }

    String requestBody = server.arg("plain");
    Serial.println("Request: " + requestBody);

    JsonDocument requestJson;
    DeserializationError error = deserializeJson(requestJson, requestBody);

    if (error) {
        server.send(400, "application/json", "{\"status\":\"Invalid JSON\"}");
        Serial.println("Failed to parse JSON");
        return;
    }

    int treatmentId = requestJson["treatmentId"];
    const char* slotName = requestJson["slotName"];
    slotsTreatmentsIds[slotNameToNum(slotName)] = treatmentId;

    Serial.println("Novo Tratamento: Id " + String(treatmentId) + " no compartimento " + String(slotName));
    server.send(200, "application/json", "{\"status\":\"Received successfully\"}");
}

// void getSlotsTreatments(){}