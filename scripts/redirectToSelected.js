function redirectToSelectedDate() {
  var selectedDate = document.getElementById("eventDate").value;
  window.location.href = "/events/list/display?date=" + selectedDate;
}
function redirectToSelectedLocation() {
  var selectedLocation = document.getElementById("location").value;
  window.location.href = "/events/list/display?location=" + selectedLocation;
}

function redirectToSelectedTitle() {
  var selectedTitle = document.getElementById("eventName").value;
  window.location.href = "/events/list/display?title=" + selectedTitle;
}
