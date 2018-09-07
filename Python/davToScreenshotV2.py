#!/usr/bin/python

import os
from subprocess import call

files = [f for f in os.listdir('.') if os.path.isfile(f)]

for f in files:
    ext = f.split(".")[-1]
    if ext == "dav" or ext == "DAV":
    	mp4Name = f.replace("dav", "jpg")
        fname = f.split(".")[0]   #LNR6108_ch1_main_20180825130000_20180825133000

        subfnameA = fname.split("_")[0] #LNR6108
        subfnameB = fname.split("_")[1] #ch1
        subfnameC = fname.split("_")[2] #main
        subfnameD = fname.split("_")[3] #20180825130000
        year = subfnameD[:-6] #20180825
        subfnameD = subfnameD[:-2] #201808251300 xx
        minutes = subfnameD.replace(year,"") #1300

        cam = subfnameB.replace("ch","")

        dir = year + "-070000-190000-rcc-pga"

        if not os.path.exists(dir): #create directory if not excist
            os.makedirs(dir)
        i = 0
        tt = minutes[2:]
        hours= minutes[0:2]
        while i < 1800:
            time = i
            if tt == "30":
                time = 1800 + i
            # day = time // (24 * 3600)
            time = time % (24 * 3600)
            #hour = time // 3600
            time %= 3600
            minutes2 = time // 60
            time %= 60
            seconds = time
            filenameTime = "%02d%02d" % (minutes2, seconds)
            ffmpegTime = "00:%02d:%02d" % (minutes2, seconds)
            #minutes2 = minutes[:-2] + filenameTime
            mp4Name = dir +"/" + year + "-" + hours + filenameTime + "-cam" + cam + ".jpg"

            print mp4Name

        	#print "Converting: " + mp4Name
            #ffmpeg -ss 01:23:45 -i input.dav -vframes 1 -q:v 2 output.jpg
            call(['ffmpeg', '-i', f, "-ss",ffmpegTime,"-vframes","1" ,"-q:v","1",mp4Name])
            #call(['ffmpeg', '-i', f, "-q:v","1","-vf","fps=1", mp4Name])
        	#print "Converted: " + mp4Name
            i += 1
