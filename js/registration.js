let emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
/**
 *
 * @param {Event} event
 */
function registerNewUser(event) {
  event.preventDefault();
  const username = $("#username").val();
  const email = $("#email").val();
  const firstname = $("#firstname").val();
  const surname = $("#surname").val();
  const dob = $("#dob").val();
  const password = $("#pwd").val();
  const confirmPassword = $("#confirmpwd").val();
  if (inputValidation(email, password, confirmPassword)) return;
  $.ajax({
    method: "POST",
    url: "../api/login_registration/registration.php",
    data: {
      function: "info_validate",
      username: username,
      email: email,
      firstname: firstname,
      surname: surname,
      dob: dob,
      password: password,
      confirmpassword: confirmPassword,
    },
    success: (response) => {
      if (document.getElementById("warning"))
        document.getElementById("warning").remove();
      let data = JSON.parse(response);
      if (data.status == 200) {
        registerUser(
          username,
          email,
          firstname,
          surname,
          dob,
          password,
          confirmPassword
        );
      } else {
        if (!document.getElementById("warning")) {
          let newNode = document.createElement("div");
          newNode.id = "warning";
          newNode.classList.add("alert", "alert-danger");
          if (data.message_username && data.message_email)
            newNode.innerHTML = `${data.message_username}. ${data.message_email}`;
          else if (data.message_email)
            newNode.innerHTML = `${data.message_email}`;
          else if (data.message_username)
            newNode.innerHTML = `${data.message_username}.`;
          let parentDiv = document.getElementById("signupform").parentElement;
          parentDiv.insertBefore(
            newNode,
            document.getElementById("signupform")
          );
        }
      }
    },
  });
}

function registerUser(
  username,
  email,
  firstname,
  surname,
  dob,
  password,
  confirmPassword
) {
  $.ajax({
    method: "POST",
    url: "../api/login_registration/registration.php",
    data: {
      function: "register_user",
      username: username,
      email: email,
      firstname: firstname,
      surname: surname,
      dob: dob,
      password: password,
      confirmpassword: confirmPassword,
    },
    success: (response) => {
      let data = JSON.parse(response);
      if (data.status == 200) {
        let newNode = document.createElement("div");
        newNode.id = "success";
        newNode.classList.add("alert", "alert-success");
        newNode.innerHTML = `${data.message} Redirecting you to setup your profile.`;
        let parentDiv = document.getElementById("signupform").parentNode;
        parentDiv.replaceChild(newNode, document.getElementById("signupform"));
        window.setTimeout(() => {
          window.location.href = "../profileSetup.php";
        }, 5000);
      } else {
        if (!document.getElementById("warning")) {
          let newNode = document.createElement("div");
          newNode.id = "warning";
          newNode.classList.add("alert", "alert-danger");
          newNode.innerHTML = data.message;
          let parentDiv = document.getElementById("signupform").parentElement;
          parentDiv.insertBefore(
            newNode,
            document.getElementById("signupform")
          );
        }
      }
    },
  });
}

/**
 * Validates the email and password/confirmPassword fields entered by the user to see if they are valid/match
 * the requirements set
 * @param {string} email
 * @param {string} password
 * @param {string} confirmPassword
 * @returns
 */
function inputValidation(email, password, confirmPassword) {
  let confpwd = false,
    emailTruth = false;
  if (password !== confirmPassword) {
    document.getElementById("confirmpwd").classList.add("is-invalid");
    document.getElementById("confirmpwdmsg").innerHTML =
      "Confirm password and password fields do not match";
    document.getElementById("confirmpwdmsg").style.color = "red";
    confpwd = true;
  }
  if (!email.match(emailRegex)) {
    document.getElementById("email").classList.add("is-invalid");
    document.getElementById("email").innerHTML =
      "Confirm password and password fields do not match";
    document.getElementById("confirmpwdmsg").classList.add("invalid-feedback");
    emailTruth = true;
  }
  if (emailTruth || confpwd) return true;
  return false;
}

$(document).on("keyup", () => {
  if (
    $("#username").val() === "" ||
    $("#pwd").val() === "" ||
    $("#confirmpwd").val() === "" ||
    $("#email").val() === "" ||
    $("#surname").val() === "" ||
    $("#firstname").val() === "" ||
    $("#dob").val() === ""
  ) {
    $("#submit").prop("disabled", true);
  } else {
    $("#submit").prop("disabled", false);
  }
});
