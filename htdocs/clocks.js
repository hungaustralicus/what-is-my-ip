var timeString = "";
var timeDiff = 0;

function setClockTimeZone(timeString) {
    window.timeString = timeString;
    var passedDate = Date.parse(timeString);
    var serverTime = new Date(Number(passedDate));
    timeDiff = serverTime - new Date();
}

function leadingZero(timeValue) {
    if (timeValue < 10) {
        timeValue = "0" + timeValue;
    }
    return timeValue;
}

function browserClock() {
    insertClock(new Date(), "browserTime", "browserDate");
}

function locationClock() {
    var lbDate = new Date();
    lbDate.setTime(lbDate.getTime() + timeDiff);
    insertClock(lbDate, "locationTime", "locationDate");
}

function insertClock(cbDate, clockID, dateID) {
    document.getElementById(clockID).innerHTML =
        leadingZero(cbDate.getHours())
        + " : " 
        + leadingZero(cbDate.getMinutes()) 
        + " : " 
        + leadingZero(cbDate.getSeconds());
    document.getElementById(dateID).innerHTML = cbDate.toDateString();
}