<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS Admin Portal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <div class="container">

        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-lg-4 col-md-6">

                <div class="card shadow-lg">

                    <div class="card-header">
                        <h3>EMS Admin Portal</h3>
                        <p class="mb-0">Employee Management System</p>
                    </div>

                    <div class="card-body">

                        <h4 class="text-center mb-4">
                            Admin Login
                        </h4>

                        <form>

                            <div class="mb-3">
                                <label class="form-label">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    placeholder="Enter Email">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    Password
                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    placeholder="Enter Password">
                            </div>

                            <button
                                type="submit"
                                class="btn btn-login w-100">

                                Sign In

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>