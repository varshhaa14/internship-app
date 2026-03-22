$(document).ready(function () {
  $("#registerForm").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
      url: "php/register.php",
      type: "POST",
      dataType: "json",
      data: {
        name: $("#name").val().trim(),
        email: $("#email").val().trim(),
        password: $("#password").val()
      },
      success: function (response) {
        if (response.success) {
          $("#message").html(`<span class="text-success">${response.message}</span>`);
          $("#registerForm")[0].reset();

          setTimeout(() => {
            window.location.href = "login.html";
          }, 1000);
        } else {
          $("#message").html(`<span class="text-danger">${response.message}</span>`);
        }
      },
      error: function (xhr) {
        console.log(xhr.responseText);
        $("#message").html(`<span class="text-danger">Server error occurred.</span>`);
      }
    });
  });
});