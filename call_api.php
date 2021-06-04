<?php
  /*callapi function start */
  function callapi($method, $url, $data) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);

    if($method == 'POST') {
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    if($method == 'PUT') {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    if($method == 'DELETE') {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    }
    
    curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
      ));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      $output = curl_exec($ch);

    curl_close ($ch);

    return $output;
  }
  /*callapi function end */

  $result = '';

  // Call GET method fetch all records
  $method = 'GET';
  $url = 'http://localhost:3000/products';
  $data = NULL;

  $products = callapi($method, $url, $data);

  //Call GET method fetch single record
  if(isset($_GET['action']) && $_GET['action'] == 'edit') {

    $id = $_GET['id'];

    $method = 'GET';
    $url = 'http://localhost:3000/products/'.$id;
    $data = NULL;

    $prod = callapi($method, $url, $data);
    $prod = json_decode($prod);
  }

  //Call DELETE method
  if(isset($_GET['action']) && $_GET['action'] == 'del') {

    $id = $_GET['id'];

    $method = 'DELETE';
    $url = 'http://localhost:3000/products/delete/'.$id;
    $data = NULL;

    $result = callapi($method, $url, $data);

    header('location: index.php');
  }
  
  if(isset($_POST['submit']))
  {
    // Call POST method
    if($_POST['submit'] == 'create')
    {
      $method = 'POST';
      $url = 'http://localhost:3000/products/create';
      $data = json_encode($_POST);

      $result = callapi($method, $url, $data);

      header('location: index.php');
    }

    // Call PUT method
    if($_POST['submit'] == 'update')
    {
      $id = $_POST['id'];

      $method = 'PUT';
      $url = 'http://localhost:3000/products/update/'.$id;
      $data = json_encode($_POST);

      $result = callapi($method, $url, $data);

      header('location: index.php');
    }
  }
?>
<!doctype html>
<html>
  <head>
    <title>Call Node.Js API</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <h1>Call Node.Js API</h1>
      <hr />
      <p><?php echo $result ?></p>
      <form action="" method="POST">
        <div class="form-row">
          <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="name" />
          </div>
          <div class="col-md-3">
            <input type="text" name="sku" class="form-control" placeholder="sku" />
          </div>
          <div class="col-md-3">
            <input type="text" name="price" class="form-control" placeholder="price" />
          </div>
          <div class="col-md-3">
            <button type="submit" name="submit" value="create" class="btn btn-success">Create</button>
          </div>
        </div>
      </form>
      <?php if(isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
      <br />
      <form action="" method="POST">
        <div class="form-row">
          <div class="col-md-3">
            <input type="text" name="name" class="form-control" value="<?php echo $prod->name ?>" />
          </div>
          <div class="col-md-3">
            <input type="text" name="sku" class="form-control" value="<?php echo $prod->sku ?>" />
          </div>
          <div class="col-md-3">
            <input type="text" name="price" class="form-control" value="<?php echo $prod->price ?>" />
          </div>
          <div class="col-md-3">
            <input type="hidden" name="id" value="<?php echo $prod->id ?>" />
            <button type="submit" name="submit" value="update" class="btn btn-warning">Update</button>
          </div>
        </div>
      </form>
      <?php } ?>
      <br />
      <div class="row">
        <div class="col-md-12">
          <?php $products = json_decode($products) ?>
          <?php if(!empty($products)) { ?>
            <table class="table">
              <tr>
                <th>#ID</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            <?php foreach($products as $product) { ?>
              <tr>
                <td><?php echo $product->id ?></td>
                <td><?php echo $product->name ?></td>
                <td><?php echo $product->sku ?></td>
                <td><?php echo $product->price ?></td>
                <td>
                  <a href="index.php?id=<?php echo $product->id ?>&action=edit" class="btn btn-info btn-sm">Edit</a>
                  <a href="index.php?id=<?php echo $product->id ?>&action=del" class="btn btn-danger btn-sm">Delete</a>
                </td>
              </tr>
            <?php } ?>
            </table>
          <?php } ?>
        </div>
      </div>
    </div>
  </body>
</html>