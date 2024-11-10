<?php

require_once './classes/Database.php';
require_once './classes/Store.php';
require_once './classes/User.php';

$database = new Database();
$user = new User($Database->getConnection());
if (!$_SESSION['auth']['auth']) {header('location: ./auth.php');}
$user = $user->find($_SESSION['auth']['id']) ?? '';
$store = new Store($database->getConnection());

if(isset($_POST['save_store'])){
  // print_r($_POST[]);
  $store->name = $_POST['name'];
  $store->email = $_POST['email'];
  $store->number_staff = $_POST['number_staff'];
  $store->type = $_POST['type'];
  $store->location = $_POST['location'];
  $store->user_id = $_SESSION['auth']['id'];

  if(isset($user->store_id) && $user->store_id != null) {
    $store->update($user->store_id);
  }else{
    $store->create();
  }
  $store_id =Array($store);

  print_r($store_id['insert_id']);
  $user->store_id = $store_id->insert_id;
  $user->update($_SESSION['auth']['id']);
}

?>

<div class="d-flex align-items-start">
  <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Store</button>
    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</button>

    <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</button>
  </div>
  <div class="tab-content" id="v-pills-tabContent">
    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
    Profile
    </div>
    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">

        <form class="row g-3 p-2 rounded" method="post" style="width:75%; margin:auto; border:2px double wheat;">
          <div class="col-md-6">
            <label for="validationDefault01" class="form-label">Store name</label>
            <input type="text" class="form-control" id="validationDefault01" name="name" value="<?php echo $store->name; ?>" required>
          </div>
          <div class="col-md-6">
            <label for="validationDefault05" class="form-label">Support Email</label>
            <input type="text" class="form-control" id="validationDefault05" name="email" value="<?php echo $store->email ?? ''; ?>" required>
          </div>
          <div class="col-md-6">
            <label for="validationDefaultUsername" class="form-label">Number of staff</label>
            <div class="input-group">
              <span class="input-group-text" id="inputGroupPrepend2">Min of 1</span>
              <input type="number" class="form-control" id="validationDefaultUsername" name="number_staff" value="<?php echo $store->number_staff ?? '';?>" aria-describedby="inputGroupPrepend2" required>
            </div>
          </div>
          <div class="col-md-6">
            <label for="validationDefault04" class="form-label">Item Type</label>
            <select class="form-select" id="validationDefault04" name="type" required>
              <option <?php echo !isset($store->type) || empty($store->type) ? 'selected' : ''; ?> disabled value="">Choose...</option>
              <option value="Electronics" <?php echo $store->type == 'Electronics' ? "selected" : '';?>>Electonics</option>
              <option value="Accessories" <?php echo $store->type == 'Accessories' ? "selected" : '';?>>Accessories</option>
              <option value="Laptops" <?php echo $store->type == 'Laptops' ? "selected" : '';?>>Laptops</option>
              <option value="Phones" <?php echo $store->type == 'Phones' ? "selected" : '';?>>Phones</option>
              <option value="Others" <?php echo $store->type == 'Others' ? "selected" : '';?>>Others</option>
            </select>
          </div>
          <div class="col-md-12">
            <label for="validationDefault02" class="form-label">Address</label>
            <input type="text" name="location"  class="form-control" id="validationDefault02" value="<?php echo $store->location; ?>" required>
          </div>

          <div class="col-12">
            <button class="btn btn-primary" type="submit" name="save_store">Save</button>
          </div>
        </form>

    </div>
    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="0">Settings...</div>
  </div>
</div>
