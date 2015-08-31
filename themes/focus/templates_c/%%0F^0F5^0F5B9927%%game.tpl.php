<?php /* Smarty version 2.6.16, created on 2013-05-02 09:09:16
         compiled from game.tpl */ ?>
<?php if ($_SESSION['user_id']): ?>
	<script src="includes/easeljs.js"></script>
	<script src="includes/tween.js"></script>
	<script src="includes/movieclip.js"></script>
	<script src="includes/sound.js"></script>
	<script src="includes/preload.js"></script>
	<script src="includes/fb_jatek.js"></script>
	<script>
		<?php echo '
		var canvas, stage, exportRoot, csap1, csap2, csap3, csap4, csap5, randNum = 0, points = 0, inditokep, indit_btn, end_ok, end_wrong, 
		new_btn, end_kep_cont, isRestart = false, csepeg1, csepeg2, folyik, folyik2, ontes, ovacio, rosszvalasz, csap1hang, csap2hang, csap3hang, csap4hang, csap5hang;
		var csapokStates = new Array(0, 0, 0, 0, 0);
		var csapokHangok = new Array();
		var csapok = new Array();
		var vizek = new Array(0, 0, 0, 0, 0);
		var gameTime = 0, gameInterval;

		function init() {
			canvas = document.getElementById("canvas");
			images = images||{};

			var manifest = [
				{src:"themes/focus/images/csapos/csap.png", id:"csap"},
				{src:"themes/focus/images/csapos/eros_folyas_1.png", id:"eros_folyas_1"},
				{src:"themes/focus/images/csapos/eros_folyas_2.png", id:"eros_folyas_2"},
				{src:"themes/focus/images/csapos/gyenge_folyas_1.png", id:"gyenge_folyas_1"},
				{src:"themes/focus/images/csapos/gyenge_folyas_2.png", id:"gyenge_folyas_2"},
				{src:"themes/focus/images/csapos/hatter.jpg", id:"hatter"},
				{src:"themes/focus/images/csapos/indito.png", id:"indito"},
				{src:"themes/focus/images/csapos/kis_csepp_1.png", id:"kis_csepp_1"},
				{src:"themes/focus/images/csapos/kis_csepp_2.png", id:"kis_csepp_2"},
				{src:"themes/focus/images/csapos/kozepes_folyas_1.png", id:"kozepes_folyas_1"},
				{src:"themes/focus/images/csapos/kozepes_folyas_2.png", id:"kozepes_folyas_2"},
				{src:"themes/focus/images/csapos/nagy_csepp_1.png", id:"nagy_csepp_1"},
				{src:"themes/focus/images/csapos/nagy_csepp_2.png", id:"nagy_csepp_2"},
				{src:"themes/focus/images/csapos/num_0.png", id:"num_0"},
				{src:"themes/focus/images/csapos/num_1.png", id:"num_1"},
				{src:"themes/focus/images/csapos/num_2.png", id:"num_2"},
				{src:"themes/focus/images/csapos/num_3.png", id:"num_3"},
				{src:"themes/focus/images/csapos/num_4.png", id:"num_4"},
				{src:"themes/focus/images/csapos/num_5.png", id:"num_5"},
				{src:"themes/focus/images/csapos/num_6.png", id:"num_6"},
				{src:"themes/focus/images/csapos/num_7.png", id:"num_7"},
				{src:"themes/focus/images/csapos/num_8.png", id:"num_8"},
				{src:"themes/focus/images/csapos/num_9.png", id:"num_9"},
				{src:"themes/focus/images/csapos/indito.png", id:"indito"},
				{src:"themes/focus/images/csapos/indit_btn.png", id:"indit_btn"},
				{src:"themes/focus/images/csapos/new_btn.png", id:"new_btn"},
				{src:"themes/focus/images/csapos/end_ok.png", id:"end_ok"},
				{src:"themes/focus/images/csapos/end_wrong.png", id:"end_wrong"}
			];

			var loader = new createjs.PreloadJS(false);
			loader.onFileLoad = handleFileLoad;
			loader.onComplete = handleComplete;
			loader.loadManifest(manifest);
		}

		function handleFileLoad(o) {
			if (o.type == "image") { images[o.id] = o.result; }
		}

		function handleComplete() {
			exportRoot = new lib.fb_jatek();

			stage = new createjs.Stage(canvas);
			stage.addChild(exportRoot);
			
			csap1 = new lib.csap();
			csap1.x = 90;
			csap1.num = 0;
			csap1.onPress = csapClicked;
			csapok.push(csap1);
			
			csap2 = new lib.csap();
			csap2.x = 215;
			csap2.num = 1;
			csap2.onPress = csapClicked;
			csapok.push(csap2);
			
			csap3 = new lib.csap();
			csap3.x = 340;
			csap3.num = 2;
			csap3.onPress = csapClicked;
			csapok.push(csap3);
			
			csap4 = new lib.csap();
			csap4.x = 465;
			csap4.num = 3;
			csap4.onPress = csapClicked;
			csapok.push(csap4);
			
			csap5 = new lib.csap();
			csap5.x = 590;
			csap5.num = 4;
			csap5.onPress = csapClicked;
			csapok.push(csap5);
			
			csap1.y = csap2.y = csap3.y = csap4.y = csap5.y = 250;
			
			stage.addChild(csap1);
			stage.addChild(csap2);
			stage.addChild(csap3);
			stage.addChild(csap4);
			stage.addChild(csap5);
			
			stage.enableMouseOver(15);
			
			inditokep = new lib.indito();
			stage.addChild(inditokep);
			
			indito_btn = new lib.indit_btn();
			indito_btn.x = 302;
			indito_btn.y = 339;
			indito_btn.onMouseOver = function(e) {
				document.body.style.cursor = "pointer";
			}
			indito_btn.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
			indito_btn.onPress = startGame;
			stage.addChild(indito_btn);
			
			end_kep_cont = new lib.end_kep_cont();
			
			stage.update();

			createjs.Sound.registerSound("themes/focus/sounds/csapos/csepeg.mp3|themes/focus/sounds/csapos/csepeg.ogg", "csepeg1");
			createjs.Sound.registerSound("themes/focus/sounds/csapos/csepeg2.mp3|themes/focus/sounds/csapos/csepeg2.ogg", "csepeg2");
			createjs.Sound.registerSound("themes/focus/sounds/csapos/ontes2.mp3|themes/focus/sounds/csapos/ontes2.ogg", "ontes2");
			createjs.Sound.registerSound("themes/focus/sounds/csapos/folyik2.mp3|themes/focus/sounds/csapos/folyik2.ogg", "folyik2");
			createjs.Sound.registerSound("themes/focus/sounds/csapos/folyik.mp3|themes/focus/sounds/csapos/folyik.ogg", "folyik");
			createjs.Sound.registerSound("themes/focus/sounds/csapos/ovacio.mp3|themes/focus/sounds/csapos/ovacio.ogg", "ovacio");
			createjs.Sound.registerSound("themes/focus/sounds/csapos/rosszvalasz.mp3|themes/focus/sounds/csapos/rosszvalasz.ogg", "rosszvalasz");
			
			csepeg1 = createjs.Sound.play("csepeg1", createjs.Sound.INTERRUPT_NONE, 0, 0, 0, 0.4);
			csepeg1.stop();
			csepeg2 = createjs.Sound.play("csepeg2", createjs.Sound.INTERRUPT_NONE, 0, 0, 0, 0.4);
			csepeg2.stop();
			ontes = createjs.Sound.play("ontes2", createjs.Sound.INTERRUPT_NONE, 0, 0, 0, 0.4);
			ontes.stop();
			folyik = createjs.Sound.play("folyik", createjs.Sound.INTERRUPT_NONE, 0, 0, 0, 0.4);
			folyik.stop();
			folyik2 = createjs.Sound.play("folyik2", createjs.Sound.INTERRUPT_NONE, 0, 0, 0, 0.4);
			folyik2.stop();
			
			csapokHangok.push(csepeg1);
			csapokHangok.push(csepeg1);
			csapokHangok.push(csepeg1);
			csapokHangok.push(csepeg1);
			csapokHangok.push(csepeg1);
			
			/*csap1hang = createjs.Sound.play("csepeg1", createjs.Sound.INTERRUPT_ANY, 0, 0, -1, 0.4);
			csap1hang.stop();
			csapokHangok.push(csap1hang);
			csap2hang = createjs.Sound.play("csepeg1", createjs.Sound.INTERRUPT_ANY, 0, 0, -1, 0.4); 
			csap2hang.stop();
			csapokHangok.push(csap2hang);
			csap3hang = createjs.Sound.play("csepeg1", createjs.Sound.INTERRUPT_ANY, 0, 0, -1, 0.4);
			csap3hang.stop();
			csapokHangok.push(csap3hang);
			csap4hang = createjs.Sound.play("csepeg1", createjs.Sound.INTERRUPT_ANY, 0, 0, -1, 0.4);
			csap4hang.stop();
			csapokHangok.push(csap4hang);
			csap5hang = createjs.Sound.play("csepeg1", createjs.Sound.INTERRUPT_ANY, 0, 0, -1, 0.4);
			csap5hang.stop();
			csapokHangok.push(csap5hang);*/
			
			rosszvalasz = createjs.Sound.play("rosszvalasz", createjs.Sound.INTERRUPT_ANY, 0, 0, 0, 0.4);
			rosszvalasz.stop();
			
			ovacio = createjs.Sound.play("ovacio", createjs.Sound.INTERRUPT_ANY, 0, 0, 0, 0.4);
			ovacio.stop();
			
			createjs.Ticker.setFPS(24);
			createjs.Ticker.addListener(window);

		}

		function addMouseOver() {
			csap1.onMouseOver = function(e) {
				document.body.style.cursor = "pointer";
			}
			csap1.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
			csap2.onMouseOver = function(e) {
				document.body.style.cursor = "pointer";
			}
			csap2.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
			csap3.onMouseOver = function(e) {
				document.body.style.cursor = "pointer";
			}
			csap3.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
			csap4.onMouseOver = function(e) {
				document.body.style.cursor = "pointer";
			}
			csap4.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
			csap5.onMouseOver = function(e) {
				document.body.style.cursor = "pointer";
			}
			csap5.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
		}

		function removeMouseOver() {
			csap1.onMouseOver = function(e) {
				document.body.style.cursor = "default";
			}
			csap1.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
			csap2.onMouseOver = function(e) {
				document.body.style.cursor = "default";
			}
			csap2.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
			csap3.onMouseOver = function(e) {
				document.body.style.cursor = "default";
			}
			csap3.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
			csap4.onMouseOver = function(e) {
				document.body.style.cursor = "default";
			}
			csap4.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
			csap5.onMouseOver = function(e) {
				document.body.style.cursor = "default";
			}
			csap5.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
		}

		function tick() {
			stage.update();
		}

		function csapClicked(e) {
			csapokStates[e.target.num] = 0;
			csapokHangok[e.target.num].stop();
			stage.removeChild(vizek[e.target.num]);
		}

		function startGame() {
			gameTime = 0;
			points = 0;
			csapokStates = new Array(0, 0, 0, 0, 0);
			vizek = new Array(0, 0, 0, 0, 0);
			
			if (isRestart) {
				rosszvalasz.stop();
				ovacio.stop();
				var szamjegy = new lib.num_0();
				exportRoot.szazas.removeAllChildren();
				exportRoot.szazas.addChild(szamjegy);
				exportRoot.tizes.removeAllChildren();
				exportRoot.tizes.addChild(szamjegy);
				exportRoot.egyes.removeAllChildren();
				exportRoot.egyes.addChild(szamjegy);
				exportRoot.tized.removeAllChildren();
				exportRoot.tized.addChild(szamjegy);
				exportRoot.szazad.removeAllChildren();
				exportRoot.szazad.addChild(szamjegy);
				
				stage.removeChild(new_btn);
				stage.removeChild(end_kep_cont);
			} else {
				stage.removeChild(indito_btn);
				stage.removeChild(inditokep);
			}
			addMouseOver();
			gameInterval = setInterval("gameLoop()", 800);
		}

		function randomNum(min, max) {
			return Math.floor(Math.random() * (max - min) + min);
		}

		function getRandomZartCsap() {
			var zartak = new Array();
			
			for (var i = 0; i < csapokStates.length; i++) {
				if (csapokStates[i] == 0) {
					zartak.push(i);
				}
			}
			
			var rand = randomNum(0, zartak.length);
			
			return zartak[rand];
		}

		function gameLoop() {
			
			var actFolyas, vanZart = false;
			var vizX, vizY = 363;
			
			for (var i = 0; i < csapokStates.length; i++) {
				
				if (csapokStates[i] > 0) {
					if (csapokStates[i] < 5) {
						csapokStates[i]++;
						switch(csapokStates[i]) {
							case 1:
								actFolyas = new lib.kisCsepp();
								//csapokHangok[i] = createjs.Sound.play("csepeg1", createjs.Sound.INTERRUPT_ANY, 0, 0, -1, 0.4);
								csepeg1.play();
								csapokHangok[i] = csepeg1;
							break;
							case 2:
								actFolyas = new lib.nagyCsepp();
								points += 0.01;
								//csapokHangok[i] = createjs.Sound.play("csepeg2", createjs.Sound.INTERRUPT_ANY, 0, 0, -1, 0.4);
								csepeg2.play();
								csapokHangok[i] = csepeg2;
							break;
							case 3:
								actFolyas = new lib.gyengeFolyas();
								points += 0.1;
								//csapokHangok[i] = createjs.Sound.play("ontes2", createjs.Sound.INTERRUPT_ANY, 0, 0, -1, 0.4);
								ontes.play();
								csapokHangok[i] = ontes;
							break;
							case 4:
								actFolyas = new lib.kozepesFolyas();
								points += 0.5;
								//csapokHangok[i] = createjs.Sound.play("folyik2", createjs.Sound.INTERRUPT_ANY, 0, 0, -1, 0.4);
								folyik2.play();
								csapokHangok[i] = folyik2;
							break;
							case 5:
								actFolyas = new lib.erosFolyas();
								points += 1;
								//csapokHangok[i] = createjs.Sound.play("folyik", createjs.Sound.INTERRUPT_ANY, 0, 0, -1, 0.4);
								folyik.play();
								csapokHangok[i] = folyik;
							break;
						}
						
						switch(i) {
							case 0:
								vizX = csap1.x + 35;
							break;
							case 1:
								vizX = csap2.x + 35;
							break;
							case 2:
								vizX = csap3.x + 35;
							break;
							case 3:
								vizX = csap4.x + 35;
							break;
							case 4:
								vizX = csap5.x + 35;
							break;
						}
						
						if (actFolyas != undefined) {
							stage.removeChild(vizek[i]);
							vizek[i] = actFolyas;

							actFolyas.x = vizX;
							actFolyas.y = vizY;
							stage.addChild(actFolyas);
						}
					}
					if (csapokStates[i] == 5) {
						points += 2.5;
					}
				} else {
					vanZart = true;
				}
			}
			
			if (vanZart) {
				var csapToStart = getRandomZartCsap();

				csapokStates[csapToStart] = 1;
				actFolyas = new lib.kisCsepp();
				switch(csapToStart) {
					case 0:
						vizX = csap1.x + 35;
					break;
					case 1:
						vizX = csap2.x + 35;
					break;
					case 2:
						vizX = csap3.x + 35;
					break;
					case 3:
						vizX = csap4.x + 35;
					break;
					case 4:
						vizX = csap5.x + 35;
					break;
				}
				
				stage.removeChild(vizek[csapToStart]);
				vizek[csapToStart] = actFolyas;

				actFolyas.x = vizX;
				actFolyas.y = vizY;
				stage.addChild(actFolyas);
			}
			
			var fixPoints = str_pad(points.toFixed(2).toString(), 6, 0, \'STR_PAD_LEFT\');
			
			for(var f = 0; f < fixPoints.length; f++){
				var szamjegy;

				switch(fixPoints[f]) {
					case \'0\':
						szamjegy = new lib.num_0();
					break;
					case \'1\':
						szamjegy = new lib.num_1();
					break;
					case \'2\':
						szamjegy = new lib.num_2();
					break;
					case \'3\':
						szamjegy = new lib.num_3();
					break;
					case \'4\':
						szamjegy = new lib.num_4();
					break;
					case \'5\':
						szamjegy = new lib.num_5();
					break;
					case \'6\':
						szamjegy = new lib.num_6();
					break;
					case \'7\':
						szamjegy = new lib.num_7();
					break;
					case \'8\':
						szamjegy = new lib.num_8();
					break;
					case \'9\':
						szamjegy = new lib.num_9();
					break;
				}
				
				switch(f) {
					case 0:
						exportRoot.szazas.removeAllChildren();
						exportRoot.szazas.addChild(szamjegy);
					break;
					case 1:
						exportRoot.tizes.removeAllChildren();
						exportRoot.tizes.addChild(szamjegy);
					break;
					case 2:
						exportRoot.egyes.removeAllChildren();
						exportRoot.egyes.addChild(szamjegy);
					break;
					case 3:
					break;
					case 4:
						exportRoot.tized.removeAllChildren();
						exportRoot.tized.addChild(szamjegy);
					break;
					case 5:
						exportRoot.szazad.removeAllChildren();
						exportRoot.szazad.addChild(szamjegy);
					break;
				}
			}
			
			gameTime++;
			
			if (gameTime == 37) {
				clearInterval(gameInterval);
				for (var v = 0; v < vizek.length; v++) {
					stage.removeChild(vizek[v]);
				}
				showEnd();
			}
		}

		function showEnd() {
			removeMouseOver();
			
			/*for (var i = 0; i < csapokHangok.length; i++) {
				csapokHangok[i] = createjs.Sound.play("csepeg1", createjs.Sound.INTERRUPT_ANY, 0, 0, -1, 0.4);
				csapokHangok[i].stop();
			}*/
			
			/*$.ajax({
				url: \'ajax.php?cmd=saveResult&user=<?php echo $user["id"]; ?>&res=\'+points,
				success: function(data) {
				}
			});*/
			$.ajax({
				url: \'ajax.php?act=saveResult\'
			});
			
			csepeg1.stop();
			csepeg2.stop();
			ontes.stop();
			folyik.stop();
			folyik2.stop();
			
			isRestart = true;
			
			if (points < 2.5) {
				ovacio.play();
				var egkep = new lib.end_ok();
			} else {
				rosszvalasz.play();
				var egkep = new lib.end_wrong();
			}
			end_kep_cont.removeAllChildren();
			end_kep_cont.addChild(egkep);
			stage.addChild(end_kep_cont);
			
			var text = new createjs.Text(points.toFixed(2), "14px Arial", "#000000");
			text.x = 388;
			text.y = 235;
			text.textAlign = "right";
			end_kep_cont.addChild(text);
			
			new_btn = new lib.new_btn();
			new_btn.x = 302;
			new_btn.y = 339;
			new_btn.onMouseOver = function(e) {
				document.body.style.cursor = "pointer";
			}
			new_btn.onMouseOut = function(e) {
				document.body.style.cursor = "default";
			}
			new_btn.onPress = startGame;
			stage.addChild(new_btn);
		}

		function str_pad (input, pad_length, pad_string, pad_type) {
		  // http://kevin.vanzonneveld.net
		  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		  // + namespaced by: Michael White (http://getsprink.com)
		  // +      input by: Marco van Oort
		  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
		  // *     example 1: str_pad(\'Kevin van Zonneveld\', 30, \'-=\', \'STR_PAD_LEFT\');
		  // *     returns 1: \'-=-=-=-=-=-Kevin van Zonneveld\'
		  // *     example 2: str_pad(\'Kevin van Zonneveld\', 30, \'-\', \'STR_PAD_BOTH\');
		  // *     returns 2: \'------Kevin van Zonneveld-----\'
		  var half = \'\',
			pad_to_go;

		  var str_pad_repeater = function (s, len) {
			var collect = \'\',
			  i;

			while (collect.length < len) {
			  collect += s;
			}
			collect = collect.substr(0, len);

			return collect;
		  };

		  input += \'\';
		  pad_string = pad_string !== undefined ? pad_string : \' \';

		  if (pad_type != \'STR_PAD_LEFT\' && pad_type != \'STR_PAD_RIGHT\' && pad_type != \'STR_PAD_BOTH\') {
			pad_type = \'STR_PAD_RIGHT\';
		  }
		  if ((pad_to_go = pad_length - input.length) > 0) {
			if (pad_type == \'STR_PAD_LEFT\') {
			  input = str_pad_repeater(pad_string, pad_to_go) + input;
			} else if (pad_type == \'STR_PAD_RIGHT\') {
			  input = input + str_pad_repeater(pad_string, pad_to_go);
			} else if (pad_type == \'STR_PAD_BOTH\') {
			  half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
			  input = half + input + half;
			  input = input.substr(0, pad_length);
			}
		  }

		  return input;
		}
		'; ?>

		</script>
	<div style="text-align: center;">
	Belépve: <?php echo $_SESSION['usermail']; ?>
 | <a href="index.php?p=account&act=account_out">Kilépés</a>
	<br />
	<br />
	
	<canvas id="canvas" width="750" height="500" style="background-color:#ffffff"></canvas>
	
	<br />
	<br />
	<a href="jatekszabaly.pdf" target="_blank">Szabályzat</a>
	</div>
<?php else: ?>
	<script src="clearbox.js"></script>
	<div style="clear: both; text-align: center;">
		<a href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/scr_1_csapos_n.jpg" rel="clearbox[gallery=game]"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/scr_1_csapos.jpg"></a> 
		<a href="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/scr_2_csapos_n.jpg" rel="clearbox[gallery=game]"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/images/scr_2_csapos.jpg"></a>
	</div>
	<div style="clear: both; text-align: center;">
		Amennyiben játszani szeretnél jelentkezz be!
	</div>
	<?php $this->assign('prevpage', 'game'); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "block_account.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<div style="text-align: center; font-size: 14px;">
A játékosok között naponta értékes ajándékcsomagot sorsolunk ki!
<br />
<br />
<div style="text-align: center;">
	A Cseppecske kifestõ letöltéséhez kattints a képre!<br />
	<a href="Cseppecske_fekete_feher.pdf" target="_blank"><img src="<?php echo $this->_tpl_vars['theme_dir']; ?>
/themes/focus/images/csapos/cseppecske.jpg" /></a>
</div>