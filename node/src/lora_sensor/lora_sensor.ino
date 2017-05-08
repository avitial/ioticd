/* Spring 2017. CPE190, Senior Design I, CSUS
 * Team 12, IoT Irrigation Controller Development. Version 1.4
 * This sample program has been modified to fit our project needs. All the licensing is described below.
 *****************************************************************************
 *  Copyright (C) 2016 Congduc Pham, University of Pau, France
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.

 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with the program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *****************************************************************************
 * last update: Nov. 16th by C. Pham
 */

#include <DHT.h>
#include <DHT_U.h>
#include <SPI.h>
#include <LowPower.h>
#include "Arduino.h"
#include "DHT.h"
#include "SX1272.h"
#include "Wire.h"


// Selected the appropriate US band
#define FCC_US_REGULATION 
// Uncomment if radio used is a HopeRF RFM92W, HopeRF RFM95W, Modtronix inAir9B, NiceRF1276.
#define PABOOST

// Bands allowed in the US
#define BAND868
//#define BAND900

// Here is where the DBM is set
#if defined FCC_US_REGULATION
  #define MAX_DBM 14
#endif

// Channel selected depending on band
#ifdef BAND868
  const uint32_t DEFAULT_CHANNEL=CH_10_868;
#endif
#if defined BAND900
  const uint32_t DEFAULT_CHANNEL=CH_05_900;
#endif


// Change here the LORA MODE, NODE ADDRESS and DEFAULT DESTINATION ADDRESS
/* setCR(CR_5);  CR = 4/5
   setSF(SF_12); SF = 12
   setBW(BW_125); BW = 125 KHz */
#define LORAMODE  1 // Modes 1-11, 
#define NODE_ADDR 209 // Addresses 0-255
#define DEFAULT_DEST_ADDR 1
#define DHTPIN A1     // what digital pin we're connected to
#define DHTTYPE DHT11 // DHT 11


#define PRINTLN                   Serial.println("")
#define PRINT_CSTSTR(fmt,param)   Serial.print(F(param))
#define PRINT_STR(fmt,param)      Serial.print(param)
#define PRINT_VALUE(fmt,param)    Serial.print(param)
#define FLUSHOUTPUT               Serial.flush();


uint8_t message[100];
int loraMode = LORAMODE;
int moisturePin = A0; // select the input pin for the humidity sensor
int temperature = 0;  // temperature
int humidity = 0;     // humidity
int moisture = 0;     // moisture
int packet_num = 1; 

DHT dht(DHTPIN, DHTTYPE); // Initialize DHT sensor.

void setup(){
  int e;

  // Open serial communications and wait for port to open
  Serial.begin(9600);   
  dht.begin();
  delay(300);

  /* Determine TH02_dev is available or not */
  PRINT_CSTSTR("%s","TH02_dev is available.\n");  
  /* Print a start message */
  PRINT_CSTSTR("%s","LoRa communication channel from Sensor Node to Gateway Controller\n");  

  #ifdef ARDUINO_AVR_PRO
    PRINT_CSTSTR("%s","Arduino Pro Mini detected\n");
  #endif

  #ifdef BAND868
    PRINT_CSTSTR("%s","BAND868 selected\n");
  #else
    PRINT_CSTSTR("%s","BAND900 selected\n");
  #endif

  // Power ON the module
  sx1272.ON();
  
  // Set transmission mode and print the result
  /*
    mode 1 (better reach, medium time on air)
        setCR(CR_5);        // CR = 4/5
        setSF(SF_12);       // SF = 12
        setBW(BW_125);      // BW = 125 KHz
    mode 2 (medium reach, less time on air)
        setCR(CR_5);        // CR = 4/5
        setSF(SF_12);       // SF = 12
        setBW(BW_250);      // BW = 250 KHz
    mode 3 (worst reach, less time on air)
        setCR(CR_5);        // CR = 4/5
        setSF(SF_10);       // SF = 10
        setBW(BW_125);      // BW = 125 KHz
    mode 4 (better reach, low time on air)
        setCR(CR_5);        // CR = 4/5
        setSF(SF_12);       // SF = 12
        setBW(BW_500);      // BW = 500 KHz
    mode 5 (better reach, medium time on air)
        setCR(CR_5);        // CR = 4/5
        setSF(SF_10);       // SF = 10
        setBW(BW_250);      // BW = 250 KHz
    mode 6 (better reach, worst time-on-air)
        setCR(CR_5);        // CR = 4/5
        setSF(SF_11);       // SF = 11
        setBW(BW_500);      // BW = 500 KHz
    mode 7 (medium-high reach, medium-low time-on-air)
        setCR(CR_5);        // CR = 4/5
        setSF(SF_9);        // SF = 9
        setBW(BW_250);      // BW = 250 KHz
    mode 8 (medium reach, medium time-on-air)
      setCR(CR_5);        // CR = 4/5
        setSF(SF_9);        // SF = 9
        setBW(BW_500);      // BW = 500 KHz
    mode 9 (medium-low reach, medium-high time-on-air)
        setCR(CR_5);        // CR = 4/5
        setSF(SF_8);        // SF = 8
        setBW(BW_500);      // BW = 500 KHz
    mode 10 (worst reach, less time_on_air)
        setCR(CR_5);        // CR = 4/5
        setSF(SF_7);        // SF = 7
        setBW(BW_500);      // BW = 500 KHz
    mode 11 (test for LoRaWAN channel)
        setCR(CR_5);        // CR = 4/5
        setSF(SF_12);        // SF = 12
        setBW(BW_125);      // BW = 125 KHz
        // set the sync word to the LoRaWAN sync word which is 0x34
        setSyncWord(0x34);
        Serial.print(F("** Using sync word of 0x"));
        Serial.println(_syncWord, HEX);
        break;
  */
  
  e = sx1272.setMode(loraMode);
  PRINT_CSTSTR("%s","Setting Mode: state ");
  PRINT_VALUE("%d", e); PRINTLN;
  
  // Get transmission mode and print the result
  e = sx1272.getMode();
  PRINT_CSTSTR("%s","Mode Set: state ");
  PRINT_VALUE("%d", e); PRINTLN;

  // enable carrier sense
  sx1272._enableCarrierSense=true;

  // Select frequency channel
  e = sx1272.setChannel(DEFAULT_CHANNEL);
  PRINT_CSTSTR("%s","Setting Channel: state ");
  PRINT_VALUE("%d", e); PRINTLN;

  // Select amplifier line; PABOOST or RFO
  #ifdef PABOOST
    sx1272._needPABOOST = true;
  #else
    sx1272._needPABOOST = false;
  #endif

  // Set Power DBM
  e = sx1272.setPowerDBM((uint8_t)MAX_DBM); 
  PRINT_CSTSTR("%s","Setting Power: state ");
  PRINT_VALUE("%d", e); PRINTLN;

  // Set the node address and print the result
  e = sx1272.setNodeAddress(NODE_ADDR);
  PRINT_CSTSTR("%s","Setting node addr: state ");
  PRINT_VALUE("%d", e); PRINTLN;

  // Print a success message
  PRINT_CSTSTR("%s","SX1272 successfully configured\n"); delay(500);
}

void loop(void){
  uint8_t r_size;
  int e, i;
  sx1272.CarrierSense();
  sx1272.setPacketType(PKT_TYPE_DATA);

  while(1){
    moisture = analogRead(moisturePin);
    temperature = dht.readTemperature(true);
    humidity = dht.readHumidity();

    if (isnan(humidity) || isnan(temperature)){
      PRINT_CSTSTR("%s","Failed to read from DHT sensor!"); PRINTLN;
      return;
    }
    PRINT_CSTSTR("%s","Humidity: ");
    PRINT_VALUE("%f",humidity); PRINTLN;
    PRINT_CSTSTR("%s","Temperature: ");
    PRINT_VALUE("%f", temperature); PRINTLN;

//    r_size = sprintf((char*)message, "%d, %d, %d, %d, %d", packet_num, NODE_ADDR, moisture, temperature, humidity);
    r_size = sprintf((char*)message, "%d %d %d %d %d", packet_num, NODE_ADDR, moisture, temperature, humidity);

    PRINT_CSTSTR("%s","Sending Sensor Data, Packet Num "); PRINTLN;
  
    /*
    Function: Configures the module to transmit information and receive an ACK.
    Returns: Integer that determines if there has been any error
    state = 3  --> Packet has been sent but ACK has not been received
    state = 2  --> The command has not been executed
    state = 1  --> There has been an error while executing the command
    state = 0  --> The command has been executed with no errors
    */
    // sendPacketTimeoutACK(uint8_t dest, uint8_t *payload, uint16_t length16)
    //e = sx1272.sendPacketTimeoutACK(DEFAULT_DEST_ADDR, message, r_size);
    // this is the NO-ACK version
    // sendPacketTimeout(uint8_t dest, uint8_t *payload, uint16_t length16)
    // 1st packet
    e = sx1272.sendPacketTimeout(DEFAULT_DEST_ADDR, message, r_size);
    PRINT_CSTSTR("%s","Packet sent, state ");
    PRINT_VALUE("%d", e); PRINTLN;
    if (e==3)
      PRINT_CSTSTR("%s","No reply!");
    if (e==0)
      PRINT_CSTSTR("%s","Gateway Controller received node packet!"); PRINTLN;
      packet_num++;
    PRINT_CSTSTR("%s","Sensor going to sleep now..."); PRINTLN;
    delay(100);
    for(i=1; i>0; i--){
      LowPower.powerDown(SLEEP_8S, ADC_OFF, BOD_OFF);
    } 
  }
}
