<?php
// Header
include 'header.php';

// Cart Page Layout
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(52, 152, 219, 0.8), rgba(46, 204, 113, 0.8));
            overflow: hidden;
            color: white;
        }

        .btn{
            color:#c0111 !important;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/images/pattern.png') repeat;
            opacity: 0.1;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            from { background-position: 0 0; }
            to { background-position: 100% 100%; }
        }

        .hero-section .display-4 {
            font-size: 3.5rem;
            font-weight: 300;
            margin-bottom: 1.5rem;
            background: linear-gradient(90deg, #fff, #e0e0e0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .hero-section .lead {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .cart-items {
            margin-top: 20px;
        }

        .cart-item {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }

        .cart-summary {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .checkout {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .checkout:hover {
            background-color: #0056b3;
        }
    </style>
    <title>Your Cart</title>
</head>
<body>
    <div class="container">
        <h1>Your Shopping Cart</h1>
        <div class="cart-items">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Courses</th>
                        <th scope="col">Course Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <img src="assets/images/php.jpg" alt="PHP Course" class="img-fluid" style="max-height: 80px; object-fit: cover;"> 
                        </td>
                        <td>PHP Course</td>
                        <td>₹99</td>
                        <td><button class="btn" onclick="deleteItem(this)"><i class="fas fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>
                            <img src="assets/images/java.jpg" alt="Java Course" class="img-fluid" style="max-height: 80px; object-fit: cover;"> 
                        </td>
                        <td>Java Course</td>
                        <td>₹89</td>
                        <td><button class="btn" onclick="deleteItem(this)"><i class="fas fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>
                            <img src="assets/images/python.jpg" alt="Python Course" class="img-fluid" style="max-height: 80px; object-fit: cover;"> 
                        </td>
                        <td>Python Course</td>
                        <td>₹79</td>
                        <td><button class="btn" onclick="deleteItem(this)"><i class="fas fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td>
                            <img src="assets/images/javascript.jpg" alt="JavaScript Course" class="img-fluid" style="max-height: 80px; object-fit: cover;"> 
                        </td>
                        <td>JavaScript Course</td>
                        <td>₹59</td>
                        <td><button class="btn" onclick="deleteItem(this)"><i class="fas fa-trash"></i></button></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right"><strong>Total Courses:</strong></td>
                        <td><strong>₹326</strong></td>
                        <td>
                            <a href="checkout.php" class="btn btn-primary">Checkout <i class="fas fa-shopping-cart"></i></a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="cart-summary">
            <p>Total Items: 4</p>
            <p>Total Price: ₹326</p>
            <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
        </div>
    </div>
    <script>
    function deleteItem(button) {
        // Remove the row from the table
        var row = button.parentNode.parentNode; // Get the row of the button
        row.parentNode.removeChild(row); // Remove the row
    }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
// Footer
include 'footer.php';
?>
