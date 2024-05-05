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


function setCalendar(){
var currentDate = new Date();
var currentDay = currentDate.getDate();
var currentMonth = currentDate.getMonth() + 1; 
var currentYear = currentDate.getFullYear();

selectedDate = document.getElementById('selectedMonthDate');
selectedDate.value = currentYear + "-" + currentMonth + "-" + currentDay;
calendarYearAndMonth = document.getElementById('calendarYearAndMonth');
calendarYearAndMonth.value = currentYear + "-" + currentMonth;

if(currentMonth == 0){
    currentMonth = 12;
}

monthAndYearText = document.getElementById('monthAndYear');
monthAndYearText.textContent = monthNames[currentMonth] + " " + currentYear;


setDays(currentYear, currentMonth);
}

function setDays(currentYear, currentMonth){
    var firstDayOfMonth = new Date(currentYear, currentMonth - 1, 1); // Month is zero-based, so subtract 1 from the month
    
    // Get the day of the week (0-6, where 0 is Sunday, 1 is Monday, ..., 6 is Saturday)
    var firstWeekday = firstDayOfMonth.getDay();
    
    if (firstWeekday === 0) {
        firstWeekday = 7; // Change Sunday (0) to 7
    }

    var calendarDaysDiv = document.getElementById('calendarDays');
    calendarDaysDiv.innerHTML = "";


    
    var nextMonth = new Date(currentYear, currentMonth, 1);
    // Subtract one day from the first day of the next month to get the last day of the current month
    var lastDayOfMonth = new Date(nextMonth - 1);
    // Get the day of the month (1-31)
    var totalDays = lastDayOfMonth.getDate();

    var calendarYearAndMonth = document.getElementById('calendarYearAndMonth');
    calendarYearAndMonth.value = currentYear + "-" + currentMonth;
    var calendarYear = calendarYearAndMonth.value.split('-')[0];
    var calendarMonth = calendarYearAndMonth.value.split('-')[1];
    var selectedDate = document.getElementById('selectedMonthDate');
    var selectedYear = selectedDate.value.split('-')[0];
    var selectedMonth = selectedDate.value.split('-')[1];
    var selectedDay = selectedDate.value.split('-')[2];
    
    


    for (day = 1; day <= totalDays; day++) {
        var dayDiv = document.createElement("div");
        dayDiv.classList.add('monthlyDayDiv');
        var listItem = document.createElement("div");
        listItem.textContent = day;
        listItem.classList.add('no-select');
        listItem.classList.add('day');
        var tasksList = document.createElement("ul");
        tasksList.id = "list-" + day; 

        if(calendarYear == selectedYear && calendarMonth == selectedMonth && selectedDay == day){
            listItem.classList.add('activeDay');

        }

        dayDiv.appendChild(listItem);
        dayDiv.appendChild(tasksList);
        calendarDaysDiv.appendChild(dayDiv);
    }
    
    
}


function refreshMonthlyDisplay(){
    deleteMonthlyTasks();
    listMonthTasks();
}

function deleteMonthlyTasks(){
    var calendarDaysDiv = document.getElementById('calendarDays');
    var ulElements = calendarDaysDiv.getElementsByTagName('ul');

    // Összes lista törlése
    for (var i = 0; i < ulElements.length; i++) {
        ulElements[i].innerHTML = '';
    }
}

function updateCalendar(year, month){
    if(month == 0){
        month = 12;
    }
    else if(month == 13){
        month = 1;
    }

    var monthAndYearText = document.getElementById('monthAndYear');
    monthAndYearText.textContent = monthNames[month] + " " + year;

    setDays(year, month, day);
    refreshMonthlyDisplay();
}


function changeDateToStringFormat(date){
    var currentDay = date.getDate();
    var currentMonth = date.getMonth() + 1; 
    var currentYear = date.getFullYear();
    return currentYear + "-" + currentMonth + "-" + currentDay;
}

function listMonthTasks(){
     var calendarYearAndMonth = document.getElementById('calendarYearAndMonth').value;
     var year = calendarYearAndMonth.split('-')[0];
     var month = calendarYearAndMonth.split('-')[1] -1;
     var daysInMonth = new Date(year, month, 0).getDate();
     console.log(daysInMonth);
     console.log(calendarYearAndMonth);
    var dayOfMonth = new Date(year, month, 1);

    for (var i = 0; i < daysInMonth; i++) {
        (function (index) {
            console.log(dayOfMonth);
            var date = changeDateToStringFormat(dayOfMonth);
            $.ajax({
                type: 'POST',
                url: 'task_query.php',
                dataType: "json",
                data: {'date': date}, // Use a new instance of the date to prevent modification
                credentials: 'same-origin',
                success: function(response) {
                    console.log(response);
                    fillMonthDay(index + 1, response);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        })(i);

        // Move this outside of the AJAX call to avoid incrementing `firstdayOfWeek` multiple times
        dayOfMonth.setDate(dayOfMonth.getDate() + 1);
    }
    
}

function fillMonthDay(day, task_details){
    if(task_details.length != 0){
        var taskList = document.getElementById('list-' + day);

        var tasks = task_details.tasks;
        // Feladatok rendezése időrendben
        tasks.sort(function (a, b) {
            var startTimeComparison = a.start_time.localeCompare(b.start_time);
        
            if (startTimeComparison === 0) {
                return a.end_time.localeCompare(b.end_time);
            }

            return startTimeComparison;
        });

        for (var i = 0; i < tasks.length; i++) {
            var li = document.createElement('li');


            var svg = createColoredSVG(tasks[i].task_color);
            var svgDataURL = 'data:image/svg+xml;base64,' + btoa(new XMLSerializer().serializeToString(svg));

            li.style.backgroundImage = "url('" + svgDataURL + "')";
            li.classList.add("clickable");
            li.classList.add("no-select");
            li.textContent = tasks[i].title;
            li.id = "li-" + tasks[i].task_id;
            li.addEventListener('click', taskClick, false);

            taskList.appendChild(li);
        }
    }
}


// Előző hónap click
function showPrevMonth(){
    var calendarYearAndMonth = document.getElementById('calendarYearAndMonth');
    var date = calendarYearAndMonth.value;
    var dateComponents = date.split('-');
    var year = dateComponents[0];
    var month = dateComponents[1];

    if (month == 1) {
        month = 12;
        year--; 
    } else {
        month--;
    }

    updateCalendar(year,month);
}

// Következő hónap click
function showNextMonth(){
    calendarYearAndMonth = document.getElementById('calendarYearAndMonth');
    var date = calendarYearAndMonth.value;
    var dateComponents = date.split('-');
    var year = dateComponents[0];
    var month = dateComponents[1];

    if (month == 12) {
        month = 1;
        year++;
        
    } else {
        month++;
    }

    updateCalendar(year,month);
}

function init(){
    document.getElementById('nextMonth').addEventListener('click', showNextMonth, false);
    document.getElementById('prevMonth').addEventListener('click', showPrevMonth, false);
    document.getElementById('addNewTaskMonthly').addEventListener('click', addTask, false)
    setCalendar();
    listMonthTasks();
}

window.addEventListener('load', init, false);