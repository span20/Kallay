function initArray() {  
  this.length = initArray.arguments.length  
  for (var i = 0; i < this.length; i++)  
  this[i+1] = initArray.arguments[i]  
}  
  
function havinev(ev,ho,nap) {  
if (ho==1)  
  { var napok = new initArray("�J�V","�bel","Benj�min", 
        "Titusz","Simon","Boldizs�r","Attila, Ram�na","Gy�ngyv�r","Marcell",  
        "Mel�nia","�gota","Ern�","Veronika","B�dog","L�r�nt",  
        "Guszt�v","Antal","Piroska","S�ra, M�ri�","F�bi�n",  
        "�gnes","Vince","Rajmund","Tim�t","P�l","Vanda",  
        "Angelika","K�roly,","Ad�l","Martina","Marcella","") }  
if (ho==2)  
  if ((ev!=2008) && (ev!=2012) && ev!=2016)  
    { var napok=new initArray("Ign�c","Karolina","Bal�zs","R�hel", 
        "�gota","Dorottya","T�dor","Aranka","Abig�l","Elvira",  
        "Marietta","L�via","Ella","Valentin",  
        "Kolos","Julianna","Don�t","Bernadett","Zsuzsanna",  
        "Alad�r","Eleon�ra","Gerzson","Alfr�d",  
        "M�ty�s","G�za","Edina","�kos, B�tor","Elem�r","","")  }  
     else  
    { var napok=new initArray("Ign�c","Karolina","Bal�zs","R�hel", 
        "�gota","Dorottya","R�me�","Aranka","Abig�l","Elvira",  
        "Marietta","L�via","Ella","Valentin",  
        "Kolos","Julianna","Don�t","Bernadett","Zsuzsanna",  
        "Alad�r","Eleon�ra","Gerzson","Alfr�d",  
        "Sz�k�nap","M�ty�s","G�za","Edina","�kos, B�tor","Elem�r","","") }  
if (ho==3)  
  { var napok=new initArray("Albin","Lujza","Korn�lia","K�zm�r","Adorj�n", 
        "Leon�ra","Tam�s","Zolt�n","Franciska","Ildik�", 
        "Szil�rd","Gergely","Kriszti�n","Matild","Krist�f",  
        "Henrietta","Gertr�d","S�ndor","J�zsef","Klaudia",  
        "Benedek","Be�ta","Em�ke","G�bor","Ir�n",  
        "Em�nuel","Hajnalka","Gedeon","Auguszta","Zal�n","�rp�d","" ) }  
if (ho==4)  
  { var napok=new initArray("Hug�","�ron","Rich�rd","Izidor","Vince", 
        "Vilmos","Herman","D�nes","Erhard","Zsolt","Le�","Gyula", 
        "Ida","Tibor","Anaszt�zia","Csongor","Rudolf","Andrea","Emma",  
        "Tivadar","Konr�d","Csilla","B�la","Gy�rgy","M�rk","Ervin",  
        "Zita","Val�ria","P�ter","Katalin, Kitti","" )}  
if (ho==5)  
  { var napok=new initArray("F�l�p","Zsigmond","T�mea", 
        "M�nika","Gy�rgyi","Ivett","Gizella","Mih�ly","Gergely", 
        "�rmin","Ferenc","Pongr�c","Szerv�c","Bonif�c","Zs�fia",  
        "M�zes","Paszk�l","Erik","Iv�, Mil�n",  
        "Bern�t","Konstantin","J�lia, Rita","Dezs�","Eszter",  
        "Orb�n","F�l�p","Hella","Emil","Magdolna",  
        "Zsanett","Ang�la","" )}  
if (ho==6)  
  { var napok=new initArray("T�nde","Anita","Klotild","Bulcs�","Fatime", 
        "Norbert","R�bert","Med�rd","F�lix","Margit","Barnab�s", 
        "Vill�","Antal","Vazul","Jol�n","Jusztin","Laura",  
        "Levente","Gy�rf�s","Rafael","Alajos","Paulina",  
        "Zolt�n","Iv�n","Vilmos","J�nos","L�szl�","Levente",  
        "P�ter, P�l","P�l","" ) }  
if (ho==7)  
  { var napok=new initArray("Tiham�r","Ott�","Korn�l","Ulrik", 
        "Sarolta","Csaba","Appol�nia","Ell�k","Lukr�cia","Am�lia",  
        "N�ra","Izabella","Jen�","�rs","Henrik","Valter",  
        "Endre","Frigyes","Em�lia","Ill�s","D�niel",  
        "Magdolna","Lenke","Kinga, Kincs�","Krist�f, Jakab","Anna, Anik�",  
        "Olga","Szabolcs","M�rta","Judit","Oszk�r","" )}  
if (ho==8)  
  { var napok=new initArray("Bogl�rka","Lehel","Hermina","Domonkos", 
        "Krisztina","Berta","Ibolya","L�szl�","Em�d","L�rinc",  
        "Zsuzsanna","Kl�ra","Ipoly","Marcell","M�ria","�brah�m",  
        "J�cint","Ilona","Huba","Istv�n","S�muel",  
        "Menyh�rt","Bence","Bertalan","Lajos","Izs�",  
        "G�sp�r","�goston","Beatrix","R�zsa","Erika") }  
if (ho==9)  
  { var napok= new initArray("Egon","Rebeka","Hilda","Roz�lia", 
        "Viktor","Zakari�s","Regina","M�ria","�d�m","Nikolett",  
        "Teod�ra","M�ria","Korn�l","Szer�na","Enik�","Edit",  
        "Zs�fia","Di�na","Vilhelmina","Friderika","M�t�,","M�ric",  
        "Tekla","Gell�rt","Eufrozina","Jusztina","Adalbert",  
        "Vencel","Mih�ly","Jeromos","" )}  
if (ho==10)  
  { var napok= new initArray("Malvin","Petra","Helga","Ferenc","Aur�l","Ren�ta", 
       "Am�lia","Kopp�ny","D�nes","Gedeon","Brigitta","Miksa","K�lm�n","Hel�n",  
        "Ter�z","G�l","Hedvig","Luk�cs","N�ndor","Vendel","Orsolya","El�d",  
        "Gy�ngyi","Salamon","Bianka","D�m�t�r",  
        "Szabina","Simon","N�rcisz","Alfonz","Farkas","" )}  
if (ho==11)  
  { var napok=new initArray("Marianna","Achilles","Gy�z�","K�roly","Imre","L�n�rd", 
        "Rezs�","Zsombor","Tivadar","R�ka","M�rton","J�n�s, Ren�t�","Szilvia",  
        "Aliz","Albert, Lip�t","�d�n","Hortenzia, Gerg�","Jen�","Erzs�bet",  
        "Jol�n","Oliv�r","Cec�lia","Kelemen, Klementina","Emma","Katalin",  
        "Vir�g","Virgil","Stef�nia","Taksony","Andr�s, Andor","" )}  
if (ho==12)  
  { var napok=new initArray("Elza","Melinda","Ferenc","Barbara", 
        "Vilma","Mikl�s","Ambrus","M�ria","Nat�lia","Judit","�rp�d","Gabriella",  
        "Luca","Szil�rda","Val�r","Etelka","L�z�r",  
        "Auguszta","Viola","Teofil","Tam�s","Z�no","Vikt�ria","�d�m, �va",  
        "KAR�CSONY","KAR�CSONY","J�nos","Kamilla",  
        "Tam�s","D�vid","Szilveszter","") }  
   return napok[nap];  
}  
  
/* H�nap neve */  
function honev(ho) {  
   var month = new initArray("Janu�r","Febru�r","M�rcius","�prilis","M�jus",  
   "Junius","J�lius","Augusztus","Szeptember","Okt�ber","November","December");  
   return month[ho]  
   }  
  
/* Nap neve */  
function napnev(szam) {  
   var napok = new initArray("Vas�rnap","H�tf�","Kedd","Szerda","Cs�t�rt�k", 
                             "P�ntek","Szombat","Vas�rnap")  
   return napok[szam]  
   }  
  
  var ido = new Date()  
  var ev = ido.getYear()  
  var ho = ido.getMonth()+1  
  var nap = ido.getDate()  
  if (ev<1900) ev+=1900;
