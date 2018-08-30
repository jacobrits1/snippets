from pyfirmata import Arduino, util
import time
import math
from serial import SerialException # Not needed, but used for troubleshootingserial timeouts/exceptions
from AWSIoTPythonSDK.MQTTLib import *
import sys
import select
import os
import time
import datetime
import serial
import logging
import json
import getopt
####################### Genuino Device and Path Config
board = Arduino('/dev/ttyACM0')
time.sleep(1)
it = util.Iterator(board)
it.start()
board.analog[0].enable_reporting() # Enable reading of A0
board.analog[1].enable_reporting() # Enable reading of A1
board.analog[2].enable_reporting() # Enable reading of A2
board.analog[3].enable_reporting() # Enable reading of A3
time.sleep(6) # After enabling reading from analog sensor pins, wait beforeproceeding to read values, to avoid serial timeout issue.
B = 4275 # B value of the thermistor
Rk = 100000 # Rk = 100k
####################### AWS IOT Config
# # Configure logging
logger = None
if sys.version_info[0] == 3:
 logger = logging.getLogger("core") # Python 3
else:
 logger = logging.getLogger("AWSIoTPythonSDK.core") # Python 2
logger.setLevel(logging.CRITICAL)
streamHandler = logging.StreamHandler()
formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
streamHandler.setFormatter(formatter)
logger.addHandler(streamHandler)
#### MQTT Client ####
# For certificate based connection
myMQTTClient = AWSIoTMQTTClient("MyThing") # <--- Device/Thing Name Here
# For TLS mutual authentication
myMQTTClient.configureEndpoint("a210tm4lsf4jxl.iot.us-west-2.amazonaws.com", 8883) # <-
myMQTTClient.configureCredentials("/root/c52c73169b-public.pem.key","/root/c52c73169b-private.pem.key","/root/c52c73169b-certificate.pem.crt") # <--- Certs paths here. ("YOUR/ROOT/CA/PATH","PRIVATE/KEY/PATH", "CERTIFICATE/PATH")
myMQTTClient.configureOfflinePublishQueueing(-1) # Infinite offline Publishqueueing
myMQTTClient.configureDrainingFrequency(2) # Draining: 2 Hz
#myMQTTClient.configureAutoReconnectBackoffTime(1, 32, 20)
myMQTTClient.configureConnectDisconnectTimeout(10) # 10 sec
myMQTTClient.configureMQTTOperationTimeout(5) # 5 sec
def getTemp(): # Get sensor readings and convert to Deg C
 a = board.analog[1].read()
 a = a * 1000 # Multiply raw sensor analogue value by 1000

 R = 1023.0/a-1.0 # Calculate resistance of sensor
 R = Rk * R
 resistance = (float)(1023-a)*10000/a
 temperature = 1.0/(math.log(R/Rk)/B+1/298.15)-273.15 # Convert sensorresistance to temperature as per sensor's datasheet
 temperature = temperature/5*2.2 # Convert to Deg C
 return temperature
while True:
 TempOutput = getTemp()
 print TempOutput
 msg = TempOutput
 myMQTTClient.connect()
 myMQTTClient.publish("MyThing", TempOutput, 0) # <--- Your topic namehere
 print "Message Published"
 time.sleep(10)
