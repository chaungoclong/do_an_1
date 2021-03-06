<?php
require_once '../../common.php';
require_once '../include/header.php';

if(!is_login() || !is_admin()) {
  redirect('admin/form_login.php');
}

require_once '../include/sidebar.php';
require_once '../include/navbar.php';

$newsID   = data_input(input_get('newsid'));

$news     = getNewsByID($newsID);
?>

<!-- main content -row -->
<div class="main_content bg-white row m-0 pt-4">

   <div class="col-12">
      <div class="row m-0">
         <div class="col-12">
            <h5>SỬA TIN TỨC</h5>
            <p class="mb-4">SỬA TIN TỨC</p>
            <hr>
         </div>
      </div>
   </div>

   <div class="col-12 mb-5">
      <form action="	" method="POST" id="news_edit_form" enctype="multipart/form-data">
         
         <!-- news id -->
         <input type="hidden" name="newsID" value="<?= $news['news_id']; ?>">

         <div class="row m-0">
            <div class="col-12">
               <div  id="backErr" class="alert-danger"></div>

               <!-- tiêu đề bài viết -->
               <div class="form-group">
                  <label for="title"><strong>Tiêu đề bài viết:</strong></label>
                  <textarea type="text" name="title" id="title" value="<?= $news['news_title']; ?>">
                    <?= $news['news_title']; ?>
                  </textarea>
                  <div class="alert-danger" id="titleErr"></div>

                  <script>
                    CKEDITOR.replace('title', {
                      height: 100
                    });
                  </script>
               </div>

               <!-- mô tả -->
               <div class="form-group">
                  <label for="title"><strong>Mô tả:</strong></label>
                  <textarea type="text" name="desc" id="desc" value="<?= $news['news_desc']; ?>">
                    <?= $news['news_desc']; ?>
                  </textarea>
                  <div class="alert-danger" id="descErr"></div>

                   <script>
                    CKEDITOR.replace('desc', {
                      height: 100
                    });
                  </script>
               </div>

               <!-- ảnh -->
               <div class="form-row">
                  <div class="form-group col-6">
                     <label for="image"><strong>Ảnh bài viết:</strong></label>
                     <input type="file" name="image" id="image">
                     <input type="hidden" name="oldImage" value="<?= $news['news_img']; ?>">

                     <div class="previewImage">
                       <img src="../../image/<?= $news['news_img']; ?>" class="img-fluid">
                     </div>
                     <script>
                        $(document).on('change', '#image', function() {
                          showImg(this, ".previewImage", 0);
                        });
                     </script>
                     <div class="alert-danger" id="imageErr"></div>
                  </div>
               </div>

               <!-- nội dung bài viết -->
              <div class="form-group">
                 <label for="content"><strong>Nội dung bài viết:</strong></label>
                 <textarea name="content" id="content" value="<?= $news['news_content']; ?>">
                   <?= $news['news_content']; ?>
                 </textarea>
                 <script>
                    CKEDITOR.replace( 'content', {
                        filebrowserBrowseUrl: '../../dist/ckfinder/ckfinder.html',
                        filebrowserImageBrowseUrl: '../../dist/ckfinder/ckfinder.html?Type=Images',
                        filebrowserUploadUrl: '../../dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                        filebrowserImageUploadUrl: '../../dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                        filebrowserWindowWidth : '1000',
                        filebrowserWindowHeight : '700'
                    });
                 </script>
                 <div class="alert-danger" id="contentErr"></div> 
              </div>

              <!-- tác giả -->
              <div class="form-group">
                <label for="auth"><strong>Tác giả:</strong></label>
                <input type="text" name="auth" id="auth" class="form-control" value="<?= $news['create_by']; ?>">
                <div class="alert-danger" id="authErr"></div>
              </div>

              <!-- trạng thái -->
              <div class="custom-control custom-switch mb-3">
                 <input
                 type  ="checkbox"
                 id    ="active"
                 name  ="active"
                 class ="custom-control-input"
                 <?= $news['news_active'] ? "checked" : ""; ?>
                 >
                 <label for="active" class="custom-control-label">Trạng thái</label>
              </div>

            </div>

            <button class="btn_edit_news btn btn-block btn-success"><strong>THÊM</strong></button>
              
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
   	$(document).on('submit', "#news_edit_form", function(e) {
   		e.preventDefault();
         // //validateNewsAdd();
         console.log($(this).serializeArray());
         editNews();

      });
   });
</script>