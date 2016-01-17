import urllib
import xml.etree.ElementTree as ET
import sys
import MySQLdb
import logging
import sqlite3


logging.basicConfig(filename='fcc.log',format='%(asctime)s %(message)s',level=logging.DEBUG)

#op productie andere directory
dbname ='fcc2.sqlite'
club_id = 'fzxv68x'

#programma(datum,klasse,thuis,uit,scheidsrechter,aanwezig,aanvang) VALUES(%s,%s,%s,%s,%s,%s,%s)"
# voorbeeld list -> cats = ['Tom', 'Snappy', 'Kitty', 'Jessie', 'Chester']
# voorbeeld tuple -> months = ('January','February','March','April','May','June','July','August','September','October','November','  December')
# voorbeeld dictonary -> phonebook = {'Andrew Parson':8806336, 'Emily Everett':6784346, 'Peter Power':7658344, 'Lewis Lame':1122345}

teamlist=(  '1 (zat)','2 (zat)','3 (zat)','4 (zat)','5 (zat)','6 (zat)','7 (zat)',
            '2 (zon)','3 (zon)',
            'A1 (zon)','A2 (zon)','A3 (zon)',
            'B1','B2','B3','B4',
            'C1','C2','C3','C4','C5',
            'D1','D2','D3','D4','D5','O12 1',
            'E1','E2','E3','E4','E5','E6','E7','E8','E9','E10','O10 1',
            'F1','F2','F3','F4','F5','F6','F7',
            'MB1','MC1','MC2','MD1','ME1')

try:
    conn = sqlite3.connect(dbname)
    c = conn.cursor()
except sqlite3.Error, e:
    if conn:
        conn.rollback()
    print "Error %s: "% e.args[0]
    sys.exit(1)

team_dic = {}
db_write_error = False

#------------------------------------------VULLEN DB TABEL TEAM--------------------------
#url = raw_input('Enter location: ')
teamindeling_url = 'http://mijnclub.nu/clubs/teams/xml/%s' % club_id
#url = 'http://mijnclub.nu/clubs/teams/xml/FZXV68X/team/E3'

print 'Retrieving', teamindeling_url,
uh = urllib.urlopen(teamindeling_url)
data = uh.read()
print 'Retrieved',len(data),'characters'
teamstree = ET.fromstring(data)

#print tree.tag
#print tree.attrib

try:
    c.execute("CREATE TABLE IF NOT EXISTS `teams` (`id` text UNIQUE,`knvb_id` text UNIQUE)")
except:
    print 'mislukt'
    logging.debug("ERROR: de table teams kan niet gecreeerd worden, EXIT dit script")
    if conn:
        print 'ERROR: sqlite db geclosed '
        conn.close()
    sys.exit(1)


#deze 2 loops zorgen voor het creeeren van colommen als ze nog niet bestaan
listelements = []
for team in teamstree:
    for teamelement in team:
        listelements.append(teamelement.tag)
        setelements = set(listelements)
for teamelement in setelements:                             #controleer of de column al bestaat, zo niet aanmaken
    try:
        c.execute("SELECT %s FROM `teams`" % (teamelement))
    except:
        print 'bestaat niet, dus colomn aanmaken:',teamelement
        try:
            c.execute("ALTER TABLE teams ADD COLUMN %s text" % (teamelement))
            logging.debug("INFO: de column %s is nieuw en is toegevoegd aan de teams tabel" % teamelement)
        except:
            print 'niet gelukt om column aan te maken:', teamelement

#-------------------het echte vullen----------------------------------------------------------
teamlist = []
for team in teamstree:
    teamproperties={}
    teamproperties['id'] = team.attrib['id']
    teamproperties['knvb_id'] = team.attrib['knvb_id']
    for teamelement in team:
        teamproperties[teamelement.tag]=teamelement.text
    #print teamproperties
    teamlist.append(teamproperties)

#print teamlist

for item in teamlist:
    columns=[]
    values =[]
    for key,value in item.iteritems() :
        #print key,value,
        columns.append(key)
        values.append('\''+value+'\'')
    #print columns,
    #print values
    columnstring = ','.join(str(i) for i in columns)
    valuesstring = ','.join(str(i) for i in values)
    #print stringetje
    #print strangetje

    try:
        sql = "INSERT OR IGNORE INTO teams(%s) VALUES(%s)" % (columnstring,valuesstring)
        #print sql
        c.execute(sql)
        #print 'fuck gelukt'
    except:
        logging.debug('ERROR: niet gelukt om de teamstabel te bewerken')
        db_write_error = True


try:
    if not db_write_error:
        conn.commit()
        logging.debug('gelukt om te commiten naar de db voor de teamstabel')
    else:
        conn.rollback()
        logging.debug('ERROR: rollback gedaan van SQL transactie omdat eerdere query fouten heeft opgeleverd voor teamtabel')
    #print ('gelukt\n')
except:
    db.rollback()
    logging.debug('ERROR: niet gelukt om te committen naar de db voor teamtabel')

#----------------------------------------EINDE TEAMS DB VULLEN------------------------------
#---------------------------------------------------------------------------------------------------------------
#------------------------------------------VULLEN DB TABEL WEDSTRIJDEN--------------------------
#url = raw_input('Enter location: ')
wedstrijden_url = 'http://mijnclub.nu/clubs/speelschema/xml/%s/periode,/' % club_id
#url = 'http://mijnclub.nu/clubs/teams/xml/FZXV68X/team/E3'

print 'Retrieving', wedstrijden_url,
uh = urllib.urlopen(wedstrijden_url)
data = uh.read()
print 'Retrieved',len(data),'characters'
wedstrijdentree = ET.fromstring(data)

#print wedstrijdentree.tag
#print wedstrijdentree.attrib
db_write_error = False

try:
     c.execute("CREATE TABLE IF NOT EXISTS `wedstrijden` (`id` text,'knvb_id' text, 'afgelast' text, 'aanwezig' text)")
     #-------gaat dit goed? alles deleten, commit komt later dus moet goed gaan
     c.execute("DELETE FROM `wedstrijden`")
except:
#     print 'mislukt'
     logging.debug("ERROR: de table wedstrijden kan niet gecreeerd worden, EXIT dit script")
     if conn:
         print 'ERROR: sqlite db geclosed'
         conn.close()
     sys.exit(1)

# #deze 2 loops zorgen voor het creeeren van colommen als ze nog niet bestaan
setelements = set()
for wedstrijd in wedstrijdentree.findall('wedstrijden/wedstrijd'):
    for wedstrijdelement in wedstrijd:
        setelements.add(wedstrijdelement.tag)
#print setelements

for wedstrijdelement in setelements:                             #controleer of de column al bestaat, zo niet aanmaken
    try:
        c.execute("SELECT %s FROM `wedstrijden`" % (wedstrijdelement))
    except:
        print 'bestaat niet, dus colomn aanmaken:',wedstrijdelement
        try:
            c.execute("ALTER TABLE wedstrijden ADD COLUMN %s text" % (wedstrijdelement))
            logging.debug("INFO: de column %s is nieuw en is toegevoegd aan de wedstrijden tabel" % wedstrijdelement)
        except:
            print 'niet gelukt om column aan te maken in wedstrijdentabel:', wedstrijdelement

# #-------------------het echte vullen----------------------------------------------------------
wedstrijdenlist = []
for wedstrijd in wedstrijdentree.findall('wedstrijden/wedstrijd'):
    #print wedstrijd.attrib['id'],wedstrijd.attrib['teamnaam'],wedstrijd.attrib['lokatie']
    #teams.knvb_id
    wedstrijdproperties = {}
    wedstrijdproperties['id'] = wedstrijd.attrib['id']
    try:
        if (wedstrijd.attrib['afgelast']=='ja'):
            #print'afgelast:',wedstrijd.attrib['afgelast']
            wedstrijdproperties['afgelast'] = wedstrijd.attrib['afgelast']
    except:
            wedstrijdproperties['afgelast'] = 'nee'
    c.execute("SELECT knvb_id FROM `teams` WHERE teams.naam=?",(wedstrijd.attrib['teamnaam'],))
    row = c.fetchone()
    if row:
        knvb_id = str(row[0])
    else:
        knvb_id = wedstrijd.attrib['teamnaam']
    #print 'kntje is:',knvb_id
    wedstrijdproperties['knvb_id'] = knvb_id
    for wedstrijdelement in wedstrijd:
        #print wedstrijdelement.tag, wedstrijdelement.text
        wedstrijdproperties[wedstrijdelement.tag] = wedstrijdelement.text
        if (wedstrijdelement.tag=='aanvang'):
            wedstrijdproperties['aanwezig'] = wedstrijdelement.attrib['aanwezig']
            #print wedstrijdelement.attrib['aanwezig']
    #print wedstrijdproperties
    wedstrijdenlist.append(wedstrijdproperties)
#print wedstrijdenlist

for item in wedstrijdenlist:
    columns=[]
    values =[]
    rawlist =()
    for key,value in item.iteritems() :
        #print key,value,
        columns.append(key)
        values.append('\''+value+'\'')
        rawlist= rawlist + (value,)
        #print key,type(value)
    #print rawlist
    #print rawlist,len(rawlist)
    columnstring = ','.join(i for i in columns)
    valuesstring = ','.join(i for i in values)
    vraagtekens ='?'
    i=1
    while (i< len(rawlist)):
        vraagtekens+=',?'
        i+=1
    #print vraagtekens

    try:
        #print rawlist
        #sql = "INSERT INTO wedstrijden (%s) VALUES(%s)" % (columnstring,valuesstring)
        sql = "INSERT INTO wedstrijden (%s) VALUES(%s)" % (columnstring,vraagtekens)
        #print sql,rawlist
        #c.execute("INSERT OR IGNORE INTO wedstrijden(?) VALUES(?)" % (columnstring,)(valuesstring,))
        #c.executemany("INSERT INTO programma(datum,klasse,thuis,uit,scheidsrechter,aanwezig,aanvang,fccteam_id) VALUES(?,?,?,?,?,?,?,?)",data)
        c.execute(sql,rawlist)
        #c.execute(sql)
        #conn.commit()
        #print 'fuck gelukt'
    except:
        print 'niet gelukt'
        logging.debug('ERROR: niet gelukt om de wedstrijdentabel te bewerken')
        db_write_error = True

try:
     if not db_write_error:
         conn.commit()
         logging.debug('gelukt om te committen voor de wedstrijden naar de db')
     else:
         conn.rollback()
         logging.debug('ERROR: rollback gedaan van SQL transactie omdat eerdere query fouten heeft opgeleverd voor de wedstrijden')
     #print ('gelukt\n')
except:
     db.rollback()
     logging.debug('ERROR: niet gelukt om te committen naar de db')
#------------------------------------------EINDE VULLEN DB TABEL WEDSTRIJDEN---------------------------------------
#------------------------------------------------------------------------------------------------------------------
#_________________________________________AANMAKEN VAN TABELLEN ALS DEZE NOG NIET BESTAAN--------------------------
try:
    c.execute("CREATE TABLE IF NOT EXISTS `competitie` (\
      `nr` TEXT,\
      `team` TEXT,\
      `gespeeld` TEXT,\
      `gewonnen` TEXT,\
      `gelijk` TEXT,\
      `verloren` TEXT,\
      `punten` TEXT,\
      `voor` TEXT,\
      `tegen` TEXT,\
      `verschil` TEXT,\
      `penaltypunten` TEXT,\
      `knvb_id` TEXT NOT NULL\
     )")
    c.execute("CREATE TABLE IF NOT EXISTS `beker` (\
        `nr` TEXT,\
        `team` TEXT,\
        `gespeeld` TEXT,\
        `gewonnen` TEXT,\
        `gelijk` TEXT,\
        `verloren` TEXT,\
        `punten` TEXT,\
        `voor` TEXT,\
        `tegen` TEXT,\
        `verschil` TEXT,\
        `penaltypunten` TEXT,\
        `knvb_id` TEXT NOT NULL\
        )")
    c.execute("CREATE TABLE IF NOT EXISTS `uitslag` (\
        `id` TEXT,\
        `uitslag` TEXT,\
        `lokatie` TEXT,\
        `afgelast` TEXT,\
        `verslag` TEXT,\
        `datum` TEXT,\
        `soort` TEXT,\
        `thuisteam` TEXT,\
        `uitteam` TEXT,\
        `knvb_id` TEXT NOT NULL\
        )")
     #-------gaat dit goed? alles deleten, commit komt later dus moet goed gaan
except:
#     print 'mislukt'
     logging.debug("ERROR: de table competitie kan niet gecreeerd worden")

#----------------------------------------EINDE AANMAKEN TABELLEN



#voor uitslagen:http://mijnclub.nu/clubs/uitslagen/xml/fzxv68x/?team=O10%201&periode=SEIZOEN&seizoen=8
#http://mijnclub.nu/clubs/teams/xml/FZXV68X/team/O10%201?layout=stand&stand=1
#http://mijnclub.nu/clubs/teams/embed/fzxv68x/team/1%20%28zat%29?layout=stand&stand=1&format=xml
#verslag = http://mijnclub.nu/clubs/wedstrijdverslagen/FZXV68X/wedstrijd/2421886?tmpl=component&layout=detail
#DONE: PROGRAMMA; TO DO: STAND, STAND BEKER, UITSLAGEN

#----------------------------PER TEAM INVULLEN: STAND, STAND BEKER, UITSLAGEN
team_dic = {}
db_write_error = False
try:
    c.execute("SELECT knvb_id,naam FROM teams") #LIMIT 5 op het einde voor DEBUG doeleinden
    teams = c.fetchall()
except:
    pass
for team in teams:
    print team[0],team[1]
    team_dic[str(team[0])] = str(team[1])
#------------------------------------------------HOOFDLOOP om de 3 tabellen in te vullen
for knvb_id,teamnaam in team_dic.iteritems():
    #print teamnaam,knvb_id

    url = 'http://mijnclub.nu/clubs/teams/embed/%s/team/%s?layout=stand&stand=1&format=xml' % (club_id,teamnaam) #stand
    url2 = 'http://mijnclub.nu/clubs/uitslagen/xml/%s/?team=%s&periode=SEIZOEN&seizoen=8' % (club_id,teamnaam) #uitslag
    url3 = 'http://mijnclub.nu/clubs/teams/xml/%s/team/%s' % (club_id,teamnaam) #bekerstand
    #url = 'http://mijnclub.nu/clubs/teams/xml/FZXV68X/team/E3'

    print 'Retrieving standurl', url,
    uh = urllib.urlopen(url)
    data = uh.read()
    print 'Retrieved',len(data),'characters'
    #print data
    standtree = ET.fromstring(data)
    print 'Retrieving uitslagurl', url2,
    uh = urllib.urlopen(url2)
    data = uh.read()
    print 'Retrieved',len(data),'characters'
    #print data
    uitslagtree = ET.fromstring(data)
    print 'Retrieving bekerurl', url3,
    uh = urllib.urlopen(url3)
    data = uh.read()
    print 'Retrieved',len(data),'characters'
    #print data
    try:
        bekertree = ET.fromstring(data)
        bekertreeopgehaald = True
    except:
        bekertreeopgehaald = False
        logging.debug("ERROR: kan bekertree voor team %s niet inladen waarschijnlijk omdat de url niet goed is, url %s" % (teamnaam,url3))


    data=[]
    # #print 'deleten van team', fccteam,team_dic[fccteam]
    try:
        c.execute("DELETE FROM competitie WHERE knvb_id=? AND EXISTS (SELECT 1 FROM competitie WHERE knvb_id=?)",(knvb_id,knvb_id))
        c.execute("DELETE FROM uitslag WHERE knvb_id=? AND EXISTS (SELECT 1 FROM uitslag WHERE knvb_id=?)",(knvb_id,knvb_id))
        if bekertreeopgehaald==True:
            c.execute("DELETE FROM beker WHERE knvb_id=? AND EXISTS (SELECT 1 FROM beker WHERE knvb_id=?)",(knvb_id,knvb_id))
            #db.commit()
        logging.debug("db  geleegd voor team %s met knvb_id %s" % (teamnaam,knvb_id))
    except:
        #db.rollback()
        logging.debug("ERROR: db geleegd niet gelukt voor team %s met knvb_id %s" % (teamnaam,knvb_id))
        #db_write_error = True

    try:
        standtree_diep = standtree.findall('table/tbody/tr')
        gevonden = True
    except:
        gevonden = False
    if (gevonden):
        for td in standtree_diep:
            #print standpositie
            for standprops in td:
                #print standprops.attrib['class'],standprops.text
                if (standprops.get("class") == 'nr'):
                    nr = standprops.text
                if (standprops.get("class") == 'team'):
                    team = standprops.text
                if (standprops.get("class") == 'played'):
                    gespeeld = standprops.text
                if (standprops.get("class") == 'wins'):
                    gewonnen = standprops.text
                if (standprops.get("class") == 'draws'):
                    gelijk = standprops.text
                if (standprops.get("class") == 'losses'):
                    verloren = standprops.text
                if (standprops.get("class") == 'points'):
                    punten = standprops.text
                if (standprops.get("class") == 'for'):
                    voor = standprops.text
                if (standprops.get("class") == 'against'):
                    tegen = standprops.text
                if (standprops.get("class") == 'difference'):
                    verschil = standprops.text
                if (standprops.get("class") == 'penaltypoints'):
                    penaltypunten = standprops.text

            #print nr, team, gespeeld, gewonnen, gelijk, verloren,punten,voor,tegen,verschil,penaltypunten,knvb_id

                                #print("Datum: %s Wedstrijd: %s Uistlag: %s" % (datum,wedstrijd,uitslag))
            data.append((nr, team, gespeeld, gewonnen, gelijk, verloren,punten,voor,tegen,verschil,penaltypunten,knvb_id))

    try:
        #print data
    #    stmt="INSERT INTO competitie(nr,team,gespeeld,gewonnen,gelijk,verloren,punten,voor,tegen,verschil,penaltypunten) \
    #        VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
    #    cursor.executemany(stmt,data)
        c.executemany("INSERT INTO competitie(nr,team,gespeeld,gewonnen,gelijk,verloren,punten,voor,tegen,verschil,penaltypunten,knvb_id) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)",data)

        logging.debug("gelukt om COMPETITIE naar de database te schrijven voor team %s" % teamnaam)
    #    db.commit()
    #    print ('gelukt\n')
    except:
         db_write_error = True
         logging.debug('ERROR: niet gelukt om COMPETITIE naar de database te schrijven')
    #    print('niet gelukt\n')
#------------------------------------------------------------------COMPETITIE(stand) ingelezen Nu uitslagen
    data=[]
    try:
        uitslagtree_diep = uitslagtree.findall('wedstrijd')
        gevonden = True
    except:
        gevonden = False
    if (gevonden):
        for wedstrijddetails in uitslagtree_diep:
            wed_id = wedstrijddetails.attrib['id']
            lokatie = wedstrijddetails.attrib['lokatie']
            #teamnaam = wedstrijddetails.attrib['teamnaam']
            #team_id = wedstrijddetails.attrib['team_id']
            try:
                if (wedstrijddetails.attrib['afgelast']=='ja'):
                    afgelast = 'ja'
            except:
                afgelast = 'nee'
            try:
                uitslag = (wedstrijddetails.find('uitslag')).text
            except:
                uitslag = '-'
                continue
            verslag = wedstrijddetails.attrib['verslag']
            datum = (wedstrijddetails.find('datum')).text
            soort = (wedstrijddetails.find('soort')).text
            thuisteam = (wedstrijddetails.find('thuisteam')).text
            uitteam = (wedstrijddetails.find('uitteam')).text

            data.append((wed_id,uitslag,lokatie,afgelast,verslag,datum,soort,thuisteam,uitteam,knvb_id))

        try:
            #print data
            c.executemany("INSERT INTO uitslag(id,uitslag,lokatie,afgelast,verslag,datum,soort,thuisteam,uitteam,knvb_id) VALUES(?,?,?,?,?,?,?,?,?,?)",data)

            logging.debug("gelukt om UITSLAG naar de database te schrijven voor team %s" % teamnaam)
            conn.commit()
        #    db.commit()
        #    print ('gelukt\n')
        except:
            logging.debug('ERROR: niet gelukt om UITSLAG naar de database te schrijven')
            conn.rollback()
        #print('niet gelukt\n')

    #---------------------------------------------------EINDE uitslag VULLEN
    #-------------------------------------------nu de beker
    data=[]
    try:
        if (bekertreeopgehaald==False):
            raise
        beker_diep = bekertree.findall("dl/dd/div/")
        #print 'gevonden'
        gevonden = True
    except:
        gevonden = False

    if (gevonden):
        #print repr(beker_diep)

        for div in beker_diep:
            #print repr(jan)
            if (div.get('id')=='content_bekerstand'):
                for table in div:
                    for tbody in table:
                        if (tbody.tag=="tbody"):
                            for tds in tbody:
                                for td in tds:
                                    if (td.get("class") == 'nr'):
                                        nr = td.text
                                    if (td.get("class") == 'team'):
                                        team = td.text
                                    if (td.get("class") == 'played'):
                                        gespeeld = td.text
                                    if (td.get("class") == 'wins'):
                                        gewonnen = td.text
                                    if (td.get("class") == 'draws'):
                                        gelijk = td.text
                                    if (td.get("class") == 'losses'):
                                        verloren = td.text
                                    if (td.get("class") == 'points'):
                                        punten = td.text
                                    if (td.get("class") == 'for'):
                                        voor = td.text
                                    if (td.get("class") == 'against'):
                                        tegen = td.text
                                    if (td.get("class") == 'difference'):
                                        verschil = td.text
                                    if (td.get("class") == 'penaltypoints'):
                                        penaltypunten = td.text
                                #print nr, team, gespeeld, gewonnen, gelijk, verloren,punten,voor,tegen,verschil,penaltypunten,knvb_id
                                data.append((nr, team, gespeeld, gewonnen, gelijk, verloren,punten,voor,tegen,verschil,penaltypunten,knvb_id))

        try:
            #print data
            c.executemany("INSERT INTO beker(nr,team,gespeeld,gewonnen,gelijk,verloren,punten,voor,tegen,verschil,penaltypunten,knvb_id) \
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?)",data)
            logging.debug("gelukt om BEKER naar de database te schrijven voor team %s" % teamnaam)
            conn.commit()
        #    print ('gelukt\n')
        except:
             #db_write_error = True
             logging.debug('ERROR: niet gelukt om BEKER naar de database te schrijven voor %s' % teamnaam)
             conn.rollback()
        #    print('niet gelukt\n')



#---------------------------------------------------EINDE VULLEN UITSLAG,STAND,STANDBEKER
#----------------------------------------------------------------------------------------------
if conn:
    print 'sqlite db geclosed'
    conn.close()
