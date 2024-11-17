<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './debugger.php';
require_once './classes/Database.php';
require_once './classes/Product.php';
require_once './classes/Order.php';
require_once './classes/User.php';

$database = new Database;
$orders = new Order($database->getConnection());
$orders = $orders->all();
$count = 0;

$user = new User($database->getConnection());
if (!$_SESSION['auth']['auth']) {header('location: ./auth.php');}

$user = $user->find($_SESSION['auth']['id']) ?? '';

if(isset($_POST['view_product'])){
    $this_order = new Order($database->getConnection());
    $this_order = $this_order->find($_POST['item_id']);
    // print_r($this_order);

    $product_view = new Product($database->getConnection());
    $product_view = $product_view->find($this_order['product_id']);
    // print_r($product_view);
}

?>
<?php if(isset($product_view)):?>

    <form method="POST" class="card" style="margin:auto; padding:30px; width:60%; margin-bottom:35px;">
        <h1><?php echo $product_view['name']; ?></h1>
        <h4><?php echo $product_view['brand']; ?></h4>
        <h2>₦ <?php echo $product_view['price']; ?></h2>
        <p><?php echo $product_view['description']; ?></p>
        <input type="hidden" name="item_id" value="<?php echo $product_view['id']; ?>">
        <div class="row">
            <label for="validationDefault04" class="col">Status</label>
        <div class="col-6">
            <select class="form-select " id="validationDefault04" name="type" required>
              <option <?php echo !isset($this_order["status"]) || empty($this_order["status"]) ? 'selected' : ''; ?> disabled value="">Choose...</option>
              <option value="pending" <?php echo $this_order["status"] == 'pending' ? "selected" : '';?>>Pending</option>
              <option value="shipped" <?php echo $this_order["status"] == 'shipped' ? "selected" : '';?>>Shipped</option>
              <option value="completed" <?php echo $this_order["status"] == 'completed' ? "selected" : '';?>>Completed</option>
            </select>
            </div>
            <button name="update_product" class="btn btn-success col-4" type="submit">Update status</button>
        </div>
    </form>
<?php endif ?>
<div class="card" id="create_product_model">

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                            <th><input id="select_all" type="checkbox"></th>
                            <th scope="col">#</th>
                            <th scope="col">Product</th>
                            <th scope="col">Costumer</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $item):
                                 if($item['status'] != 'pending'): ?>

                            <tr>
                                <td><input class="checkbox" type="checkbox"></td>
                                <th scope="row"><?php echo ++$count ;?></th>
                                <td>
                                    <?php
                                        $product = new Product($database->getConnection());
                                        $product = $product->find($item['product_id']);
                                        echo $product["name"];
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $buyer = new User($database->getConnection());
                                        $buyer = $buyer->find($item['user_id']);
                                        echo $buyer->name;
                                    ?>
                                </td>
                                <td><?php echo $product['price']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>
                                    <form method="post" class="btn">
                                        <input type="hidden" value="<?php echo $item['id']; ?>" name="item_id" />
                                        <button type="submit" name="delete_product" class="btn btn-warning">Delete</button>
                                        <button name="view_product" class="btn btn-info">View</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endif; endforeach; ?>

                        </tbody>
                    </table>
                    <?php if($count < 1): ?>
                        <center><h2>No Shipped item found </h2></center>
                    <?php endif; ?>
                </div>
            </div>
