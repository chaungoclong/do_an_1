<?php
require_once '../../common.php';
require_once '../include/header.php';

if(!is_login() || !is_admin()) {
  redirect('admin/form_login.php');
}

require_once '../include/sidebar.php';
require_once '../include/navbar.php';

// danh sách thể loại
$listBrand = db_fetch_table("db_brand", 0);
?>

<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">

   <div class="col-12">
      <div class="row m-0">
       <div class="col-12">
        <a class="" onclick="window.location='<?= base_url('admin/brand/'); ?>'" style="cursor: pointer;">
          <i class="fas fa-angle-left"></i> TRỞ LẠI
        </a>
        <hr>
      </div>
      </div>
   </div>

   <div class="col-12 mb-5">
      <form action="	" method="POST" id="brand_add_form" enctype="multipart/form-data">
         <!-- previous link -->
         
         <div class="row m-0">
            <div class="col-12">
               <div  id="backErr" class="alert-danger"></div>

               <!-- tên sản phẩm -->
               <div class="form-group">
                  <label for="name"><strong>Tên hãng:</strong></label>
                  <input type="text" class="form-control" name="name" id="name">
                  <div class="alert-danger" id="nameErr"></div>
               </div>

             <!-- ảnh đại diện -->
             <div class="form-row">
                <div class="form-group col-6">
                   <label for="image"><strong>Logo hãng:</strong></label>
                   <input type="file" name="image" id="image">

                   <div class="previewLogo"></div>
                   <script>
                      $(document).on('change', '#image', function() {
                        showImg(this, ".previewLogo", 0);
                      });
                   </script>
                   <div class="alert-danger" id="imageErr"></div>
                </div>
             </div>

            <!-- trạng thái -->
            <div class="custom-control custom-switch mb-3">
               <input
               type  ="checkbox"
               id    ="active"
               name  ="active"
               class ="custom-control-input"
               checked
               >
               <label for="active" class="custom-control-label">Trạng thái</label>
            </div>

            <button class="btn_add_bra btn btn-block btn-success"><strong>THÊM</strong></button>
              
         </div>
      </div>
   </div>
</form>
</div>
</div>
</div>
<!-- /right-col -->
</div>
<!-- /wrapper -row -->
</main>
</body>
</html>
<script>
   $(function() {
   	$(document).on('submit', "#brand_add_form", function(e) {
   		e.preventDefault();
         addBrand();

      });
   });
</script>