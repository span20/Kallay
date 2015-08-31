<?php /* Smarty version 2.6.16, created on 2011-09-07 19:55:18
         compiled from account_cal.tpl */ ?>
<script>
    <?php echo '
    function showTitle(obj, text) {
        jQuery(\'#\'+obj).tooltip({
            track: true,
            delay: 0,
            showURL: false,
            bodyHandler: function() {
                return text;
            },
            fade: 250
        });
    }
    '; ?>

</script>
<h1>T�zv�delmi napt�r</h1>
<div style="padding: 0 0 20px 0;">
	A t�zv�delmi rendszerek tervez�se komoly szak�rtelmet �s felk�sz�l�st ig�nyl�, felel�ss�g teljes folyamat. A t�zv�delmi tervez�s k�l�nb�z� ter�letei, az �p�t�sz t�zv�delmi tervez�s (t�zv�delmi szak�rt�), a t�zjelz� rendszer vagy t�zolt� rendszer tervez�se, kivitelez�se, a be�p�tett berendez�sek karbantart�sa, telep�t�se t�zv�delmi szakvizsga �s vagy az Orsz�gos Katasztr�fav�delmi F�igazgat�s�gon (OKF) val� regisztr�ci� ut�n.
</div>
<div style="padding: 20px 0 20px 0; font-size: 15px; font-weight: bold; color: #000000;">
	<?php echo $this->_tpl_vars['cegnev']; ?>
 t�zv�delmi napt�ra
</div>
<div style="padding-bottom: 20px;">
	<div style="float: left; padding-top: 70px;">
		<a href="index.php?p=account&act=account_cal&year=<?php echo $this->_tpl_vars['prevlink_year']; ?>
&month=<?php echo $this->_tpl_vars['prevlink_month']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/bal_nyil.jpg" /></a>
	</div>
	<div style="padding-left: 20px; float: left;">
		<?php echo $this->_tpl_vars['cal_prev']; ?>

	</div>
	<div style="padding-left: 20px; float: left;">
		<?php echo $this->_tpl_vars['cal']; ?>

	</div>
	<div style="padding-left: 20px; padding-right: 20px; float: left;">
		<?php echo $this->_tpl_vars['cal_next']; ?>

	</div>
	<div style="float: left; padding-top: 70px;">
		<a href="index.php?p=account&act=account_cal&year=<?php echo $this->_tpl_vars['nextlink_year']; ?>
&month=<?php echo $this->_tpl_vars['nextlink_month']; ?>
"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/jobb_nyil.jpg" /></a>
	</div>
</div>
<div style="padding: 20px 0 20px 0; clear: both;">
	<div style="font-size: 13px; color: #000000; font-weight: bold; padding-bottom: 10px;">
		Legk�zelebbi esem�nyek
	</div>
	<div>
		<?php echo $this->_tpl_vars['textlist']; ?>

	</div>
</div>
<div style="padding: 20px 0 50px 0; clear: both;">
	Amennyiben a fenti lista nem teljes, vagy megv�ltoznak az adatok, <a style="color: #3F64B8;" href="index.php?p=account&act=account_mod">szerkessze szem�lyes napt�r�t</a>!
</div>