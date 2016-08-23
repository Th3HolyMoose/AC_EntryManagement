ballastGain = [15, 10, 5]
ballastLose = [-5, -10, -15, -20, -25, -30]
ballastMin = 0
ballastMax = 150
def getBallastDelta(ball, pos, num):
    delta = 0
    
    bgi = pos - 1
    if bgi >= 0 and bgi < len(ballastGain):
        delta += ballastGain[bgi]
    
    bli = len(ballastLose) - (num - pos) - 1
    if bli >= 0 and bli <= len(ballastLose):
        delta += ballastLose[bli]
    
    nb = ball + delta
    if nb < ballastMin:
        nb = ballastMin
    if nb > ballastMax:
        nb = ballastMax
    return nb