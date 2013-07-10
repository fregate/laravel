
// comm editor bar
var comm_editor_settings = {
    onTab: { keepDefault: false, replaceWith: '    ' },
    markupSet: [
        {name:'Bold', className: 'Bold', key:'B', openWith:'(!(<b>|!|<strong>)!)', closeWith:'(!(</b>|!|</strong>)!)' },
        {name:'Emphasis', className: 'Emphasis', key:'I', openWith:'(!(<i>|!|<em>)!)', closeWith:'(!(</i>|!|</em>)!)'  },
//      {name:'Stroke through', className: 'Stroke', key:'S', openWith:'<del>', closeWith:'</del>' },
        {name:'Underline', className: 'Underline', key:'U', openWith:'<u>', closeWith:'</u>' },
        {name:'Superscript', className: 'Sup', openWith:'<sup>', closeWith:'</sup>' },
        {name:'Subscript', className: 'Sub', openWith:'<sub>', closeWith:'</sub>' },
        {separator:'---------------' },
        {name:'Spoiler', className: 'Spoiler', openWith:'<spoiler>', closeWith:'</spoiler>' },
        {name:'Irony', className: 'Irony', openWith:'<irony>', closeWith:'</irony>' },
        {separator:'---------------' },
        {name:'Picture', className: 'Image', key:'P', replaceWith:'<img src="[![Source:!:http://]!]" alt="[![Alternative text]!]" />' },
        {name:'Link', className: 'Link', key:'L', openWith:'<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
        {name:'Video', className: 'Video', openWith:'<media src="[![Link:!:http://]!]" />', closeWith: '</media>' }
//      {name:'Audio', className: 'Audio', openWith:'<audio>', closeWith:'</audio>' }
    ]
};

// post editor bar
var post_editor_settings = {
    onTab: { keepDefault: false, replaceWith: '    '},
    markupSet: [
        {name:'Bold', className: 'Bold', key:'B', openWith:'(!(<b>|!|<strong>)!)', closeWith:'(!(</b>|!|</strong>)!)' },
        {name:'Emphasis', className: 'Emphasis', key:'I', openWith:'(!(<i>|!|<em>)!)', closeWith:'(!(</i>|!|</em>)!)'  },
//      {name:'Stroke through', className: 'Stroke', key:'S', openWith:'<del>', closeWith:'</del>' },
        {name:'Underline', className: 'Underline', key:'U', openWith:'<u>', closeWith:'</u>' },
        {name:'Superscript', className: 'Sup', openWith:'<sup>', closeWith:'</sup>' },
        {name:'Subscript', className: 'Sub', openWith:'<sub>', closeWith:'</sub>' },
        {separator:'---------------' },
        {name:'Spoiler', className: 'Spoiler', openWith:'<spoiler>', closeWith:'</spoiler>' },
        {separator:'---------------' },
        {name:'Picture', className: 'Image', key:'P', replaceWith:'<img src="[![Source:!:http://]!]" alt="[![Alternative text]!]" />' },
        {name:'Link', className: 'Link', key:'L', openWith:'<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
        {name:'Video', className: 'Video', openWith:'<media src="[![Link:!:http://]!]" />', closeWith: '</media>' }
//      {name:'Audio', className: 'Audio', openWith:'<audio>', closeWith:'</audio>' }
    ]
};

function html_parse($textarea) {
    var html = $textarea.val();

    // replace known tags
    html = html.replace(/(<media)([^>]*)(>)/gm, "[media$2]")
               .replace(/(<img)([^>]*)(>)/gm, "[img$2]")
               .replace(/(<a)(.*)?(href=['|"]([^['|"]]*)['|"])([^>]*)(>)/gm, "[a $3]") // remove all except href="" attribute
               .replace(/<\/(a|media)>/g, "[\/$1]")
               .replace(/<(\/?)(strong|em|sup|sub|spoiler|irony)(:?[^>]*)?(>)/gm, "[$1$2]")
               .replace(/<(\/?)([biu])(?:[^>]*)?(>)/gm, "[$1$2]")
               .replace(/(accesskey|class|contenteditable|contextmenu|dir|hidden|id|lang|spellcheck|style|tabindex|title|xml:lang|onblur|onchange|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onselect|onsubmit|onunload)(?:\s?=\s?)(['"][^'"]*['"])?/gm, ""); // remove additional attributes and events

    //replace all unknown tags as lt-gt
    html = html.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');

    // return back known tags
    html = html.replace(/(\[media)([^\]]*)(\])/gm, "<media$2>")
               .replace(/(\[img)([^\]]*)(\])/gm, "<img$2>")
               .replace(/(\[a)([^\]]*)(\])/gm, "<a$2>")
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
