<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './debugger.php';
require_once './classes/Database.php';
require_once './classes/Product.php';

$Database = new Database;
$product = new Product($Database->getConnection());
$products = $product->all();
$count = 0;
if(isset($_POST['create_product'])){
    $item = new Product($Database->getConnection());
    $item = $item->create($_POST['name'], $_POST['description'], $_POST['price'], $_POST['brand'], $_POST['quantity']);
    $products = $product->all();
}
?>

<div class="card" id="create_product_model">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Create New Product
                </button>
                <hr>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name"  placeholder="Enter Product name" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Brand" class="form-label">Product Brand</label>
                                        <input type="text" class="form-control" id="brand" name="brand"  placeholder="Enter Product Brand" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="description" class="form-label">Product description</label>
                                        <input type="text" class="form-control" id="description" name="description"  placeholder="Enter description Brand" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="price" class="form-label">Products Price</label>
                                        <input type="number" class="form-control" id="Price" name="price" placeholder="Enter Products Price">
                                    </div>
                                    <div class="col-12">
                                        <label for="quantity" class="form-label">Product Quantity</label>
                                        <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Enter Products Price">
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="create_product" class="btn btn-primary">Save Product</button>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $item): ?>
                        <tr>
                            <th scope="row"><?php echo ++$count ;?></th>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['brand']; ?></td>
                            <td><?php echo $item['price']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo $item['description']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
