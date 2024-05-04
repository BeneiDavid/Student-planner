

// Hónapok nevei szöveggel
var monthNames = {
    1: "Január",
    2: "Február",
    3: "Március",
    4: "Április",
    5: "Május",
    6: "Június",
    7: "Július",
    8: "Augusztus",
    9: "Szeptember",
    10: "Október",
    11: "November",
    12: "December"
};


function showPrevMonth(){
    var firstdayOfWeekString = document.getElementById('firstdayOfWeek').value;
    var firstdayOfWeek = new Date(firstdayOfWeekString);
    firstdayOfWeek.setDate(firstdayOfWeek.getDate() - 7);
    setWeekDate(firstdayOfWeek);
    refreshWeeklyDisplay();
}

// Következő hónap click
function showNextMonth(){
    var firstdayOfWeekString = document.getElementById('firstdayOfWeek').value;
    var firstdayOfWeek = new Date(firstdayOfWeekString);
    firstdayOfWeek.setDate(firstdayOfWeek.getDate() + 7);
    setWeekDate(firstdayOfWeek);
    refreshWeeklyDisplay();
}


function setWeekDate(date){
    var weekDate = document.getElementById('weekDate');
    var dayOfWeek = date.getDay();

    var mondayDate = new Date(date);
    mondayDate.setDate(date.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1));
    var sundayDate = new Date(date);
    sundayDate.setDate(date.getDate() - dayOfWeek + 7);

    var monday = mondayDate.getFullYear() + '. ' + (monthNames[mondayDate.getMonth() + 1]) + " " + mondayDate.getDate() + '.';
    var sunday = sundayDate.getFullYear() + '. ' + (monthNames[sundayDate.getMonth() + 1]) + " " + sundayDate.getDate() + '.';

    var firstdayOfWeek = document.getElementById('firstdayOfWeek');
    firstdayOfWeek.value = mondayDate;

    var weekDate = document.getElementById('weekDate');
    weekDate.textContent = monday + " - " + sunday;
}


function setCurrentWeekDate(){
    var today = new Date();
    setWeekDate(today);
}

function changeDateToStringFormat(date){
    var currentDay = date.getDate();
    var currentMonth = date.getMonth() + 1; 
    var currentYear = date.getFullYear();
    return currentYear + "-" + currentMonth + "-" + currentDay;
}

function listWeekTasks(){
    var firstdayOfWeekString = document.getElementById('firstdayOfWeek').value;
    var firstdayOfWeek = new Date(firstdayOfWeekString);

    for (var i = 0; i < 7; i++) {
        (function (index) {
            console.log(firstdayOfWeek);
            var date = changeDateToStringFormat(firstdayOfWeek);
            $.ajax({
                type: 'POST',
                url: 'task_query.php',
                dataType: "json",
                data: {'date': date}, // Use a new instance of the date to prevent modification
                credentials: 'same-origin',
                success: function(response) {
                    console.log(response);
                    fillWeekDay(index, response);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        })(i);

        // Move this outside of the AJAX call to avoid incrementing `firstdayOfWeek` multiple times
        firstdayOfWeek.setDate(firstdayOfWeek.getDate() + 1);
    }
    
}

function fillWeekDay(dayIndex, task_details){
    if(task_details.length != 0){
        var list;

        switch(dayIndex){
            case 0:
                list = document.getElementById('mondayList');
            break;
            case 1:
                list = document.getElementById('tuesdayList');
            break;
            case 2:
                list = document.getElementById('wednesdayList');
            break;
            case 3:
                list = document.getElementById('thursdayList');
            break;
            case 4:
                list = document.getElementById('fridayList');
            break;
            case 5:
                list = document.getElementById('saturdayList');
            break;
            case 6:
                list = document.getElementById('sundayList');
            break;
        }

        var tasks = task_details.tasks;
        for (var i = 0; i < tasks.length; i++) {
            var li = document.createElement('li');


            var svg = createColoredSVG(tasks[i].task_color);
            var svgDataURL = 'data:image/svg+xml;base64,' + btoa(new XMLSerializer().serializeToString(svg));

            li.style.backgroundImage = "url('" + svgDataURL + "')";
            li.classList.add("clickable");
            li.textContent = tasks[i].title;
            li.id = "li-" + tasks[i].task_id;
            li.addEventListener('click', taskClick, false);

            list.appendChild(li);
        }
    }
}

function deleteWeekTasks(){
    document.getElementById('mondayList').innerHTML = "";
    document.getElementById('tuesdayList').innerHTML = "";
    document.getElementById('wednesdayList').innerHTML = "";
    document.getElementById('thursdayList').innerHTML = "";
    document.getElementById('fridayList').innerHTML = "";
    document.getElementById('saturdayList').innerHTML = "";
    document.getElementById('sundayList').innerHTML = "";
}

function refreshWeeklyDisplay(){
    deleteWeekTasks();
    listWeekTasks();
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

function setSelectedWeekDate(date){
    var selectedWeekDate = document.getElementById('selectedWeekDate');
    selectedWeekDate.value = date;
}

function addTaskMonday(){
    var firstdayOfWeekString = document.getElementById('firstdayOfWeek').value;
    var firstdayOfWeek = new Date(firstdayOfWeekString);
    var date = changeDateToStringFormat(firstdayOfWeek);
    setSelectedWeekDate(date);
    addTask();
}

function addTaskTuesday(){
    var firstdayOfWeekString = document.getElementById('firstdayOfWeek').value;
    var firstdayOfWeek = new Date(firstdayOfWeekString);
    firstdayOfWeek.setDate(firstdayOfWeek.getDate() + 1);
    var date = changeDateToStringFormat(firstdayOfWeek);
    setSelectedWeekDate(date);
    addTask();
}

function addTaskWednesday(){
    var firstdayOfWeekString = document.getElementById('firstdayOfWeek').value;
    var firstdayOfWeek = new Date(firstdayOfWeekString);
    firstdayOfWeek.setDate(firstdayOfWeek.getDate() + 2);
    var date = changeDateToStringFormat(firstdayOfWeek);
    setSelectedWeekDate(date);
    addTask();
}

function addTaskThursday(){
    var firstdayOfWeekString = document.getElementById('firstdayOfWeek').value;
    var firstdayOfWeek = new Date(firstdayOfWeekString);
    firstdayOfWeek.setDate(firstdayOfWeek.getDate() + 3);
    var date = changeDateToStringFormat(firstdayOfWeek);
    setSelectedWeekDate(date);
    addTask();
}

function addTaskFriday(){
    var firstdayOfWeekString = document.getElementById('firstdayOfWeek').value;
    var firstdayOfWeek = new Date(firstdayOfWeekString);
    firstdayOfWeek.setDate(firstdayOfWeek.getDate() + 4);
    var date = changeDateToStringFormat(firstdayOfWeek);
    setSelectedWeekDate(date);
    addTask();
}

function addTaskSaturday(){
    var firstdayOfWeekString = document.getElementById('firstdayOfWeek').value;
    var firstdayOfWeek = new Date(firstdayOfWeekString);
    firstdayOfWeek.setDate(firstdayOfWeek.getDate() + 5);
    var date = changeDateToStringFormat(firstdayOfWeek);
    setSelectedWeekDate(date);
    addTask();
}

function addTaskSunday(){
    var firstdayOfWeekString = document.getElementById('firstdayOfWeek').value;
    var firstdayOfWeek = new Date(firstdayOfWeekString);
    firstdayOfWeek.setDate(firstdayOfWeek.getDate() + 6);
    var date = changeDateToStringFormat(firstdayOfWeek);
    setSelectedWeekDate(date);
    addTask();
}

function init(){
    setCurrentWeekDate();
    listWeekTasks();
    document.getElementById('nextMonth').addEventListener('click', showNextMonth, false);
    document.getElementById('prevMonth').addEventListener('click', showPrevMonth, false);
    document.getElementById('addMonday').addEventListener('click', addTaskMonday, false);
    document.getElementById('addTuesday').addEventListener('click', addTaskTuesday, false);
    document.getElementById('addWednesday').addEventListener('click', addTaskWednesday, false);
    document.getElementById('addThursday').addEventListener('click', addTaskThursday, false);
    document.getElementById('addFriday').addEventListener('click', addTaskFriday, false);
    document.getElementById('addSaturday').addEventListener('click', addTaskSaturday, false);
    document.getElementById('addSunday').addEventListener('click', addTaskSunday, false);
}

window.addEventListener('load', init, false);