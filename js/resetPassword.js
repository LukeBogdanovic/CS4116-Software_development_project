// Allows username to be used in multiple functions
let username;

/**
 * Gets the securityQuestions answered by the user from the database
 * @param {Event} event
 */
function getSecurityQuestions(event) {
  // preventing reload of the page
  event.preventDefault();
  // Getting the value of the element with the username id
  username = $("#username").val();
  $.ajax({
    // POST request to protect user data
    method: "POST",
    // Script on server that is being called
    url: "../api/resetPassword/getSecQA.php",
    data: {
      // function that is being called
      function: "get_Security_Questions",
      // The username being sent to the server
      username: username,
    },
    // On success this function will be executed and receives the data returned from the server
    success: (response) => {
      // convert json string into object
      let data = JSON.parse(response);
      // Check the status code returned from the server
      if (data.status == 200) {
        // Array with data returned from the server passed to function
        updatePage_username(data[0]);
      } else {
        // Creating an alert for user with message returned from server
        if (!document.getElementById("warning")) {
          let newNode = document.createElement("div");
          newNode.id = "warning";
          newNode.classList.add("alert", "alert-danger");
          newNode.innerHTML = data.message;
          let parentDiv = document.getElementById("form").parentElement;
          let form = document.getElementById("form");
          parentDiv.insertBefore(newNode, form);
        }
      }
    },
  });
}

/**
 * Gets the security answers from the database and checks if the answer provided by the user is correct
 * @param {Event} event
 */
function getSecurityAnswers(event) {
  event.preventDefault();
  const SecurityQuestion = $("#SecurityQ").val();
  const SecurityAnswer = $("#SecurityAnswer").val();
  $.ajax({
    method: "POST",
    url: "../api/resetPassword/getSecQA.php",
    data: {
      function: "get_Security_Answers",
      username: username,
      SecurityQuestion: SecurityQuestion,
      SecurityAnswer: SecurityAnswer,
    },
    success: (response) => {
      // convert json string into object
      let data = JSON.parse(response);
      // Check the status code returned from the server
      if (data.status == 200) {
        updatePage_Answer();
      } else {
        // Creating an alert for user with message returned from server
        if (!document.getElementById("warning")) {
          let newNode = document.createElement("div");
          newNode.id = "warning";
          newNode.classList.add("alert", "alert-danger");
          newNode.innerHTML = data.message;
          let parentDiv = document.getElementById("form").parentElement;
          let form = document.getElementById("form");
          parentDiv.insertBefore(newNode, form);
        }
      }
    },
  });
}

/**
 * Sends the new password created by the user to be hashed and stored within the database
 * @param {Event} event
 */
function resetPassword(event) {
  event.preventDefault();
  const password = $("#pwd").val();
  const confirmPwd = $("#confirmpwd").val();
  $.ajax({
    method: "POST",
    url: "../api/resetPassword/getSecQA.php",
    data: {
      function: "reset_Password",
      username: username,
      password: password,
      confirmPwd: confirmPwd,
    },
    success: (response) => {
      // convert json string into object
      let data = JSON.parse(response);
      // Check the status code returned from the server
      if (data.status == 200) {
        updatePage_Success(data.message);
        // Redirect the user after 5 seconds to the login page
        window.setTimeout(() => {
          window.location.href = "../login.php";
        }, 5000);
      } else {
        // Creating an alert for user with message returned from server
        if (!document.getElementById("warning")) {
          var newNode = document.createElement("div");
          newNode.id = "warning";
          newNode.classList.add("alert", "alert-danger");
          newNode.innerHTML = data.message;
          var parentDiv = document.getElementById("form").parentElement;
          var form = document.getElementById("form");
          parentDiv.insertBefore(newNode, form);
        }
      }
    },
  });
}

/**
 * Updates the page after username is submitted by the user
 * @param {Array} response
 */
function updatePage_username(response) {
  // HTML code that is to be inserted into the dom
  let form = `<form onsubmit="getSecurityAnswers(event);" method="post" id="form">
    <div class="mb-4">
      <select class="form-select" name="SecurityQ" id="SecurityQ">
        <option selected>Select a Security Question</option>
      </select>
      </div>
      <div class="form-floating mb-4">
      <input
        name="securityAnswer"
        type="text"
        id="SecurityAnswer"
        data-toggle="tooltip"
        class="form-control form-control-lg"
        placeholder="Security Answer"
      />
      <label for="SecurityAnswer">Security Answer</label>
      </div>
    <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
  </form>`;
  // Inserting after the element with id input
  $("#input").html(form);
  // Looping through the response data and inserting secQ's into a select box list
  for (var i = 0; i < response.length; i++) {
    var opt = response[i];
    var el = document.createElement("option");
    el.textContent = opt;
    el.value = opt;
    $("#SecurityQ").append(el);
  }
}

/**
 * Updates the page after security Question is answered by the user
 * @param {Array} response
 */
function updatePage_Answer() {
  // HTML to be inserted into the dom
  var form = `<form onsubmit="resetPassword(event);" method="post" id="form">
  <div class="form-floating mb-4">
    <input
      name="pwd"
      type="password"
      id="pwd"
      data-toggle="tooltip"
      class="form-control form-control-lg"
      placeholder="Password"
      pattern="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$"
    />
    <label for="pwd">Password</label>
  </div>
  <div class="form-floating mb-4">
    <input
      name="confirmpwd"
      type="password"
      id="confirmpwd"
      data-toggle="tooltip"
      class="form-control form-control-lg"
      placeholder="Confirm Password"
      pattern="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$"
    />
    <label for="confirmpwd">Confirm Password</label>
  </div>
  <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
</form>
`;
  // Insert after element with id input
  $("#input").html(form);
  $('[data-toggle="tooltip"]').tooltip();
}

/**
 * Updates the page after succesful password update
 * @param {string} response
 */
function updatePage_Success(response) {
  // HTML to be inserted into the dom upon successful password change
  var form = `<div id="success" class="alert alert-success" role="alert">${response}You are being redirected to the login page.</div>`;
  // Inserting after element with id input
  $("#input").html(form);
}
