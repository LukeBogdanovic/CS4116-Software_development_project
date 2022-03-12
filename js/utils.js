function forgotPassword() {
  $("#form").html("");
  var formString = `<form action="resetPassword.php" method="POST"><div class="form-outline mb-4"><input type="password" id="pwd" data-toggle="tooltip" class="form-control form-control-lg" placeholder="Password"pattern="(?=.*d)(?=.*[a-z])(?=.*[A-Z]).{8,16}" title="Must contain at least one number and one uppercase and lowercase character, and between 8 and 16 characters long."></div><div class="form-outline mb-4"><input type="password" id="confirmpwd" data-toggle="tooltip" class="form-control form-control-lg" placeholder="Confirm Password"pattern="(?=.*d)(?=.*[a-z])(?=.*[A-Z]).{8,16}" title="Must contain at least one number and one uppercase and lowercase character, and between 8 and 16 characters long."></div><button type="Submit" class="btn btn-primary btn-lg btn-block">Reset Password</button>`;
  $("#form").html(formString);
  $('[data-toggle="tooltip"').tooltip();
}
