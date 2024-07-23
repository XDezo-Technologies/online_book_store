<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_POST['add_book_listing'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    $select_book_listing_name = mysqli_query($conn, "SELECT name FROM `book_listing` WHERE name = '$name'");

    if (mysqli_num_rows($select_book_listing_name) > 0) {
        $message[] = 'Book listing name already exists';
    } else {
        $add_product_query = mysqli_query($conn, "INSERT INTO `book_listing` (name, price, image) VALUES ('$name', '$price', '$image')");
        if ($add_product_query) {
            if ($image_size > 2000000) {
                $message[] = 'Image size is too large';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Book listing added successfully!';
            }
        } else {
            $message[] = 'Book listing could not be added!';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `book_listing` WHERE id = '$delete_id'");
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('uploaded_img/' . $fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM `book_listing` WHERE id = '$delete_id'");
    header('location:admin_book_listing.php');
}

if (isset($_POST['update_book_listing'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];

    mysqli_query($conn, "UPDATE `book_listing` SET name = '$update_name', price = '$update_price' WHERE id = '$update_p_id'");

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = 'uploaded_img/' . $update_image;
    $update_old_image = $_POST['update_old_image'];

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Image file size is too large';
        } else {
            mysqli_query($conn, "UPDATE `book_listing` SET image = '$update_image' WHERE id = '$update_p_id'");
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('uploaded_img/' . $update_old_image);
        }
    }

    header('location:admin_book_listing.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Listing</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
    <style>
        .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .box {
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
            width: 200px;
        }
        .box img {
            width: 100%;
            height: auto;
            max-width: 150px;
            max-height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .edit-book_listing-form {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .edit-book_listing-form form {
            border: 1px solid #ccc;
            padding: 20px;
            width: 300px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="add-products">
    <h1 class="title">Book Listing</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <h3>Add Book Listing</h3>
        <input type="text" name="name" class="box" placeholder="Enter book listing name" required>
        <input type="number" min="0" name="price" class="box" placeholder="Enter book listing price" required>
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
        <input type="submit" value="Add Book Listing" name="add_book_listing" class="btn">
    </form>
</section>

<section class="show-book_listing">
    <div class="box-container">
        <?php
        $select_book_listing = mysqli_query($conn, "SELECT * FROM `book_listing`");
        if (mysqli_num_rows($select_book_listing) > 0) {
            while ($fetch_book_listing = mysqli_fetch_assoc($select_book_listing)) {
                ?>
                <div class="box">
                    <img src="uploaded_img/<?php echo $fetch_book_listing['image']; ?>" alt="">
                    <div class="name"><?php echo $fetch_book_listing['name']; ?></div>
                    <div class="price">$<?php echo $fetch_book_listing['price']; ?>/-</div>
                    <a href="admin_book_listing.php?update=<?php echo $fetch_book_listing['id']; ?>" class="option-btn">Update</a>
                    <a href="admin_book_listing.php?delete=<?php echo $fetch_book_listing['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">No book listing added yet!</p>';
        }
        ?>
    </div>
</section>

<section class="edit-book_listing-form">
    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        $update_query = mysqli_query($conn, "SELECT * FROM `book_listing` WHERE id = '$update_id'");
        if (mysqli_num_rows($update_query) > 0) {
            while ($fetch_update = mysqli_fetch_assoc($update_query)) {
                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                    <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
                    <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
                    <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Enter book listing name">
                    <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="Enter book listing price">
                    <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
                    <input type="submit" value="Update" name="update_book_listing" class="btn">
                    <input type="reset" value="Cancel" id="close-update" class="option-btn">
                </form>
                <?php
            }
        }
    } else {
        echo '<script>document.querySelector(".edit-book_listing-form").style.display = "none";</script>';
    }
    ?>
</section>

<script src="js/admin_script.js"></script>

</body>
</html>
