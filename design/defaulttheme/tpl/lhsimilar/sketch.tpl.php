<div class="header-list">
            <h1>Search by sketch</h1>
</div>
    
<p><a style="display:none;" title="Freshalicious" 
      href="http://www.robodesign.ro/marius/my-projects/images/freshalicious"><img 
      id="editableImage" src="<?=erLhcoreClassDesign::design('js/paintweb/bgedit.jpg')?>" alt="Freshalicious"></a></p>

    <div id="PaintWebTarget"></div>

    <script type="text/javascript" src="<?=erLhcoreClassDesign::design('js/paintweb/build/paintweb.js')?>"></script>

    <script type="text/javascript">
(function () { 
  function pwInit (ev) {
    var initTime = (new Date()).getTime() - timeStart,str = 'Demo: Yay, PaintWeb loaded in ' + initTime + ' ms! ' + pw.toString();
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
      pw = new PaintWeb();
      pw.config.guiPlaceholder = target;
      pw.config.imageLoad      = img; 
      pw.config.uploadCanvasURL      = '<?=erLhcoreClassDesign::baseurl('similar/uploadcanvas')?>';
      pw.config.configFile     = 'config-example.json';
      loadp.appendChild(document.createTextNode('Loading, please wait...'));
      timeStart = (new Date()).getTime();
      pw.init(pwInit);
})();</script>

<div id="img-list-search">
</div>