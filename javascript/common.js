// hexadecimális-ból RGB színné konvertálás
function hexToRgb(hex) {
  hex = hex.replace(/^#/, '');

  var bigint = parseInt(hex, 16);
  var r = (bigint >> 16) & 255;
  var g = (bigint >> 8) & 255;
  var b = bigint & 255;

  return { r: r, g: g, b: b };
}

// RGB-ből hexadecimális színné konvertálás
function rgbToHex(rgb) {
  var match = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
  if (!match) {
      throw new Error('Invalid RGB format');
  }

  var hex = '#';
  for (var i = 1; i <= 3; i++) {
      var component = parseInt(match[i], 10);
      if (isNaN(component) || component < 0 || component > 255) {
          throw new Error('Invalid RGB component');
      }
      hex += ('0' + component.toString(16)).slice(-2);
  }

  return hex;
}

// Megadott szín alapján kontraszt visszaadása
function getContrastColor(rgb) {
  var rgbValues = rgb.match(/\d+/g);
  var red = parseInt(rgbValues[0]);
  var green = parseInt(rgbValues[1]);
  var blue = parseInt(rgbValues[2]);
  
  if (red*0.299 + green*0.587 + blue*0.114 > 150){
    return 'black';
  }
  else{
    return 'white';
  }
}