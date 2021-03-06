$(document).ready(function() {

    //active tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    //dropdown hover
    var dropdown = $('#navbar .dropdown, #account');
    // $(dropdown).hover(function(e) {
    //     console.log(e);
    //     $(this).find('.dropdown-menu').slideToggle(0);
    // });
    $(dropdown).mouseenter(function(event) {
        $(this).find('.dropdown-menu').slideDown(0);
    });

    $(dropdown).mouseleave(function(event) {
        $(this).find('.dropdown-menu').slideUp(0);
    });

    //search
    // $("#boxSearch").on("input", function() {
    //     var data = $(this).val();
    //     var keyWord = $(this).serialize();
    //     if (data) {
    //         $.ajax({
    //             url: "search_live.php",
    //             type: "GET",
    //             data: keyWord,
    //             dataType: "html",
    //             success: function(res) {
    //                 $("#ajaxSearch").html(res);
    //             }
    //         });
    //     } else {
    //         $("#ajaxSearch").html("");
    //     }
    // });

});


//hàm hiển thị ảnh khi chọn
function showImg(input, storeImg, mode = 0, storeNameImg = "") {
    //lấy số lượng file trong thẻ input
    var numberFile = input.files.length;
    // console.log(numberFile);
    $(storeImg).empty();
    //đọc từng file
    for (let i = 0; i < numberFile; ++i) {
        //tạo đối tượng đọc file
        var reader = new FileReader();

        /**
         * [onload description]
         * khi xảy ra sự kiện load lấy kết quả tại vị trí xảy ra sự kiện
         * tạo một thẻ phần tử 'img' để hiển thị
         * gán đường dẫn của hình ảnh bằng kết quả lấy được
         * thêm hình ảnh vào nơi cần thêm
         * 
         */
        reader.onload = function(e) {
            var url = e.target.result;
            var name = input.files[i].name;
            var img = $('<img >');
            if (mode) {
                img.attr('src', url).css({borderRadius: "5px", width: "80px"});
            } else {
                img.attr('src', url).css("width", "230px");
            }
            if (storeNameImg) {
                $(storeNameImg).text(name);
            }

            $(storeImg).append(img);
        }

        //trả kết quả của từng phần tử file dưới dạng url
        reader.readAsDataURL(input.files[i]);
    }
}

//get ajax
function sendAJax(url, method, type = 'text', data = {}, mode = 0) {
    var result = false;
    var ajaxData = {};
    if(!mode) {
        ajaxData = {
            url: url,
            data: data,
            method: method,
            dataType: type,
            async: false
        }
    } else {
         ajaxData = {
            url: url,
            data: data,
            method: method,
            dataType: type,
            cache: false,
            contentType: false,
            processData: false,
            async: false
        }
    }

    $.ajax(ajaxData).done(function(res) {
        result = res;
    }).fail(function(a, b, c) {
        result = false;
    });
    return result;
}

// hàm in một phần tử

function printElement(element) {

    // nội dung khôi phục
    let restoreContent = $('body').html();

    // nội dung in
    let printContent = $(element).html();

    // xóa nội dung trong trang thay thế bằng nội dung in
    $('body').empty().html(printContent);

    // // in
    window.print();

    // khôi phục
    $('body').html(restoreContent);
    window.reload();

}



