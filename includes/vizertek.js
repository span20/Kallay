(function (lib, img, cjs) {

var p; // shortcut to reference prototypes

// stage content:
(lib.web_jatek = function() {
	this.initialize();

	// Layer 1
	this.instance = new lib.jatek_back();

	this.addChild(this.instance);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(0,0,525,700);


// symbols:
(lib.alma = function() {
	this.initialize(img.alma);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,62,69);


(lib.kerek = function() {
	this.initialize(img.kerek);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,32,32);


(lib.csepp_1 = function() {
	this.initialize(img.csepp_1);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,30,70);


(lib.csepp_2 = function() {
	this.initialize(img.csepp_2);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,30,70);


(lib.jatek_back = function() {
	this.initialize(img.jatek_back);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,525,700);

(lib.eredmeny_back = function() {
	this.initialize(img.eredmeny_back);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,525,700);

(lib.end_1 = function() {
	this.initialize(img.end_1);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,525,349);

(lib.end_2 = function() {
	this.initialize(img.end_2);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,525,349);

(lib.end_3 = function() {
	this.initialize(img.end_3);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,525,349);

(lib.end_4 = function() {
	this.initialize(img.end_4);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,525,349);

(lib.end_5 = function() {
	this.initialize(img.end_5);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,525,349);

(lib.end_6 = function() {
	this.initialize(img.end_6);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,525,349);

(lib.end_7 = function() {
	this.initialize(img.end_7);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,525,349);

(lib.intro = function() {
	this.initialize(img.intro);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,525,700);

(lib.indit_btn = function() {
	this.initialize(img.indit_btn);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,178,29);

(lib.szorp = function() {
	this.initialize(img.szorp);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,45,80);

(lib.new_btn = function() {
	this.initialize(img.new_btn);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,178,29);

(lib.virag = function() {
	this.initialize(img.virag);
}).prototype = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,65,89);


(lib.cseppek = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{},true);

	// Layer 1
	this.instance = new lib.csepp_1();
	this.instance.setTransform(-14.9,0);

	this.instance_1 = new lib.csepp_2();
	this.instance_1.setTransform(-14.9,0);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.instance}]}).to({state:[{t:this.instance_1}]},10).wait(10));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-14.9,0,30,70);

(lib.Tween1 = function() {
	this.initialize();

	// Layer 1
	this.instance = new lib.kerek();
	this.instance.setTransform(-15.9,-15.9);

	this.addChild(this.instance);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(-15.9,-15.9,32,32);

(lib.kerekMc = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{},true);

	// Layer 1
	this.instance_3 = new lib.Tween1("synched",0);
	this.addChild(this.instance_3);
	
}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-15.9,-15.9,32,32);

(lib.csepp_cont_1 = function() {
	this.initialize();

	this.addChild(this.instance);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(0,0,30,70);

(lib.csepp_cont_2 = function() {
	this.initialize();

	this.addChild(this.instance);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(0,0,30,70);

(lib.csepp_cont_3 = function() {
	this.initialize();

	this.addChild(this.instance);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(0,0,30,70);

(lib.end_cont = function() {
	this.initialize();

	this.addChild(this.instance);
}).prototype = p = new cjs.Container();
p.nominalBounds = new cjs.Rectangle(0,0,525,349);

})(lib = lib||{}, images = images||{}, createjs = createjs||{});
var lib, images, createjs;