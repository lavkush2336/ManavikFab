<?PHP
    session_start();
    include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManavikFab User Signup</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* This CSS is identical to your login page for a consistent UI */
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
        .signup-wrapper{ /* Renamed from login-wrapper */
            width:100%;
            max-width:1100px; /* Slightly wider for more fields */
            min-height: 650px;
            border-radius:var(--panel-radius);
            overflow:hidden;
            display:flex;
            box-shadow:0 20px 60px rgba(16,24,40,0.25);
            background:transparent;
        }
        .signup-illustration{ /* Renamed */
            flex:1;
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
        .signup-panel{ /* Renamed */
            flex:1.1; /* More space for the form */
            background: #ffffff;
            padding:2rem 2.5rem;
            display:flex;
            flex-direction:column;
            justify-content:center;
            overflow-y: auto; /* Allow scrolling on small heights */
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
        .signup-form{
            width:100%;
            margin-top:1.5rem;
        }
        .form-control{
            border-radius:8px;
            border:1px solid #e6d7de;
            padding:0.75rem 0.9rem; /* Slightly smaller padding */
            box-shadow:none;
        }
        .form-control:focus{
            border-color:var(--accent-1);
            box-shadow:0 6px 20px rgba(234,174,190,0.12);
            outline:none;
        }
        .btn-signup{ /* Renamed */
            background: linear-gradient(90deg,var(--pink-200),var(--accent-1));
            color:#fff;
            border:none;
            padding:0.9rem;
            border-radius:10px;
            font-weight:700;
            box-shadow:0 8px 24px rgba(234,174,190,0.18);
            transition:transform .18s ease, box-shadow .18s ease;
        }
        .btn-signup:hover{transform:translateY(-2px); box-shadow:0 18px 36px rgba(234,174,190,0.22)}
        .login-section{ /* Renamed */
            text-align: center;
            margin-top: 1.5rem;
            color: #7a6a78;
        }
        .login-section a {
            color: #5b2b4a;
            font-weight: 600;
            text-decoration: none;
        }
        .error-message { color: #dc3545; font-size: 0.8rem; margin-top: 4px; display: none; }
        .info-icon { cursor: pointer; }

        /* Copied from login page for identical responsiveness */
        @media (max-width: 900px){
            body { padding: 0; height: auto; min-height: 100%; }
            .signup-wrapper { flex-direction: column; height: auto; width: 100%; max-width: 100%; border-radius: 0; box-shadow: none; }
            .signup-illustration { padding: 2rem 1rem; }
            .signup-panel { padding: 2.5rem 1.5rem; }
            .signup-illustration { border-radius: var(--panel-radius); margin: 2rem; }
            .signup-wrapper { border-radius: 0; overflow: visible; display: block; }
        }

        /* Dialogue Box styles from original signup page */
        .dialogue-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 999; opacity: 0; transition: opacity .3s ease; }
        .dialogue-overlay.active { display: block; opacity: 1; }
        .dialogue-box { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%,-50%); background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.2); z-index: 1000; width: 90%; max-width: 400px; }
        .dialogue-box.active { display: block; }
        .dialogue-box h3 { margin-top: 0; }
        .btn-close-dialogue { display: block; margin: 1rem auto 0; }
    </style>
</head>
<body>
    <div class="signup-wrapper" role="main">
        <div class="signup-illustration" aria-hidden="true">
            <div class="illustration-inner">
                <img src="images/image1.png">
                <h3 class="illustration-title">Join ManavikFab</h3>
                <div class="illustration-sub">Create an account to start your fashion journey.</div>
            </div>
        </div>

        <div class="signup-panel">
            <div>
                <div class="brand">Create Your Account</div>
                <div class="brand-sub">Let's get you started with ManavikFab</div>
            </div>

            <form id="signup-form" method="POST" class="signup-form" onsubmit="return validateForm()">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                        <div id="name-error" class="error-message">Name is required.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="you@domain.com" required>
                        <div id="email-error" class="error-message">Please enter a valid email.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="num" placeholder="10-digit number" pattern="[0-9]{10}" required>
                        <div id="phone-error" class="error-message">Please enter a valid 10-digit phone number.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" max="2020-01-01" required>
                        <div id="dob-error" class="error-message">You must be at least 5 years old.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label d-flex justify-content-between">
                            <span>Password</span>
                            <i class="bi bi-info-circle info-icon" onclick="showPasswordPattern()"></i>
                        </label>
                        <input type="password" class="form-control" id="password" name="pass" placeholder="Create a password" required>
                        <div id="password-error" class="error-message">Password does not meet requirements.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirm-password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm-password" placeholder="Confirm password" required>
                        <div id="confirm-password-error" class="error-message">Passwords do not match.</div>
                    </div>
                </div>
                <button type="submit" name="verify" class="btn-signup w-100 mt-3">Create Account</button>
            </form>
             <?PHP
                // THIS IS THE ORIGINAL PHP CODE FROM YOUR OLD signup.php
                if (isset($_POST['verify'])) {
                    $name = mysqli_real_escape_string($con, $_POST['name']);
                    $email = mysqli_real_escape_string($con, $_POST['email']);
                    $phone = mysqli_real_escape_string($con, $_POST['num']);
                    $dob = mysqli_real_escape_string($con, $_POST['dob']);
                    $pass = $_POST['pass'];
                    $hpass = password_hash($pass, PASSWORD_DEFAULT);

                    $sql_check = "SELECT * FROM `User` WHERE `Email` = ? OR `Phone` = ?";
                    $stmt_check = mysqli_prepare($con, $sql_check);
                    mysqli_stmt_bind_param($stmt_check, "ss", $email, $phone);
                    mysqli_stmt_execute($stmt_check);
                    $result_check = mysqli_stmt_get_result($stmt_check);

                    if (mysqli_num_rows($result_check) > 0) {
                        echo "<div class='alert alert-danger mt-3' role='alert'>Email ID or Phone Number already exists!</div>";
                    } else {
                        $potp = rand(100000, 999999);
                        $eotp = rand(100000, 999999);
                        $_SESSION['potp'] = $potp;
                        $_SESSION['eotp'] = $eotp;
                        
                        $sql_insert = "INSERT INTO `User` (`Name`, `Email`, `Phone`, `DOB`, `Password`, `Verified`, `eotp`, `potp`) VALUES (?, ?, ?, ?, ?, '0', ?, ?)";
                        $stmt_insert = mysqli_prepare($con, $sql_insert);
                        mysqli_stmt_bind_param($stmt_insert, "sssssss", $name, $email, $phone, $dob, $hpass, $eotp, $potp);
                        
                        if (mysqli_stmt_execute($stmt_insert)) {
                            echo "<script>window.open('verify.php','_self')</script>";
                            exit();
                        } else {
                            echo "<div class='alert alert-danger mt-3' role='alert'>Failed to register user. Please try again.</div>";
                        }
                        mysqli_stmt_close($stmt_insert);
                    }
                    mysqli_stmt_close($stmt_check);
                }
            ?>
            <div class="login-section">
                Already have an account? <a href="user-login.php">Login</a>
            </div>
        </div>
    </div>

    <!-- Dialogue Box for Password Pattern (from original signup.php) -->
    <div class="dialogue-overlay" id="dialogue-overlay"></div>
    <div class="dialogue-box" id="password-pattern-dialogue">
        <h3>Password Requirements</h3>
        <p>Your password must:</p>
        <ul>
            <li>Be at least 8 characters long</li>
            <li>Include at least one uppercase letter (A-Z)</li>
            <li>Include at least one lowercase letter (a-z)</li>
            <li>Include at least one number (0-9)</li>
            <li>Include at least one special character (!@#$%^&*)</li>
        </ul>
        <button class="btn btn-sm btn-light btn-close-dialogue" onclick="hidePasswordPattern()">Close</button>
    </div>

    <script>
        // THIS IS THE ORIGINAL JAVASCRIPT VALIDATION FROM YOUR OLD signup.php
        function validateForm() {
            document.querySelectorAll('.error-message').forEach(e => e.style.display = 'none');
            let isValid = true;
            
            if (!document.getElementById('name').value.trim()) {
                document.getElementById('name-error').style.display = 'block';
                isValid = false;
            }

            const email = document.getElementById('email').value.trim();
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById('email-error').style.display = 'block';
                isValid = false;
            }

            const phone = document.getElementById('phone').value.trim();
            if (!/^[0-9]{10}$/.test(phone)) {
                document.getElementById('phone-error').style.display = 'block';
                isValid = false;
            }

            const dob = document.getElementById('dob').value;
            if (!dob || new Date(dob) > new Date('2020-01-01')) {
                document.getElementById('dob-error').style.display = 'block';
                isValid = false;
            }

            const password = document.getElementById('password').value;
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
            if (!passwordPattern.test(password)) {
                document.getElementById('password-error').style.display = 'block';
                isValid = false;
            }

            if (document.getElementById('confirm-password').value !== password) {
                document.getElementById('confirm-password-error').style.display = 'block';
                isValid = false;
            }
            return isValid;
        }

        function showPasswordPattern() {
            document.getElementById('password-pattern-dialogue').classList.add('active');
            document.getElementById('dialogue-overlay').classList.add('active');
        }

        function hidePasswordPattern() {
            document.getElementById('password-pattern-dialogue').classList.remove('active');
            document.getElementById('dialogue-overlay').classList.remove('active');
        }
    </script>
</body>
</html>