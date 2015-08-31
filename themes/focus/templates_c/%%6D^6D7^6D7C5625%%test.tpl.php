<?php /* Smarty version 2.6.16, created on 2013-05-23 16:41:34
         compiled from test.tpl */ ?>
<?php if ($this->_tpl_vars['topli']): ?>
	<div>
		<table align="center" width="500">
		<?php $this->assign('count_top', '1'); ?>
		<?php $_from = $this->_tpl_vars['topli']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
			<tr>
				<td><?php echo $this->_tpl_vars['count_top']; ?>
.</td>
				<td><?php echo $this->_tpl_vars['data']['name']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['result']; ?>
</td>
				<td><?php echo $this->_tpl_vars['data']['speed']; ?>
</td>
			</tr>
			<?php $this->assign('count_top', $this->_tpl_vars['count_top']+1); ?>
		<?php endforeach; endif; unset($_from); ?>
		</table>
	</div>
<?php else: ?>
	<?php if ($_SESSION['teszt_email']): ?>
		<div id="countdown"></div>
		<div id="q_pager">
			<div id="next_q">
				<a href="javascript:void(0);" onclick="switchQ('next');">következõ &raquo;</a>
			</div>
			<div id="prev_q">
				<a href="javascript:void(0);" onclick="switchQ('prev');">&laquo; elõzõ</a>
			</div>
		</div>
		<div id="teszt_cont">
			<div id="question_1" class="q_cont">
				<div class="question">
					1. Az alábbiak közül melyiket lehet "„review”" technikával ellenõrizni?
				</div>
				<div class="answer">
					<input type="radio" name="a_1" value="a" id="1_a"> <label for="1_a">Szoftver kód</label><br />
					<input type="radio" name="a_1" value="b" id="1_b"> <label for="1_b">Specifikáció</label><br />
					<input type="radio" name="a_1" value="c" id="1_c"> <label for="1_c">Teszt tervezet</label><br />
					<input type="radio" name="a_1" value="d" id="1_d"> <label for="1_d">Mindhármat lehet</label><br />
				</div>
			</div>
			<div id="question_2" class="q_cont">
				<div class="question">
					2. Egy tesztesetnek az lenne a célja, hogy letesztelje sikeres-e a felhasználó adatainak módosítása. Mi a helyes sorrend a teszteset lépéseit illetõen:<br /><br />
					1. A felhasználó címének átírása (utca, házszám)<br />
					2. A megváltozott adatok kiíratása, majd printscreen-nel történõ lementése<br />
					3. Az eredeti és változtatott adatok összevetése és analizálása (printscreen-ek összehasonlítása)<br />
					4. Felhasználó adattáblájának megnyitása<br />
					5. A felhasználó (képernyõn megjelenõ) eredeti adatainak lementése (printscreen)<br />
				</div>
				<div class="answer">
					<input type="radio" name="a_2" value="a" id="2_a"> <label for="2_a">4,3,1,2,5</label><br />
					<input type="radio" name="a_2" value="b" id="2_b"> <label for="2_b">2,4,1,5,3</label><br />
					<input type="radio" name="a_2" value="c" id="2_c"> <label for="2_c">4,5,1,2,3</label><br />
					<input type="radio" name="a_2" value="d" id="2_d"> <label for="2_d">4,1,2,3,5</label><br />
				</div>
			</div>
			<div id="question_3" class="q_cont">
				<div class="question">
					3. Lovagok és csirkefogók<br /><br />
					Texel szigetén a helyi lakosok külsõleg ugyanúgy néznek ki, de belsõleg különböznek egy lényeges dologban az alábbiak szerint:<br />
					- Lovagok, akik mindig igazat mondanak<br />
					- Csirkefogók, akik soha sem mondanak igazat<br />
					- Normál emberek, akik néha igazat mondanak, néha nem<br />
					Ha az egyik helyi lakos az mondja nekünk hogy: "„Nem vagyok lovag”", akkor melyik típusba tartozik?
				</div>
				<div class="answer">
					<input type="radio" name="a_3" value="a" id="3_a"> <label for="3_a">Lovag</label><br />
					<input type="radio" name="a_3" value="b" id="3_b"> <label for="3_b">Csirkefogó</label><br />
					<input type="radio" name="a_3" value="c" id="3_c"> <label for="3_c">Normál ember</label><br />
					<input type="radio" name="a_3" value="d" id="3_d"> <label for="3_d">Ennyi információból nem oldható meg a feladat</label><br />
				</div>
			</div>
			<div id="question_4" class="q_cont">
				<div class="question">
					4. Az alábbi folyamatábra egy program mûködését írja le. Mit csinál a program?<br /><br />
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/folyamatabra.png" />
				</div>
				<div class="answer">
					<input type="radio" name="a_4" value="a" id="4_a"> <label for="4_a">A beolvasott számokat elhelyezi egy tömbben növekvõ sorrendben.</label><br />
					<input type="radio" name="a_4" value="b" id="4_b"> <label for="4_b">Kiírja hogy "„0”".</label><br />
					<input type="radio" name="a_4" value="c" id="4_c"> <label for="4_c">A beolvasott nem negatív számok közül kiírja a legkisebbet (min). Negatívra kilép.</label><br />
					<input type="radio" name="a_4" value="d" id="4_d"> <label for="4_d">Végtelen ciklusban marad, ha negatív számot kap.</label><br />
				</div>
			</div>
			<div id="question_5" class="q_cont">
				<div class="question">
					5. Mi az '’x’' végsõ értéke az alábbi program futása után?
					<?php echo '
	<pre>
	int x; 
	for(x=0; x<10; x++) {} 
	</pre>
					'; ?>

				</div>
				<div class="answer">
					<input type="radio" name="a_5" value="a" id="5_a"> <label for="5_a">10</label><br />
					<input type="radio" name="a_5" value="b" id="5_b"> <label for="5_b">9</label><br />
					<input type="radio" name="a_5" value="c" id="5_c"> <label for="5_c">1</label><br />
					<input type="radio" name="a_5" value="d" id="5_d"> <label for="5_d">0</label><br />
				</div>
			</div>
			<div id="question_6" class="q_cont">
				<div class="question">6. Mi a különbség a QUEUE és a STACK között?</div>
				<div class="answer">
					<input type="radio" name="a_6" value="a" id="6_a"> <label for="6_a">Semmi.</label><br />
					<input type="radio" name="a_6" value="b" id="6_b"> <label for="6_b">A QUEUE-ból a legrégebben berakott elem távozik elõször, a STACK-bõl a legfrissebb.</label><br />
					<input type="radio" name="a_6" value="c" id="6_c"> <label for="6_c">A QUEUE kétszer annyi memóriát foglal, mint az ugyanannyi elemet tartalmazó STACK.</label><br />
					<input type="radio" name="a_6" value="d" id="6_d"> <label for="6_d">A QUEUE egy LIFO, a STACK pedig FIFO.</label><br />
				</div>
			</div>
			<div id="question_7" class="q_cont">
				<div class="question">
					7. Mi a következõ szám a sorban?<br /><br />
					9 5 6 8 4 5 7 3 _
				</div>
				<div class="answer">
					<input type="radio" name="a_7" value="a" id="7_a"> <label for="7_a">3</label><br />
					<input type="radio" name="a_7" value="b" id="7_b"> <label for="7_b">4</label><br />
					<input type="radio" name="a_7" value="c" id="7_c"> <label for="7_c">5</label><br />
					<input type="radio" name="a_7" value="d" id="7_d"> <label for="7_d">6</label><br />
				</div>
			</div>
			<div id="question_8" class="q_cont">
				<div class="question">
					8. Melyik ábra illik az üres helyre?<br /><br />
					<img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/abra.png" />
				</div>
				<div class="answer">
					A helyes ábra sorszáma: <input type="text" name="a_8" id="a_8" value="" style="width: 50px;" /><br />
				</div>
			</div>
			<div id="question_9" class="q_cont">
				<div class="question">9. A www.parosvagyparatlan.com weboldal a begépelt számról eldönti, hogy páros vagy páratlan. Milyen sorozattal tesztelnéd le a mûködését egy ilyen weboldalnak?</div>
				<div class="answer">
					<input type="radio" name="a_9" value="a" id="9_a"> <label for="9_a">1, 2, 3, 4, 5, 6</label><br />
					<input type="radio" name="a_9" value="b" id="9_b"> <label for="9_b">1, 10, 2, 20, 3, 30</label><br />
					<input type="radio" name="a_9" value="c" id="9_c"> <label for="9_c">-2, -1, 0, 1, 2, 3</label><br />
					<input type="radio" name="a_9" value="d" id="9_d"> <label for="9_d">-2, -1, 0, 1, 2, 2.5</label><br />
				</div>
			</div>
			<div id="question_10" class="q_cont">
				<div class="question">10. A tesztelés célja, hogy hibát találjon...</div>
				<div class="answer">
					<input type="radio" name="a_10" value="a" id="10_a"> <label for="10_a">csak a specifikációban.</label><br />
					<input type="radio" name="a_10" value="b" id="10_b"> <label for="10_b">csak a kódban.</label><br />
					<input type="radio" name="a_10" value="c" id="10_c"> <label for="10_c">Akár mindkettõben találhat hibát.</label><br />
					<input type="radio" name="a_10" value="d" id="10_d"> <label for="10_d">Nem célja a hiba megtalálása.</label><br />
				</div>
			</div>
			<div id="question_11" class="q_cont">
				<div class="question">11. Egy teszt eredménye a tesztelõ szerint FAILED, de a programozó szerint PASSED. Hogyan tovább? </div>
				<div class="answer">
					<input type="radio" name="a_11" value="a" id="11_a"> <label for="11_a">A teszt tervezet (test plan) alapján kell meghatározni, hogy kinek van igaza.</label><br />
					<input type="radio" name="a_11" value="b" id="11_b"> <label for="11_b">Mindig a tesztelõnek van igaza.</label><br />
					<input type="radio" name="a_11" value="c" id="11_c"> <label for="11_c">A specifikáció és követelménylista alapján kell meghatározni, hogy kinek helyes az állítása.</label><br />
					<input type="radio" name="a_11" value="d" id="11_d"> <label for="11_d">A menedzserüknek kell eldönteniük, kinek van igaza.</label><br />
				</div>
			</div>
			<div id="question_12" class="q_cont">
				<div class="question">
					12. Értékeld ki az alábbi formulát:<br />
					NOT(1 AND NOT(0 OR 1))
				</div>
				<div class="answer">
					<input type="radio" name="a_12" value="a" id="12_a"> <label for="12_a">0</label><br />
					<input type="radio" name="a_12" value="b" id="12_b"> <label for="12_b">1</label><br />
					<input type="radio" name="a_12" value="c" id="12_c"> <label for="12_c">Syntax error</label><br />
					<input type="radio" name="a_12" value="d" id="12_d"> <label for="12_d">01</label><br />
				</div>
			</div>
			<div id="question_13" class="q_cont">
				<div class="question">13. Tedd "“idõrendi”" sorrendbe az alábbi hálózati technológiákat:</div>
				<div class="answer">
					<input type="radio" name="a_13" value="a" id="13_a"> <label for="13_a">GSM -> GPRS -> 3G ->  HSDPA -> LTE(4G)</label><br />
					<input type="radio" name="a_13" value="b" id="13_b"> <label for="13_b">GPRS -> GSM -> 3G ->  HSDPA -> LTE(4G)</label><br />
					<input type="radio" name="a_13" value="c" id="13_c"> <label for="13_c">GSM -> GPRS -> 3G ->  LTE(4G) -> HSDPA</label><br />
					<input type="radio" name="a_13" value="d" id="13_d"> <label for="13_d">GSM -> 3G ->  GPRS -> HSDPA -> LTE(4G)</label><br />
				</div>
			</div>
			<div id="question_14" class="q_cont">
				<div class="question">
					14. Mi ír ki az alábbi függvény n=5-tel meghívva?<br />
					<?php echo '
	<pre>
	void isosceles(int n)
	{
		int x,y;
		for (y= 0; y < n; y++)
		{
			for (x= 0; x <= y; x++)
				putchar(\'*\');
			putchar(\'\\n\');
		}
	}
	</pre>
					'; ?>

				</div>
				<div class="answer">
					<div style="float: left; width: 100px;">
						<input type="radio" name="a_14" value="a" id="14_a"><br />
						<label for="14_a"><br />
							*<br />
							**<br />
							***<br />
							****<br />
							*****<br />
							******<br />
						</label>
					</div>
					<div style="float: left; width: 100px;">
						<input type="radio" name="a_14" value="b" id="14_b"><br /><label for="14_b">******</label>
					</div>
					<div style="float: left; width: 100px;">
					<input type="radio" name="a_14" value="c" id="14_c"><br />
					<label for="14_c"><br />
						*n<br />
						**n<br />
						***n<br />
						**n<br />
						*n<br />
					</label>
					</div>
					<div style="float: left; width: 100px;">
						<input type="radio" name="a_14" value="d" id="14_d"><br /><label for="14_d">nnnnn</label>
					</div>
				</div>
			</div>
			<div style="text-align: center; padding-top: 10px; clear: both;">
				<input type="button" onclick="checkTest();" id="ertekel" value="Értékelés" disabled />
			</div>
		</div>
	<?php else: ?>
		<?php if ($_REQUEST['err']): ?>
			<div style="color: #B84954; font-size: 14px; padding: 20px;">
				<?php if ($_REQUEST['err'] == 1): ?>
					Kérjük minden adatot adj meg!
				<?php endif; ?>
				<?php if ($_REQUEST['err'] == 2): ?>
					A megadott e-mail cím hibás!
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ($_REQUEST['m']): ?>
			<div style="color: #296603; font-size: 14px; padding: 20px;">
			<?php if ($_REQUEST['m'] == 'ok'): ?>
				Köszönjük a jelentkezésedet!
			<?php endif; ?>
			</div>
		<?php endif; ?>
		Elsõ lépésként meg kell adnod az e-mail címed, sikeres tesztkitöltés esetén ezen a címen fogjuk felvenni veled a kapcsolatot!
		<form method="post" action="index.php?p=test">
			<input type="hidden" name="sent" value="1">
			<div style="padding-top: 20px; clear: both; float: left;">
				<div style="width: 100px; float: left;">E-mail cím:</div>
				<div style="width: 200px; float: left;"><input type="text" name="email" style="width: 200px;" /></div>
			</div>
			<div style="padding-top: 10px; clear: both; float: left;">
				<div style="width: 100px; float: left; clear: both;">Név:</div>
				<div style="width: 200px; float: left;"><input type="text" name="name" style="width: 200px;" /></div>
			</div>
			<div style="padding-top: 10px; clear: both; float: left;">
				<input type="submit" name="sub" value="Indítás" />
			</div>
		</form>
		<div class="spacer" style="clear: both;"></div>
	<?php endif;  endif; ?>