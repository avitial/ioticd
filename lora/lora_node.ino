/*  Copyright (C) 2016 Congduc Pham, University of Pau, France
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
 * modified: Mar. 15th 2017 by L. Avitia
 */
#include <SPI.h>  
#include "SX1272.h"

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
#define LORAMODE  1
#define NODE_ADDR 6
#define DEFAULT_DEST_ADDR 1

// 
#define PRINTLN                   Serial.println("")
#define PRINT_CSTSTR(fmt,param)   Serial.print(F(param))
#define PRINT_STR(fmt,param)      Serial.print(param)
#define PRINT_VALUE(fmt,param)    Serial.print(param)
#define FLUSHOUTPUT               Serial.flush();


uint8_t message[100];

int loraMode=LORAMODE;

void setup()
{
  int e;
  
  // Open serial communications and wait for port to open
  Serial.begin(9600); 
  delay(300);

  // Print a start message
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
  e = sx1272.setMode(loraMode);
  PRINT_CSTSTR("%s","Setting Mode: state ");
  PRINT_VALUE("%d", e);
  PRINTLN;

  // enable carrier sense
  sx1272._enableCarrierSense=true;
    
  // Select frequency channel
  e = sx1272.setChannel(DEFAULT_CHANNEL);
  PRINT_CSTSTR("%s","Setting Channel: state ");
  PRINT_VALUE("%d", e);
  PRINTLN;
  
// Select amplifier line; PABOOST or RFO
#ifdef PABOOST
  sx1272._needPABOOST=true;
#else
#endif

  // Set Power DBM
  e = sx1272.setPowerDBM((uint8_t)MAX_DBM); 
  PRINT_CSTSTR("%s","Setting Power: state ");
  PRINT_VALUE("%d", e);
  PRINTLN;
  
  // Set the node address and print the result
  e = sx1272.setNodeAddress(NODE_ADDR);
  PRINT_CSTSTR("%s","Setting node addr: state ");
  PRINT_VALUE("%d", e);
  PRINTLN;
  
  // Print a success message
  PRINT_CSTSTR("%s","SX1272 successfully configured\n");
  delay(500);
}


void loop(void)
{
  uint8_t r_size;
  int e;
  int temperature = 70;
  int humidity = 50;
  int moisture = 90;
  
  sx1272.CarrierSense();
  sx1272.setPacketType(PKT_TYPE_DATA);

  // Setting message
  r_size = sprintf((char*)message, "Moist: %%%d|Temp: %dF|Hum: %%%d", moisture, temperature, humidity);
      
  while (1) {
      PRINT_CSTSTR("%s","Sending Sensor Data");  
      PRINTLN;
            
      e = sx1272.sendPacketTimeoutACK(DEFAULT_DEST_ADDR, message, r_size);
      // this is the no-ack version
      // e = sx1272.sendPacketTimeout(DEFAULT_DEST_ADDR, message, r_size);
            
      PRINT_CSTSTR("%s","Packet sent, state ");
      PRINT_VALUE("%d", e);
      PRINTLN;
      
      if (e==3)
          PRINT_CSTSTR("%s","No reply!");
      if (e==0)
          PRINT_CSTSTR("%s","Gateway Controller received node packet!");      

      PRINTLN;
      delay(5000);    
  }          
}
