<?php

require_once './classes/Database.php';
require_once './classes/Product.php';
require_once './classes/Order.php';

$Database = new Database;
$product = new Product($Database->getConnection());
$products = $product->all();
$count = 0;
if(isset($_POST['place_order'])){
    $item = new Product($Database->getConnection());
    $item_id = $_POST['item_id'];
    $item = $item->find($item_id);

    // $user_id, $store_id, $total, $quantity, $item_id, $status;

    if (!$_SESSION['auth']['auth']) {header('location: ./auth.php');}

    $user = $user->find($_SESSION['auth']['id']) ?? '';
    $order = new Order($Database->getConnection());

    $order = $order->create($user->id, $user->store_id, $item['price'], 1, $item['id'], "pending"  );
    $products = $product->all();
    echo '<script>alert("order placed success fully. you item is on its way");</script>';
}
?>

<div class="row" id="create_product_model">

    <?php foreach($products as $item): ?>
        <div class="col">
            <form method="POST" class="card" style="margin:auto; padding:4px;">
                <h1><?php echo $item['name']; ?></h1>
                <h4><?php echo $item['brand']; ?></h4>
                <h2>₦ <?php echo $item['price']; ?></h2>
                <p><?php echo $item['description']; ?></p>
                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                <button name="place_order" class="btn btn-success" type="submit">Buy now</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
