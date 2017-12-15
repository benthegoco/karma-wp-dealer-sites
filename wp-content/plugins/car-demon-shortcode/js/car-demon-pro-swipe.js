(function(jQuery, undefined) {
	jQuery(document).ready(function() {
		var wrap = jQuery('.bjqs'),
			slides = wrap.find('.bjqs-slide'),
			active = slides.filter('.active'),
			i = slides.index(active),
			width = wrap.width();
		// Listen for swipe events on slides, and use a custom 'activate'
		// event to add and remove the class 'active' to the previous
		// or next slide, and to keep the index up-to-date. The class
		// 'active' uses CSS transitions to make the slide move.
		slides
		.on('swipeleft', function(e) {
			if (i === slides.length - 1) { return; }
			slides.eq(i + 1).trigger('activate');
			alert('left');
		})
		.on('swiperight', function(e) {
			if (i === 0) { return; }
			slides.eq(i - 1).trigger('activate');
		})
		.on('activate', function(e) {
			slides.eq(i).removeClass('active');
			jQuery(e.target).addClass('active');
			// Update the active slide index
			i = slides.index(e.target);
		})
		// The code below handles what happens before any swipe event is triggered.
		// It makes the slides demo on this page work nicely, but really doesn't
		// have much to do with demonstrating the swipe events themselves. For more
		// on move events see:
		//
		// http://stephband.info/jquery.event.move
		.on('movestart', function(e) {
			// If the movestart heads off in a upwards or downwards
			// direction, prevent it so that the browser scrolls normally.
			if ((e.distX > e.distY && e.distX < -e.distY) ||
				(e.distX < e.distY && e.distX > -e.distY)) {
				e.preventDefault();
				return;
			}
			// To allow the slide to keep step with the finger,
			// temporarily disable transitions.
			wrap.addClass('notransition');
		})
		.on('move', function(e) {
			var left = 100 * e.distX / width;
			// Move slides with the finger
			if (e.distX < 0) {
				if (slides[i+1]) {
					slides[i].style.left = left + '%';
					slides[i+1].style.left = (left+100)+'%';
				}
				else {
					slides[i].style.left = left/4 + '%';
				}
			}
			if (e.distX > 0) {
				if (slides[i-1]) {
					slides[i].style.left = left + '%';
					slides[i-1].style.left = (left-100)+'%';
				}
				else {
					slides[i].style.left = left/5 + '%';
				}
			}
		})
		.on('moveend', function(e) {
			wrap.removeClass('notransition');
			
			slides[i].style.left = '';
			if (slides[i+1]) {
				slides[i+1].style.left = '';
			}
			if (slides[i-1]) {
				slides[i-1].style.left = '';
			}
		});
		// Make the buttons work, too. Hijack the id stored in the href and use
		// it to activate the slide.
		jQuery(document)
		.on('click', '.slide_button', function(e) {
			var href = e.currentTarget.hash;
			jQuery(href).trigger('activate');
			e.preventDefault();
		});
	});
})(jQuery);