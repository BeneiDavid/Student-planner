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

function createColoredSVG(color, size, shape){
  // Create the SVG element
var svgElement = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
svgElement.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
svgElement.setAttribute('width', size);
svgElement.setAttribute('height', size);
svgElement.setAttribute('viewBox', '0 0 20 20');

if(shape == "circle"){
  var circleElement = document.createElementNS('http://www.w3.org/2000/svg', 'path');
  circleElement.setAttribute('d', 'M7.8 10a2.2 2.2 0 0 0 4.4 0 2.2 2.2 0 0 0-4.4 0');
  circleElement.setAttribute('stroke', color); // Set stroke color instead of fill
  circleElement.setAttribute('stroke-width', '0.5');
  circleElement.setAttribute('fill', "transparent");
svgElement.appendChild(circleElement);
}
else if(shape == "dot"){
// Create the path element

var pathElement = document.createElementNS('http://www.w3.org/2000/svg', 'path');
pathElement.setAttribute('d', 'M7.8 10a2.2 2.2 0 0 0 4.4 0 2.2 2.2 0 0 0-4.4 0');
pathElement.setAttribute('fill', color);

// Append the path element to the SVG element
svgElement.appendChild(pathElement)
}


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

async function getTaskGroupName(taskId){
  return new Promise((resolve, reject) => {
    $.ajax({
      type: 'POST',
      url: 'queries/task_group_name_query.php', 
      data: {'taskId': taskId},
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


async function getFullname(userId){
  return new Promise((resolve, reject) => {
    $.ajax({
      type: 'POST',
      url: 'queries/full_name_query.php', 
      data: {'userId': userId},
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

function setSeasonColors(date) {

  const month = date.getMonth() + 1; 

  if (month >= 3 && month <= 5) {
      const dayElements = document.querySelectorAll('.day');

      dayElements.forEach(dayElement => {
          if (!dayElement.classList.contains('activeDay')) {
              dayElement.style.backgroundColor = '#ffe0e2'; 
          }
      });
      
      const daysElement = document.querySelector('.days');
      daysElement.style.backgroundColor = '#ffe0e2';

      const month = document.querySelector('.month');
      month.style.backgroundColor = '#cf366b';

      const calendarArrows = document.querySelectorAll('.month ul li');

      calendarArrows.forEach(arrowElement => {
              arrowElement.style.color = 'black'; 
      });

      const activeDay = document.querySelector('.activeDay');
     
      if(activeDay){
          activeDay.style.backgroundColor = '#cf366b';
      }

      const selectedDay = document.getElementById('selectedDay');
      if(selectedDay){
        document.getElementById('selectedDay').style.backgroundColor = '#cf366b';
      }
      

  } else if (month >= 6 && month <= 8) {
      const dayElements = document.querySelectorAll('.day');

      dayElements.forEach(dayElement => {
          if (!dayElement.classList.contains('activeDay')) {
              dayElement.style.backgroundColor = '#dcf5dd'; 
          }
      });
      
      const daysElement = document.querySelector('.days');
      daysElement.style.backgroundColor = '#dcf5dd';

      const month = document.querySelector('.month');
      month.style.backgroundColor = '#6f9270';

      const calendarArrows = document.querySelectorAll('.month ul li');

      calendarArrows.forEach(arrowElement => {
              arrowElement.style.color = 'black'; 
      });

      const activeDay = document.querySelector('.activeDay');
      if(activeDay){
          activeDay.style.backgroundColor = '#6f9270';
      }

      const selectedDay = document.getElementById('selectedDay');
      if(selectedDay){
        document.getElementById('selectedDay').style.backgroundColor = '#6f9270';
      }


  } else if (month >= 9 && month <= 11) {
      const dayElements = document.querySelectorAll('.day');

      dayElements.forEach(dayElement => {
          if (!dayElement.classList.contains('activeDay')) {
              dayElement.style.backgroundColor = '#fcddb8'; 
          }
      });
      
      const daysElement = document.querySelector('.days');
      daysElement.style.backgroundColor = '#fcddb8';

      const month = document.querySelector('.month');
      month.style.backgroundColor = '#dd7116';

      const calendarArrows = document.querySelectorAll('.month ul li');

      calendarArrows.forEach(arrowElement => {
              arrowElement.style.color = 'black'; 
      });

      const activeDay = document.querySelector('.activeDay');
     
      if(activeDay){
          activeDay.style.backgroundColor = '#dd7116';
      }

      const selectedDay = document.getElementById('selectedDay');
      if(selectedDay){
        document.getElementById('selectedDay').style.backgroundColor = '#dd7116';
      }
      
  } else {
      const dayElements = document.querySelectorAll('.day');

      dayElements.forEach(dayElement => {
          if (!dayElement.classList.contains('activeDay')) {
              dayElement.style.backgroundColor = '#e6f1fc'; 
          }
      });
      
      const daysElement = document.querySelector('.days');
      daysElement.style.backgroundColor = '#e6f1fc';

      const month = document.querySelector('.month');
      month.style.backgroundColor = '#9AAFC5';

      const calendarArrows = document.querySelectorAll('.month ul li');

      calendarArrows.forEach(arrowElement => {
              arrowElement.style.color = 'black'; 
      });

      const activeDay = document.querySelector('.activeDay');
     
      if(activeDay){
          activeDay.style.backgroundColor = '#9AAFC5';
      }

      const selectedDay = document.getElementById('selectedDay');
      if(selectedDay){
        document.getElementById('selectedDay').style.backgroundColor = '#9AAFC5';
      }
      }
     

}


function getSeasonDarkColor(date){
 const month = date.getMonth() + 1; 

  if (month >= 3 && month <= 5) {
    return '#cf366b';
  }
  else if (month >= 6 && month <= 8) {
    
    return '#6f9270';
  }
  else if (month >= 9 && month <= 11) {
    return '#dd7116';
  }
  else{
    return '#9AAFC5';
  }
}