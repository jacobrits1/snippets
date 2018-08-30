import threading
import urllib
import requests
import subprocess

print("Start Auto tiling process")

def main_activity():
  threading.Timer(20.0, main_activity).start() # called every 20 seconds
  r = requests.get("https://fancam.com/ssl/install/rig/rigGetStatusTiler.php?pss=yoqwryhg28!244G6534!&ven=tiler")
  status = r.content
  if status == "30":
    print ("status : Rig IDLE")
  if status == "90":
    print ("status : Get AWS Directory copy to local DIR")
    awsdir = requests.get("https://fancam.com/ssl/install/rig/tileGetAwsDir.php?pss=yoqwryhg28!244G6534!")
    subprocess.call(['aws s3 cp --recursive s3://in3sixty-source/'+awsdir+' ~/from_source/'])
    subprocess.wait()
    r = requests.get("https://fancam.com/ssl/install/rig/rigSetStatus.php?pss=yoqwryhg28!244G6534!&ven=tiler&sta=92")
  if status == "91":
    print ("status : Get Directory")
    subprocess.call(['~/apg/', '~/msg.pno'])
    subprocess.wait()
    r = requests.get("https://fancam.com/ssl/install/rig/rigSetStatus.php?pss=yoqwryhg28!244G6534!&ven=tiler&sta=92")
  if status == "92":
    print ("status : Make Flat tile")
    subprocess.call(['~/apg/', '~/msg.pno'])
    subprocess.wait()
    r = requests.get("https://fancam.com/ssl/install/rig/rigSetStatus.php?pss=yoqwryhg28!244G6534!&ven=tiler&sta=93")
main_activity()
