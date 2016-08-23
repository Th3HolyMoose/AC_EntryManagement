import os
import sys
import json
from os import listdir
from os.path import isfile, join
import ConfigParser
import time

#afj = dict()
def addField(afj, name, key, ):
    return name + "=" + str(afj[key]) + "\n"

def getResults(fn):
    f = open(fn, "rb")
    s = f.read()
    f.close()
    js = json.loads(s)
    h = "[" + fn + "] "
    if not js["Type"].upper() == "RACE":
        print "Warning: File " + n + " is not a race file"
    print h + "Track: " + js["TrackName"] + " (" + js["TrackConfig"] + ") #" + str(len(js["Result"]))
    r = js["Result"]
    out = []
    times = []
    pos = []
    i = 1
    guid = []
    acd = []
    ballasts = []
    best = 99999999999999
    numPeeps = 0
    for c in js["Cars"]:
        #print c
        #afj = c
        a = addField(c, "MODEL", "Model") + addField(c, "SKIN", "Skin") + "SPECTATOR_MODE=0\n" + addField(c, "BALLAST", "BallastKG")
        afj = c["Driver"]
        a += addField(afj, "DRIVERNAME", "Name") + addField(afj, "TEAM", "Team") + addField(afj, "GUID", "Guid")
        acd.append(a)
    for car in r:
        if len(car["DriverGuid"]) <= 0:
            continue
        DNS = True
        for lap in js["Laps"]:
            if lap["DriverGuid"] == str(car["DriverGuid"]):
                DNS = False
                break
        if DNS == True:
            print "DNS on Driver: " + str(car["DriverGuid"]) + ", " + car["DriverName"] 
            continue
        numPeeps += 1
        guid.append(str(car["DriverGuid"]))
        out.append(str(car["DriverName"]).lower())
        times.append(car["TotalTime"])
        ballasts.append(car["BallastKG"])
        pos.append(i)
        
        if int(car["TotalTime"]) > 0 and int(car["TotalTime"]) < best:
            best = int(car["TotalTime"])
        i += 1
    #       0,   1,   2,    3,  4,  5
    return (acd, guid,times,out,pos,numPeeps, best, ballasts)

import ballast_handler
    
acdFiles = [f for f in listdir("drivers/") if isfile(join("drivers/", f))]
resultFiles = [f for f in listdir("results/") if isfile(join("results/", f))]

acdfc = acdFiles[:]
i = 0
for acd in acdfc:
    if not acd.endswith(".acd"):
        acdFiles.pop(i)
        continue
    i += 1
print str(acdFiles)
print str(len(acdFiles))

acdfc = resultFiles[:]
i = 0
for acd in acdfc:
    if not acd.endswith(".json"):
        resultFiles.pop(i)
        continue
    i += 1
print str(resultFiles)
print str(len(resultFiles))


for resultFile in resultFiles:
    print "----------------------------------------------------\n"
    print "Analyzing result from " + resultFile
    
    R = getResults("results/" + resultFile)
    print "Best Time: " + str(R[6])
    ri = 0
    numPeople = R[5]
    for guid in R[1]:
        acd = "drivers/" + guid + ".acd"
        if not os.path.isfile(acd):
            print "Guid " + guid + " was not a registered driver, skipping!"
            ri += 1
            continue
        f = open(acd, "rb")
        info = f.read()
        f.close()
        
        pos = R[4][ri]
        
        
        f = open(acd, "wb")
        for line in info.split("\n"):
            if line.startswith("DRIVERNAME="):
                print "Driver: " + line[11:] + ", (" + str(pos) + " / " + str(numPeople) + ")"
            if line.startswith("GUID="):
                print "GUI: " + line[5:] + ", (" + str(pos) + " / " + str(numPeople) + ")"
            
            if line.startswith("BALLAST="):
                ball = R[7][ri]
                oball = ball + 0
                ball = ballast_handler.getBallastDelta(ball, pos, numPeople)
                print "Ballast: " + str(oball) + " + " + str((ball - oball)) + " = " + str(ball)
                line = "BALLAST=" + str(ball)
            
            f.write(line + "\n")
            
        f.close()
        ri += 1
    
    
    
    





























