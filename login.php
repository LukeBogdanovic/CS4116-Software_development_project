<!Doctype html>
<html>

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="http://group13.epizy.com/css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="http://group13.epizy.com/js/utils.js"></script>
</head>

<body>

    <div>
        <section class="vh-100">
            <div class="container py-5 h-100">
                <div class="row d-flex align-items-center justify-content-center h-100">
                    <div class="col-md-8 col-lg-7 col-xl-6">
                        <img src="" class="img-fluid" alt="image to be found">
                    </div>
                    <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1" id="form">
                        <form action="login.php" method="POST">
                            <div class="form-floating mb-4">
                                <input name="username" type="text" id="username" data-toggle="tooltip" class="form-control form-control-lg <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" pattern="[A-Za-z0-9_]{0,16}" placeholder="Username" title="Must be less than 16 characters. Can contain alphanumeric characters and underscores." value="<?php echo $username; ?>" />
                                <label for="username">Username</label>
                                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="password" type="password" id="pwd" data-toggle="tooltip" class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}" placeholder="Password" title="Must contain at least one number and one uppercase and lowercase character, and between 8 and 16 characters long." />
                                <label for="password">Password</label>
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                            <div class="d-flex justify-content-around align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="form1checkbox" />
                                    <label class="form-check-label" for="form1checkbox"> Remember Me </label>
                                </div>
                                <a onclick="forgotPassword();" href="#!">Forgot Password?</a>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
                            <div class="divider d-flex align-items-center my-4">
                                <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
                            </div>
                            <a class="btn btn-primary btn-lg btn-block" style="background-color: #3b5998;" href="signup.php" role="button">
                                Register for an Account
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        $(document).ready(() => {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
</body>

</html>