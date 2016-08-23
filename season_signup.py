import sys
import os
import os.path

f = open("numbers.csv", "rb")

numbName = []

for line in f.read().split("\n"):
    numbName.append(line.split(",")[1:3])
    #print str(line.split(",")[1:3])

f.close()

f = open("season3final.csv", "rb")

content = f.read()

f.close()

lines = content.split("\n")[1:]

startBallast = 50
car = "ks_mazda_mx5_cup"

for line in lines:
    data = line.split(",")
    guid = data[2].replace("\"", "").replace(" ", "")
    name = data[1].replace("\"", "")
    
    if os.path.isfile("drivers/" + guid + ".acd"):
        print "File found for " + name + ", " + guid
        continue
    
    number = -1
    for nn in numbName:
        if len(nn) < 2:
            continue
        if nn[1].lower().replace("\"", "").replace(" ", "") == name.lower().replace(" ", ""):
            number = int(nn[0])
            print "Found number for " + name + ", " + str(number)
    if number == -1:
        number = int(raw_input("No number for " + name + " found, what is it: "))
    
    print guid + ", " + name + ", " + str(number)
    
    f = open("drivers/" + guid + ".acd", "wb")
    f.write("MODEL=" + car + "\n")
    f.write("SKIN=" + str(number) + "\n")
    f.write("SPECTATOR_MODE=0\n")
    f.write("DRIVERNAME=" + name + "\n")
    f.write("TEAM=\n")
    f.write("GUID=" + guid + "\n")
    f.write("BALLAST=" + str(startBallast) + "\n\n\n")
    f.write("ATTENDANCE=\n")
    f.close()

#os.system("chown -R www-data:www-data /var/www/html/ac_signup/drivers/*")
print "Done"