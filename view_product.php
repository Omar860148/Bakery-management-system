<?php
require_once("DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT p.*,c.name as cname FROM `product_list` p inner join `category_list` c on p.category_id = c.category_id where p.product_id = '{$_GET['id']}'");
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
    <div class="col-12">
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Product Code:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($name) ? $name : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Category:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($cname) ? $cname : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Product:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($name) ? $name : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Description:</b></div>
            <div class="fs-6 ps-4"><?php echo isset($description) ? $description : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Price:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($price) ? number_format($price,2) : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Status:</b></div>
            <div class="fs-5 ps-4">
                <?php 
                    if(isset($status) && $status == 1){
                        echo "<small><span class='badge rounded-pill bg-success'>Active</span></small>";
                    }else{
                        echo "<small><span class='badge rounded-pill bg-danger'>Inactive</span></small>";
                    }
                ?>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-end">
            <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>