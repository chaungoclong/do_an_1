<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="<?= base_url('dist/bootstrap/css/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/font/css/all.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('dist/jquery/jquery-ui.min.css'); ?>">
    <script src="<?= base_url('dist/jquery/jquery-3.5.1.js'); ?>"></script>
    <script src="<?= base_url('dist/jquery/jquery-ui.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/admin.js'); ?>"></script>
    <script src="<?= base_url('assets/js/common.js'); ?>"></script>
    <script src="<?= base_url('assets/js/valid.js'); ?>"></script>
    <script src="<?= base_url('assets/js/cart.js'); ?>"></script>
    <script src="<?= base_url('assets/js/rate.js'); ?>"></script>
    <script src="<?= base_url('dist/popper/popper.min.js'); ?>"></script>
    <script src="<?= base_url('dist/bootstrap/js/bootstrap.js'); ?>"></script>
    <script src="<?= base_url('dist/ckeditor/ckeditor.js'); ?>"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
      // khởi tạo lấy thông báo
      Pusher.logToConsole = true;
      var pusher = new Pusher('73ef9c76d34ce11d7557', {
        cluster: 'ap1'
      });
      // Kênh thông báo
      var channel = pusher.subscribe('notify');

      // kênh gửi email
      var channel2 = pusher.subscribe('email');
      
      // nhận tín hiệu có đơn hàng mới
      $(function() {
        channel2.bind('email_new_order', function(data) {
          let customerID_email = data.customerID;
          let orderID_email = data.orderID;
          let url = '<?= base_url("admin/email/process_email.php"); ?>';
          let data_email_new_order = {customerID: customerID_email, orderID: orderID_email, action: "send_email_new_order"};
          sendAJax(url, 'post', 'json', data_email_new_order);
        });
      })
    </script>
  </head>
  <body>
    

    <!-- xác thực -->
  <?php 
    if (is_login() && is_admin()) {
      $authID = $_SESSION['user_token']['id'];
      $authInfo = get_user_by_id($authID, 1);
      if($authInfo['ad_active'] == 0) {
        $goToAfterAuth = base_url("index.php");
        set_logout();
        die('
          <div class="ml-5 mt-5">
            <h1 class="text-danger">TÀI KHOẢN CỦA BẠN BỊ KHÓA</h1>
            <a class="btn btn-primary" href="' . $goToAfterAuth . '">TRANG CHỦ</a>
          </div>
        ');
      }
    }
  ?>