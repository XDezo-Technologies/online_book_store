


<!doctype html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <title>Title</title>
   <style>
        .card {
            width: 18rem;
            margin: 1rem;
        }
        .container-flex {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .card-img-top {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    
    
<?php include 'header.php'; ?>

   <div class="container">
      <h2> Books List</h2><br>
      <img src="images/bestseller.jpg" alt="">
   </div>

   <div class="container mt-5">
        <div class="row">
            <div class="container d-flex">
                <?php
                include 'config.php';

                $book_listing = "SELECT * FROM book_listing";
                $book_listing_result = mysqli_query($conn, $book_listing);
                while ($book_listing_data = $book_listing_result->fetch_array()) {
                ?>
                    <div class="card">
                        <img src="uploaded_img/<?php echo htmlspecialchars($book_listing_data['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($book_listing_data['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($book_listing_data['name']); ?></h5>
                            <h3 class="card-text text-color"><?php echo htmlspecialchars($book_listing_data['price']); ?></h3>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


   
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>