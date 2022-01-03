
$(document).ready(function () {
  $(window).scroll(function () {
    if ($(window).scrollTop() >= 250 && window.screen.width > 900) {
      $(".navsbar").addClass("sticky");

      $(".main-content").css("margin-top", "30px");
      $(".nav-logo").css("display", "flex");
    } else if ($(window).scrollTop() < 70) {
      $(".main-content").css("margin-top", "0");
      $(".navsbar").removeClass("sticky");
      $(".nav-logo").css("display", "none");
    }
  });
});
