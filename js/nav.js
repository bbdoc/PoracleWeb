
// SideNav
$('[data-trigger]').on('click', function () {
  var offcanvasId = $(this).attr('data-trigger')
  $(offcanvasId).toggleClass('show')
  $('.screen-overlay').toggleClass('show')
})

$('.screen-overlay').click(function () {
  $('.offcanvas').removeClass('show')
  $('.screen-overlay').removeClass('show')
})

$(window).on('scroll', function () {
  if (!$('#header').visible()) {
    $('.offcanvas').removeClass('show')
    $('.screen-overlay').removeClass('show')
  }
})

// Nav Styling
$('#color-button-dark').on('click', function () {
  darkMode()
  Store.set('navColor', 'dark')
})

$('#color-button-light').on('click', function () {
  lightMode()
  Store.set('navColor', 'light')
})

$('#color-button-secondary').on('click', function () {
  greyMode()
  Store.set('navColor', 'grey')
})

// Close on Menu Item Click

document.getElementById("logout").addEventListener("click", function(event) {
  $('.offcanvas').removeClass('show')
  $('.screen-overlay').removeClass('show')
});


