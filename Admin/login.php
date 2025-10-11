<?php
    session_start();
    include '../connection.php';

    // Check for the "Remember Me" cookie at the top of the page
    $remembered_email = "";
    if (isset($_COOKIE['remember_email'])) {
        $remembered_email = $_COOKIE['remember_email'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManavikFab Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root{
            --pink-100: #f8c9d8;
            --pink-200: #f4b6cc;
            --accent-1: #eaaec0;
            --panel-radius: 16px;
        }
        html,body{height:100%;}
        body {
            margin:0;
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(180deg,#efe6ee 0%, #f8f0f5 100%);
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:2rem;
        }

        /* Card wrapper */
        .login-wrapper{
            width:100%;
            max-width:1000px;
            height:calc(100vh - 4rem);
            min-height: 550px;
            border-radius:var(--panel-radius);
            overflow:hidden;
            display:flex;
            box-shadow:0 20px 60px rgba(16,24,40,0.25);
            background:transparent;
        }

        /* Left column: illustration / gradient */
        .login-illustration{
            flex:1.1;
            background: linear-gradient(135deg,var(--pink-100) 0%, var(--pink-200) 100%);
            position:relative;
            display:flex;
            align-items:center;
            justify-content:center;
            color:rgba(255,255,255,0.95);
            padding:2.5rem;
        }

        .illustration-inner{
            position:relative;
            z-index:2;
            max-width:320px;
            text-align:center;
        }
        .illustration-title{
            font-size:1.8rem;
            font-weight:700;
            margin-bottom:0.5rem;
            color:#5b2b4a;
        }
        .illustration-sub{
            color:rgba(0,0,0,0.45);
            background:rgba(255,255,255,0.6);
            display:inline-block;
            padding:0.5rem 1rem;
            border-radius:999px;
            font-size:0.95rem;
            margin-top:1rem;
        }

        /* Right column: panel */
        .login-panel{
            flex:0.9;
            background: #ffffff;
            padding:2.75rem 2.5rem;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .brand {
            font-size:1.6rem;
            font-weight:700;
            color:#3a2a3f;
            letter-spacing:0.2px;
        }
        .brand-sub{
            color:#7a6a78;
            font-size:0.95rem;
            margin-top:0.25rem;
        }

        .login-form{
            width:100%;
            margin-top:1.5rem;
        }
        .form-control{
            border-radius:8px;
            border:1px solid #e6d7de;
            padding:0.85rem 0.9rem;
            box-shadow:none;
        }
        .form-control:focus{
            border-color:var(--accent-1);
            box-shadow:0 6px 20px rgba(234,174,190,0.12);
            outline:none;
        }

        .btn-login{
            background: linear-gradient(90deg,var(--pink-200),var(--accent-1));
            color:#fff;
            border:none;
            padding:0.9rem;
            border-radius:10px;
            font-weight:700;
            box-shadow:0 8px 24px rgba(234,174,190,0.18);
            transition:transform .18s ease, box-shadow .18s ease;
        }
        .btn-login:hover{transform:translateY(-2px); box-shadow:0 18px 36px rgba(234,174,190,0.22)}

        /* MODIFIED: Responsive behavior */
        @media (max-width: 900px){
            body {
                padding: 0;
                /* Allow body to scroll if content is tall */
                height: auto;
                min-height: 100%;
            }
            .login-wrapper {
                flex-direction: column; /* Stack columns vertically */
                height: auto;
                width: 100%;
                max-width: 100%;
                border-radius: 0;
                box-shadow: none;
            }
            .login-illustration {
                padding: 2rem 1rem;
                /* Let the illustration take its natural height */
            }
            .login-panel {
                padding: 2.5rem 1.5rem;
            }
            /* NEW: Rounded corners for the illustration on small screens */
            .login-illustration {
                border-radius: var(--panel-radius); /* Apply the same rounded corners as the card */
                margin: 2rem; /* Add margin to separate it from the edges */
            }
            .login-wrapper {
                /* On small screens, the wrapper should not be rounded at all to allow for the rounded illustration and the full-width panel */
                border-radius: 0; 
                overflow: visible; /* Prevent content from being clipped */
                display: block; /* Change to block to handle spacing better */
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper" role="main">
        <div class="login-illustration" aria-hidden="true">
            <div class="illustration-inner">
                <img src="..\images\image1.png">
                <h3 class="illustration-title">Welcome to ManavikFab</h3>
                <div class="illustration-sub">Premium fashion, curated for you.</div>
            </div>
        </div>

        <div class="login-panel">
            <div>
                <div class="brand">ManavikFab <small class="text-muted">Admin</small></div>
                <div class="brand-sub">Sign in to manage products, orders & customers</div>
            </div>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="login-form">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="you@domain.com" value="<?php echo htmlspecialchars($remembered_email); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="pass" class="form-control" placeholder="Enter your password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember" <?php echo !empty($remembered_email) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    </div>
                <button type="submit" name="login" class="btn-login w-100">Sign in</button>
            </form>
            <?php
                if (isset($_POST['login'])) {
                    $email = $_POST['email'];
                    $pass = $_POST['pass'];

                    $stmt = mysqli_prepare($con, "SELECT AdminID, Password FROM `Admin` WHERE `Email` = ?");
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        if (password_verify($pass, $row['Password'])) {
                            // On successful login, check if "Remember Me" is ticked
                            if (!empty($_POST['remember'])) {
                                // Set cookie for 30 days
                                setcookie("remember_email", $email, time() + (86400 * 30), "/");
                            } else {
                                // If not ticked, delete any existing cookie
                                if (isset($_COOKIE['remember_email'])) {
                                    setcookie("remember_email", "", time() - 3600, "/");
                                }
                            }
                            
                            $_SESSION['adminid'] = $row['AdminID'];
                            echo "<script>window.open('../Admin','_self')</script>";
                        } else {
                            echo "<div class='alert alert-danger mt-4' role='alert'>Invalid Password!</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger mt-4' role='alert'>Admin not Found!</div>";
                    }
                    mysqli_stmt_close($stmt);
                }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>