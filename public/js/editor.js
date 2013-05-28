
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
        {name:'Video', className: 'Video', replaceWith:'<video src="[![Youtube Link:!:http://]!]" />' }
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
        {name:'Video', className: 'Video', replaceWith:'<video src="[![Youtube Link:!:http://]!]" />' }
//      {name:'Audio', className: 'Audio', openWith:'<audio>', closeWith:'</audio>' }
    ]
};

jQuery.fn.encodevalue = function() {
  return this.each(function() {
    var me   = jQuery(this);
    var html = me.val();

    // replace known tags
    html = html.replace(/(<video)([^>]*)(>)/gm, "[video$2]")
               .replace(/(<img)([^>]*)(>)/gm, "[img$2]")
               .replace(/(<a)([^>]*)(>)/gm, "[a$2]")
               .replace(/<\/a>/g, "[\/a]")
               .replace(/<(\/?)(strong|em|sup|sub|spoiler|irony)(:?[^>]*)?(>)/gm, "[$1$2]")
               .replace(/<(\/?)([biu])(?:[^>]*)?(>)/gm, "[$1$2]")
               .replace(/(accesskey|class|contenteditable|contextmenu|dir|hidden|id|lang|spellcheck|style|tabindex|title|xml:lang)(?:\s?=\s?)(['"][^'"]*['"])?/gm, ""); // remove additional attributes

    //replace all unknown tags as lt-gt
    html = html.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');

    // return back known tags
    html = html.replace(/(\[video)([^\]]*)(\])/gm, "<video$2>")
               .replace(/(\[img)([^\]]*)(\])/gm, "<img$2>")
               .replace(/(\[a)([^\]]*)(\])/gm, "<a$2>")
               .replace(/\[\/a\]/gm, "<\/a>")
               .replace(/\[(\/?)(strong|em|sup|sub|spoiler|irony)(\])/gm, "<$1$2>")
               .replace(/\[(\/?)([biu])\]/gm, "<$1$2>");

    // test for emptiness
    var ivpattern = /<video|<img/g;
    var x = $('<div></div>');
    x.html(html);
    if(x.text() === "" && !ivpattern.test(html))
        html = "";

    me.val(html);
  });
};
