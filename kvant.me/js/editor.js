
function html_parse($textarea) {
    var html = $textarea.val();

    // replace known tags
    html = html.replace(/(<media)([^>]*)(>)/gm, "[media$2]")
               .replace(/(<img)([^>]*)(>)/gm, "[img$2]")
               .replace(/(<a(.*)?(href=['"][^'"]+['"])([^>]+)?[>])/gm, "[a $3]") // remove all except href="" attribute
               .replace(/<\/(a|media)>/g, "[\/$1]")
               .replace(/<(\/?)(strong|em|sup|sub|spoiler|irony)(:?[^>]*)?(>)/gm, "[$1$2]")
               .replace(/<(\/?)([biu])(?:[^>]*)?(>)/gm, "[$1$2]")
               .replace(/(accesskey|class|contenteditable|contextmenu|dir|hidden|id|lang|spellcheck|style|tabindex|title|xml:lang|onblur|onchange|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onselect|onsubmit|onunload)(?:\s?=\s?)(['"][^'"]*['"])?/gm, ""); // remove additional attributes and events

    //replace all unknown tags as lt-gt
    html = html.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');

    // return back known tags
    html = html.replace(/(\[media)([^\]]*)(\])/gm, "<media$2>")
               .replace(/(\[img)([^\]]*)(\])/gm, "<img$2>")
               .replace(/((\[a)(.*)?(href=["'][^"']+["'])([^\]]+)?[\]])/gm, "<a $4>")
               .replace(/\[\/(a|media)\]/gm, "<\/$1>")
               .replace(/\[(\/?)(strong|em|sup|sub|spoiler|irony)(\])/gm, "<$1$2>")
               .replace(/\[(\/?)([biu])\]/gm, "<$1$2>")
               .replace(/\n/g, "<br />");

    // test for emptiness
    var ivpattern = /<media|<img/g;
    var x = $('<div></div>');
    x.html(html);
    if(x.text() === "" && !ivpattern.test(html)) {
        return false; // empty html
  	}

    if(html != "")
    {
        var doc = document.createElement('div');
        doc.innerHTML = html;
        if( doc.innerHTML !== html ) {
           html = doc.innerHTML; // change html by well-formed
        }
    }

    $textarea.val(html);
    return true;
}

function post_parse($textarea)
{
    var html = $textarea.val();
    html = html.replace(/<br(\/?)|(\ ?)>/g, "\n");
    $textarea.val(html);
}

(function($) {
    $.fn.parseVideo = function() {
        return this.each( function () {
             var uri = new URI($(this).attr('src'));
             if(uri.domain() == 'youtube.com' || uri.domain() == 'youtu.be') {
                  var uo = uri.domain() == 'youtube.com' ? uri.search(true) : { v: uri.path() };
                  $(this).after('<iframe class="videoframe" src="http://www.youtube.com/embed/'
                      + uo.v  +'"></iframe>');
             }

             if(uri.domain() == 'vimeo.com') {
                  var vid = uri.path();
                  $(this).after('<iframe class="videoframe" src="http://player.vimeo.com/video'
                         + vid  +
                        '?color=ff9f40" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
             }

             $(this).remove();
        })
    }; 
})(jQuery);

function base64_encode( data ) {  // Encodes data with MIME base64
  // 
  // +   original by: Tyler Akins (http://rumkin.com)
  // +   improved by: Bayron Guevara

  var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
  var o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='';

  do { // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1<<16 | o2<<8 | o3;

    h1 = bits>>18 & 0x3f;
    h2 = bits>>12 & 0x3f;
    h3 = bits>>6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    enc += b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  switch( data.length % 3 ){
    case 1:
      enc = enc.slice(0, -2) + '==';
    break;
    case 2:
      enc = enc.slice(0, -1) + '=';
    break;
  }

  return enc;
}
