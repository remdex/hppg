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

<div id="img-list-search">
</div>