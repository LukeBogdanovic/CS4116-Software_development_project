<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <link rel="stylesheet" type="text/css" href="css/admindashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

    <?php
    require_once "includes/navbar.php";
    ?>

    <main>

        <div class="container mt-5 mb-5">

            <div class="d-flex justify-content-center">

                <div class="col-md-6">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-right">Reports Table:</h5>
                    </div>

                    <div class="table-scroll scrollbar">

                        <table class="table table-striped">

                            <thead>

                                <tr>
                                    <th scope="col">Username</th>
                                    <th scope="col">Reported By</th>
                                    <th scope="col">Reason for Report</th>
                                </tr>

                            </thead>

                            <tbody>

                                <tr>
                                    <th scope="row">username1</th>
                                    <td>The Cool Police</td>
                                    <td>Not being cool enough</td>
                                </tr>

                                <tr>
                                    <th scope="row">username2</th>
                                    <td>The Cool Police</td>
                                    <td>Being too cool</td>
                                </tr>

                                <tr>
                                    <th scope="row">username3</th>
                                    <td>The Cool Police</td>
                                    <td>Being too cool</td>
                                </tr>

                                <tr>
                                    <th scope="row">username4</th>
                                    <td>The Cool Police</td>
                                    <td>Being too cool</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <div class="row mt-5">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-right">Ban User:</h5>
                        </div>

                        <div class="input-group mb-3">

                            <input type="text" class="form-control" placeholder="username">

                            <div class="input-group-append">
                                <button type="button" class="btn input-group-text btn-danger">Ban</button>
                            </div>

                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-right">Unban User:</h5>
                        </div>

                        <div class="input-group mb-3">

                            <input type="text" class="form-control" placeholder="username">

                            <div class="input-group-append">
                                <button type="button" class="btn input-group-text btn-success">Unban</button>
                            </div>

                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                        <h5 class="text-right">List of Banned Users:</h5>
                    </div>

                    <div class="table-scroll scrollbar">

                        <table class="table table-striped">

                            <thead>

                                <tr>
                                    <th scope="col">Username</th>
                                    <th scope="col">Banned By</th>
                                    <th scope="col">Reason for Ban</th>
                                </tr>

                            </thead>

                            <tbody>

                                <tr>
                                    <th scope="row">username1</th>
                                    <td>Admin</td>
                                    <td>Reason</td>
                                </tr>

                                <tr>
                                    <th scope="row">username2</th>
                                    <td>Admin</td>
                                    <td>Reason</td>
                                </tr>

                                <tr>
                                    <th scope="row">username3</th>
                                    <td>Admin</td>
                                    <td>Reason</td>
                                </tr>

                                <tr>
                                    <th scope="row">username4</th>
                                    <td>Admin</td>
                                    <td>Reason</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </main>

    <?php
    require_once "includes/footer.php";
    ?>

</body>

</html>