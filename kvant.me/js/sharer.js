var ptitle, psummary, pimage, purl;

    $(document).ready(function() {
        ptitle = encodeURIComponent($('meta[property="og:title"]').attr('content'));
	if(!ptitle || 0 == ptitle.length || ptitle === undefined) {
           ptitle = encodeURIComponent($('title').text());
           $('meta[property="og:title"]').attr('content', ptitle);
        }

        psummary = $('#articlemain').text();
        psummary = psummary.length > 128 ? psummary.substr(0, 125) + "..." : psummary;
        psummary = encodeURIComponent(psummary);
        pimage = encodeURIComponent($('meta[property="og:image"]').attr('content'));
        purl = encodeURIComponent($('meta[property="og:url"]').attr('content'));
    });

function fbshare() {
	var shareurl = "http://www.facebook.com/sharer.php?s=100&p[title]=" + ptitle 
		+ "&p[summary]=" + psummary
		+ "&p[url]=" + purl
		+ "&p[images][0]=" + pimage;
	window.open(shareurl,'Share on Facebook','toolbar=0,status=0,width=600,height=325');
}

function vkshare() {
	var shareurl = "http://vkontakte.ru/share.php?url=" + purl
		+ "&title=" + ptitle
		+ "&description=" + psummary
		+ "&image=" + pimage
		+ "&noparse=true";
	window.open(shareurl, 'Опубликовать ссылку во Вконтакте', 'toolbar=0,status=0,width=600,height=325');
}

function gpshare() {
	var shareurl = "https://plus.google.com/share?url=" + purl;
	window.open(shareurl, 'Share on Google+', 'toolbar=0,status=0,width=600,height=325');
}

function tweet() {
	var shareurl = "https://twitter.com/intent/tweet?url=" + purl
		+ "&text=" + ptitle;
	window.open(shareurl, 'Share on Google+', 'toolbar=0,status=0,width=600,height=325');
}
