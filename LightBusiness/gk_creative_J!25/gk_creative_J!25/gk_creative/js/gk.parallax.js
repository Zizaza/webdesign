window.addEvent('load', function() {
	var parallaxes = [];
	
	document.getElements('.gk-parallax').each(function(el, i) {
		parallaxes.push(new GKParallax(el));
	});
	
	window.addEvent('scroll', function(e) {
		var currentPosition = window.getScroll().y;
		
		parallaxes.each(function(parallax, i) {
			parallax.animate(currentPosition);
		});
	});	
});

var GKParallax = new Class({
	elements: null,
	elementsProperties: [],
	wrapper: null,
	wrapperArea: null,
	wrapperHeight: null,
	wrapperPosition: null,
	wrapperWrap: null,
	
	initialize: function(element) {
		// set the main parallax wrapper
		this.wrapper = element;
		// set the wrapper position in the document
		this.wrapperPosition = this.wrapper.getPosition().y;
		// set the wrapper height
		this.wrapperHeight = this.wrapper.get('data-height').toInt();
		// set the wrapper area
		this.wrapperArea = 1 * this.wrapper.get('data-area').toInt();
		// set the wrapper wrap
		this.wrapperWrap = this.wrapper.getElement('div');
		// set the elements
		this.initElements();
		// show the area
		this.show();
	},
	// Show the parallax area
	show: function() {
		this.wrapper.addClass('loaded');
		this.wrapperWrap.setStyle('overflow', 'visible');
		new Fx.Tween(this.wrapperWrap, {
			duration: 300
		}).start('height', this.wrapper.get('data-height'));
	},
	// Initialize the objects inside parallax area
	initElements: function() {
		// to avoid problems with the this operator
		var $this = this;
		// define animated elements array
		this.elements = this.wrapper.getElements('.gkp-element');
		this.elements.each(function(el, i) {
			var startValues = el.get('data-start').split(',');
			// set the position of the element
			el.setStyles({
				// position
				'left': startValues[0],
				'top': startValues[1],
				// margins
				'margin-top': -1 * (el.getSize().y / 2),
				'margin-left': -1 * (el.getSize().x / 2)
			});
			// set the element properties
			var startXY = el.get('data-start').split(',');
			var endXY = el.get('data-end').split(',');
			
			$this.elementsProperties.push({
				'startX': startXY[0].replace('%', ''),
				'startY': startXY[1].replace('%', ''),
				'endX': endXY[0].replace('%', ''),
				'endY': endXY[1].replace('%', ''),
				'dirX': startXY[0].replace('%', '') * 1.0 - endXY[0].replace('%', '') * 1.0 > 0 ? 'left' : 'right',
				'dirY': startXY[1].replace('%', '') * 1.0 - endXY[1].replace('%', '') * 1.0 > 0 ? 'top' : 'bottom'
			});
		});
	},
	// Animation function connected with the onScroll Window event
	animate: function(y) {
		// to avoid problems with the this operator
		var $this = this;
		// if the scroll offset is inside the parallax area - animate
		if(
			window.getSize().x > 720 
			&&
			(y > this.wrapperPosition - this.wrapperArea) 
			&&
			(y < this.wrapperPosition + this.wrapperHeight + 400)
		) {
			if(y >= this.wrapperPosition + this.wrapperHeight) {
				y = this.wrapperPosition + this.wrapperHeight;
			}
			
			// for every element calculate interpolated value for the specific scroll offset
			this.elements.each(function(element, i) {
				// calculate few values
				var total = Math.abs(1 * $this.wrapperHeight + 1 * $this.wrapperArea);
				var totalWayX = Math.abs($this.elementsProperties[i].endX - $this.elementsProperties[i].startX);
				var totalWayY = Math.abs($this.elementsProperties[i].endY - $this.elementsProperties[i].startY);
				var point = y - ($this.wrapperPosition - $this.wrapperArea);
				var offset = (point * 1.0) / (total * 1.0);
				// set new position
				var elementX = 0;
				var elementY = 0;
				//
				if($this.elementsProperties[i].dirX == 'left') {
					elementX = ($this.elementsProperties[i].startX * 1 - (totalWayX * offset));
				} else {
					elementX = ($this.elementsProperties[i].startX * 1 + (totalWayX * offset));
				}
				
				//
				if($this.elementsProperties[i].dirY == 'top') {
					elementY = ($this.elementsProperties[i].startY * 1 - (totalWayY * offset));
				} else {
					elementY = ($this.elementsProperties[i].startY * 1 + (totalWayY * offset));
				}
				
				elementY = Number.round(elementY, 2);
				elementX = Number.round(elementX, 2);
			
				//
				element.setStyle('left', (elementX * 1.0) + "%");
				element.setStyle('top', (elementY * 1.0) + "%");
			});
		}
	}
});