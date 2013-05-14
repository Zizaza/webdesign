window.addEvent('load', function(){
	// smooth anchor scrolling
	new SmoothScroll(); 
	// style area
	if(document.id('gkStyleArea')){
		$$('#gkStyleArea a').each(function(element,index){
			element.addEvent('click',function(e){
	            e.stop();
				changeStyle(index+1);
			});
		});
	}
	// font-size switcher
	if(document.id('gkTools') && document.id('gkMainbody')) {
		var current_fs = 100;
		var content_fx = new Fx.Tween(document.id('gkMainbody'), { property: 'font-size', unit: '%', duration: 200 }).set(100);
		document.id('gkToolsInc').addEvent('click', function(e){ 
			e.stop(); 
			if(current_fs < 150) { 
				content_fx.start(current_fs + 10); 
				current_fs += 10; 
			} 
		});
		document.id('gkToolsReset').addEvent('click', function(e){ 
			e.stop(); 
			content_fx.start(100); 
			current_fs = 100; 
		});
		document.id('gkToolsDec').addEvent('click', function(e){ 
			e.stop(); 
			if(current_fs > 70) { 
				content_fx.start(current_fs - 10); 
				current_fs -= 10; 
			} 
		});
	}
	// K2 font-size switcher fix
	if(document.id('fontIncrease') && document.getElement('.itemIntroText')) {
		document.id('fontIncrease').addEvent('click', function() {
			document.getElement('.itemIntroText').set('class', 'itemIntroText largerFontSize');
		});
		
		document.id('fontDecrease').addEvent('click', function() {
			document.getElement('.itemIntroText').set('class', 'itemIntroText smallerFontSize');
		});
	}
	// login popup
	if(document.id('gkPopupLogin') || document.id('gkPopupCart')) {
		var popup_overlay = document.id('gkPopupOverlay');
		popup_overlay.setStyles({'display': 'block', 'opacity': '0'});
		popup_overlay.fade('out');

		var opened_popup = null;
		var popup_login = null;
		var popup_login_h = null;
		var popup_login_fx = null;
		var popup_cart = null;
		var popup_cart_h = null;
		var popup_cart_fx = null;
		
		if(document.id('gkPopupLogin')) {
			popup_login = document.id('gkPopupLogin');
			popup_login.setStyle('display', 'block');
			popup_login_h = popup_login.getElement('.gkPopupWrap').getSize().y;
			popup_login_fx = new Fx.Morph(popup_login, {duration:200, transition: Fx.Transitions.Circ.easeInOut}).set({'opacity': 0, 'height': 0 }); 
			document.id('btnLogin').addEvent('click', function(e) {
				new Event(e).stop();
				popup_overlay.fade(0.45);
				popup_login_fx.start({'opacity':1, 'height': popup_login_h});
				opened_popup = 'login';
				
				(function() {
					if(document.id('modlgn-username')) {
						document.id('modlgn-username').focus();
					}
				}).delay(600);
			});
		}
		
		if(document.id('gkPopupCart')) {
			popup_cart = document.id('gkPopupCart');
			popup_cart.setStyle('display', 'block');
			popup_cart_h = popup_cart.getElement('.gkPopupWrap').getSize().y;
			popup_cart_fx = new Fx.Morph(popup_cart, {duration:200, transition: Fx.Transitions.Circ.easeInOut}).set({'opacity': 0, 'height': 0 }); 
			var wait_for_results = true;
			var wait = false;
			var loadingText = document.id('btnCart').getElement('span').innerHTML;
			document.id('btnCart').getElement('span').dispose();
			var baseText = document.id('btnCart').innerHTML;
			
			document.id('btnCart').addEvent('click', function(e) {
				new Event(e).stop();	
				
				if(!wait) {
					new Request.HTML({
						url: $GK_URL + 'index.php?tmpl=cart',
						onRequest: function() {
							document.id('btnCart').innerHTML = loadingText;
							wait = true;
						},
						onComplete: function() {
							var timer = (function() {
								if(!wait_for_results) {
									popup_overlay.fade(0.45);
									popup_cart_fx.start({'opacity':1, 'height': popup_cart_h});
									opened_popup = 'cart';
									wait_for_results = true;
									wait = false;
									clearInterval(timer);
									document.id('btnCart').innerHTML = baseText;
								}
							}).periodical(200);
						},
						onSuccess: function(nodes, xml, text) {
							document.id('gkAjaxCart').innerHTML = text;
							popup_cart.setStyle('display', 'block');
							popup_cart_h = popup_cart.getElement('.gkPopupWrap').getSize().y;
							popup_cart_fx = new Fx.Morph(popup_cart, {duration:200, transition: Fx.Transitions.Circ.easeInOut}).set({'opacity': 0, 'height': 0 }); 
							wait_for_results = false;
							wait = false;
						}
					}).send();
				}
			});
		}
		
		popup_overlay.addEvent('click', function() {
			if(opened_popup == 'login')	{
				popup_overlay.fade('out');
				popup_login_fx.start({
					'opacity' : 0,
					'height' : 0
				});
			}
			
			if(opened_popup == 'cart')	{
				popup_overlay.fade('out');
				popup_cart_fx.start({
					'opacity' : 0,
					'height' : 0
				});
			}	
		});
	}
	// zoom icon in NSP
	document.getElements('.zoom').each(function(el) {
		el.getElements('img').each(function(img) {
			if(img.getSize().x > 100) {
				var zoomicon = new Element('div', {'class': 'nspZoom'});
				zoomicon.setStyles({
					'top': '100%',
					'left': '50%',
					'margin-left': '-47px',
					'margin-top': '-47px',
					'opacity': 0
				});
				zoomicon.set('tween', {duration: 300});
				zoomicon.inject(img, 'after');
				var parent = img.getParent();
				parent.addEvents({
					'mouseenter' : function() {
						if(img.getSize().y > 100) {
							new Fx.Tween(zoomicon, {duration: 150, unit: '%' }).start('top', 50);
							zoomicon.fade('in');
						}
					},
					'mouseleave' : function() {
						new Fx.Tween(zoomicon, {duration: 150, unit: '%' }).start('top', 100);
						zoomicon.fade('out');
					}
				});
			}
		});
	});
	// zoom icon in VM
	document.getElements('.main-image').each(function(el) {
		el.getElements('img').each(function(img) {	
			var zoomicon = new Element('div', {'class': 'vmZoom'});
			zoomicon.setStyles({
				'top': '70%',
				'left': '50%',
				'margin-left': '-47px',
				'margin-top': '-47px',
				'opacity': 0
			});
			zoomicon.set('tween', {duration: 300});
			zoomicon.inject(img, 'after');
			var parent = img.getParent();
			parent.addEvents({
				'mouseenter' : function() {
					if(img.getSize().y > 100) {
						new Fx.Tween(zoomicon, {duration: 150, unit: '%' }).start('top', 50);
						zoomicon.fade('in');
					}
				},
				'mouseleave' : function() {
					new Fx.Tween(zoomicon, {duration: 150, unit: '%' }).start('top', 70);
					zoomicon.fade('out');
				}
			});
		});
	});
});
// function to set cookie
function setCookie(c_name, value, expire) {
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expire);
	document.cookie=c_name+ "=" +escape(value) + ((expire==null) ? "" : ";expires=" + exdate.toUTCString());
}
// Function to change styles
function changeStyle(style){
	var file1 = $GK_TMPL_URL+'/css/style'+style+'.css';
	var file2 = $GK_TMPL_URL+'/css/typography/typography.style'+style+'.css';
	var file3 = $GK_TMPL_URL+'/css/typography/typography.iconset.style'+style+'.css';
	new Asset.css(file1);
	new Asset.css(file2);
	new Asset.css(file3);
	Cookie.write('gk_creative_j25_style', style, { duration:365, path: '/' });
	window.location.reload();
}