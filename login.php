<?php
    require_once("classes/user.php");
    require_once("functions/conn.php");
    require_once("functions/functions.php");
    
    session_start();

    if (isset($_SESSION["user"])){
        header("Location: /newgate.ho/pages/dashboard.php");      
    }

    if (isset($_POST["submit"])){
        $conn = connect();
        $user = User::getUserWithLD($conn, $_POST["email"], $_POST["password"]);
        if (isset($user)){
            $user->saveToSession();
            header("Location: pages/dashboard.php");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/login.css"/>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <title> Login </title>
</head>
<body>
    <div class="topgrid">
        <div class = "floatright">
        <img src="assets/images/newgate.svg" alt="" class="nslogo">
        </div>
        <div class = "floatleft">
        <h1>CLINIC</h1>
        </div>
    </div> 
    <div class = "middlegrid">   
        <div></div>
        <div class="card">            
                <div>
                    <p class="details">Enter your details to continue</p>
                </div>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <input type="email" name="email" placeholder="Email" class="inputext" value="<?php echo Input::post('email');?>" required><br>
                    <input type="password" name="password" placeholder="Password" class="inputext" required><br><br>
                    <input type="submit" name="submit" value="Continue" class="bodbut" style="float:right">
                </form>   
        </div>  
        <div></div>
    </div>     
</body>
</html>