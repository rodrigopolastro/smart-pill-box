#include <WiFi.h>
#include <WebServer.h>
#include <HTTPClient.h>
#include <NTPClient.h>
#include <WiFiUdp.h>
#include <time.h>
#include <ArduinoJson.h>

// const char* WIFI_SSID = "Arena";
// const char* WIFI_PASSWORD = "arena12024";
// const char* SERVER_URL = "http://192.168.0.100/smart-pill-box";
const char* WIFI_SSID = "Clayton_2g";
const char* WIFI_PASSWORD = "Cla44885735";
const char* SERVER_URL = "http://192.168.0.90/smart-pill-box";
const int PERSON_IN_CARE_ID = 1;
const int QUERY_DELAY_SECS = 10;
const int NUMBER_OF_SLOTS = 6;
  
WebServer server(80);

WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "pool.ntp.org", 0, 60000); // Sync every 60s

const int SWITCH_A = 23;
const int LED_A = 22; 
const int BUZZER = 13;

bool isAlarmOn = false;

int nextDoseId, nextDoseDueTimestamp, currentTime;

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

int slotsTreatmentsIds[6] = {0, 0, 0, 0, 0, 0};

void setup() {
    Serial.begin(115200);

    pinMode(SWITCH_A, INPUT_PULLDOWN);
    pinMode(LED_A, OUTPUT);
    pinMode(BUZZER, OUTPUT);
    
    digitalWrite(LED_A, LOW);

    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
    Serial.print("Conectando-se à rede WiFi");
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    Serial.println("\nConectado!");
    Serial.print("Endereço IP ESP32: ");
    Serial.println(WiFi.localIP());

    server.on("/addTreatmentToSlot", addTreatmentToSlot);
    server.begin();
    timeClient.begin();

//   getSlotsTreatments();
}

void loop() {
    server.handleClient();
    timeClient.update();
    
    if(isAlarmOn){
        if(digitalRead(SWITCH_A)){
            takeDose(nextDoseId);
            Serial.println("Dose do compartimento A tomada.");
            digitalWrite(LED_A, LOW);
            digitalWrite(BUZZER, LOW);
            isAlarmOn = false;
        }
    } else {
        // 10 seconds delay for querying   
        static unsigned long lastQueryTime = 0; 
        unsigned long currentMillis = millis();
        if (currentMillis - lastQueryTime > QUERY_DELAY_SECS * 1000) { 
            lastQueryTime = currentMillis;
            getNextDoseTime(PERSON_IN_CARE_ID);
        }

        currentTime = timeClient.getEpochTime();
        if(nextDoseDueTimestamp != 0 && currentTime >= nextDoseDueTimestamp){
            Serial.println("Alarme A ligado!");
            isAlarmOn = true;
            digitalWrite(LED_A, HIGH);
            digitalWrite(BUZZER, HIGH);
            nextDoseDueTimestamp = 0;   
        }
    }
}

void getTreatmentNextDose(int treatment_id) {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        http.begin(String(SERVER_URL) + "/controllers/doses.php");
        http.addHeader("Content-Type", "application/json");
        String jsonBody = "{\"doses_action\":\"get_treatment_next_dose\", \"params\": {\"treatment_id\": " + String(treatment_id) + "} }";

        int httpCode = http.POST(jsonBody);

        if (httpCode == 200) {
            String payload = http.getString();
            // Serial.println("Response from server: " + payload);

            JsonDocument doc;
            DeserializationError error = deserializeJson(doc, payload);
            
            if (error) {
                Serial.println("Failed to parse JSON");
                return;
            }

            nextDoseId = doc["DOS_id"];
            nextDoseDueTimestamp = doc["due_date_timestamp"];
            if(doc["DOS_due_datetime"] != "") {
                Serial.println("Next dose time: " + String(doc["DOS_due_datetime"]) + " (" + String(nextDoseDueTimestamp) + ")");
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