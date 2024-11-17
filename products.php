<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './debugger.php';
require_once './classes/Database.php';
require_once './classes/Product.php';
require_once './classes/Store.php';
require_once './classes/Order.php';
require_once './classes/User.php';

$database = new Database;
$product = new Product($database->getConnection());
$products = $product->all();
$count = 0;

$user = new User($database->getConnection());

if (!$_SESSION['auth']['auth']) {header('location: ./auth.php');}

$user = $user->find($_SESSION['auth']['id']) ?? '';

$store = new Store($database->getConnection());
if(isset($_POST['create_product'])){
    $item = new Product($database->getConnection());
    if(isset($user->store_id)){
        $store->find($user->store_id);
    }else{
        header('location: ./?web=store');
        exit;
    }
    $item = $item->create($_POST['name'], $_POST['description'], $_POST['price'], $_POST['brand'], $_POST['quantity'], $store);
    $products = $product->all();
}
if(isset($_POST['delete_product'])){
    $item = new Product($database->getConnection());
    $item = $item->delete($_POST['item_id']);
    $products = $product->all();
}
if(isset($_POST['view_product'])){
    $this_order = new Order($database->getConnection());
    $this_order = $this_order->find($_POST['item_id']);
    // print_r($this_order);

    $product_view = new Product($database->getConnection());
    $product_view = $product_view->find($this_order['product_id']);
    // print_r($product_view);
}
?>

<div class="card" id="create_product_model">

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
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                            <th><input id="select_all" type="checkbox"></th>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Brand</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $item): ?>
                            <tr>
                                <td><input class="checkbox" type="checkbox"></td>
                                <th scope="row"><?php echo ++$count ;?></th>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['brand']; ?></td>
                                <td><?php echo $item['price']; ?></td>
                                <td><?php echo $item['stock']; ?></td>
                                <td>
                                    <form method="post" class="btn">
                                        <input type="hidden" value="<?php echo $item['id']; ?>" name="item_id" />
                                        <button type="submit" name="delete_product" class="btn btn-warning">Delete</button>
                                        <button name="view_product" class="btn btn-info">View</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
