<!Doctype html>
<html>

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="http://group13.epizy.com/css/login.css">
</head>

<body>

    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="" class="img-fluid" alt="image to be found">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <form action="login.php">
                        <div class="form-outline mb-4 textfield">
                            <input type="username" id="form1username" class="form-control form-control-lg" placeholder="Username" />
                        </div>
                        <div class="form-outline mb-4 textfield">
                            <input type="password" id="form1password" class="form-control form-control-lg" placeholder="Password" />
                        </div>
                        <div class="d-flex justify-content-around align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="form1checkbox" checked />
                                <label class="form-check-label" for="form1checkbox"> Remember Me </label>
                            </div>
                            <a href="#!">Forgot Password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
                        </div>
                        <a class="btn btn-primary btn-lg btn-block" style="background-color: #3b5998;" href="#!" role="button">
                            Register for an Account
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </section>

</body>

</html>