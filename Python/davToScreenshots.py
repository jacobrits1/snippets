#script DAV to mp4 and then to screenshots

#!/usr/bin/python


print "Fancam Converting all of the .dav files to png screenshots every second"
import os
from subprocess import call

files = [f for f in os.listdir('.') if os.path.isfile(f)]
for f in files:
    ext = f.split(".")[-1]
    if ext == "dav" or ext == "DAV":
    	mp4Name = f.replace("dav", "jpg")
        fname = f.split(".")[0]   #LNR6108_ch1_main_20180825130000_20180825133000

        if not os.path.exists(fname): #create directory if not excist
            os.makedirs(fname)

        subfnameA = fname.split("_")[0]
        subfnameB = fname.split("_")[1]
        subfnameC = fname.split("_")[2]
        subfnameD = fname.split("_")[3]
        subfnameD = subfnameD[:-2]
        mp4Name = fname +"/" + subfnameA + "-" + subfnameB + "-" + subfnameC + "-" + subfnameD + "%d.jpg"

    	print "Converting: " + mp4Name
    	call(['ffmpeg', '-i', f, "-q:v","1","-vf","fps=1", mp4Name])
    	print "Converted: " + mp4Name
