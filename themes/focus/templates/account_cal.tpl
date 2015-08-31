<script>
    {literal}
    function showTitle(obj, text) {
        jQuery('#'+obj).tooltip({
            track: true,
            delay: 0,
            showURL: false,
            bodyHandler: function() {
                return text;
            },
            fade: 250
        });
    }
    {/literal}
</script>
<h1>Tûzvédelmi naptár</h1>
<div style="padding: 0 0 20px 0;">
	A tûzvédelmi rendszerek tervezése komoly szakértelmet és felkészülést igénylõ, felelõsség teljes folyamat. A tûzvédelmi tervezés különbözõ területei, az építész tûzvédelmi tervezés (tûzvédelmi szakértõ), a tûzjelzõ rendszer vagy tûzoltó rendszer tervezése, kivitelezése, a beépített berendezések karbantartása, telepítése tûzvédelmi szakvizsga és vagy az Országos Katasztrófavédelmi Fõigazgatóságon (OKF) való regisztráció után.
</div>
<div style="padding: 20px 0 20px 0; font-size: 15px; font-weight: bold; color: #000000;">
	{$cegnev} tûzvédelmi naptára
</div>
<div style="padding-bottom: 20px;">
	<div style="float: left; padding-top: 70px;">
		<a href="index.php?p=account&act=account_cal&year={$prevlink_year}&month={$prevlink_month}"><img src="{$theme_dir}/images/bal_nyil.jpg" /></a>
	</div>
	<div style="padding-left: 20px; float: left;">
		{$cal_prev}
	</div>
	<div style="padding-left: 20px; float: left;">
		{$cal}
	</div>
	<div style="padding-left: 20px; padding-right: 20px; float: left;">
		{$cal_next}
	</div>
	<div style="float: left; padding-top: 70px;">
		<a href="index.php?p=account&act=account_cal&year={$nextlink_year}&month={$nextlink_month}"><img src="{$theme_dir}/images/jobb_nyil.jpg" /></a>
	</div>
</div>
<div style="padding: 20px 0 20px 0; clear: both;">
	<div style="font-size: 13px; color: #000000; font-weight: bold; padding-bottom: 10px;">
		Legközelebbi események
	</div>
	<div>
		{$textlist}
	</div>
</div>
<div style="padding: 20px 0 50px 0; clear: both;">
	Amennyiben a fenti lista nem teljes, vagy megváltoznak az adatok, <a style="color: #3F64B8;" href="index.php?p=account&act=account_mod">szerkessze személyes naptárát</a>!
</div>