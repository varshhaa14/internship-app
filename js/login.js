$(document).ready(function () {
  $("#loginForm").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
      url: "/php/login.php",
      type: "POST",
      dataType: "json",
      data: {
        email: $("#email").val().trim(),
        password: $("#password").val()
      },
      success: function (response) {
        if (response.success) {
          localStorage.setItem("session_token", response.token);
          window.location.href = "profile.html";
        } else {
          $("#message").html(`<span class="text-danger">${response.message}</span>`);
        }
      },
      error: function (xhr) {
        console.log(xhr.responseText);
        $("#message").html(`<span class="text-danger">Login failed.</span>`);
      }
    });
  });
});
