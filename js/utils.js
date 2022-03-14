// Should replace with username, security question and security answer box
function forgotPassword() {
  $("#form").html("");
  var formString = `<form id="" action="resetPassword.php" method="POST"><div class="form-outline mb-4"><input name="username" type="password" id="pwd" data-toggle="tooltip" class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Password"pattern="(?=.*d)(?=.*[a-z])(?=.*[A-Z]).{8,16}" title="Must contain at least one number and one uppercase and lowercase character, and between 8 and 16 characters long."><span class="invalid-feedback"><?php echo $password_err; ?></span></div><div class="form-outline mb-4"><input name="confirmPassword" type="password" id="confirmpwd" data-toggle="tooltip" class="form-control form-control-lg <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" placeholder="Confirm Password"pattern="(?=.*d)(?=.*[a-z])(?=.*[A-Z]).{8,16}" title="Must contain at least one number and one uppercase and lowercase character, and between 8 and 16 characters long."><span class="invalid-feedback"><?php echo $confirm_password_err; ?></span></div><button type="Submit" class="btn btn-primary btn-lg btn-block">Reset Password</button></form>`;
  $("#form").html(formString);
  $('[data-toggle="tooltip"]').tooltip();
}

function storeUsername() {
  var usernameInput = $("#username").val();
  var formString = `<p>Changing Password for User:</p>
  <span id="userText"></span>
  <form action="resetPassword.php" method="POST">
    <div class="form-outline mb-4">
      <input
        name="password"
        type="password"
        id="pwd"
        data-toggle="tooltip"
        class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
        placeholder="Password"
        pattern="(?=.*d)(?=.*[a-z])(?=.*[A-Z]).{8,16}"
        title="Must contain at least one number and one uppercase and lowercase character, and between 8 and 16 characters long."
      />
      <span class="invalid-feedback"><?php echo $password_err; ?></span>
    </div>
    <div class="form-outline mb-4">
      <input
        name="confirmPassword"
        type="password"
        id="confirmpwd"
        data-toggle="tooltip"
        class="form-control form-control-lg <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>"
        placeholder="Confirm Password"
        pattern="(?=.*d)(?=.*[a-z])(?=.*[A-Z]).{8,16}"
        title="Must contain at least one number and one uppercase and lowercase character, and between 8 and 16 characters long."
      />
      <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
    </div>
    <button type="Submit" class="btn btn-primary btn-lg btn-block">
      Reset Password
    </button>
  </form>`;
  $("#userText").html(usernameInput);
}
