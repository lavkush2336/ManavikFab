<?php
    session_start();
    include 'connection.php';
    function getUserIP()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // HTTP_X_FORWARDED_FOR can contain a list of IPs.
        // The first IP is generally the most accurate client IP.
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $firstIp = trim($ipList[0]);

        if (filter_var($firstIp, FILTER_VALIDATE_IP)) {
            return $firstIp;
        }
    }
    if (isset($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
        return $_SERVER['REMOTE_ADDR'];
    }
    // Return a default if no IP could be determined (should be rare)
    return 'IP not found';
    }
    $user_ip = getUserIP();
    $remember=0;
    $sql="SELECT `Email`,`Remember`,`IP` FROM `User` WHERE `IP`='$user_ip'";
    $result=mysqli_query($con,$sql);
    $rows=mysqli_num_rows($result);
    if($rows>0)
    {
        while($row=mysqli_fetch_assoc($result))
        {
            $email=$row['Email'];
            $remember=$row['Remember'];
            $ip=$row['IP'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManavikFab User Login</title>
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
        .signup-section{
            text-align: center;
            margin-top: 1.5rem;
            color: #7a6a78;
        }
        .signup-section a {
            color: #5b2b4a;
            font-weight: 600;
            text-decoration: none;
        }

        /* UPDATED: This block now perfectly matches the admin login page's responsive styles */
        @media (max-width: 900px){
            body {
                padding: 0;
                height: auto;
                min-height: 100%;
            }
            .login-wrapper {
                flex-direction: column;
                height: auto;
                width: 100%;
                max-width: 100%;
                border-radius: 0;
                box-shadow: none;
            }
            .login-illustration {
                padding: 2rem 1rem;
            }
            .login-panel {
                padding: 2.5rem 1.5rem;
            }
            .login-illustration {
                border-radius: var(--panel-radius);
                margin: 2rem;
            }
            .login-wrapper {
                border-radius: 0;
                overflow: visible;
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper" role="main">
        <div class="login-illustration" aria-hidden="true">
            <div class="illustration-inner">
                <img src="images/image1.png">
                <h3 class="illustration-title">Welcome to ManavikFab</h3>
                <div class="illustration-sub">Premium fashion, curated for you.</div>
            </div>
        </div>

        <div class="login-panel">
            <div>
                <div class="brand">ManavikFab <small class="text-muted">User Login</small></div>
                <div class="brand-sub">Sign in to continue to your account</div>
            </div>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="login-form">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <?PHP
                        if($remember==1 && $user_ip==$ip)
                        {
                            echo "<input type='email' id='email' name='email' class='form-control' placeholder='you@domain.com' value='".$email."' required>";
                        }
                        else
                        {
                            echo "<input type='email' id='email' name='email' class='form-control' placeholder='you@domain.com' required>";
                        }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="pass" class="form-control" placeholder="Enter your password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <?PHP
                            if($remember==1 && $user_ip==$ip)
                            {
                                echo "<input class='form-check-input' type='checkbox' id='remember' name='remember' checked>";
                            }
                            else
                            {
                                echo "<input class='form-check-input' type='checkbox' id='remember' name='remember'>";
                            }
                        ?>
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                </div>
                <button type="submit" name="log" class="btn-login w-100">Login</button>
            </form>
            
            <div class="signup-section">
                Don't have an Account? <a href="signup.php">Sign Up</a>
            </div>

            <?php
                if (isset($_POST['log'])) {
                    $email = $_POST['email'];
                    $pass = $_POST['pass'];

                    $stmt = mysqli_prepare($con, "SELECT UserID, Name, Password, Verified FROM `User` WHERE `Email` = ?");
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $id=$row['UserID'];
                        if(isset($_POST['remember']))
                        {
                            $sql="UPDATE `User` SET `Remember`='1' WHERE `UserID`='$id'";
                            $result=mysqli_query($con,$sql);
                        }
                        else
                        {
                            $sql="UPDATE `User` SET `Remember`='0' WHERE `UserID`='$id'";
                            $result=mysqli_query($con,$sql);
                        }
                        if($row['Verified']==0)
                        {
                            $potp = rand(100000, 999999);
                            $eotp = rand(100000, 999999);
                            $_SESSION['potp'] = $potp;
                            $_SESSION['eotp'] = $eotp;
                            $sql="UPDATE `User` SET `eotp`='$eotp',`potp`='$potp' WHERE `UserID`='$id";
                            $result=mysqli_query($con,$sql);
                            echo "<script>alert(User Not Vrifed, Verify EmailID & Phone Number!)</script>";
                            echo "<script>window.open('verify.php','_self')</script>";
                        }
                        if (password_verify($pass, $row['Password'])) {
                            $_SESSION['userid'] = $row['UserID'];
                            $_SESSION['name'] = $row['Name'];
                            $_SESSION['email'] = $email;
                            echo "<script>window.open('index.php','_self')</script>";
                        } else {
                            echo "<div class='alert alert-danger mt-4' role='alert'>Invalid Password!</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger mt-4' role='alert'>User not Found!</div>";
                    }
                    mysqli_stmt_close($stmt);
                }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>