<?php
    require_once("../functions/functions.php");
    require_once("../classes/user.php");
    require_once("../functions/conn.php");
    session_start();
    $current_user = getCurrentUserOrDie();
    if (!$current_user->isAdmin()) {
        doUnauthorized();      
    }
    $user;
    $conn = connect();
    if(isset($_GET["id"])){
        $id = Input::get("id");
        $user = User::getUserWithID($conn, $id);
    }
    if (isset($_POST["submit"])) {
        if (isset($_POST['role'])) {
            $conn = connect();
            $user = new User(Input::post("id"), $_POST['email'], $_POST['firstname'], $_POST['lastname'], $_POST['role']);
            $user->updateToDB($conn);
            redirect("viewusers.php");
        }

    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
          integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/web-fonts-with-css/css/fontawesome-all.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../assets/css/style.css"/>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../assets/css/main.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="../assets/css/editusers.css"/>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/jquery-3.3.1.min.js"></script>
    <title> Edits Users </title>
</head>
<body>
<section id="sideMenu">
    <img class="logo" src="../assets/images/newgate.svg" alt="logo here">
    <nav>
        <a href="viewusers.php" class="dash_btn"><i class="fas fa-long-arrow-alt-left"></i>Back</a>
        <a href="#" class="active dash_btn"><i class="fas fa-user-edit"></i>Edit User</a>
        <a href="../pages/dashboard.php" class="dash_btn"><i class="fas fa-home"></i>Home</a>
    </nav>
</section>
<header>
    <div class="name-field">
        <H1><?php 
        $name = ($current_user->isDoctor())?"Dr. ":"";
        $name .= strtoupper($current_user->firstname).", ";
        $name .= strtoupper($current_user->lastname);
        echo $name;
        ?></H1>
    </div>
    <div class="user-field">
        <a href="#"><i class="b far fa-question-circle"></i></a>
        <a href="#" class="notification"><i class="b fas fa-bell"></i><span class="circle">3</span></a>
        <a href="#">
            <div class="user-img"></div>
            <i class="b far fa-user"></i>
        </a>
    </div>
</header> <section class="main-container">
    <div class="stuff ">
        <div class="middlegrid">
            <div></div>
            <div class="card">
                <div class="content">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $user->id ?>">
                        <input class="inputext" type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($user->email)?>" required>
                        <br>
                        <input class="inputext" type="text" name="firstname" placeholder="Firstname" value="<?php echo htmlspecialchars($user->firstname)?>"
                               required>
                        <br>
                        <input class="inputext" type="text" name="lastname" placeholder="Lastname" value="<?php echo htmlspecialchars($user->lastname)?>"
                               required>
                        <br>
                        <br>
                        <div class="container basictext">
                            <input class="checkmarc" id="rol1" type="checkbox" name="role[]" value="ADMIN"  >
                            <label for="rol1">Admin</label>
                            <br>
                            <input class="checkmarc" id="rol2" type="radio" name="role[]" value="DOCTOR" <?php echo ($user->isDoctor())?"checked":""?>>
                            <label for="rol2">Doctor</label>
                            <input class="checkmarc" id="rol3" type="radio" name="role[]" value="SUPPORT" <?php echo ($user->isSupport())?"checked":""?>>
                            <label for="rol3">Support</label>
                        </div>
                        <br>
                        <input class="bodbut" type="submit" name="submit" value="Update User">
                    </form>
                </div>
            </div>
            <div></div>
        </div>

    </div>
</section>

</body>
</html>