# FCC

Deze software zorgt voor een website voor je voetbalclub. De website is gebaseerd op een responsive design en haalt zijn gegevens van mijnclub.nu via xml. Met python wordt een sqlite database gevuld die de site met php leest. Check fc-castricum.nl hoe je site er uit kan zien. Mocht je vragen hebben stel die hier. **Groet en happy coding! Dennis**

## Quick install instructions:

---

1. copieer de de php file in de directory van je apache server (andere kan ook, als ie maar php ondersteunt)
2. zet de python file in een directory die je zelf uitkiest (b.v. /usr/lib/fcc)
3. dan creeer je de database op basis van de sqlfile. Bijvoorbeeld: sqlite3 fcc.sqlite << create-fccdb.sql
4. controleer de db en structuur
5. run dan: python fcc.py
6. controleer of de db gevuld is
7. browse naar de website met de php file en als alles goed gaat dan heb je een responsive website voor je voetbalclub

---
