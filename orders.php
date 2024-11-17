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


?>

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
                            <?php foreach($orders as $item): ?>
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
                                    </form>
                                    <button class="btn btn-info">View</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
