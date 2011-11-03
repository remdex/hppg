/*
 * Copyright (C) 2009 Mihai Şucan
 *
 * This file is part of PaintWeb.
 *
 * PaintWeb is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PaintWeb is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PaintWeb.  If not, see <http://www.gnu.org/licenses/>.
 *
 * $URL: http://code.google.com/p/paintweb $
 * $Date: 2009-11-10 20:12:34 +0200 $
 */

/**
 * @author <a lang="ro" href="http://www.robodesign.ro/mihai">Mihai Şucan</a>
 * @fileOverview Holds the color bucket tool implementation, also known as the 
 * flood fill tool.
 */

/**
 * @class The color bucket tool.
 *
 * The implementation here is based on the seed fill algorithm of Paul S.  
 * Heckbert (1990).
 *
 * @param {PaintWeb} app Reference to the main paint application object.
 */
pwlib.tools.cbucket = function (app) {
  var _self   = this,
      config  = app.config,
      layer   = app.layer.context,
      buffer  = app.buffer.context,
      iwidth  = app.image.width,
      iheight = app.image.height,
      mouse   = app.mouse;

  var stackMax = 10000; // maximum depth of stack
  var lines = []; // stack of lines
  var pixelNew, layerpix;

  /**
   * The <code>preActivate</code> event handler. This method checks if the 
   * browser implements the <code>getImageData()</code> and 
   * <code>putImageData()</code> context methods.  If not, the color bucket tool 
   * cannot be used.
   *
   * @returns {Boolean} True if the drawing tool can be activated, or false 
   * otherwise.
   */
  this.preActivate = function () {
    // The latest versions of all browsers which implement Canvas, also 
    // implement the getImageData() method. This was only a problem with some 
    // old versions (eg. Opera 9.2).
    if (!layer.getImageData || !layer.putImageData) {
      alert(app.lang.errorCbucketUnsupported);
      return false;
    } else {
      return true;
    }
  };

  /**
   * The <code>activate</code> event handler. Canvas shadow rendering is 
   * disabled.
   */
  this.activate = function () {
    app.shadowDisallow();
  };

  /**
   * The <code>deactivate</code> event handler. Canvas shadow rendering is 
   * allowed once again.
   */
  this.deactivate = function () {
    app.shadowAllow();
  };

  /**
   * The <code>click</code> and <code>contextmenu</code> event handler. This 
   * method performs the flood fill operation.
   *
   * @param {Event} ev The DOM Event object.
   *
   * @returns {Boolean} True if the image was modified, or false otherwise.
   */
  this.click = function (ev) {
    // Allow the user to right-click or hold down the Shift key to use the 
    // border color for filling the image.
    if (ev.type === 'contextmenu' || ev.button === 2 || ev.shiftKey) {
      var fillStyle = buffer.fillStyle;
      buffer.fillStyle = buffer.strokeStyle;
      buffer.fillRect(0, 0, 1, 1);
      buffer.fillStyle = fillStyle;
    } else {
      buffer.fillRect(0, 0, 1, 1);
    }

    // Instead of parsing the fillStyle ...
    pixelNew = buffer.getImageData(0, 0, 1, 1);
    pixelNew = [pixelNew.data[0], pixelNew.data[1], pixelNew.data[2], 
             pixelNew.data[3]];

    buffer.clearRect(0, 0, 1, 1);

    var pixelOld = layer.getImageData(mouse.x, mouse.y, 1, 1).data;
    pixelOld = pixelOld[0] + ';' + pixelOld[1] + ';' + pixelOld[2] + ';' 
      + pixelOld[3];

    if (pixelOld === pixelNew.join(';')) {
      return false;
    }

    fill(mouse.x, mouse.y, pixelOld);

    app.historyAdd();

    return true;
  };
  this.contextmenu = this.click;

  /**
   * Fill the image with the current fill color, starting from the <var>x</var> 
   * and <var>y</var> coordinates.
   *
   * @private
   *
   * @param {Number} x The x coordinate for the starting point.
   * @param {Number} y The y coordinate for the starting point.
   * @param {String} pixelOld The old pixel value.
   */
  var fill = function (x, y, pixelOld) {
    var start, x1, x2, dy, tmp, idata;

    pushLine(y, x, x, 1);      // needed in some cases
    pushLine(y + 1, x, x, -1); // seed segment (popped 1st)

    while (lines.length > 0) {
      // pop segment off stack and fill a neighboring scan line
      tmp = lines.pop();
      dy = tmp[3];
      y  = tmp[0] + dy;
      x1 = tmp[1];
      x2 = tmp[2];

      layerpix = null;
      idata = layer.getImageData(0, y, iwidth, 1);
      layerpix = idata.data;

      // segment of scan line y-dy for x1 <= x <= x2 was previously filled, now 
      // explore adjacent pixels in scan line y
      for (x = x1; x >= 0 && pixelRead(x) === pixelOld; x--) {
        pixelWrite(x);
      }

      if (x >= x1) {
        for (x++; x <= x2 && pixelRead(x) !== pixelOld; x++);
        start = x;
        if (x > x2) {
          layer.putImageData(idata, 0, y);
          continue;
        }

      } else {
        start = x + 1;
        if (start < x1) {
          pushLine(y, start, x1 - 1, -dy); // leak on left?
        }

        x = x1 + 1;
      }

      do {
        for (; x < iwidth && pixelRead(x) === pixelOld; x++) {
          pixelWrite(x);
        }

        pushLine(y, start, x - 1, dy);
        if (x > (x2 + 1)) {
          pushLine(y, x2 + 1, x - 1, -dy);  // leak on right?
        }

        for (x++; x <= x2 && pixelRead(x) !== pixelOld; x++);
        start = x;

      } while (x <= x2);

      layer.putImageData(idata, 0, y);
    }

    layerpix = null;
    idata = null;
  };

  var pushLine = function (y, xl, xr, dy) {
    if (lines.length < stackMax && (y+dy) >= 0 && (y+dy) < iheight) {
      lines.push([y, xl, xr, dy]);
    }
  };

  var pixelRead = function (x) {
    var r = 4 * x;
    return layerpix[r] + ';' + layerpix[r+1] + ';' + layerpix[r+2] + ';' 
      + layerpix[r+3];
  };

  var pixelWrite = function (x) {
    var r = 4 * x;
    layerpix[r]   = pixelNew[0];
    layerpix[r+1] = pixelNew[1];
    layerpix[r+2] = pixelNew[2];
    layerpix[r+3] = pixelNew[3];
  };
};

// vim:set spell spl=en fo=wan1croqlt tw=80 ts=2 sw=2 sts=2 sta et ai cin fenc=utf-8 ff=unix:

