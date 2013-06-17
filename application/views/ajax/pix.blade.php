<img src="img/DSC06218.jpg" id="target" alt="[Jcrop Example]" />

<div id="preview-pane" class="ui-widget-content">

<div class="navbar">
 <div class="navbar-inner">
  <span class="brand">Preview pane (draggable)</span>
    <div class="well well-small pull-right" >
    <a class="btn btn-info btn-mini" href="#">
      <i class="icon-plus icon-white"></i>
      Add image
    </a>
    <a class="btn btn-danger btn-mini" href="#">
      <i class="icon-ban-circle icon-white"></i>
      Discard this image
    </a>
    <a class="btn btn-success btn-mini" href="#">
      <i class="icon-upload icon-white"></i>
      Confirm upload
    </a>
  </div>
 </div>
</div>
    <div class="preview-container">
      <img src="img/DSC06218.jpg" class="jcrop-preview" alt="Preview" />
    </div>
    Link to prepared image
    <input type=text readonly id="prepimglink" value="" style="display:block;width:605px">
</div>
<script type="text/javascript">
jQuery(function($){

 $( "#preview-pane" ).draggable();
    // Create variables (in this scope) to hold the API and image size
    var jcrop_api,
        boundx,
        boundy,

        // Grab some information about the preview pane
        $preview = $('#preview-pane'),
        $pcnt = $('#preview-pane .preview-container'),
        $pimg = $('#preview-pane .preview-container img'),

        xsize = $pcnt.width(),
        ysize = $pcnt.height();

    var H, W;

    var img = new Image();
    img.src = $pimg.attr('src');

    img.onload = function() {
      H = this.height,
      W = this.width;
    }

    console.log('init',[xsize,ysize]);
    $('#target').Jcrop({
      onChange: updatePreview,
      onSelect: updatePreview,
      onRelease: clearCoords,
      aspectRatio: xsize / ysize
    },function(){
      // Use the API to get the real image size
      var bounds = this.getBounds();
      boundx = bounds[0];
      boundy = bounds[1];
      // Store the API in the jcrop_api variable
      jcrop_api = this;

      // Move the preview into the jcrop container for css positioning
//      $preview.appendTo(jcrop_api.ui.holder);
    });

    function clearCoords(c) {
        $("#prepimglink").val($pimg.attr('src'));
        $("#prepraw").val($pimg.attr('src'));
    }

    function updatePreview(c)
    {
      if (parseInt(c.w) > 0)
      {
        var rx = xsize / c.w;
        var ry = ysize / c.h;

        $pimg.css({
          width: Math.round(rx * boundx) + 'px',
          height: Math.round(ry * boundy) + 'px',
          marginLeft: '-' + Math.round(rx * c.x) + 'px',
          marginTop: '-' + Math.round(ry * c.y) + 'px'
        });

        $("#prepimglink").val($pimg.attr('src') + '/' + create_coord(c.x, c.y, c.w, c.h, W / boundx, H / boundy, xsize, ysize));
      }
    };

  });

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

function create_coord (x, y, w, h, aspx, aspy, W, H) {
  var jobj = {
    'x' : Math.round(x * aspx),
    'y' : Math.round(y * aspy),
    'w' : Math.round(w * aspx),
    'h' : Math.round(h * aspy),
    'framex' : W,
    'framey' : H
  };
  return base64_encode(JSON.stringify(jobj));
}
</script>
