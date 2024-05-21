// Naptár beállítása
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

// Napok beállítása
function setDays(currentYear, currentMonth){
    var firstDayOfMonth = new Date(currentYear, currentMonth - 1, 1);
    
    var firstWeekday = firstDayOfMonth.getDay();
    
    if (firstWeekday === 0) {
        firstWeekday = 7;
    }

    var calendarDaysDiv = document.getElementById('calendarDays');
    calendarDaysDiv.innerHTML = "";

    for (i = 1; i < firstWeekday; i++) {
        var listItem = document.createElement("div");
        listItem.classList.add('monthlyDayDiv');
        calendarDaysDiv.appendChild(listItem);
    }
    
    var nextMonth = new Date(currentYear, currentMonth, 1);
    var lastDayOfMonth = new Date(nextMonth - 1);
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

        if (calendarYear == selectedYear && calendarMonth == selectedMonth && selectedDay == day){
            listItem.classList.add('activeDayMonthly');
            var color = getSeasonDarkColor(firstDayOfMonth);
            var svg = createColoredSVG(color, "115px", "dot");
            var svgBlob = new Blob([svg.outerHTML], {type: 'image/svg+xml'});
            var url = URL.createObjectURL(svgBlob);

            listItem.style.backgroundImage = 'url("' + url + '")';
            listItem.style.backgroundRepeat = "no-repeat";
            listItem.style.backgroundPosition = "center";
            listItem.style.backgroundSize = "115px"
        }

        dayDiv.appendChild(listItem);
        dayDiv.appendChild(tasksList);
        calendarDaysDiv.appendChild(dayDiv);
    }
}

// Naptár feladatok frissítése
function refreshMonthlyDisplay(){
    deleteMonthlyTasks();
    listMonthTasks();
}

// Feladatok eltávolítása a naptárból
function deleteMonthlyTasks(){
    var calendarDaysDiv = document.getElementById('calendarDays');
    var ulElements = calendarDaysDiv.getElementsByTagName('ul');

    for (var i = 0; i < ulElements.length; i++) {
        ulElements[i].innerHTML = '';
    }
}

// Naptár frissítése
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

// Havi feladatok listázása
function listMonthTasks(){
    var calendarYearAndMonth = document.getElementById('calendarYearAndMonth').value;
    var year = calendarYearAndMonth.split('-')[0];
    var month = calendarYearAndMonth.split('-')[1] -1;
    var daysInMonth = new Date(year, month, 0).getDate();
    var dayOfMonth = new Date(year, month, 1);
    setSeasonColors(dayOfMonth);
    for (var i = 0; i < daysInMonth; i++) {
        (function (index) {
            var date = changeDateToStringFormat(dayOfMonth);
            $.ajax({
                type: 'POST',
                url: 'queries/task_query.php',
                dataType: "json",
                data: {'date': date},
                credentials: 'same-origin',
                success: function(response) {
                    console.log(response + "asd");
                    fillMonthDay(index + 1, response);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        })(i);

        dayOfMonth.setDate(dayOfMonth.getDate() + 1);
    }
}

// Nap feltöltése a feladatokkal
function fillMonthDay(day, task_details){
    if(task_details.length != 0){
        var taskList = document.getElementById('list-' + day);
        var tasks = task_details.tasks;

        tasks.sort(function (a, b) {
            var startTimeComparison = a.start_time.localeCompare(b.start_time);

            if (startTimeComparison === 0) {
                return a.end_time.localeCompare(b.end_time);
            }

            return startTimeComparison;
        });

        for (var i = 0; i < tasks.length; i++) {
            var li = document.createElement('li');
            var svg = createColoredSVG(tasks[i].task_color, "35px", "dot");
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

// Előző hónap kattintása
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

// Következő hónap kattintása
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

// Inicializálás
function init(){
    document.getElementById('nextMonth').addEventListener('click', showNextMonth, false);
    document.getElementById('prevMonth').addEventListener('click', showPrevMonth, false);
    document.getElementById('addNewTaskMonthly').addEventListener('click', addTask, false)
    setCalendar();
    listMonthTasks();
}

window.addEventListener('load', init, false);