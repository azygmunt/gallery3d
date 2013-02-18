var f = 500;
var flickerid;

$(document).ready(function() {
	console.log('document ready');
	//create the pagination
	$('#viewport').html('<div class="onscreen view">');
	$('#viewport').append('<div class="offscreen view">');
	$('#viewport').append('<div class="loader view">');

	fillTOC('#viewport');
	$('.viewindex').click(function(event) {
		fillTOC($('#viewport'));
	});
	$('#width').change(function() {
		setLinks();
	});
	$('#gap').change(function() {
		setLinks();
	});

	$("input:radio[name=color]").change(function() {
		setLinks();
	});

	$('#viewtype').change(function() {
		setLinks();
		setControls();
	});

	$(window).resize(function() {
		setViewHeight();
	});
	setViewHeight();
});

function setViewHeight() {
	var h = $(window).height();
	//	alert($(window).height());
	$('#viewport').css({
		'height' : h - 150
	});
}

function setControls() {
	console.log('setting controls');
	$('#ctrl-type').fadeIn(f);
	$('#ctrl-width').fadeIn(f);
	$('#ctrl-index').fadeIn(f);
	var viewtype = $("select[name=viewtype] option:selected").val();
	switch (viewtype) {
		case "parallel":
		case "crosseye":
			$('#ctrl-gap').fadeIn(f);
			$('#ctrl-rate').fadeOut(f);
			$('#ctrl-color').fadeOut(f);
			break;
		case 'flicker':
			$('#ctrl-gap').fadeOut(f);
			$('#ctrl-rate').fadeIn(f);
			$('#ctrl-color').fadeOut(f);
			break;
		case 'redblue':
			$('#ctrl-color').fadeIn(f);
			$('#ctrl-gap').fadeOut(f);
			$('#ctrl-rate').fadeOut(f);
			break;
		case 'left':
		case 'right':
			$('#ctrl-gap').fadeOut(f);
			$('#ctrl-rate').fadeOut(f);
			$('#ctrl-color').fadeOut(f);
			break;
		default:
			alert('setcontrols default');
	}
}

function setLinks() {
	console.log('setting links');
	var size = calcWidth();
	//	console.log('iw: ' + size['iw']);
	//	console.log('ih: ' + size['ih']);
	var viewtype = $("select[name=viewtype] option:selected").val();
	var color = $("input[name=color]:checked").val();
	var width = $('#width').val();
	var gap = $('#gap').val();
	//	console.log(color);
	//	alert(color);

	$('a', $('#gallery')).each(function() {
		var href = $(this).attr('href');
		//		console.log('rewriting: ' + href);
		var id = getURLParameterFromString('id', href);
		var url = getURL($(this).attr('href'));
		var fullurl = url + '?id=' + id + '&width=' + width + '&gap=' + gap + '&type=' + viewtype + '&color=' + color;

		//		console.log(fullurl);
		$(this).attr({
			'href' : fullurl
		});
	});

	$('a', $('#gallery')).colorbox({
		scrolling : false,
		preloading : false,
		slideshow : true,
		slideshowSpeed : 4000,
		slideshowAuto : false,
		slideshowStart : "start slideshow",
		slideshowStop : "stop slideshow",

		//		iframe : true,
		//html : 'true',
		//				data : 'width=' + $('#width').val(),

		innerWidth : size['iw'] + 'px',
		innerHeight : size['ih'] + 'px',
		opacity : 1,
		rel : 'gal',
		onComplete : function() {
			var viewtype = $("select option:selected").val();
			//reset any flickering
			clearInterval(flickerid);
			$('.image-L').show();
			$('.image-R').show();
			var pos = calcPos();
			switch (viewtype) {
				case "parallel":
				case "crosseye":
					$('.image-L').css({
						top : pos['ly'] + 'px',
						left : pos['lx'] + 'px'
					});
					$('.image-R').css({
						top : pos['ry'] + 'px',
						left : pos['rx'] + 'px'
					});
					break;
				case 'flicker':
					$('.image-L').show();
					$('.image-R').hide();
					flickerid = setInterval(swapimage, $('#rate').val());
					break;
				case 'left':
					$('.image-L').show();
					$('.image-R').hide();
					break;
				case 'right':
					$('.image-L').hide();
					$('.image-R').show();
					break;
				case 'redblue':
					break;
				default:
					alert('def');
			}
		}
	});
}

function startLoad($target, id) {
	var $off = $('.offscreen', $target);
	var $on = $('.onscreen', $target);
	var $loader = $('.loader', $target);

	$on.fadeOut(f);
	$loader.fadeIn(f);
	$off.attr({
		'id' : id
	});
}

function finishLoad($target, html) {
	var $off = $('.offscreen', $target);
	var $on = $('.onscreen', $target);
	var $loader = $('.loader', $target);

	$off.html(html);
	$loader.fadeOut(f);
	$off.fadeIn(f, function() {
		console.log();
		setLinks();
	});

	$on.removeClass('onscreen');
	$off.removeClass('offscreen');
	$on.addClass('offscreen');
	$off.addClass('onscreen');
}

function fillTOC($target) {
	console.log('filling TOC');
	//set up the viewport
	$('#controls').fadeOut();
	$('#header h1').html('3d Photo Gallery');
	startLoad($target, 'toc');

	$.ajax({
		url : 'toc.php',
		data : '',
		success : function(data) {
			finishLoad($target, data);

			//set the clicks to redirect the php output to the gallery
			$('a', $('#toc')).click(function(e) {
				e.preventDefault();
				fillGallery($(this), $target);
			});
		}
	});
	var html;
}

function calcWidth() {
	var viewtype = $("select option:selected").val();
	var w = parseInt($("#width").val());
	var g = parseInt($("#gap").val());
	var iw = 0;
	var ih = 0;
	var r = 0;
	var mr = 0;

	$('img', $('#gallery')).each(function() {
		r = $(this).height() / $(this).width();
		//		console.log(r);
		if (r > mr) {
			mr = r;
		}
	});
	ih = w * mr * 1.1;
	switch (viewtype) {
		case "parallel":
		case "crosseye":
			iw = (2 * w) + g;
			break;
		case 'flicker':
		case 'redblue':
		case 'left':
		case 'right':
			iw = w;
			break;
		default:
			alert('def');
	}

	console.log('r: ' + r);
	console.log('w: ' + w);
	console.log('g: ' + g);
	console.log('iw: ' + iw);
	console.log('ih: ' + ih);

	//	alert($('.image-L').attr('height'));
	return ( {
		'iw' : iw,
		'ih' : ih
	});
}

function calcPos() {
	var viewtype = $("select option:selected").val();
	var w = parseInt($("#width").val());
	var g = parseInt($("#gap").val());
	var lx = 0;
	var ly = 0;
	var rx = 0;
	var ry = 0;
	switch (viewtype) {
		case "parallel":
			rx = w + g;
			break;
		case "crosseye":
			lx = w + g;
			break;
		case 'flicker':
		case 'redblue':
		case 'left':
		case 'right':
			break;
		default:
			alert('def');
	}

	console.log('w: ' + w);
	console.log('g: ' + g);
	console.log('lx: ' + lx);
	console.log('ly: ' + ly);
	console.log('rx: ' + rx);
	console.log('ry: ' + ry);

	return ( {
		'lx' : lx,
		'ly' : ly,
		'rx' : rx,
		'ry' : ry
	});
}

function fillGallery($link, $target) {
	console.log('filling gallery');

	startLoad($target, 'gallery');

	//set the gallery thumbnail width
	var width = 150;

	//get the section name from the url and add the thumbnail width
	var params = getURLParameters($link.attr('href') + '&width=' + width);
	$.ajax({
		url : 'gallery.php',
		data : params,
		success : function(data) {
			//fill the gallery div with a thumbnail grid
			finishLoad($target, data);
			//			alert(params);
			//			alert(getURLParameterFromString('section',params));
			var section = getURLParameterFromString('section', params);
			$('#header h1').append(' > ' + section);
			//alert('filling gallery');
			$('#controls').fadeIn(f);
			setControls();
		}
	});
}

function swapimage() {
	$('.image-L').toggle();
	$('.image-R').toggle();
}

function getURLParameter(name) {
	return decodeURI((RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]);
}

function getURLParameterFromString(name, url) {
	return decodeURI((RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1]);
}

function getURLParameters(url) {
	var urlparts = url.split('?');
	return (urlparts[1]);
}

function getURL(url) {
	var urlparts = url.split('?');
	return (urlparts[0]);
}
