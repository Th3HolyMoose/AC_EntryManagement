from os import listdir
from os.path import isfile, join
import ConfigParser
import sys

boxes = int(sys.argv[1])
print "Boxes: " + str(boxes)

carModel = "ks_mazda_mx5_cup"
print "Car: " + carModel

#import time
#time.sleep(5)

import os

if os.path.exists("H1_entry_list.ini"):
    os.remove("H1_entry_list.ini")
if os.path.exists("H2_entry_list.ini"):
    os.remove("H2_entry_list.ini")
if os.path.exists("H3_entry_list.ini"):
    os.remove("H3_entry_list.ini")


class FakeSecHead(object):
    def __init__(self, fp):
        self.fp = fp
        self.sechead = '[asection]\n'

    def readline(self):
        if self.sechead:
            try: 
                return self.sechead
            finally: 
                self.sechead = None
        else: 
            return self.fp.readline()

driverFiles = [f for f in listdir("drivers") if isfile(join("drivers", f))]


drivers = []
acd = []
drNames = []
hasMoose = False
hasCone = False

for file in driverFiles:
    if not file.endswith(".acd"):
        continue
    cfg = ConfigParser.ConfigParser()
    cfg.readfp(FakeSecHead(open("drivers/" + file)))
    #drivers.append((cfg.get("asection", "MODEL"), cfg.get("asection", "SKIN"), cfg.get("asection", "SPECTATOR_MODE"), cfg.get("asection", "DRIVERNAME"), cfg.get("asection", "TEAM"), ))
    
    f = open("drivers/" + file, "rb")
    acd.append(str(f.read()).split("\n\n\n")[0].replace("MODEL=", "MODEL=" + carModel))
    f.close()

    drNames.append((cfg.get("asection", "DRIVERNAME"), cfg.get("asection","BALLAST")))
    drivers.append((cfg.get("asection", "ATTENDANCE"), ""))

    if cfg.get("asection", "ATTENDANCE") == "y":
        if str(cfg.get("asection", "GUID")) == "76561198029842017":
            print "I have a Moose"
            hasMoose = True
            if str(cfg.get("asection", "GUID")) == "76561198004896172":
                print "I have a Cone"
                hasCone = True
    
#Create nominal
nominal = []
i = 0
for d in drivers:
    if(d[0] == "y"):
        nominal.append(i)
    i += 1
i = 0
print "Nominals: " + str(len(nominal))

nh = 2
if (len(nominal) % 2 == 0 and len(nominal)/2 > boxes) or (len(nominal) % 2 == 1 and len(nominal)/2+1 > boxes):
    print "We need a third heat yo!"
    nh = 3


#nomCopy = []
#if len(nominal) >= boxes:
#    nomCopy = nominal[boxes:]
#nominal = nominal[0:boxes]


n = len(nominal) / nh

n = min(n, boxes)


import random
random.shuffle(nominal)
if hasMoose or hasCone:
    while True:
        random.shuffle(nominal)
        heat1 = nominal[:n]
        heat2 = nominal[n:(2*n)]
        h1m = False
        h1c = False
        if hasCone:
            for i in heat1:
                if "76561198004896172" in acd[i]:
                    h1c = True
                if "76561198029842017" in acd[i]:
                    h1m = True
            if not h1c or h1m:
                continue

        h1m = False
        h1c = False
        if hasMoose and not hasCone:
            for i in heat2:
                if "76561198004896172" in acd[i]:
                    h1c = True
                if "76561198029842017" in acd[i]:
                    h1m = True
            if not h1m or h1c:
                continue
        
        break



heat1 = nominal[:n]
heat2 = nominal[n:(2*n)]
if(nh == 2 and len(nominal) % 2 == 1) or (nh == 3 and len(nominal) % 3 >= 1):
    heat2 = nominal[n:(2*n+1)]
heat3 = []
if nh == 3:
    off = 0
    if (len(nominal) % 3 >= 1):
        off = 1
    heat3 = nominal[(off + 2*n):]
    ex = 0
    if len(nominal) % 3 == 2:
        ex = 1
    heat3 = heat3[:(n + ex)]

f = open("H1_entry_list.ini", "wb")
k = 0
for i in heat1:
    f.write("[CAR_" + str(k) + "]\n" + acd[i] + "\n\n\n")
    k += 1
f.close()

f = open("H2_entry_list.ini", "wb")
k = 0
for i in heat2:
    f.write("[CAR_" + str(k) + "]\n" + acd[i] + "\n\n\n")
    k += 1
f.close()

if nh == 3:
    f = open("H3_entry_list.ini", "wb")
    k = 0
    for i in heat3:
        f.write("[CAR_" + str(k) + "]\n" + acd[i] + "\n\n\n")
        k += 1
    f.close()

f = open("H_text.txt", "wb")
f.write("**Heat 1/" + str(nh) + "**\n")
for i in heat1:
    f.write(" " + drNames[i][0] + "\n")

f.write("\n\n\n**Heat 2/" + str(nh) + "**\n")
for i in heat2:
    f.write(" " + drNames[i][0] + "\n")

if len(heat3) > 0:
    f.write("\n\n\n**Heat 3/" + str(nh) + "**\n")
for i in heat3:
    f.write(" " + drNames[i][0] + "\n")
f.close()
print "Done"


    
