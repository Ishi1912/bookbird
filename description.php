<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}

if (isset($_GET['ID'])) {
    $PID = $_GET['ID'];
    $query = "SELECT * FROM add_products WHERE PID = '$PID'";
    $result = mysqli_query($conn, $query) or die('query failed');

    // Check if the product exists
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        header('location:shop.php'); // Redirect if product is not found
        exit();
    }
} else {
    header('location:shop.php');
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $quantity = $_POST['quantity'];

    // Check if the product already exists in the cart
    $check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id' AND name='{$row['Title']}'") or die('query failed');

    if (mysqli_num_rows($check_cart) > 0) {
        $message[] = 'Product already in cart!';
    } else {
        // Add to cart if not exists
        $cart_query = "INSERT INTO cart (user_id, name, price, quantity, image) VALUES ('$user_id', '{$row['Title']}', '{$row['Price']}', '$quantity', '{$row['image']}')";
        mysqli_query($conn, $cart_query) or die('query failed');
        $message[] = 'Product added to cart!';
    }
}

$path = "img/books/{$row['PID']}.jpg"; // Path for the image
$target = "cart.php"; // Example target for the "Add to Cart" button
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['Title']; ?></title>
    <link rel="stylesheet" href="style.css"> <!-- CSS included -->
    <link rel="stylesheet" href="home.css"> <!-- CSS included -->
    <style>
        #description {
            border: 1px solid #DEEAEE;
            margin-bottom: 20px;
            padding: 20px 50px;
            background: #fff;
            margin-left: 10%;
            margin-right: 10%;
        }

        #description hr {
            margin: auto;
        }

        .tag {
            display: inline;
            float: left;
            padding: 2px 5px;
            width: auto;
            background: #F5A623;
            color: #fff;
            height: 23px;
        }

        .tag-side {
            display: inline;
            float: left;
        }

        .product_info {
            text-align: center;
        }
    </style>
</head>

<body>

    <?php include 'user_header.php'; ?>
    <div id="top" >
      <div id="searchbox" class="container-fluid" style="width:112%;margin-left:-6%;margin-right:-6%;">
          <div>
              <form role="search" method="POST" action="Result.php">
                  <input type="text" class="form-control" name="keyword" style="width:80%;margin:20px 10% 20px 10%;" placeholder="Search for a Book , Author Or Category">
              </form>
          </div>
      </div>

    <div class="container-fluid" id="books">
    <div class="row">
        <!-- Product Image and Add to Cart Button -->
        <div class="col-sm-10 col-md-6">
            <div class="tag"><?php echo $row['Discount']; ?>% OFF</div>
            <div class="tag-side"><img src="img/orange-flag.png"></div>
            <img class="center-block img-responsive" src="<?php echo $path; ?>" height="550px" style="padding:20px;">
        </div>
        <div class="col-sm-10 col-md-4 col-md-offset-1">
            <h2><?php echo $row['Title']; ?></h2>
            <span style="color:#00B9F5;">#<?php echo $row['Author']; ?>&nbsp;&nbsp;#<?php echo $row['Publisher']; ?></span>
            <hr>

            <!-- Quantity Input -->
            <form action="" method="POST" id="quantity">
                <span style="font-weight:bold;">Quantity:</span>
                <input type="number" name="quantity" min="1" value="1">
            </form>
            <br>

            <!-- Add to Cart Button -->
            <form action="" method="POST" id="buyLink" class="btn btn-lg btn-danger" style="padding:15px;color:white;text-decoration:none;">
                <input type="hidden" name="quantity" value="1"> <!-- Assuming default quantity is 1, adjust if needed -->
                <input type="submit" name="add_to_cart" value="ADD TO CART for Rs. <?php echo $row['Price']; ?>" style="background: none; border: none; color: white; font-size: 18px; cursor: pointer;">
                <br>
                <span style="text-decoration:line-through;">Rs. <?php echo $row['MRP']; ?></span> | <?php echo $row['Discount']; ?>% discount
            </form>
        </div>
    </div>
</div>

<!-- Product Description Section -->
<section>
    <div class="container-fluid" id="description">
        <div class="row">
            <h2>Description</h2>
            <p><?php echo $row['Description']; ?></p>
            <pre style="background:inherit;border:none;">
PRODUCT CODE  <?php echo $row['PID']; ?>   <hr>
TITLE         <?php echo $row['Title']; ?> <hr>
AUTHOR        <?php echo $row['Author']; ?> <hr>
AVAILABLE     <?php echo $row['Available']; ?> <hr>
PUBLISHER     <?php echo $row['Publisher']; ?> <hr>
EDITION       <?php echo $row['Edition']; ?> <hr>
LANGUAGE      <?php echo $row['Language']; ?> <hr>
PAGES         <?php echo $row['page']; ?> <hr>
WEIGHT        <?php echo $row['weight']; ?> <hr>
            </pre>
        </div>
    </div>
</section>
<div class="container-fluid" id="service">
      <div class="row">
          <div class="col-sm-6 col-md-3 text-center">
               <span class="glyphicon glyphicon-heart"></span> <br>
               24X7 Care <br>
               Happy to help 24X7, call us on 0120-3062244 or click here
          </div>
          <div class="col-sm-6 col-md-3 text-center">
               <span class="glyphicon glyphicon-ok"></span> <br>
               Trust <br>
               Your money is yours! All refunds come with no question asked guarantee.
          </div>
          <div class="col-sm-6 col-md-3 text-center">
               <span class="glyphicon glyphicon-check"></span> <br>
               Assurance <br>
               We provide 100% assurance. If you have any issue, your money is immediately refunded. Sit back and enjoy your shopping.
          </div>
          <div class="col-sm-6 col-md-3 text-center">
               <span class="glyphicon glyphicon-tags"></span> <br>
               24X7 Care <br>
               Happiness is guaranteed. If we fall short of your expectations, give us a shout.
          </div>
      </div>
</div>

    <?php include 'footer.php'; ?>
</body>

</html>
