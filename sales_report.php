
<?php 
$dfrom = isset($_GET['date_from']) ? $_GET['date_from'] : date("Y-m-d",strtotime(date("Y-m-d")." -1 week"));
$dto = isset($_GET['date_to']) ? $_GET['date_to'] : date("Y-m-d");
?>
<div class="card rounded-0 shadow">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Sales Report</h3>
    </div>
    <div class="card-body">
        <h5>Filter</h5>
        <div class="row align-items-end">
            <div class="form-group col-md-2">
                <label for="date_from" class="control-label">Date From</label>
                <input type="date" name="date_from" id="date_from" value="<?php echo $dfrom ?>" class="form-control rounded-0">
            </div>
            <div class="form-group col-md-2">
                <label for="date_to" class="control-label">Date To</label>
                <input type="date" name="date_to" id="date_to" value="<?php echo $dto ?>" class="form-control rounded-0">
            </div>
            <div class="form-group col-md-4 d-flex">
                <div class="col-auto">
                    <button class="btn btn-primary rounded-0" id="filter" type="button"><i class="fa fa-filter"></i> Filter</button>
                    <button class="btn btn-success rounded-0" id="print" type="button"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>
        </div>
        <hr>
        <div class="clear-fix mb-2"></div>
        <div id="outprint">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="20%">
                <col width="25%">
                <col width="10%">
                <col width="20%">
                <col width="20%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>
                    <th class="text-center p-0">Date</th>
                    <th class="text-center p-0">Receipt No</th>
                    <th class="text-center p-0">Items</th>
                    <th class="text-center p-0">Total Amount</th>
                    <th class="text-center p-0">Processed By</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $user_where = "";
                if($_SESSION['type'] != 1){
                    $user_where = " and user_id = '{$_SESSION['user_id']}' ";
                }
                $user_qry = $conn->query("SELECT user_id, fullname FROM user_list where user_id in (SELECT user_id FROM  `transaction_list` where date(date_added) between '{$dfrom}' and '{$dto}' {$user_where}) ");
                $user_arr = array_column($user_qry->fetch_all(MYSQLI_ASSOC),'fullname','user_id');
                $sql = "SELECT * FROM  `transaction_list` where date(date_added) between '{$dfrom}' and '{$dto}' {$user_where}  order by unix_timestamp(date_added) asc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetch_assoc()):
                        $items = $conn->query("SELECT count(transaction_id) as `count` FROM `transaction_items` where transaction_id = '{$row['transaction_id']}' ")->fetch_assoc()['count'];
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-0 px-1"><?php echo date("Y-m-d",strtotime($row['date_added'])) ?></td>
                    <td class="py-0 px-1"><a href="javascript:void(0)" class="view_data" data-id="<?php echo $row['transaction_id'] ?>"><?php echo $row['receipt_no'] ?></a></td>
                    <td class="py-0 px-1 text-end"><?php echo format_num($items) ?></td>
                    <td class="py-0 px-1 text-end"><?php echo format_num($row['total']) ?></td>
                    <td class="py-0 px-1"><?php echo isset($user_arr[$row['user_id']]) ? $user_arr[$row['user_id']] : 'N/A' ?></td>
                </tr>
                <?php endwhile; ?>
                <?php if($qry->num_rows <=0): ?>
                    <th colspan="6"><center>No Transaction listed in selected date.</center></th>
                <?php endif; ?>
               
            </tbody>
        </table>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.view_data').click(function(){
            uni_modal('Receipt',"view_receipt.php?view_only=true&id="+$(this).attr('data-id'),'')
        })
        $('#filter').click(function(){
            location.href="./?page=sales_report&date_from="+$('#date_from').val()+"&date_to="+$('#date_to').val();
        })
        
        $('table td,table th').addClass('align-middle')

        $('#print').click(function(){
            var h = $('head').clone()
            var p = $('#outprint').clone()
            var el = $('<div>')
            el.append(h)
            if('<?php echo $dfrom ?>' == '<?php echo $dto ?>'){
                date_range = "<?php echo date('M d, Y',strtotime($dfrom)) ?>";
            }else{
                date_range = "<?php echo date('M d, Y',strtotime($dfrom)).' - '.date('M d, Y',strtotime($dto)) ?>";
            }
            el.append("<div class='text-center lh-1 fw-bold'>Pharmacy's Sales Report<br/>As of<br/>"+date_range+"</div><hr/>")
            p.find('a').addClass('text-decoration-none')
            el.append(p)
            var nw = window.open("","","width=500,height=900")
                nw.document.write(el.html())
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    setTimeout(() => {
                        nw.close()
                    }, 150);
                }, 200);
        })
        // $('table').dataTable({
        //     columnDefs: [
        //         { orderable: false, targets:3 }
        //     ]
        // })
    })
    
</script>