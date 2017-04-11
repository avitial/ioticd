// LoRa 915MHz_RX
// -*- mode: C++ -*-
// Example sketch showing how to create a simple messaging client (receiver)
// with the RH_RF95 class. RH_RF95 class does not provide for addressing or
// reliability, so you should only use RH_RF95 if you do not need the higher
// level messaging abilities.
// It is designed to work with the other example Arduino9x_TX

#include <SPI.h>
#include <RH_RF95.h>

#define RFM95_CS 10
#define RFM95_RST 9
#define RFM95_INT 2

// change to 915.0 MHz, it must match RX's frequency!
#define RF95_FREQ 915.0

// singleton instance of the radio driver
RH_RF95 rf95(RFM95_CS, RFM95_INT);

// blink on receipt
#define LED_STBY 5 
#define LED_RCVD 6 

// packet statistics
int sent_pkt = 0;
int rcvd_pkt = 0;
int lost_pkt = 0;

void setup(){
	pinMode(LED_STBY, OUTPUT);
	pinMode(LED_RCVD, OUTPUT);
	pinMode(RFM95_RST, OUTPUT);
	digitalWrite(RFM95_RST, HIGH);
	digitalWrite(LED_STBY, HIGH);
	digitalWrite(LED_RCVD, LOW);

	while(!Serial);
	Serial.begin(9600); delay(100);

	Serial.println("Arduino LoRa RX Test!");

	// manual reset
	digitalWrite(RFM95_RST, LOW); delay(10);
	digitalWrite(RFM95_RST, HIGH); delay(10);

	while(!rf95.init()){
		Serial.println("LoRa radio init failed");
		while (1);
	}
	Serial.println("LoRa radio init OK!");

	// Defaults after init are 434.0MHz, modulation GFSK_Rb250Fd250, +13dbM
	if(!rf95.setFrequency(RF95_FREQ)){
		Serial.println("setFrequency failed");
		while (1);
	}
	Serial.print("Set Freq to: "); Serial.println(RF95_FREQ);

	// Defaults after init are 434.0MHz, 13dBm, Bw = 125 kHz, Cr = 4/5, Sf = 128chips/symbol, CRC on

	// The default transmitter power is 13dBm, using PA_BOOST.
	// If you are using RFM95/96/97/98 modules which uses the PA_BOOST transmitter pin, then 
	// you can set transmitter powers from 5 to 23 dBm:
	rf95.setTxPower(23, false);
}

void loop(){
	digitalWrite(LED_STBY, HIGH);
	if(rf95.available()){
		// there should be a message for us now   
		uint8_t buf[RH_RF95_MAX_MESSAGE_LEN];
		uint8_t len = sizeof(buf);
		if(rf95.recv(buf, &len)){
			// received message
			digitalWrite(LED_STBY, LOW);
			digitalWrite(LED_RCVD, HIGH);
			RH_RF95::printBuffer("Received: ", buf, len);
			Serial.print("Got: ");
			Serial.println((char*)buf);
			Serial.print("RSSI: ");
			Serial.println(rf95.lastRssi(), DEC);
			rcvd_pkt++;
			// send a reply
			uint8_t data[] = "And hello back to you";
			rf95.send(data, sizeof(data));
			rf95.waitPacketSent();
			Serial.println("Sent a reply"); delay(200);
			digitalWrite(LED_RCVD, LOW);
			digitalWrite(LED_STBY, HIGH);
		} else{
			// receive failed
			digitalWrite(LED_STBY, LOW);
			digitalWrite(LED_RCVD, LOW);
			Serial.println("Received failed");
			lost_pkt++;
			delay(500);
		}
	}
	sent_pkt++;
	digitalWrite(LED_STBY, HIGH); // set standby led
}