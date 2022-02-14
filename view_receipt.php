<?php
session_start();
require_once("DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `transaction_list` where transaction_id = '{$_GET['id']}'");
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>
<div class="container-fluid">
    <div id="outprint_receipt">
        <div class="text-center fw-bold lh-1">
            <span>Bakery Shop Management System</span><br>
            <small>Receipt</small>
        </div>
        <div class="fs-6 fs-light d-flex w-100 mb-1">
            <span class="col-auto pe-2">Date:</span> 
            <span class="border-bottom border-dark flex-grow-1"><?php echo date("Y-m-d",strtotime($date_added)) ?></span>
        </div>
        <div class="fs-6 fs-light d-flex w-100 mb-1">
            <span class="col-auto pe-2">Receipt No:</span> 
            <span class="border-bottom border-dark flex-grow-1"><?php echo $receipt_no ?></span>
        </div>
        <table class="table table-striped">
            <colgroup>
                <col width="15%">
                <col width="65%">
                <col width="20%">
            </colgroup>  
            <thead>
            <tr class="bg-dark bg-opacity-75 text-light">
                <th class="py-0 px-1">QTY</th>
                <th class="py-0 px-1">Product</th>
                <th class="py-0 px-1">Amount</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                $items = $conn->query("SELECT i.*, p.name as pname,p.product_code FROM `transaction_items` i inner join product_list p on i.product_id = p.product_id where `transaction_id` = '{$transaction_id}'");
                while($row=$items->fetch_assoc()):
                ?>
                <tr>
                    <td class="px-1 py-0 align-middle"><?php echo $row['quantity'] ?></td>
                    <td class="px-1 py-0 align-middle">
                        <div class="fw light lh-1">
                            <small><?php echo $row['product_code'] ?></small><br>
                            <small><?php echo $row['pname'] ?></small>
                            <small>(<?php echo format_num($row['price']) ?>)</small>
                        </div>
                    </td>
                    <td class="px-1 py-0 align-middle text-end"><?php echo format_num($row['price'] * $row['quantity']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="px-1 py-0" colspan="2">Total</th>
                    <th class="px-1 py-0 text-end"><?php echo format_num($total) ?></th>
                </tr>
                <tr>
                    <th class="px-1 py-0" colspan="2">Tax 12%</th>
                    <th class="px-1 py-0 text-end"><?php echo format_num($total * .12) ?></th>
                </tr>
                <tr>
                    <th class="px-1 py-0" colspan="2">Tendered Amount</th>
                    <th class="px-1 py-0 text-end"><?php echo format_num($tendered_amount) ?></th>
                </tr>
                <tr>
                    <th class="px-1 py-0" colspan="2">Change</th>
                    <th class="px-1 py-0 text-end"><?php echo format_num($change) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="w-100 d-flex justify-content-end mt-2">
        <?php if(isset($_GET['view_only']) && $_GET['view_only'] == true && $_SESSION['type'] == 1): ?>
        <button class="btn btn-sm btn-danger me-2 rounded-0" type="button" id="delete_data"><i class="fa fa-trash"></i> Delete</button>
        <?php endif; ?>
        <button class="btn btn-sm btn-success me-2 rounded-0" type="button" id="print_receipt"><i class="fa fa-print"></i> Print</button>
        <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Close</button>
    </div>
</div>
<script>
    $(function(){
        $("#print_receipt").click(function(){
            var h = $('head').clone()
            var p = $('#outprint_receipt').clone()
            var el = $('<div>')
            el.append(h)
            el.append(p)
            var nw = window.open("","","width=500,height=900")
                nw.document.write(el.html())
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    setTimeout(() => {
                        nw.close()

                        $('#uni_modal').on('hide.bs.modal',function(){
                            if($(this).find('#outprint_receipt').length > 0 && '<?php echo !isset($_GET['view_only']) ?>' == 1){
                                location.reload()
                            }
                        })
                        if('<?php echo !isset($_GET['view_only']) ?>' == 1)
                        $('#uni_modal').modal('hide')
                    }, 150);
                }, 200);
        })
        $('#uni_modal').on('hide.bs.modal',function(){
            if($(this).find('#outprint_receipt').length > 0){
                location.reload()
            }
        })
        $('#uni_modal').modal('hide')
        $('#delete_data').click(function(){
            _conf("Are you sure to delete <b>"+<?php echo $receipt_no ?>+"</b>?",'delete_data',['<?php echo $transaction_id ?>'])
        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./Actions.php?a=delete_transaction',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                console.log(err)
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled',false)
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert("An error occurred.")
                    $('#confirm_modal button').attr('disabled',false)
                }
            }
        })
    }
</script>