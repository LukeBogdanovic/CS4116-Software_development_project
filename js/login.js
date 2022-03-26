/**
 *
 * @param {Event} event
 */
function loginUser(event) {
  event.preventDefault();
  const username = $("#username").val();
  const password = $("#pwd").val();
  $.ajax({
    method: "POST",
    url: "../api/login_registration/login.php",
    data: {
      function: "login_user",
      username: username,
      password: password,
    },
    success: (response) => {
      var data = JSON.parse(response);
      if (data.status == 200) {
        if (document.getElementById("warning"))
          document.getElementById("warning").remove();
        var newNode = document.createElement("div");
        newNode.id = "success";
        newNode.classList.add("alert", "alert-success");
        newNode.innerHTML = `${data.message} Redirecting you to your Home page.`;
        var parentDiv = document.getElementById("loginForm").parentNode;
        parentDiv.replaceChild(newNode, document.getElementById("loginForm"));
        window.setTimeout(() => {
          window.location.href = "../home.php";
        }, 5000);
      } else {
        if (!document.getElementById("warning")) {
          var newNode = document.createElement("div");
          newNode.id = "warning";
          newNode.classList.add("alert", "alert-danger");
          newNode.innerHTML = data.message;
          var parentDiv = document.getElementById("loginForm").parentElement;
          parentDiv.insertBefore(newNode, document.getElementById("loginForm"));
        }
      }
    },
  });
}

$(document).on("keyup", () => {
  if ($("#username").val() === "" || $("#pwd").val() === "") {
    $("#submit").prop("disabled", true);
  } else {
    $("#submit").prop("disabled", false);
  }
});
