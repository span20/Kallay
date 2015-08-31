function initArray() {  
  this.length = initArray.arguments.length  
  for (var i = 0; i < this.length; i++)  
  this[i+1] = initArray.arguments[i]  
}  
  
function havinev(ev,ho,nap) {  
if (ho==1)  
  { var napok = new initArray("ÚJÉV","Ábel","Benjámin", 
        "Titusz","Simon","Boldizsár","Attila, Ramóna","Gyöngyvér","Marcell",  
        "Melánia","Ágota","Ernõ","Veronika","Bódog","Lóránt",  
        "Gusztáv","Antal","Piroska","Sára, Márió","Fábián",  
        "Ágnes","Vince","Rajmund","Timót","Pál","Vanda",  
        "Angelika","Károly,","Adél","Martina","Marcella","") }  
if (ho==2)  
  if ((ev!=2008) && (ev!=2012) && ev!=2016)  
    { var napok=new initArray("Ignác","Karolina","Balázs","Ráhel", 
        "Ágota","Dorottya","Tódor","Aranka","Abigél","Elvira",  
        "Marietta","Lívia","Ella","Valentin",  
        "Kolos","Julianna","Donát","Bernadett","Zsuzsanna",  
        "Aladár","Eleonóra","Gerzson","Alfréd",  
        "Mátyás","Géza","Edina","Ákos, Bátor","Elemér","","")  }  
     else  
    { var napok=new initArray("Ignác","Karolina","Balázs","Ráhel", 
        "Ágota","Dorottya","Rómeó","Aranka","Abigél","Elvira",  
        "Marietta","Lívia","Ella","Valentin",  
        "Kolos","Julianna","Donát","Bernadett","Zsuzsanna",  
        "Aladár","Eleonóra","Gerzson","Alfréd",  
        "Szökõnap","Mátyás","Géza","Edina","Ákos, Bátor","Elemér","","") }  
if (ho==3)  
  { var napok=new initArray("Albin","Lujza","Kornélia","Kázmér","Adorján", 
        "Leonóra","Tamás","Zoltán","Franciska","Ildikó", 
        "Szilárd","Gergely","Krisztián","Matild","Kristóf",  
        "Henrietta","Gertrúd","Sándor","József","Klaudia",  
        "Benedek","Beáta","Emõke","Gábor","Irén",  
        "Emánuel","Hajnalka","Gedeon","Auguszta","Zalán","Árpád","" ) }  
if (ho==4)  
  { var napok=new initArray("Hugó","Áron","Richárd","Izidor","Vince", 
        "Vilmos","Herman","Dénes","Erhard","Zsolt","Leó","Gyula", 
        "Ida","Tibor","Anasztázia","Csongor","Rudolf","Andrea","Emma",  
        "Tivadar","Konrád","Csilla","Béla","György","Márk","Ervin",  
        "Zita","Valéria","Péter","Katalin, Kitti","" )}  
if (ho==5)  
  { var napok=new initArray("Fülöp","Zsigmond","Tímea", 
        "Mónika","Györgyi","Ivett","Gizella","Mihály","Gergely", 
        "Ármin","Ferenc","Pongrác","Szervác","Bonifác","Zsófia",  
        "Mózes","Paszkál","Erik","Ivó, Milán",  
        "Bernát","Konstantin","Júlia, Rita","Dezsõ","Eszter",  
        "Orbán","Fülöp","Hella","Emil","Magdolna",  
        "Zsanett","Angéla","" )}  
if (ho==6)  
  { var napok=new initArray("Tünde","Anita","Klotild","Bulcsú","Fatime", 
        "Norbert","Róbert","Medárd","Félix","Margit","Barnabás", 
        "Villõ","Antal","Vazul","Jolán","Jusztin","Laura",  
        "Levente","Gyárfás","Rafael","Alajos","Paulina",  
        "Zoltán","Iván","Vilmos","János","László","Levente",  
        "Péter, Pál","Pál","" ) }  
if (ho==7)  
  { var napok=new initArray("Tihamér","Ottó","Kornél","Ulrik", 
        "Sarolta","Csaba","Appolónia","Ellák","Lukrécia","Amália",  
        "Nóra","Izabella","Jenõ","Õrs","Henrik","Valter",  
        "Endre","Frigyes","Emília","Illés","Dániel",  
        "Magdolna","Lenke","Kinga, Kincsõ","Kristóf, Jakab","Anna, Anikó",  
        "Olga","Szabolcs","Márta","Judit","Oszkár","" )}  
if (ho==8)  
  { var napok=new initArray("Boglárka","Lehel","Hermina","Domonkos", 
        "Krisztina","Berta","Ibolya","László","Emõd","Lörinc",  
        "Zsuzsanna","Klára","Ipoly","Marcell","Mária","Ábrahám",  
        "Jácint","Ilona","Huba","István","Sámuel",  
        "Menyhért","Bence","Bertalan","Lajos","Izsó",  
        "Gáspár","Ágoston","Beatrix","Rózsa","Erika") }  
if (ho==9)  
  { var napok= new initArray("Egon","Rebeka","Hilda","Rozália", 
        "Viktor","Zakariás","Regina","Mária","Ádám","Nikolett",  
        "Teodóra","Mária","Kornél","Szeréna","Enikõ","Edit",  
        "Zsófia","Diána","Vilhelmina","Friderika","Máté,","Móric",  
        "Tekla","Gellért","Eufrozina","Jusztina","Adalbert",  
        "Vencel","Mihály","Jeromos","" )}  
if (ho==10)  
  { var napok= new initArray("Malvin","Petra","Helga","Ferenc","Aurél","Renáta", 
       "Amália","Koppány","Dénes","Gedeon","Brigitta","Miksa","Kálmán","Helén",  
        "Teréz","Gál","Hedvig","Lukács","Nándor","Vendel","Orsolya","Elõd",  
        "Gyöngyi","Salamon","Bianka","Dömötör",  
        "Szabina","Simon","Nárcisz","Alfonz","Farkas","" )}  
if (ho==11)  
  { var napok=new initArray("Marianna","Achilles","Gyõzõ","Károly","Imre","Lénárd", 
        "Rezsõ","Zsombor","Tivadar","Réka","Márton","Jónás, Renátó","Szilvia",  
        "Aliz","Albert, Lipót","Ödön","Hortenzia, Gergõ","Jenõ","Erzsébet",  
        "Jolán","Olivér","Cecília","Kelemen, Klementina","Emma","Katalin",  
        "Virág","Virgil","Stefánia","Taksony","András, Andor","" )}  
if (ho==12)  
  { var napok=new initArray("Elza","Melinda","Ferenc","Barbara", 
        "Vilma","Miklós","Ambrus","Mária","Natália","Judit","Árpád","Gabriella",  
        "Luca","Szilárda","Valér","Etelka","Lázár",  
        "Auguszta","Viola","Teofil","Tamás","Zéno","Viktória","Ádám, Éva",  
        "KARÁCSONY","KARÁCSONY","János","Kamilla",  
        "Tamás","Dávid","Szilveszter","") }  
   return napok[nap];  
}  
  
/* Hónap neve */  
function honev(ho) {  
   var month = new initArray("Január","Február","Március","Április","Május",  
   "Junius","Július","Augusztus","Szeptember","Október","November","December");  
   return month[ho]  
   }  
  
/* Nap neve */  
function napnev(szam) {  
   var napok = new initArray("Vasárnap","Hétfõ","Kedd","Szerda","Csütörtök", 
                             "Péntek","Szombat","Vasárnap")  
   return napok[szam]  
   }  
  
  var ido = new Date()  
  var ev = ido.getYear()  
  var ho = ido.getMonth()+1  
  var nap = ido.getDate()  
  if (ev<1900) ev+=1900;
