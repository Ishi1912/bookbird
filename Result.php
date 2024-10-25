<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
  header('location: index.php?Message=Login To Continue');
}

// Check for add to cart submission
if (isset($_POST['add_to_cart'])) {
  $user_id = $_SESSION['user_id'];
  $product_name = $_POST['product_name'];
  $product_price = $_POST['product_price'];
  $product_image = $_POST['product_image'];
  $product_quantity = $_POST['product_quantity'];

  // Check if product is already in the cart
  $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

  if (mysqli_num_rows($check_cart_numbers) > 0) {
    $message[] = 'Already added to cart!';
  } else {
    // Insert product into the cart
    mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
    $message[] = 'Product added to cart!';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Page</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="css/w3.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="css/my.css" type="text/css">
  <style>
    #books .row {
      margin-top: 30px;
      font-weight: 800;
    }

    @media only screen and (max-width: 760px) {
      #books .row {
        margin-top: 10px;
      }
    }

    .book-block {
      margin-top: 20px;
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #DEEAEE;
      border-radius: 10px;
      height: 100%;
    }
  </style>
</head>

<body>

  <?php include 'user_header.php'; ?>

  <div id="top">
    <div id="searchbox" class="container-fluid" style="width:112%;margin-left:-6%;margin-right:-6%;">
      <div>
        <form role="search" method="POST" action="" style="display: flex; width: 100%;">
          <input type="text" class="form-control" name="keyword" style="width:80%;margin:20px 10% 20px 10%;" placeholder="Search for a Book, Author or Category">
          <input type="submit" value="Search" name="submit" class="btn btn-primary">
        </form>
      </div>
    </div>
  </div>

  <?php
  if (isset($_POST['submit']) && !empty($_POST['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);

    // Query to search in both `products` and `add_products` tables
    $query = "
      SELECT PID AS id, Title AS name, Author, Price, MRP, Discount, PID AS image, 'add_products' AS source FROM `add_products`
      WHERE PID LIKE '%$keyword%' OR Title LIKE '%$keyword%' OR Author LIKE '%$keyword%' OR Publisher LIKE '%$keyword%' OR Category LIKE '%$keyword%'
      UNION
      SELECT id, name, NULL AS Author, price, NULL AS MRP, NULL AS Discount, image, 'products' AS source FROM `products`
      WHERE name LIKE '%$keyword%'
    ";
    
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    
    $i = 0;
    echo '<div class="container-fluid" id="books">
            <div class="row">
              <div class="col-xs-12 text-center" id="heading">
                <h4 style="color:#00B9F5;text-transform:uppercase;">Found ' . mysqli_num_rows($result) . ' records</h4>
              </div>
            </div>';

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        // Determine image path based on the source table
        if ($row['source'] == 'add_products') {
          $path = "img/books/" . $row['image'] . ".jpg"; // Assuming add_products images are stored in img/books/
        } else {
          $path = "./uploaded_img/" . $row['image']; // Assuming products images are stored in uploaded_img/
        }

        $description = "description.php?ID=" . $row['id'];
        if ($i % 3 == 0) {
          $offset = 0;
        } else {
          $offset = 1;
        }
        if ($i % 3 == 0) echo '<div class="row">';
        
        echo '
          <div class="col-sm-5 col-sm-offset-1 col-md-3 col-md-offset-' . $offset . ' col-lg-3 text-center w3-card-8 w3-dark-grey">
            <div class="book-block">
              <img class="book block-center img-responsive" src="' . $path . '" alt="Book Image">
              <hr>
              <h4>' . $row["name"] . '</h4>';

        if (!empty($row['Price'])) {
          echo 'Rs. ' . $row["Price"] . ' &nbsp;';
        }
        if (!empty($row['MRP'])) {
          echo '<span style="text-decoration:line-through;color:#828282;"> Rs. ' . $row["MRP"] . ' </span>';
        }
        if (!empty($row['Discount'])) {
          echo '<span class="label label-warning">' . $row["Discount"] . '%</span>';
        }
        
        // Form to add to cart
        echo '
              <form action="" method="post">
                <input type="hidden" name="product_name" value="' . $row['name'] . '">
                <input type="hidden" name="product_price" value="' . $row['Price'] . '">
                <input type="hidden" name="product_image" value="' . $row['image'] . '">
                <input type="number" name="product_quantity" min="1" value="1" style="width:60%; margin-top:5px;">
                <input type="submit" value="Add to Cart" name="add_to_cart" class="product_btn" style="margin-top:10px;">
              </form>
            </div>
          </div>';
        
        $i++;
        if ($i % 3 == 0) echo '</div>';
      }
    } else {
      echo '<p class="empty">No result found!</p>';
    }

    echo '</div>';
  }
  ?>

  <?php include 'footer.php'; ?>

</body>

</html>
