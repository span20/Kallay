<div id="static_text" style="width: 100%; min-height: 450px; background: url('{$theme_dir}/images/szolg_side.png') no-repeat left top;">
	{literal}
	<script>
		//a kinyithat�, becsukhat� r�szek id-jeinek felsorol�sa, vessz�vel elv�lasztva
		//�j r�sz felv�telekor az �j id-t hozz� kell adni a list�hoz
               var div_ids = new Array("kreativ_grafikai_tervezes", "csomagolastervezes_es_fejlesztes", "komplex_kampanyok_promociok_tervezese", "pos_anyagok_tervezese_es_gyartasa");
	</script>
	{/literal}
	<div style="padding-top: 50px;">
		<!--
			SZ�VEGES R�SZ
			A sz�veg sorait "div" tag-ekbe kell rakni, �s minden sornak meg kell adni a bal beh�z�st pixelben (pl.: <div style="padding-left: 122px;">sz�veg</div>).
			A beh�z�st soronk�nt 4 pixellel kell cs�kkenteni. Amennyiben �res sor ker�l be (<br />), azt is be kell sz�molni -4 pixellel.
		-->
		
		


		<!-- 
			KINYITHAT� R�SZ KEZDETE
			Az "a" tag "rel" attrib�tum�nak meg kell egyezni a kinyithat� "div" "id" attrib�tum�val (itt pl.: miert_pont_velunk), �s ezt kell megadni az "a" tag "onclick" attrib�tum�ban,
			a "showHide" f�ggv�ny megh�v�sakor param�terk�nt (itt pl.: onclick="showHide('miert_pont_velunk', 'MI�RT PONT VEL�NK?');"), valamint ezt az egyedi azonos�t�t kell hozz�adni a fenti list�hoz. A f�ggv�ny m�sodik param�tere a kinyithat� r�sz sz�vege legyen.
			FONTOS! Az azonos�t� egyedi legyen, �s ne tartalmazzon sz�k�zt, �kezetes bet�ket �s egy�b speci�lis karaktereket.
		-->
		<div style="padding-left: 260px;"><a rel="csomagolastervezes_es_fejlesztes" href="javascript:void(0);" style="color: #73cbcb;" onclick="showHide('csomagolastervezes_es_fejlesztes', 'Csomagol�stervez�s �s -fejleszt�s', 1); pushContent(1);">+ Csomagol�stervez�s �s -fejleszt�s</a></div>
		<div id="csomagolastervezes_es_fejlesztes" style="display: none;">
			<div style="padding-left: 264px;">&#9679; Term�kdesign, csomagol�s</div>
                        <div style="padding-left: 260px;">&#9679; Gy�jt�- �s k�n�l� karton</div>
               <br />
		</div>


<div id="nyithato_2_1" style="padding-left: 256px;"><a rel="kreativ_grafikai_tervezes" href="javascript:void(0);" style="color: #73cbcb;" onclick="showHide('kreativ_grafikai_tervezes', 'Grafikai tervez�s', 2); pushContent(2);">+ Grafikai tervez�s</a></div>
		<div id="kreativ_grafikai_tervezes" style="display: none;">
			<div style="padding-left: 260px;">&#9679; Kis- �s nagyarculat kialak�t�sa</div>
			<div style="padding-left: 256px;">&#9679; Term�kkatal�gusok, kiadv�nyok</div>
            <div style="padding-left: 252px;">&#9679; Sz�r�lapok, prospektusok, plak�tok</div>			
            <div style="padding-left: 248px;">&#9679; Megh�v�k</div>
		    <div style="padding-left: 244px;">&#9679; Napt�rak</div>
               <br />
		</div>
<div id="nyithato_3_1" style="padding-left: 252px;"><a rel="komplex_kampanyok_promociok_tervezese" href="javascript:void(0);" style="color: #73cbcb;" onclick="showHide('komplex_kampanyok_promociok_tervezese', 'Komplex BTL kamp�nyok, prom�ci�k tervez�se', 3); pushContent(3);">+ Komplex BTL kamp�nyok, prom�ci�k tervez�se</a></div>
		<div id="komplex_kampanyok_promociok_tervezese" style="display: none;">
			<div style="padding-left: 256px;">&#9679; L�tv�nytervek</div>
			<div style="padding-left: 252px;">&#9679; Koncepci�k, mechanizmusok kidolgoz�sa</div>
			<div style="padding-left: 248px;">&#9679; �rt�kes�t�st t�mogat� eszk�z�k</div>
			<div style="padding-left: 244px;">&#9679; Sz�veg�r�s</div>
               <br />
		</div>
<div id="nyithato_4_1" style="padding-left: 248px;"><a rel="pos_anyagok_tervezese_es_gyartasa" href="javascript:void(0);" style="color: #73cbcb;" onclick="showHide('pos_anyagok_tervezese_es_gyartasa', 'POS anyagok tervez�se �s gy�rt�sa', 4); pushContent(4);">+ POS anyagok tervez�se �s gy�rt�sa</a></div>
		<div id="pos_anyagok_tervezese_es_gyartasa" style="display: none;">
			<div style="padding-left: 252px;">&#9679; Term�kbemutat� display-ek</div>
			<div style="padding-left: 248px;">&#9679; Wobblerek, c�mk�k, polccs�kok</div>
			<div style="padding-left: 244px;">&#9679; K�n�l�- �s k�stoltat�pult dekorok</div>
			<div style="padding-left: 240px;">&#9679; Elad�shelyi dekor�ci�k</div>
			<div style="padding-left: 236px;">&#9679; D�szdobozok �s d�szcsomagol�sok</div>
			<div style="padding-left: 232px;">&#9679; Log�zott mapp�k, iratrendez�k</div>
			<div style="padding-left: 228px;">&#9679; H�t�m�gnesek, m�gneses sz�r�lapok</div>
			<div style="padding-left: 224px;">&#9679; Kirakati �s egy�b matric�k</div>
			<div style="padding-left: 220px;">&#9679; Puzzle, t�rsas- �s k�rtyaj�t�kok</div>
			<div style="padding-left: 216px;">&#9679; Egy�b egyedi eszk�z�k</div>
               <br />
		</div>
<div id="szoveg_lent_1" style="padding-left: 252px;">Professzion�lis fot�z�s</div>
<div id="szoveg_lent_2" style="padding-left: 248px;">Innovat�v megold�sok</div>
<div id="szoveg_lent_3" style="padding-left: 244px;">Teljes arculatkialak�t�s �s annak koordin�l�sa</div>
<div id="szoveg_lent_4" style="padding-left: 240px;">Nyomdai el�k�sz�t�s</div>
<div id="szoveg_lent_5" style="padding-left: 236px;">Gy�rt�s, produkci�</div>
<div id="szoveg_lent_6" style="padding-left: 232px;">Rekl�meszk�z�k kisz�ll�t�sa, dekor�ci�s munk�k</div>
<div id="szoveg_lent_7" style="padding-left: 228px;">Marketing tan�csad�s</div>
  
		<!--
			KINYITHAT� R�SZ V�GE
		-->
	</div>
</div>