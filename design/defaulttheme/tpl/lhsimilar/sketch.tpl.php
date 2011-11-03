<div class="header-list">
            <h1>Search by sketch</h1>
</div>
    
<?php 

/*
<div id="colors_demo">
<div class="tools">
  <a href="#colors_sketch" data-download="jpg" data-url="/similar/uploadcanvas" style="float: right; width: 100px;">Search</a>
  &nbsp;
  <a href="#colors_sketch" data-resetmap="data-reset" style="float: right; width: 100px;margin-right:5px;">Reset painting</a>
</div>
<canvas id="colors_sketch" width="400" height="400"></canvas>
</div>

<script type="text/javascript">
  $(function() {
    $.each(['#f00', '#ff0', '#0f0', '#0ff', '#00f', '#f0f', '#000', '#fff'], function() {
      $('#colors_demo .tools').append("<a href='#colors_sketch' data-color='" + this + "' style='width: 10px; background: " + this + ";'></a> ");
    });
    $.each([3, 5, 10, 15], function() {
      $('#colors_demo .tools').append("<a href='#colors_sketch' data-size='" + this + "' style='background: #ccc'>" + this + "</a> ");
    });
    $('#colors_sketch').sketch();
  });
</script>
*/ ?>

<p><a style="display:none;" title="Freshalicious" 
      href="http://www.robodesign.ro/marius/my-projects/images/freshalicious"><img 
      id="editableImage" src="<?=erLhcoreClassDesign::design('js/paintweb/bgedit.jpg')?>" alt="Freshalicious"></a></p>

    <div id="PaintWebTarget"></div>

    <script type="text/javascript" src="<?=erLhcoreClassDesign::design('js/paintweb/build/paintweb.js')?>"></script>

    <script type="text/javascript"><!--
(function () { 
  // Function called when the PaintWeb application fires the "appInit" event.
  function pwInit (ev) {
    var initTime = (new Date()).getTime() - timeStart,
        str = 'Demo: Yay, PaintWeb loaded in ' + initTime + ' ms! ' +
              pw.toString();

    document.body.removeChild(loadp);

    if (ev.state === PaintWeb.INIT_ERROR) {
      alert('Demo: PaintWeb initialization failed.');
      return;

    } else if (ev.state === PaintWeb.INIT_DONE) {
      if (window.console && console.log) {
        console.log(str);
      } else if (window.opera) {
        opera.postError(str);
      }

    } else {
      alert('Demo: Unrecognized PaintWeb initialization state ' + ev.state);

      return;
    }
    img.style.display = 'none';
  };

  var img    = document.getElementById('editableImage'),
      target = document.getElementById('PaintWebTarget'),
      loadp  = document.createElement('p'),
      timeStart = null,

      // Create a PaintWeb instance.
      pw = new PaintWeb();

      pw.config.guiPlaceholder = target;
      pw.config.imageLoad      = img; 
      pw.config.uploadCanvasURL      = '<?=erLhcoreClassDesign::baseurl('similar/uploadcanvas')?>';
      pw.config.configFile     = 'config-example.json';
      loadp.appendChild(document.createTextNode('Loading, please wait...'));
    
      timeStart = (new Date()).getTime();
      pw.init(pwInit);

})();
    --></script>














<div id="img-list-search">
</div>