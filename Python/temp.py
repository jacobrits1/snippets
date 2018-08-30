from pyfirmata import Arduino, util
import time
import math
from serial import SerialException # Not needed, but used for troubleshooting
serial timeouts/exceptions
board = Arduino('/dev/ttyACM0')
time.sleep(1)
it = util.Iterator(board)
it.start()
board.analog[0].enable_reporting() # Enable reading of A0
board.analog[1].enable_reporting() # Enable reading of A1
board.analog[2].enable_reporting() # Enable reading of A2
board.analog[3].enable_reporting() # Enable reading of A3
time.sleep(6) # After enabling reading from analog sensor pins, wait before
proceeding to read values, to avoid serial timeout issue.
B = 4275 # B value of the thermistor
Rk = 100000 # Rk = 100k
while True: # Loop sensor readings and output to SSH session
 a = board.analog[1].read()
 a = a * 1000 # Multiply raw sensor analogue value by 1000

 R = 1023.0/a-1.0 # Calculate resistance of sensor
 R = Rk * R
 resistance = (float)(1023-a)*10000/a
 temperature = 1.0/(math.log(R/Rk)/B+1/298.15)-273.15 # Convert sensor
resistance to temperature as per sensor's datasheet
 temperature = temperature/5*2.2 # Convert to Deg C
 print "Raw Sensor:" , a
 print "Resistance:" , resistance
 print "Temperature:" , temperature
 time.sleep(5)
