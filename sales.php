<div class="card shadow rounded-0">
    <div class="card-body">
        <div class="w-100 h-100 d-flex flex-column">
            <div class="row">
                <div class="col-8">
                    <h3>Transaction</h3>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary rounded-0 " id="transaction-save-btn" type="button">Save Transaction</button>
                </div>
                <div class="clear-fix mb-1"></div>
                <hr>
            </div>
            <style>
                #plist .item,#item-list tr{
                    cursor:pointer
                }
            </style>
            <div class="col-12 flex-grow-1">
            <form action="" class="h-100" id="transaction-form">
                <div class="w-100 h-100 mx-0 row row-cols-2 bg-dark">
                    <div class="col-8 h-100 pb-2 d-flex flex-column">
                        <div>
                            <h3 class="text-light">Please select product below</h3>
                        </div>
                        <div class="flex-grow-1 d-flex flex-column bg-light bg-opacity-50">
                            <div class="form-group py-2 d-flex border-bottom">
                                <label for="search" class="col-auto px-2 fw-bolder text-light">Search</label>
                                <div class="flex-grow-1 col-auto pe-2">
                                    <input type="text" autocomplete="off" class="form-control form-control-sm rounded-0" id="search">
                                </div>
                            </div>
                            <div>
                                <table class="table table-hover table-striped bg-light mb-0">
                                    <colgroup>
                                        <col width="25%">
                                        <col width="20%">
                                        <col width="25%">
                                        <col width="15%">
                                        <col width="15%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th class="py-0 px-1">Category</th>
                                            <th class="py-0 px-1">Product Code</th>
                                            <th class="py-0 px-1">Product Name</th>
                                            <th class="py-0 px-1">Price</th>
                                            <th class="py-0 px-1">Available Quantity</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="flex-grow-1" >
                                <div class="h-100 overflow-auto" style="height:60vh !important ">
                                    <table class="table table-hover table-striped bg-light" id="plist">
                                        <colgroup>
                                            <col width="25%">
                                            <col width="20%">
                                            <col width="25%">
                                            <col width="15%">
                                            <col width="15%">
                                        </colgroup>
                                        <tbody>
                                        <?php 
                                        $sql = "SELECT p.*,c.name as cname FROM `product_list` p inner join `category_list` c on p.category_id = c.category_id where p.status = 1 and p.delete_flag = 0 order by p.`name` asc";
                                        $qry = $conn->query($sql);
                                        while($row = $qry->fetch_assoc()):
                                            $stock_in = $conn->query("SELECT sum(quantity) as `total` FROM `stock_list` where unix_timestamp(`expiry_date`) >= unix_timestamp(CURRENT_TIMESTAMP) and product_id = '{$row['product_id']}' ")->fetch_assoc()['total'];
                                            $stock_out = $conn->query("SELECT sum(quantity) as `total` FROM `transaction_items` where product_id = '{$row['product_id']}' ")->fetch_assoc()['total'];
                                            $stock_in = $stock_in > 0 ? $stock_in : 0;
                                            $stock_out = $stock_out > 0 ? $stock_out : 0;
                                            $qty = $stock_in-$stock_out;
                                            $qty = $qty > 0 ? $qty : 0;
                                        ?>
                                        <tr class="item <?php echo $qty < 50? "bg-danger bg-opacity-25":'' ?>" data-id="<?php echo $row['product_id'] ?>">
                                            <td class="td py-0 px-1 pname"><?php echo $row['cname'] ?></td>
                                            <td class="td py-0 px-1 pcode"><?php echo $row['product_code'] ?></td>
                                            <td class="td py-0 px-1 name"><?php echo $row['name'] ?></td>
                                            <td class="td py-0 px-1 text-end price"><?php echo format_num($row['price']) ?></td>
                                            <td class="td py-0 px-1 text-end qty"><?php echo $qty ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 h-100 py-2">
                        <div class="h-100 d-flex flex-column">
                            <div class="w-100 flex-grow-1">
                                <div class="h-100 d-flex w-100 flex-column">
                                    <div class="d-flex">
                                    <div class="fs-5 fw-bolder text-light flex-grow-1">Items</div>
                                    <div class="col-auto">
                                        <button class="btn btn-danger rounded-0 py-0" type="button" id="remove-item" disabled onclick="remove_item()"><i class="fa fa-trash"></i></button>
                                    </div>
                                    </div>
                                    <div>
                                        <table class="table table-hover table-bordered table-striped bg-light m-0">
                                            <colgroup>
                                                <col width="20%">
                                                <col width="65%">
                                                <col width="15%">
                                            </colgroup>  
                                            <thead>
                                                <th class="py-0 px-1 text-center">Qty</th>
                                                <th class="py-0 px-1 text-center">Product</th>
                                                <th class="py-0 px-1 text-center">Total</th>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div style="height:55vh !important" class="overflow-auto bg-light bg-opacity-75">
                                            <table class="table table-hover table-bordered table-striped bg-light" id="item-list">
                                            <colgroup>
                                                <col width="20%">
                                                <col width="65%">
                                                <col width="15%">
                                            </colgroup>  
                                            <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="pt-2">
                                        <div class="w-100 mx-0 d-flex pb-1">
                                            <div class="col-4 pe-2 fs-6 fw-bolder text-light">Sub-Total</div>
                                            <div class="flex-grow-1 bg-light fs-6 fw-bolder text-end px-2" id="subTotal">0.00</div>
                                        </div>
                                        <div class="w-100 mx-0 d-flex pb-1 align-items-center">
                                            <div class="col-4 pe-2 fs-6 fw-bolder text-light">Tax (12%)<small>Inclusive</small></div>
                                            <div class="flex-grow-1 bg-light fs-6 fw-bolder text-end px-2" id="tax">0.00</div>
                                        </div>
                                        <div class="w-100 mx-0 d-flex pb-1">
                                            <div class="col-4 pe-2 fs-6 fw-bolder text-light">Total</div>
                                            <div class="flex-grow-1 bg-light fs-6 fw-bolder text-end px-2" id="total">0.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="total" value="0">
                <input type="hidden" name="tendered_amount" value="0">
                <input type="hidden" name="change" value="0">
            </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#search').on('input',function(){
            var _search = $(this).val().toLowerCase()
            $('#plist tbody tr').each(function(){
                var _text = $(this).text().toLowerCase()
                if(_text.includes(_search) === true){
                    $(this).toggle(true)
                }else{
                    $(this).toggle(false)
                }
            })
        })
        $('#plist tbody tr').click(function(){
            var _tr = $(this);
            var pid = _tr.attr('data-id')
            var cname = _tr.find('.cname').text()
            var pcode = _tr.find('.pcode').text()
            var name = _tr.find('.name').text()
            var price = _tr.find('.price').text().replace(/,/gi,'')
            var max = _tr.find('.qty').text()
            var qty = 1
            if($('#item-list tbody tr[data-id="'+pid+'"]').length > 0){
                qty += parseFloat($('#item-list tbody tr[data-id="'+pid+'"]').find('[name="quantity[]"]').val())
                $('#item-list tbody tr[data-id="'+pid+'"]').find('[name="quantity[]"]').val(qty).trigger('keydown')
                return false;
            }
            var ntr  = $("<tr tabindex='0'>")
                ntr.attr('data-id',pid)
                ntr.append('<td class="py-0 px-1 align-middle"><input class="w-100 text-center" type="number" name="quantity[]" min="1" value="'+qty+'"/>'+
                        '<input type="hidden" name="product_id[]" value="'+pid+'"/>'+
                        '<input type="hidden" name="price[]" value="'+price+'"/>'+
                '</td>')
                ntr.append('<td class="py-0 px-1 align-middle"><div class="fs-6 mb-0 lh-1">'+pcode+'<br/>'+
                            '<span class="name">'+name+'</span></br>'+
                            '(<span class="price">'+parseFloat(price).toLocaleString('en-US',{style:'decimal',maximumFractionDigits:2})+'</span>)</div>'+
                            '</td>');
            ntr.append('<td class="py-0 px-1 align-middle text-end total">'+parseFloat(price).toLocaleString('en-US',{style:'decimal',maximumFractionDigits:2})+'</td>')
            $('#item-list tbody').append(ntr)
            compute(ntr)
            calculate_total()
        })

        $('#transaction-save-btn').click(function(){
            if($('#item-list tbody tr').length <= 0){
                alert("Please add atleast 1 item first.")
                return false;
            }
            uni_modal("Payment","tender_amount.php?amount="+$('[name="total"]').val())
        })
        $('#transaction-form').submit(function(e){
            e.preventDefault()
            $('#transaction-save-btn').attr('disabled',true)
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $.ajax({
                url:'./Actions.php?a=save_transaction',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                    $('#transaction-save-btn').attr('disabled',false)
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        setTimeout(() => {
                            uni_modal("RECEIPT","view_receipt.php?id="+resp.transaction_id)
                        }, 1000);
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    $('#transaction-save-btn').attr('disabled',false)
                }
            })
        })
        $('#transaction-form input').keydown(function(e){
            if(e.which == 13){
            e.preventDefault()
            return false
            }
        })
    })
    function compute(_this){
        _this.find('[name="quantity[]"]').on('input keydown',function(){
            var qty = $(this).val() > 0 ? $(this).val() : 0;
            var price = _this.find('[name="price[]"]').val()
            var _total = parseFloat(qty) * parseFloat(price)

            _this.find('.total').text(parseFloat(_total).toLocaleString('en-US',{style:'decimal',maximumFractionDigits:2}))
            calculate_total()
        })
        _this.find('[name="quantity[]"]').on('focusout',function(){
            if($(this).val() <= 0){
                $(this).val('0')
            }
        })
        _this.on('focusin',function(){
            $(this).addClass("bg-primary bg-opacity-50 selected-item")
            $('#remove-item').attr('disabled',false)
        })
        _this.on('focusout',function(){
            if($('#remove-item').is(':focus') == true || $('#remove-item').is(':hover') == true)
            return false;
            $(this).removeClass("bg-primary bg-opacity-50 selected-item")
            $('#remove-item').attr('disabled',true)
        })
        $('#transaction-form input').keydown(function(e){
            if(e.which == 13){
            e.preventDefault()
            return false
            }
        })
    }
    function calculate_total(){
        var sub = 0
        var total = 0
        var discount = 0
        $('#item-list tr .total').each(function(){
            val = $(this).text().replace(/,/gi,'')
            sub += parseFloat(val)
        })
        $('[name="total"]').val(parseFloat(sub))
        $('#total').text(parseFloat(sub).toLocaleString('en-US',{style:'decimal',manimumFractionDigits:2,maximumFractionDigits:2}))
        $('#subTotal').text(parseFloat(sub).toLocaleString('en-US',{style:'decimal',manimumFractionDigits:2,maximumFractionDigits:2}))
        discount = sub * .12;
        $('#tax').text(parseFloat(discount).toLocaleString('en-US',{style:'decimal',manimumFractionDigits:2,maximumFractionDigits:2}))
    }
    function remove_item(){
        $('#item-list tr.selected-item').remove()
        calculate_total()
        $('#remove-item').attr('disabled',true)
        }
</script>