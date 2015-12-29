import urllib
import xml.etree.ElementTree as ET
import sys
import MySQLdb
import logging
import sqlite3


logging.basicConfig(filename='/home/dennis/example.log',format='%(asctime)s %(message)s',level=logging.DEBUG)

#op productie andere directory
dbname ='fcc.sqlite'


#try:
#    conn = sqlite3.connect('/home/dennis/fcc.sqlite')
#    c = conn.cursor()
#    c.execute('SELECT SQLITE_VERSION()')
#    data = c.fetchone()
#    print "SQLite version %s"% data
#except sqlite3.Error, e:
#    print "Error %s:" % e.args[0]
#    sys.exit(1)

#programma(datum,klasse,thuis,uit,scheidsrechter,aanwezig,aanvang) VALUES(%s,%s,%s,%s,%s,%s,%s)"
# voorbeeld list -> cats = ['Tom', 'Snappy', 'Kitty', 'Jessie', 'Chester']
# voorbeeld tuple -> months = ('January','February','March','April','May','June','July','August','September','October','November','  December')
# voorbeeld dictonary -> phonebook = {'Andrew Parson':8806336, 'Emily Everett':6784346, 'Peter Power':7658344, 'Lewis Lame':1122345}

try:
    conn = sqlite3.connect(dbname)
    c = conn.cursor()
except sqlite3.Error, e:
    if conn:
        conn.rollback()
    print "Error %s: "% e.args[0]
    sys.exit(1)

db_write_error = False

teamlist=(  '1 (zat)','2 (zat)','3 (zat)','4 (zat)','5 (zat)','6 (zat)','7 (zat)',
            '2 (zon)','3 (zon)',
            'A1 (zon)','A2 (zon)','A3 (zon)',
            'B1','B2','B3','B4',
            'C1','C2','C3','C4','C5',
            'D1','D2','D3','D4','D5','O12 1',
            'E1','E2','E3','E4','E5','E6','E7','E8','E9','E10','O10 1',
            'F1','F2','F3','F4','F5','F6','F7',
            'MB1','MC1','MC2','MD1','ME1')

print "Ophalen gegevens voor de FC teams:",
for fccteamnog in teamlist:
    print fccteamnog,
print " "
#fccteam=teamlist[1]
#print fccteam

#HOOFDLOOP
for fccteam in teamlist:

    #db = MySQLdb.connect(host="localhost", # your host, usually localhost
    #                     user=username, # your username
    #                      passwd=password, # your password
    #                      db=dbname) # name of the data base
    #cursor = db.cursor()

    #url = raw_input('Enter location: ')
    url = 'http://mijnclub.nu/clubs/teams/xml/FZXV68X/team/%s' % fccteam
    #url = 'http://mijnclub.nu/clubs/teams/xml/FZXV68X/team/E3'

    print 'Retrieving', url,
    uh = urllib.urlopen(url)
    data = uh.read()
    print 'Retrieved',len(data),'characters'
    #print data
    tree = ET.fromstring(data)

    data=[]
    try:
        #stmt="DELETE * FROM %s"
        #cursor.executemany(stmt,data)
    #    cursor.execute('DELETE FROM programma')
    #    cursor.execute('DELETE FROM uitslag')
    #    cursor.execute('DELETE FROM competitie')
    #    cursor.execute('DELETE FROM beker')
        c.execute("DELETE FROM programma WHERE fccteam=?",(fccteam,))
        c.execute("DELETE FROM uitslag  WHERE fccteam=?",(fccteam,))
        c.execute("DELETE FROM competitie  WHERE fccteam=?",(fccteam,))
        c.execute("DELETE FROM beker  WHERE fccteam=?",(fccteam,))
        #db.commit()
        logging.debug("db  geleegd voor team %s" % fccteam)
    except:
        #db.rollback()
        logging.debug("ERROR: db geleegd niet gelukt voor team %s" % fccteam)
        db_write_error = True

#----------------check eerst juiste xml tag nr , daarna vul programma----------------------------------
    data=[]
    for idx,item in enumerate(tree):
        if ((item.tag=='dl') ):  #& (item.attrib['class']=='tabs')
            try:
                if (item.attrib['class']=='tabs'): #print getattr(item,'class')
                    if(item.attrib['id']=='menu-pane'):
                        hoofdelementnr = idx
                    else:
                        print "GAAT FOUT, item.tag dl met class=tabs bestaat, maar id=menu-pane niet"
                else:
                    print "GAAT FOUT, item.tag dl bestaat maar class=tabs niet"
            except AttributeError:
                print "foutje"


    try:
        sections = tree[hoofdelementnr][1][0][1] # hoofdelementnr moet tag dl zijn met attribute class=tabs en is=menu-pane ,1=dd,0=table,1=tbody,
        gevonden=True
    except:
        print "Niks gevonden voor het programma van team %s" % fccteam

        gevonden=False



    if (gevonden==True):
        for wedstrijd in sections:
            for l in wedstrijd:
                if (l.get("class") == 'datum'):
                    datum = l.text
                if (l.get("class") == 'soort klasse'):
                    klasse = l.text
                if (l.get("class") == 'thuisteam'):
                    thuisteam = l.text
                if (l.get("class") == 'uitteam'):
                    uitteam = l.text
                if (l.get("class") == 'scheidsrechter'):
                    scheidsrechter = l.text
                if (l.get("class") == 'aanvang'):
                    aanvang = l.text
                if (l.get("class") == 'aanwezig'):
                    aanwezig = l.text

            #print "datum:\t\t",datum
            #print "soort klasse: \t",klasse
            #print "thuisteam: \t",thuisteam
            #print "uitteam: \t",uitteam
            if (scheidsrechter == None):
                scheidsrechter = "niet bekend"
            #print "scheidsrechter:\t",scheidsrechter
            #print "aanwezig: \t",aanwezig
            #print "aanvang: \t",aanvang
            #print "\n"

            data.append((datum,klasse,thuisteam,uitteam,scheidsrechter,aanwezig,aanvang,fccteam))
        #print data
        try:
            #print sql
            #print data
        #    stmt="INSERT INTO programma(datum,klasse,thuis,uit,scheidsrechter,aanwezig,aanvang) VALUES(%s,%s,%s,%s,%s,%s,%s)"
            #cursor.executemany(stmt,data)

            #c.executemany("INSERT INTO programma(datum,klasse,thuis,uit,scheidsrechter,aanwezig,aanvang) VALUES(?,?,?,?,?,?,?)",data)
        #    cursor.executemany(stmt,data)
            c.executemany("INSERT INTO programma(datum,klasse,thuis,uit,scheidsrechter,aanwezig,aanvang,fccteam) VALUES(?,?,?,?,?,?,?,?)",data)
            logging.debug("gelukt om PROGRAMMA naar de database te schrijven voor team %s" % fccteam)

            #conn.commit()
            #db.commit()
        except:
            db_write_error = True
            logging.debug('ERROR: niet gelukt om PROGRAMMA naar de database te schrijven')
            #db.rollback()


#-------------------------------------- vul uitslag--------------------------------------------------------------------

    data=[]
    try:
        sections = tree[hoofdelementnr][3][0][1] #hoofdelementnr moet tag dl zijn met attribute class=tabs en is=menu-pane,3=dd,0=table,1=tbody,
        #print sections
        gevonden=True
    except:
        print "Niks gevonden voor de uitslag van team %s" % fccteam
        gevonden=False

    #print "gevonden",gevonden
    if (gevonden):
        for wedstrijd in sections:
            for idx,l in enumerate(wedstrijd):
                if idx==0:
                    datum=l.text
                if idx==1:
                    wedstrijd=l.text
                if idx==2:
                     uitslag=l.text

            #print("Datum: %s Wedstrijd: %s Uistlag: %s" % (datum,wedstrijd,uitslag))
            data.append([datum,wedstrijd,uitslag,fccteam])

        try:
            #print data
        #    stmt="INSERT INTO uitslag(datum,wedstrijd,uitslag) VALUES(%s,%s,%s)"
        #    cursor.executemany(stmt,data)
            c.executemany("INSERT INTO uitslag(datum,wedstrijd,uitslag,fccteam) VALUES(?,?,?,?)",data)
            logging.debug("gelukt om UITSLAG naar de database te schrijven voor team %s" % fccteam)
        #    conn.commit()
        #    db.commit()
        #    print ('gelukt\n')
        except:
            #db.rollback()
            db_write_error = True
            logging.debug('ERROR: niet gelukt om UITSLAG naar de database te schrijven')
        #    print('niet gelukt\n')


#-----------------------------------------------------vul competitie-----------------------------------
    data=[]

    try:
        sections = tree[hoofdelementnr][5][3][0]
        dezetreebestaat = True
    except:
        dezetreebestaat = False
        print "Niks gevonden voor de competitie van team %s" % fccteam

    if dezetreebestaat:
        try:
            if (tree[hoofdelementnr][5][3][0].attrib['class']=='ranking'): #print getattr(item,'class')
                #print "hallo class is ranking"
                comp_soort=0
        except AttributeError:
            print "foutje"
        try:
            if (tree[hoofdelementnr][5][3][0].attrib['class']=='periodetitel'): #print getattr(item,'class')
                #print "hallo class is periodetitel"
                comp_soort=1
        except AttributeError:
            print "foutje"

        if (comp_soort==None):
            print "geen class ranking of periodetitel gevonden"
            exit()
        #if tree[hoofdelementnr][5][3][0].attrib['class=']

        try:
            sections = tree[hoofdelementnr][5][3][comp_soort][2] #jjj,5=dd,3=div,0=table,2=tbody,
            gevonden=True
        except:
            print "Niks gevonden voor de competitie van team %s" % fccteam
            gevonden=False
        if (gevonden):
            for stand in sections:
                for l in stand:
                    if (l.get("class") == 'nr'):
                        nr = l.text
                    if (l.get("class") == 'team'):
                        team = l.text
                    if (l.get("class") == 'played'):
                        gespeeld = l.text
                    if (l.get("class") == 'wins'):
                        gewonnen = l.text
                    if (l.get("class") == 'draws'):
                        gelijk = l.text
                    if (l.get("class") == 'losses'):
                        verloren = l.text
                    if (l.get("class") == 'points'):
                        punten = l.text
                    if (l.get("class") == 'for'):
                        voor = l.text
                    if (l.get("class") == 'against'):
                        tegen = l.text
                    if (l.get("class") == 'difference'):
                        verschil = l.text
                    if (l.get("class") == 'penaltypoints'):
                        penaltypunten = l.text

                #print nr, team, gespeeld, gewonnen, gelijk, verloren,punten,voor,tegen,verschil,penaltypunten

                #print("Datum: %s Wedstrijd: %s Uistlag: %s" % (datum,wedstrijd,uitslag))
                data.append((nr, team, gespeeld, gewonnen, gelijk, verloren,punten,voor,tegen,verschil,penaltypunten,fccteam))

            try:
                #print data
            #    stmt="INSERT INTO competitie(nr,team,gespeeld,gewonnen,gelijk,verloren,punten,voor,tegen,verschil,penaltypunten) \
            #        VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
            #    cursor.executemany(stmt,data)
                c.executemany("INSERT INTO competitie(nr,team,gespeeld,gewonnen,gelijk,verloren,punten,voor,tegen,verschil,penaltypunten,fccteam) \
                    VALUES(?,?,?,?,?,?,?,?,?,?,?,?)",data)
                logging.debug("gelukt om COMPETITIE naar de database te schrijven voor team %s" % fccteam)
            #    db.commit()
            #    print ('gelukt\n')
            except:
                 db_write_error = True
                 logging.debug('ERROR: niet gelukt om COMPETITIE naar de database te schrijven')
            #    print('niet gelukt\n')


#-------------------------vullen competitie----------------------------------------------------------------------------
    data=[]

    try:
        sections = tree[hoofdelementnr][7][3][0][2]# hoofdelementnr moet tag dl zijn met attribute class=tabs en is=menu-pane ,1=dd,0=table,1=tbody,
        gevonden=True
    except:
        print "Niks gevonden in de competitie van team %s" % fccteam
        gevonden=False

    if(gevonden):
        for stand in sections:
            for l in stand:
                if (l.get("class") == 'nr'):
                    nr = l.text
                if (l.get("class") == 'team'):
                    team = l.text
                if (l.get("class") == 'played'):
                    gespeeld = l.text
                if (l.get("class") == 'wins'):
                    gewonnen = l.text
                if (l.get("class") == 'draws'):
                    gelijk = l.text
                if (l.get("class") == 'losses'):
                    verloren = l.text
                if (l.get("class") == 'points'):
                    punten = l.text
                if (l.get("class") == 'for'):
                    voor = l.text
                if (l.get("class") == 'against'):
                    tegen = l.text
                if (l.get("class") == 'difference'):
                    verschil = l.text
                if (l.get("class") == 'penaltypoints'):
                    penaltypunten = l.text

            #print nr, team, gespeeld, gewonnen, gelijk, verloren,punten,voor,tegen,verschil,penaltypunten

            #print("Datum: %s Wedstrijd: %s Uistlag: %s" % (datum,wedstrijd,uitslag))
            data.append((nr, team, gespeeld, gewonnen, gelijk, verloren,punten,voor,tegen,verschil,penaltypunten,fccteam))

        try:
            #print data
        #    stmt="INSERT INTO beker(nr,team,gespeeld,gewonnen,gelijk,verloren,punten,voor,tegen,verschil,penaltypunten) \
        #        VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
        #    cursor.executemany(stmt,data)
            c.executemany("INSERT INTO beker(nr,team,gespeeld,gewonnen,gelijk,verloren,punten,voor,tegen,verschil,penaltypunten,fccteam) \
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?)",data)
            logging.debug("gelukt om BEKER naar de database te schrijven voor team %s" % fccteam)
        #    db.commit()
        #    print ('gelukt\n')
        except:
             db_write_error = True
             logging.debug('ERROR: niet gelukt om BEKER naar de database te schrijven')
        #    print('niet gelukt\n')

    #db_write_error = True

    #print "db_write_error:",db_write_error

    try:
        if not db_write_error:
            conn.commit()
            #        db.commit()
            #print "commitje"
            logging.debug('gelukt om te commiten naar de db')
        else:
            conn.rollback()
    #        db.rollback()
            logging.debug('ERROR: rollback gedaan van SQL transactie omdat eerdere query fouten heeft opgeleverd')
        #print ('gelukt\n')
    except:
    #    db.rollback()
        logging.debug('ERROR: niet gelukt om te committen naar de db')

if conn:
    print 'sqlite db geclosed'
    conn.close()

    #db.close()
        #print i.items()

    #print (tree.getelementpath(a[0]))
    #for i in jan:
    #    print i.tag
    #    print i.items()
    #print (tree.getelementpath)
    #print tree._children
    #for child in tree:
        #print child.tag
        #if (child.tag =='dl'):
            #for child2 in tree.iter():
            #    print child2.tag

    #for item in lst:
        #print item.tag
        #print item.items()
        #print item.keys()
    #    for deeltje in item:
    #        if (deeltje.get("class")) :
    #            print deeltje.get("class"),": ",deeltje.text

        #lst2 = lst.findall(".//tr")
    #print list(tree)
        #for item2 in lst2:
        #    print item2.keys
        #print 'leiders: ', item.attrib.get["id"]
    #for item in lst:
        #print 'name: ', item.find('name').text
        #print 'count: ', item.find('count')
        #sum = sum + int(item.text)
        #sum = sum + int(item.find('count').text)

    #print 'Count: ', len(lst)
    #print 'Sum: ', sum
    #results = tree.findall('result')
    #lat = results[0].find('geometry').find('location').find('lat').text
    #lng = results[0].find('geometry').find('location').find('lng').text
    #location = results[0].find('formatted_address').text

    #sql = "INSERT INTO programma(datum,klasse,thuis,uit,scheidsrechter,aanwezig,aanvang) \
    #VALUES('%s','%s','%s','%s','%s','%s','%s');" % (datum,klasse,thuisteam,uitteam,scheidsrechter,aanwezig,aanvang)
    #k = ("ADO'20 O-10",)
    #cursor.execute("SELECT * FROM programma WHERE uit= %s",k)
    #print cursor.fetchone()
