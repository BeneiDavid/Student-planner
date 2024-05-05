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

// Előző hónap click
function showPrevMonth(){
    calendarYearAndMonth = document.getElementById('calendarYearAndMonth');
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

// Napok beállítása a naptárban
function setDays(currentYear, currentMonth){
    var firstDayOfMonth = new Date(currentYear, currentMonth - 1, 1); // Month is zero-based, so subtract 1 from the month
    
    // Get the day of the week (0-6, where 0 is Sunday, 1 is Monday, ..., 6 is Saturday)
    var firstWeekday = firstDayOfMonth.getDay();
    
    if (firstWeekday === 0) {
        firstWeekday = 7; // Change Sunday (0) to 7
    }

    var calendarDaysDiv = document.getElementById('calendarDays');
    calendarDaysDiv.innerHTML = "";

    for (i = 1; i < firstWeekday; i++) {
        var listItem = document.createElement("li");
        calendarDaysDiv.appendChild(listItem);
    }

    var nextMonth = new Date(currentYear, currentMonth, 1);
    // Subtract one day from the first day of the next month to get the last day of the current month
    var lastDayOfMonth = new Date(nextMonth - 1);
    // Get the day of the month (1-31)
    var totalDays = lastDayOfMonth.getDate();

    var calendarYearAndMonth = document.getElementById('calendarYearAndMonth');
    calendarYearAndMonth.value = currentYear + "-" + currentMonth;
    var calendarYear = calendarYearAndMonth.value.split('-')[0];
    var calendarMonth = calendarYearAndMonth.value.split('-')[1];
    var selectedDate = document.getElementById('selectedDate');
    var selectedYear = selectedDate.value.split('-')[0];
    var selectedMonth = selectedDate.value.split('-')[1];
    var selectedDay = selectedDate.value.split('-')[2];
    
    


    for (day = 1; day <= totalDays; day++) {
        var listItem = document.createElement("li");
        listItem.textContent = day;
        listItem.classList.add('clickable');
        listItem.classList.add('no-select');
        listItem.classList.add('day');

        if(calendarYear == selectedYear && calendarMonth == selectedMonth && selectedDay == day){
            listItem.classList.add('activeDay');
            listTasks(calendarYear + "-" + selectedMonth + "-" + selectedDay);
        }


        listItem.addEventListener('click', chooseDay, false);
        calendarDaysDiv.appendChild(listItem);
    }
    
    
}

// Nap kiválasztása a naptárban
function chooseDay(){
    var calendarDaysDiv = document.getElementById('calendarDays'); 

    var items = calendarDaysDiv.querySelectorAll('li'); 
    items.forEach(function(item) {
        item.classList.remove('activeDay');
    });
    this.classList.add('activeDay');

    // nap száma
    var day = this.textContent;

    // év és hónap "-"-el elválasztva
    var calendarYearAndMonth = document.getElementById('calendarYearAndMonth');
    var selectedDateValue = calendarYearAndMonth.value;
    var dateComponents = selectedDateValue.split('-');
    var year = dateComponents[0];
    var month = dateComponents[1];
    
    var selectedDayText = document.getElementById('selectedDay');
    selectedDayText.textContent = year + ". " + monthNames[month] + " " + day + ".";
    
    
    var fullDate = year + "-" + month + "-" + day;
    var selectedDate = document.getElementById('selectedDate');
    selectedDate.value = fullDate;

    listTasks(fullDate);
}

// Naptár frissítése
function updateCalendar(year, month){
    if(month == 0){
        month = 12;
    }
    else if(month == 13){
        month = 1;
    }

    monthAndYearText = document.getElementById('monthAndYear');
    monthAndYearText.textContent = monthNames[month] + " " + year;

    setDays(year, month, day);
}

// Naptár beállítása jelenlegi dátumra
function setCalendar(){
    var currentDate = new Date();
    var currentDay = currentDate.getDate();
    var currentMonth = currentDate.getMonth() + 1; 
    var currentYear = currentDate.getFullYear();

    selectedDate = document.getElementById('selectedDate');
    selectedDate.value = currentYear + "-" + currentMonth + "-" + currentDay;
    calendarYearAndMonth = document.getElementById('calendarYearAndMonth');
    calendarYearAndMonth.value = currentYear + "-" + currentMonth;

    if(currentMonth == 0){
        currentMonth = 12;
    }

    monthAndYearText = document.getElementById('monthAndYear');
    monthAndYearText.textContent = monthNames[currentMonth] + " " + currentYear;
    selectedDayText = document.getElementById('selectedDay');
    selectedDayText.textContent = currentYear + ". " + monthNames[currentMonth] + " " + currentDay + ".";

    setDays(currentYear, currentMonth);

}

function setCalendarToDate(date){
    var Year = date.split('-')[0];
    var Month = date.split('-')[1];
    var Day = date.split('-')[2];
    selectedDate = document.getElementById('selectedDate');
    selectedDate.value = Year + "-" + Month + "-" + Day;
    calendarYearAndMonth = document.getElementById('calendarYearAndMonth');
    calendarYearAndMonth.value = Year + "-" + Month;

    if(Month == 0){
        Month = 12;
    }

    monthAndYearText = document.getElementById('monthAndYear');
    monthAndYearText.textContent = monthNames[Month] + " " + Year;
    selectedDayText = document.getElementById('selectedDay');
    selectedDayText.textContent = Year + ". " + monthNames[Month] + " " + Day + ".";

    setDays(Year, Month);

}

function getDateFromURL() {
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('date');
}

function removeDateFromURL() {
    var urlParams = new URLSearchParams(window.location.search);
    urlParams.delete('date');
    var newUrlWithoutDate = window.location.pathname + '?' + urlParams.toString();
    history.replaceState(null, '', newUrlWithoutDate);
}


function init(){
    document.getElementById('nextMonth').addEventListener('click', showNextMonth, false);
    document.getElementById('prevMonth').addEventListener('click', showPrevMonth, false);
    
    var date = getDateFromURL();
    if(date != null){
        removeDateFromURL();
        setCalendarToDate(date);
    }
    else{
        setCalendar();
    }
        
 
    
}

window.addEventListener('load', init, false);