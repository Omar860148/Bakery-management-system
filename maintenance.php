
<div class="card h-100 d-flex flex-column rounded-0 shadow">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Maintenance</h3>
        <div class="card-tools align-middle">
            <!-- <button class="btn btn-dark btn-sm py-1 rounded-0" type="button" id="create_new">Add New</button> -->
        </div>
    </div>
    <div class="card-body flex-grow-1">
        <div class="col-12 h-100">
            <div class="row h-100">
                <div class="col-md-6 h-100 d-flex flex-column">
                    <div class="w-100 d-flex border-bottom border-dark py-1 mb-1">
                        <div class="fs-5 col-auto flex-grow-1"><b>Category List</b></div>
                        <div class="col-auto flex-grow-0 d-flex justify-content-end">
                            <a href="javascript:void(0)" id="new_category" class="btn btn-dark btn-sm bg-gradient rounded-2" title="Add Category"><span class="fa fa-plus"></span></a>
                        </div>
                    </div>
                    <div class="h-100 overflow-auto border rounded-1 border-dark">
                        <ul class="list-group">
                            <?php 
                            $cat_qry = $conn->query("SELECT * FROM `category_list` where delete_flag = 0 order by `name` asc");
                            while($row = $cat_qry->fetch_assoc()):
                            ?>
                            <li class="list-group-item d-flex">
                                <div class="col-auto flex-grow-1">
                                    <?php echo $row['name'] ?>
                                </div>
                                <div class="col-auto pe-2">
                                    <?php 
                                        if(isset($row['status']) && $row['status'] == 1){
                                            echo "<small><span class='badge rounded-pill bg-success'>Active</span></small>";
                                        }else{
                                            echo "<small><span class='badge rounded-pill bg-danger'>Inactive</span></small>";
                                        }
                                    ?>
                                </div>
                                <div class="col-auto d-flex justify-content-end">
                                    <a href="javascript:void(0)" class="view_category btn btn-sm btn-info text-light bg-gradient py-0 px-1 me-1" title="View Category Details" data-id="<?php echo $row['category_id'] ?>" ><span class="fa fa-th-list"></span></a>
                                    <a href="javascript:void(0)" class="edit_category btn btn-sm btn-primary bg-gradient py-0 px-1 me-1" title="Edit Category Details" data-id="<?php echo $row['category_id'] ?>"  data-name="<?php echo $row['name'] ?>"><span class="fa fa-edit"></span></a>
                                    <a href="javascript:void(0)" class="delete_category btn btn-sm btn-danger bg-gradient py-0 px-1" title="Delete Category" data-id="<?php echo $row['category_id'] ?>"  data-name="<?php echo $row['name'] ?>"><span class="fa fa-trash"></span></a>
                                </div>
                            </li>
                            <?php endwhile; ?>
                            <?php if($cat_qry->num_rows <= 0): ?>
                                <li class="list-group-item text-center">No data listed yet.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        // Category Functions
        $('#new_category').click(function(){
            uni_modal('Add New Category',"manage_category.php")
        })
        $('.edit_category').click(function(){
            uni_modal('Edit Category Details',"manage_category.php?id="+$(this).attr('data-id'))
        })
        $('.view_category').click(function(){
            uni_modal('Category Details',"view_category.php?id="+$(this).attr('data-id'))
        })
        $('.delete_category').click(function(){
            _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from Category List?",'delete_category',[$(this).attr('data-id')])
        })
    })
    function delete_category($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./Actions.php?a=delete_category',
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