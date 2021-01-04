
var error_div = document.getElementById("position_error_div");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(setPosition, showError);
  } else {
    error_div.style.display = "block"
    document.getElementById("NOT_SUPPORTED").style.display = "block"
  }
}

function setPosition(position) {
   window.location = './set_location.php?lat=' + position.coords.latitude + '&lon=' + position.coords.longitude;
}

function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      error_div.style.display = "block"
      document.getElementById("PERMISSION_DENIED").style.display = "block"
      break;
    case error.POSITION_UNAVAILABLE:
      error_div.style.display = "block"
      document.getElementById("POSITION_UNAVAILABLE").style.display = "block"
      break;
    case error.TIMEOUT:
      error_div.style.display = "block"
      document.getElementById("TIMEOUT").style.display = "block"
      break;
    case error.UNKNOWN_ERROR:
      error_div.style.display = "block"
      document.getElementById("UNKNOWN_ERROR").style.display = "block"
      break;
  }
}

