<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("index.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

if (isset($_GET['code']) && is_numeric($_GET['code'])) {
	$code = intval($_GET['code']);

	switch ($code)
	{
		//sikeres bejelentkezes
		/*case "001":
			$tpl->assign('successmsg', $strSuccessAccountLogin);
			break;*/
		//sikeres kijelentkezes
		/*case "002":
			$tpl->assign('successmsg', $strSuccessAccountLogout);
			break;*/
		//sikeres aktivalas
		/*case "003":
			$tpl->assign('successmsg', $strSuccessAccountActivate);
			break;*/
		//sikeres jelszomodositas
		/*case "004":
			$tpl->assign('successmsg', $strSuccessAccountModify);
			break;*/
		//sikeres regisztracio
		/*case "005":
			$tpl->assign('successmsg', $strSuccessAccountRegister);
			break;*/
		//regisztracio torlese
		/*case "006":
			$tpl->assign('successmsg', $strSuccessAccountRegisterdel);
			break;*/
		//elfelejtett jelszo
		/*case "007":
			$tpl->assign('successmsg', $strSuccessAccountLostpass);
			break;*/
		//elfelejtett jelszo aktivalas
		/*case "008":
			$tpl->assign('successmsg', $strSuccessAccountLostpassAct);
			break;*/
		//e-mail kuldes sikeres - modules/feedback.php
		/*case "009":
			$tpl->assign('successmsg', $strSuccessFeedbackSend);
			break;*/
		//uj uzenet beszurasa - modules/guestbook.php
		/*case "010":
			$tpl->assign('successmsg', $strSuccessGuestbookAdminAdd);
			break;*/
		//uj uzenet beszurasa - modules/guestbook.php
		/*case "011":
			$tpl->assign('successmsg', $strSuccessGuestbookNoadminAdd);
			break;*/
		//valasz uzenetre - modules/guestbook.php
		/*case "012":
			$tpl->assign('successmsg', $strSuccessGuestbookReply);
			break;*/
		//rendeles elkuldes - modules/shop.php
		case "013":
			$tpl->assign('successmsg', $strSuccessShopOrders);
			break;
		//aktivalas - modules/shop
		case "014":
			$tpl->assign('successmsg', $strSuccessShopNotregActivate);
			break;
		//aktivalas - modules/classifieds
		case "015":
			$tpl->assign('successmsg', $strSuccessClassActivate);
			break;
		//torles - modules/classifieds
		case "016":
			$tpl->assign('successmsg', $strSuccessClassDelete);
			break;
		//uj hozzaszolas - hirek
		case "017":
			$tpl->assign('successmsg', $strSuccessNewsAddComment);
			break;
	}

	//megadjuk a tpl file nevet, amit atadunk az index.php-nek
	$acttpl = 'success';
}

?>
