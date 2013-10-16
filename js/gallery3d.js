var f = 500;
var flickerid;
$(document).ready(function() {
	console.log('document ready');
	//	setViewHeight($('#loading'));
	//	alert($(window).height() - $('.navbar').outerHeight(true));
	//	alert($(window).width());
	fillTOC($('#content'));
	var options = readOptions();

	$('.options1').fancybox({
		type : 'ajax',
		padding : 10
	});

	$('#options #Submit').click(function() {
		setOptions();
		//		$('.options').fancybox.close(true);
	});
});

function setOptions() {
	$.cookie("type", $('#options #type').val());
	$.cookie("anaglyph", $('#options #anaglyph').val());
	alert('Settings Saved');
}

function readOptions() {
	var options = new Array();
	options['type'] = $.cookie("type");
	options['anaglyph'] = $.cookie("anaglyph");
	$("#type option[value=" + options['type'] + "]").attr("selected", "selected");
	$("#anaglyph option[value=" + options['anaglyph'] + "]").attr("selected", "selected");
	return options;
}

function fillTOC($target) {
	$target.fadeOut();
	$('#loading').fadeIn();
	$.ajax({
		url : 'toc.php',
		data : '',
		success : function(data) {
			$('#loading').fadeOut();
			$target.html(data);
			$target.fadeIn();
			$('a', $('.toc')).click(function(e) {
				e.preventDefault();
				fillGallery($(this), $target);
			});
		}
	});
}

function fillGallery($link, $target) {
	$('#loading').fadeIn();

	var width = 200;
	//get the section name from the url and add the thumbnail width
	var params = getURLParameters($link.attr('href') + '&width=' + width);
	$.ajax({
		url : 'gallery.php',
		data : params,
		success : function(data) {
			$target.html(data);
			listToGrid($('.gridlist'));

			$('#loading').fadeOut();
			$target.fadeIn();
			$('a.fancybox').fancybox({
				type : 'ajax',
				padding : 10,
				autoPlay : false,
				playSpeed : 500,
				closeBtn : true,
				//				height : $(window).height() - $('.navbar').outerHeight(true) - 200,
				autoSize : true,
				width : 'auto',
				height : 300,
				beforeShow : function() {
					$('.image-ana img').css({
						'max-height' : $(window).height() - $('.navbar').outerHeight(true) - 20 + 'px',
						'max-width' : $(window).width() + 'px'
					});
				},
				helpers : {
					title : {
						type : 'inside'
					},
					buttons : {}
				}
			});
		}
	});
}

function listToGrid($list) {
	var count = 0;
	var $grid = $('<div class="listgrid" />');
	var $row;
	var cols = 6;
	var cols_mobile = 3;
	$('li', $list).each(function() {
		var max = $('li', $list).length;
		var $item = $('<div class="col-sm-2 col-xs-4" />');
		$item.html($(this).html());
		var modcount = count % cols;
		if (modcount == 0) {
			$row = $('<div class="row">');
		}
		$row.append($item);
		if (modcount == (cols_mobile - 1)) {
			$row.append('<div class="clearfix visible-xs" />');
		}
		if (modcount == (cols - 1) || count == (max - 1)) {
			$grid.append($row);
		}++count;
	});
	$list.replaceWith($grid);
}

function setViewHeight($target) {
	$target.css({
		'height' : $(window).height() - $('.navbar').outerHeight(true)
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
	var viewtype = $("select[name=viewtype] option:selected").val();
	var color = $("input[name=color]:checked").val();
	var width = $('#width').val();
	var gap = $('#gap').val();
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

function fillTOCv1($target) {
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