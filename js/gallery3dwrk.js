$(document).ready(function() {
	console.log('document ready');
	$(window).resize(function() {
		setViewHeight();
	});
	setViewHeight();
	setHeader();
});

function setViewHeight() {
	var h = $(window).height();
	console.log($(window).height());
	$('#threedviewer').css({
		'height' : h
	});
}

function setHeader() {
	var size_header = {
		'height' : 60,
		'margin' : 15
	};
	$('#header').css({
		'height' : size_header.height
	});
	$('#title').css({
		'line-height' : size_header.height + 'px',
		'font-size' : size_header.height - (2 * size_header.margin) + 'px',
		'padding' : '0px ' + size_header.margin + 'px 0px ' + size_header.margin + 'px'
	});
}
