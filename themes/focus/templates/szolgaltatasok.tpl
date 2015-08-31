<div id="static_text" style="width: 100%; min-height: 450px; background: url('{$theme_dir}/images/szolg_side.png') no-repeat left top;">
	{literal}
	<script>
		//a kinyitható, becsukható részek id-jeinek felsorolása, vesszõvel elválasztva
		//új rész felvételekor az új id-t hozzá kell adni a listához
               var div_ids = new Array("kreativ_grafikai_tervezes", "csomagolastervezes_es_fejlesztes", "komplex_kampanyok_promociok_tervezese", "pos_anyagok_tervezese_es_gyartasa");
	</script>
	{/literal}
	<div style="padding-top: 50px;">
		<!--
			SZÖVEGES RÉSZ
			A szöveg sorait "div" tag-ekbe kell rakni, és minden sornak meg kell adni a bal behúzást pixelben (pl.: <div style="padding-left: 122px;">szöveg</div>).
			A behúzást soronként 4 pixellel kell csökkenteni. Amennyiben üres sor kerül be (<br />), azt is be kell számolni -4 pixellel.
		-->
		
		


		<!-- 
			KINYITHATÓ RÉSZ KEZDETE
			Az "a" tag "rel" attribútumának meg kell egyezni a kinyitható "div" "id" attribútumával (itt pl.: miert_pont_velunk), és ezt kell megadni az "a" tag "onclick" attribútumában,
			a "showHide" függvény meghívásakor paraméterként (itt pl.: onclick="showHide('miert_pont_velunk', 'MIÉRT PONT VELÜNK?');"), valamint ezt az egyedi azonosítót kell hozzáadni a fenti listához. A függvény második paramétere a kinyitható rész szövege legyen.
			FONTOS! Az azonosító egyedi legyen, és ne tartalmazzon szóközt, ékezetes betûket és egyéb speciális karaktereket.
		-->
		<div style="padding-left: 260px;"><a rel="csomagolastervezes_es_fejlesztes" href="javascript:void(0);" style="color: #73cbcb;" onclick="showHide('csomagolastervezes_es_fejlesztes', 'Csomagolástervezés és -fejlesztés', 1); pushContent(1);">+ Csomagolástervezés és -fejlesztés</a></div>
		<div id="csomagolastervezes_es_fejlesztes" style="display: none;">
			<div style="padding-left: 264px;">&#9679; Termékdesign, csomagolás</div>
                        <div style="padding-left: 260px;">&#9679; Gyújtõ- és kínáló karton</div>
               <br />
		</div>


<div id="nyithato_2_1" style="padding-left: 256px;"><a rel="kreativ_grafikai_tervezes" href="javascript:void(0);" style="color: #73cbcb;" onclick="showHide('kreativ_grafikai_tervezes', 'Grafikai tervezés', 2); pushContent(2);">+ Grafikai tervezés</a></div>
		<div id="kreativ_grafikai_tervezes" style="display: none;">
			<div style="padding-left: 260px;">&#9679; Kis- és nagyarculat kialakítása</div>
			<div style="padding-left: 256px;">&#9679; Termékkatalógusok, kiadványok</div>
            <div style="padding-left: 252px;">&#9679; Szórólapok, prospektusok, plakátok</div>			
            <div style="padding-left: 248px;">&#9679; Meghívók</div>
		    <div style="padding-left: 244px;">&#9679; Naptárak</div>
               <br />
		</div>
<div id="nyithato_3_1" style="padding-left: 252px;"><a rel="komplex_kampanyok_promociok_tervezese" href="javascript:void(0);" style="color: #73cbcb;" onclick="showHide('komplex_kampanyok_promociok_tervezese', 'Komplex BTL kampányok, promóciók tervezése', 3); pushContent(3);">+ Komplex BTL kampányok, promóciók tervezése</a></div>
		<div id="komplex_kampanyok_promociok_tervezese" style="display: none;">
			<div style="padding-left: 256px;">&#9679; Látványtervek</div>
			<div style="padding-left: 252px;">&#9679; Koncepciók, mechanizmusok kidolgozása</div>
			<div style="padding-left: 248px;">&#9679; Értékesítést támogató eszközök</div>
			<div style="padding-left: 244px;">&#9679; Szövegírás</div>
               <br />
		</div>
<div id="nyithato_4_1" style="padding-left: 248px;"><a rel="pos_anyagok_tervezese_es_gyartasa" href="javascript:void(0);" style="color: #73cbcb;" onclick="showHide('pos_anyagok_tervezese_es_gyartasa', 'POS anyagok tervezése és gyártása', 4); pushContent(4);">+ POS anyagok tervezése és gyártása</a></div>
		<div id="pos_anyagok_tervezese_es_gyartasa" style="display: none;">
			<div style="padding-left: 252px;">&#9679; Termékbemutató display-ek</div>
			<div style="padding-left: 248px;">&#9679; Wobblerek, címkék, polccsíkok</div>
			<div style="padding-left: 244px;">&#9679; Kínáló- és kóstoltatópult dekorok</div>
			<div style="padding-left: 240px;">&#9679; Eladáshelyi dekorációk</div>
			<div style="padding-left: 236px;">&#9679; Díszdobozok és díszcsomagolások</div>
			<div style="padding-left: 232px;">&#9679; Logózott mappák, iratrendezõk</div>
			<div style="padding-left: 228px;">&#9679; Hûtõmágnesek, mágneses szórólapok</div>
			<div style="padding-left: 224px;">&#9679; Kirakati és egyéb matricák</div>
			<div style="padding-left: 220px;">&#9679; Puzzle, társas- és kártyajátékok</div>
			<div style="padding-left: 216px;">&#9679; Egyéb egyedi eszközök</div>
               <br />
		</div>
<div id="szoveg_lent_1" style="padding-left: 252px;">Professzionális fotózás</div>
<div id="szoveg_lent_2" style="padding-left: 248px;">Innovatív megoldások</div>
<div id="szoveg_lent_3" style="padding-left: 244px;">Teljes arculatkialakítás és annak koordinálása</div>
<div id="szoveg_lent_4" style="padding-left: 240px;">Nyomdai elõkészítés</div>
<div id="szoveg_lent_5" style="padding-left: 236px;">Gyártás, produkció</div>
<div id="szoveg_lent_6" style="padding-left: 232px;">Reklámeszközök kiszállítása, dekorációs munkák</div>
<div id="szoveg_lent_7" style="padding-left: 228px;">Marketing tanácsadás</div>
  
		<!--
			KINYITHATÓ RÉSZ VÉGE
		-->
	</div>
</div>