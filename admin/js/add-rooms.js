// var nvl = <?php echo $nvl?>;
    function script()
    {
        let productOption = '\
        <div class="form-group orderProductRow">\
            <label for="tenThanhPham">Tên thành phẩm:</label>\
            <select class="appFormInput" name="tenThanhPham[]" id="tenThanhPham">\
                <option value="NVL 1">Chọn thành phẩm</option>\
                INSERTPRODUCTHERE\
            </select>\
        </div>\
        <div class="form-group ">\
            <label for="soLuongNhap">Số lượng:</label>\
            <input type="text" class="form-control" id="soLuongNhap" name="soLuongNhap[]" placeholder="Nhập số lượng">\
        </div>\
        <div class="form-group ">\
            <label for="donViTinh">Đơn vị tính:</label>\
            <select class="appFormInput" name="donViTinh[]" id="donViTinh">\
                <option value="hộp">hộp</option>\
                <option value="lon">lon</option>\
            </select>\
        </div>\
        <div class="form-group ">\
            <label for="ngaySanXuat">NSX</label>\
            <input type="date" class="form-control" name="ngaySanXuat[]" id="ngaySanXuat" placeholder="Nhập ngày sản xuất">\
        </div>\
        <div class="form-group ">\
            <label for="hanSuDung">HSD</label>\
            <input type="date" class="form-control" name="hanSuDung[]" id="hanSuDung" placeholder="Nhập hạn sử dụng">\
        </div>\
        <div class="alignRight ">\
           <button class="btn btn-danger removeOrderBtn">Huỷ</button>\
        </div><br><br>\
        <button type="submit" class="btn btn-success submitOrderBtn">Lưu toàn bộ</button>';
        this.initallize =function(){
            this.registerEvents();
            this.renderProductOption();
        }
        this.renderProductOption = function() {
            // console.log(nvl);
            let optionHtml = '';

            nvl.forEach((p)=>{
                optionHtml+= '<option value="'+p.maThanhPham+'">'+p.tenThanhPham+'</option>';
            });
            // console.log(productOption);
            // console.log(optionHtml);
            productOption =productOption.replace("INSERTPRODUCTHERE", optionHtml);

            // Xử lý tiếp theo (nếu có) hoặc trả về optionHtml nếu cần
            // Ví dụ: return optionHtml;
        };


        this.registerEvents = function()
        {
            document.addEventListener('click', function(e){
                targetElement = e.target;
                classList =targetElement.classList;
                if(targetElement.id === 'orderProductBtn')
                {
                    let orderProductListContainer = document.getElementById('orderProductList');
                    orderProductList.innerHTML += '\
                    <div class="orderProductRow">\
                        '+productOption +'\
                    </div>';
                    // console.log(productOption);
                   
                }
                if (targetElement.classList.contains('removeOrderBtn')) {
                    let orderRow = targetElement.closest('div.orderProductRow');

                    if (orderRow) {
                        // Xóa phần tử
                        orderRow.remove();
                    }
                }

            });
            document.addEventListener('change', function(e){
                targetElement = e.target;
                classList =targetElement.classList;
                
                if(classList.contains('appFormInput'))
                {
                    let pid =targetElement.value;
                    // alert('change');
                    console.log(pid);
                   
                }
            });
        }
        this.renderSupplierRows = function(suppliers, counterId) {
            let supplierRows = '';

            suppliers.forEach((supplier) => {
                supplierRows += `
                    <div class="row">
                        <div style="width: 50%;">
                            <p class="supplierName">${supplier.supplier_name}</p>
                        </div>
                        <div style="width: 50%;">
                            <label for="quantity">Số lượng: </label>
                            <input type="number" class="appFormInput orderProductQty" id="quantity" placeholder="Nhập số lượng..." name="quantity" />
                        </div>
                    </div>
                `;
            });

            // Nối vào container
            let supplierRowContainer = document.getElementById('supplierRows_' + counterId);
            if (supplierRowContainer) {
                supplierRowContainer.innerHTML = supplierRows;
            }
        };

    }
    (new script()).initallize();