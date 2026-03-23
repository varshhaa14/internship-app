$(document).ready(function () {
  const token = localStorage.getItem("session_token");

  if (!token) {
    $("#message").html('<span class="text-danger">No session token found. Please login again.</span>');
    return;
  }

  $.ajax({
    url: "php/get_profile.php?token=" + encodeURIComponent(token),
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        $("#name").val(response.data.name || "");
        $("#email").val(response.data.email || "");
        $("#age").val(response.data.age || "");
        $("#dob").val(response.data.dob || "");
        $("#contact").val(response.data.contact || "");
      } else {
        $("#message").html(`<span class="text-danger">${response.message || 'Failed to load profile.'}</span>`);
      }
    },
    error: function () {
      $("#message").html('<span class="text-danger">Failed to load profile.</span>');
    }
  });

  $("#profileForm").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
      url: "php/update_profile.php?token=" + encodeURIComponent(token),
      type: "POST",
      dataType: "json",
      data: {
        age: $("#age").val(),
        dob: $("#dob").val(),
        contact: $("#contact").val()
      },
      success: function (response) {
        if (response.success) {
          $("#message").html(`<span class="text-success">${response.message || 'Profile updated successfully.'}</span>`);
        } else {
          $("#message").html(`<span class="text-danger">${response.message || 'Update failed.'}</span>`);
        }
      },
      error: function () {
        $("#message").html('<span class="text-danger">Update failed.</span>');
      }
    });
  });

  $("#logoutBtn").on("click", function () {
    $.ajax({
      url: "php/logout.php?token=" + encodeURIComponent(token),
      type: "POST",
      complete: function () {
        localStorage.removeItem("session_token");
        window.location.href = "login.html";
      }
    });
  });
});
