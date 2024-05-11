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

function createColoredSVG(color){
  // Create the SVG element
var svgElement = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
svgElement.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
svgElement.setAttribute('width', '35px');
svgElement.setAttribute('height', '35px');
svgElement.setAttribute('viewBox', '0 0 20 20');

// Create the path element
var pathElement = document.createElementNS('http://www.w3.org/2000/svg', 'path');
pathElement.setAttribute('d', 'M7.8 10a2.2 2.2 0 0 0 4.4 0 2.2 2.2 0 0 0-4.4 0');
pathElement.setAttribute('fill', color);

// Append the path element to the SVG element
svgElement.appendChild(pathElement)
return svgElement;
}


async function getCurrentUserId(){
  return new Promise((resolve, reject) => {
    $.ajax({
      type: 'POST',
      url: 'queries/current_user_query.php', 
      data: {},
      credentials: 'same-origin',
      success: function(response) {
          console.log(response); 
          resolve(response);
      },
      error: function(xhr, status, error) {
          console.error(xhr.responseText);
          reject("");
      }
    });
});
}