// VERSION 001
//      Turn led on at specific fixed time 
//      and turn it of when its slot is opened

#include <WiFi.h>
#include <WebServer.h>
#include <HTTPClient.h>
#include <NTPClient.h>
#include <WiFiUdp.h>
#include <time.h>
// #include <ArduinoJson.h>

// const char* WIFI_SSID = "Arena";
// const char* WIFI_PASSWORD = "arena12024";
const char* WIFI_SSID = "Clayton_2g";
const char* WIFI_PASSWORD = "Cla44885735";
const char* SERVER_URL = "http://192.168.0.90/smart-pill-box";
const int PERSON_IN_CARE_ID = 1;
  
WebServer server(80);

WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "pool.ntp.org", 0, 60000); // Sync every 60s

const int SWITCH_A = 17;
const int LED_A = 16;
const int BUZZER = 13;

bool is_alarm_a_on = false;

int next_alarm_time = 1732524600; //05:50
int currentTime;

void setup() {
  Serial.begin(115200);

  pinMode(SWITCH_A, INPUT);
  pinMode(LED_A, OUTPUT);

  pinMode(BUZZER, OUTPUT);

  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Conectando-se à rede WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConectado!");
  Serial.print("Endereço IP: ");
  Serial.println(WiFi.localIP());

  // server.on("/turnAlarmOn", turnAlarmOn);

  server.begin();
  timeClient.begin();
}

void loop() {
  server.handleClient();
  timeClient.update();
  
  if(is_alarm_a_on){
    if(digitalRead(SWITCH_A)){
      Serial.println("Alarme A desligado.");
      digitalWrite(LED_A, LOW);
      digitalWrite(BUZZER, LOW);
      is_alarm_a_on = false;
    }
  } else { 
    currentTime = timeClient.getEpochTime();
    if(currentTime >= next_alarm_time){
      Serial.println("Alarme A ligado!");
      is_alarm_a_on = true;
      digitalWrite(LED_A, HIGH);
      digitalWrite(BUZZER, HIGH);   
    }
  }

  delay(500);
}