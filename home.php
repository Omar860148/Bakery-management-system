<div class="content py-3">
    <div class="card rounded-0 shadow">
        <div class="card-body">
            <h3>Welcome to Bakery Shop Management System</h3>
            <hr>
            <div class="col-12">
                <div class="row gx-3 row-cols-4">
                    <div class="col">
                        <div class="card text-dark">
                            <div class="card-body">
                                <div class="w-100 d-flex align-items-center">
                                    <div class="col-auto pe-1">
                                        <span class="fa fa-th-list fs-3 text-primary"></span>
                                    </div>
                                    <div class="col-auto flex-grow-1">
                                        <div class="fs-5"><b>Catrgories</b></div>
                                        <div class="fs-6 text-end fw-bold">
                                            <?php 
                                            $category = $conn->query("SELECT count(category_id) as `count` FROM `category_list` where delete_flag = 0 ")->fetch_array()['count'];
                                            echo $category > 0 ? format_num($category) : 0 ;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card text-dark">
                            <div class="card-body">
                                <div class="w-100 d-flex align-items-center">
                                    <div class="col-auto pe-1">
                                        <span class="fas fa-shopping-bag fs-3 text-secondary"></span>
                                    </div>
                                    <div class="col-auto flex-grow-1">
                                        <div class="fs-5"><b>Products</b></div>
                                        <div class="fs-6 text-end fw-bold">
                                            <?php 
                                            $product = $conn->query("SELECT count(product_id) as `count` FROM `product_list` where delete_flag = 0 ")->fetch_array()['count'];
                                            echo $product > 0 ? format_num($product) : 0 ;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card text-dark">
                            <div class="card-body">
                                <div class="w-100 d-flex align-items-center">
                                    <div class="col-auto pe-1">
                                        <span class="fa fa-file-alt fs-3 text-info"></span>
                                    </div>
                                    <div class="col-auto flex-grow-1">
                                        <div class="fs-5"><b>Total Stocks</b></div>
                                        <div class="fs-6 text-end fw-bold">
                                            <?php 
                                            $stock = 0;
                                            $stock_query = $conn->query("SELECT * FROM `stock_list` where product_id in (SELECT product_id FROM `product_list` where delete_flag = 0) and unix_timestamp(CONCAT(`expiry_date`)) >= unix_timestamp(CURRENT_TIMESTAMP) ");
                                            while($row = $stock_query->fetch_assoc()):
                                                $stock_in = $conn->query("SELECT sum(quantity) as `total` FROM `stock_list` where unix_timestamp(CONCAT(`expiry_date`, ' 23:59:59')) >= unix_timestamp(CURRENT_TIMESTAMP) and product_id = '{$row['product_id']}' ")->fetch_array()['total'];
                                                $stock_out = $conn->query("SELECT sum(quantity) as `total` FROM `transaction_items` where product_id = '{$row['product_id']}' ")->fetch_array()['total'];
                                                $stock_in = $stock_in > 0 ? $stock_in : 0;
                                                $stock_out = $stock_out > 0 ? $stock_out : 0;
                                                $qty = $stock_in-$stock_out;
                                                $qty = $qty > 0 ? $qty : 0;
                                                $stock += $qty;
                                            endwhile;
                                            echo $stock > 0 ? format_num($stock) : 0 ;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card text-dark">
                            <div class="card-body">
                                <div class="w-100 d-flex align-items-center">
                                    <div class="col-auto pe-1">
                                        <span class="fa fa-coins fs-3 text-warning"></span>
                                    </div>
                                    <div class="col-auto flex-grow-1">
                                        <div class="fs-5"><b>Today's Sales</b></div>
                                        <div class="fs-6 text-end fw-bold">
                                            <?php 
                                            $sales = $conn->query("SELECT sum(total) as `total` FROM `transaction_list` where date(date_added) = date(CURRENT_TIMESTAMP) ".(($_SESSION['type'] != 1)? " and user_id = '{$_SESSION['user_id']}' " : ""))->fetch_array()[0];
                                            echo $sales > 0 ? format_num($sales) : 0 ;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h3>Stock Available</h3>
                        <hr>
                        <table class="table table-striped table-hover table-bordered" id="inventory">
                            <colgroup>
                                <col width="25%">
                                <col width="25%">
                                <col width="25%">
                                <col width="25%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="py-0 px-1">Category</th>
                                    <th class="py-0 px-1">Product Code</th>
                                    <th class="py-0 px-1">Product Name</th>
                                    <th class="py-0 px-1">Available Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = "SELECT p.*,c.name as cname FROM `product_list` p inner join `category_list` c on p.category_id = c.category_id where p.status = 1 and p.delete_flag = 0 order by `name` asc";
                                    $qry = $conn->query($sql);
                                    while($row = $qry->fetch_assoc()):
                                        $stock_in = $conn->query("SELECT sum(quantity) as `total` FROM `stock_list` where unix_timestamp(CONCAT(`expiry_date`, ' 23:59:59')) >= unix_timestamp(CURRENT_TIMESTAMP) and product_id = '{$row['product_id']}' ")->fetch_array()['total'];
                                        $stock_out = $conn->query("SELECT sum(quantity) as `total` FROM `transaction_items` where product_id = '{$row['product_id']}' ")->fetch_array()['total'];
                                        $stock_in = $stock_in > 0 ? $stock_in : 0;
                                        $stock_out = $stock_out > 0 ? $stock_out : 0;
                                        $qty = $stock_in-$stock_out;
                                        $qty = $qty > 0 ? $qty : 0;
                                ?>
                                    <tr class="<?php echo $qty < 50? "bg-danger bg-opacity-25":'' ?>">
                                        <td class="td py-0 px-1"><?php echo $row['cname'] ?></td>
                                        <td class="td py-0 px-1"><?php echo $row['product_code'] ?></td>
                                        <td class="td py-0 px-1"><?php echo $row['name'] ?></td>
                                        <td class="td py-0 px-1 text-end">
                                            <?php  if($_SESSION['type'] == 1): ?>
                                            <?php echo $qty < $row['alert_restock']? "<a href='javascript:void(0)' class='restock me-1' data-pid = '".$row['product_id']."' data-name = '".$row['product_code'].' - '.$row['name']."'> Restock</a>":'' ?>
                                            <?php endif; ?>
                                            <?php echo $qty ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('.restock').click(function(){
            uni_modal('Add New Stock for <span class="text-primary">'+$(this).attr('data-name')+"</span>","manage_stock.php?pid="+$(this).attr('data-pid'))
        })
        $('table#inventory').dataTable()

    })
</script>