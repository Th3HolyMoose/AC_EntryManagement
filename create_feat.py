import os
import sys
import json
from os import listdir
from os.path import isfile, join
import ConfigParser
import time

import ballast_handler

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
        if i == 1:
            best = int(car["TotalTime"])
        #if int(car["TotalTime"]) > 0 and int(car["TotalTime"]) < best:
        #    best = int(car["TotalTime"])
        i += 1
    #       0,   1,   2,    3,  4,  5
    return (acd, guid,times,out,pos,numPeeps, best, ballasts)


resultFiles = [f for f in listdir("results/") if isfile(join("results/", f))]
acdfc = resultFiles[:]
i = 0
for acd in acdfc:
    if not acd.endswith(".json"):
        resultFiles.pop(i)
        continue
    i += 1
print str(resultFiles)
print str(len(resultFiles))

def getAcd(acd, g,d):
    for a in acd:
        if g in a:
            return a

    for a in acd:
        if d in a.lower():
            return a
    return ""

carLimit = 18
carLimit = int(sys.argv[1])
carLimit = 20
carLimit = min(int(sys.argv[1]), 10 * len(resultFiles))
print "Max cars for feature: " + str(carLimit)

def applyBallasts(R):
    i = 0
    for acd in R[0]:
        guid = "1911"
        name = ""
        for line in acd.split("\n"):
            if line.startswith("GUID="):
                guid = line[5:]
                break
        pos = -110
        part = 10000
        ball = 0
        ri = 0
        for ag in R[1]:
            if ag == guid:
                ball = R[7][ri]
                pos = R[4][ri]
                part = R[5]
                name = R[3][ri]
                break
            ri += 1
        print "Driver: " + name + ", " + guid + ", " + str(pos) + " / " + str(part)
        oball = ball + 0
        ball = ballast_handler.getBallastDelta(ball, pos, part)
        print "Ballast: " + str(oball) + " + " + str((ball - oball)) + " = " + str(ball)
        
        out = ""
        for line in acd.split("\n"):
            if line.startswith("BALLAST="):
                line = "BALLAST=" + str(ball)
            out += line + "\n"
        
        R[0][i] = out
        
        i += 1
        
if len(resultFiles) == 2:
    h1o = False
    h2o = False
    _H1 = getResults("results/" + resultFiles[0])
    _H2 = getResults("results/" + resultFiles[1])
    H1 = _H1
    H2 = _H2
    
    applyBallasts(H1)
    applyBallasts(H2)

    print resultFiles[0] + ": " + str(H1[6])
    print resultFiles[1] + ": " + str(H2[6])
    
    
    if _H1[6] >= _H2[6]:
        H1 = _H2
        H2 = _H1
    
    out0 = ""
    out1 = ""
    out = ""
    cc = 0
    h1i = 0
    h2i = 0
    feature = 0
    while ((not h1o) or (not h2o)):
        if cc >= carLimit:
            if feature == 1:
                break
            else:
                feature = 1
                out0 = out[:]
                out = ""
                cc = 0
        if (not h1o) and (cc % 2 == 0 or h2o):
            if h1i >= H1[5]:
                h1o = True
                print "Out of Row 1 people"
                continue
            print "[CAR_" + str(cc) + "]"
            print getAcd(H1[0], str(H1[1][h1i]), str(H1[3][h1i]))
            out += "[CAR_" + str(cc) + "]\n"
            cc += 1
            out += getAcd(H1[0], str(H1[1][h1i]), str(H1[3][h1i]))
            h1i += 1
        elif (not h2o):
            if h2i >= H2[5]:
                h2o = True
                print "Out of Row 2 people"
                continue
            print "[CAR_" + str(cc) + "]"
            print getAcd(H2[0], str(H2[1][h2i]), str(H2[3][h2i]))
            out += "[CAR_" + str(cc) + "]\n"
            cc += 1
            out += getAcd(H2[0], str(H2[1][h2i]), str(H2[3][h2i]))
            h2i += 1
        print "\n\n"
        out += "\n\n\n"
        
        
                
    f = open("entry_list.ini", "wb")
    f.write(out0)
    f.close()
    f = open("entry_list_2.ini", "wb")
    f.write(out)
    f.close()
    print "\n\n\n\n"
    #print out
elif len(resultFiles) == 3:
    h1o = False
    h2o = False
    h3o = False
    _H1 = getResults("results/" + resultFiles[0])
    _H2 = getResults("results/" + resultFiles[1])
    _H3 = getResults("results/" + resultFiles[2])
    H1 = _H1
    H2 = _H2
    H3 = _H3
    
    applyBallasts(_H1)
    applyBallasts(_H2)
    applyBallasts(_H3)
    
    fastest = min(min(_H1[6], _H2[6]), _H3[6])
    """
    if fastest == _H1[6]:
        H1 = _H1
        second = min(_H2[6], _H3[6])
        if second == _H2[6]:
            H2 = _H2
            H3 = _H3
        else:
            H2 = _H3
            H3 = _H2
    
    if fastest == _H2[6]:
        H1 = _H2
        second = min(_H1[6], _H3[6])
        if second == _H1[6]:
            H2 = _H1
            H3 = _H3
        else:
            H2 = _H3
            H3 = _H1
    
    if fastest == _H3[6]:
        H1 = _H3
        second = min(_H1[6], _H2[6])
        if second == _H1[6]:
            H2 = _H1
            H3 = _H2
        else:
            H2 = _H2
            H3 = _H1
    """
    heat_tuples = [[_H1, _H1[6]], [_H2, _H2[6]], [_H3, _H3[6]]]
    heat_tuples.sort(key=lambda tup: tup[1])  # sorts in place

    H1 = heat_tuples[0][0]
    H2 = heat_tuples[1][0]
    H3 = heat_tuples[2][0]
    
    print str(H1[6]) + ", " + str(H2[6]) + ", " + str(H3[6])
    
    out0 = ""
    out1 = ""
    out = ""
    cc = 0
    h1i = 0
    h2i = 0
    h3i = 0
    feature = 0
    while ((not h1o) or (not h2o) or (not h3o)):
        if cc >= carLimit:
            if feature == 1:
                break
            else:
                feature = 1
                out0 = out[:]
                out = ""
                cc = 0
        if (not h1o) and (cc % 3 == 0 or (h2o and h3o)):
            if h1i >= H1[5]:
                h1o = True
                print "Out of Row 1 people"
                continue
            print "[CAR_" + str(cc) + "]"
            print getAcd(H1[0], str(H1[1][h1i]), str(H1[3][h1i]))
            out += "[CAR_" + str(cc) + "]\n"
            cc += 1
            out += getAcd(H1[0], str(H1[1][h1i]), str(H1[3][h1i]))
            h1i += 1
        elif (not h2o) and (cc % 3 == 1 or (h1o and h3o)):
            if h2i >= H2[5]:
                h2o = True
                print "Out of Row 2 people"
                continue
            print "[CAR_" + str(cc) + "]"
            print getAcd(H2[0], str(H2[1][h2i]), str(H2[3][h2i]))
            out += "[CAR_" + str(cc) + "]\n"
            cc += 1
            out += getAcd(H2[0], str(H2[1][h2i]), str(H2[3][h2i]))
            h2i += 1
        elif (not h3o):
            if h3i >= H3[5]:
                h3o = True
                print "Out of Row 3 people"
                continue
            print "[CAR_" + str(cc) + "]"
            print getAcd(H3[0], str(H3[1][h3i]), str(H3[3][h3i]))
            out += "[CAR_" + str(cc) + "]\n"
            cc += 1
            out += getAcd(H3[0], str(H3[1][h3i]), str(H3[3][h3i]))
            h3i += 1
        print "\n\n"
        out += "\n\n\n"
        
        
                
    f = open("entry_list.ini", "wb")
    f.write(out0)
    f.close()
    f = open("entry_list_2.ini", "wb")
    f.write(out)
    f.close()
    print "\n\n\n\n"
    #print out
