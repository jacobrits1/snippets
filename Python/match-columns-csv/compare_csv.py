from datetime import datetime

with open('receipt_201903010510.csv', 'r') as t1, open('receipt_after_script.csv', 'r') as t2:
    fileone = t1.readlines()
    filetwo = t2.readlines()

with open('update-' + datetime.now().strftime('%Y%m%d-%H%M%S') + '.csv', 'w') as outFile:
    for line in filetwo:
        linetwo = line.split(',')
        outFile.write(line)
        for line1 in fileone:
            lineone = line1.split(',')
            if linetwo[0] == lineone[0]:
                outFile.write(line1)
