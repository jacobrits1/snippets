#!/usr/bin/env python

import os
import sys
import datetime

st = datetime.datetime.now()
st = st.strftime('%m%d%Y-%H%M%S')

with open('Translation Keys Unilever Azuri -2019-02-07_1220.csv','r') as in_file, open('Azuri_no_duplicate_translation-' + st + '.csv','w') as out_file:
    seen = set()
    for line in in_file:
        column = line.split(',')
        if column[0] in seen: continue
        seen.add(column[0])
        out_file.write(line)
