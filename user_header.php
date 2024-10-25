<?php

if (isset($_GET['Message'])) {
    print '<script type="text/javascript">
               alert("' . $_GET['Message'] . '");
           </script>';
}

if (isset($_GET['response'])) {
    print '<script type="text/javascript">
               alert("' . $_GET['response'] . '");
           </script>';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Books">
    <meta name="author" content="Ishika Jindal">
    <title>Online Bookstore</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/my.css" rel="stylesheet">
    <style>
        .modal-header {background:#D67B22;color:#fff;font-weight:800;}
        .modal-body {font-weight:800;}
        .modal-body ul {list-style:none;}
        .modal .btn {background:#D67B22;color:#fff;}
        .modal a {color:#D67B22;}
        .modal-backdrop {position:inherit !important;}
        #login_button, #register_button {background:none;color:#D67B22!important;}
        #query_button {
            position:fixed;
            right:0px;
            bottom:0px;
            padding:10px 80px;
            background-color:#D67B22;
            color:#fff;
            border-color:#f05f40;
            border-radius:2px;
        }
        @media (max-width:767px) {
            #query_button {padding: 5px 20px;}
        }
        .navbar-nav {
            flex: 1;
            display: flex;
            justify-content: center;
            font-size: 20px;
        }
        .navbar-nav.navbar-right {
            justify-content: flex-end;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" style="padding: 1px;">
                    <img class="img-responsive" alt="Brand" src="img/logo.jpg" style="width: 147px; margin: 0px;">
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php
                    if (isset($_SESSION['user'])) {
                        echo '
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="shop.php">Shop</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="orders.php">Orders</a></li>';
                    }
                    ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if (!isset($_SESSION['user'])) {
                        echo '
                        <li><a href="login.php" class="btn btn-lg">Login</a></li>
                        <li><a href="register.php" class="btn btn-lg">Sign Up</a></li>';
                    } else {
                        echo '
                        <li><a href="#" class="btn btn-lg">Hello ' . $_SESSION['user'] . '</a></li>
                        <li><a href="cart.php" class="btn btn-lg">Cart</a></li>
                        <li><a href="logout.php" class="btn btn-lg">LogOut</a></li>';
                    }
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
