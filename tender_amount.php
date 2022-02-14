<style>
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>
<div class="container-fluid">
    <div class="form-group">
        <label for="amount" class="control-label fs-4 fw-bold">Payable Amount</label>
        <input type="text" id="amount" class="form-control form-control-lg text-end" value="<?php echo $_GET['amount'] ?>" disabled>
    </div>
    <div class="form-group">
        <label for="tender" class="control-label fs-4 fw-bold">Tendered Amount</label>
        <input type="number" step="any" id="tender" class="form-control form-control-lg text-end" value="0">
    </div>
    <div class="form-group">
        <label for="change" class="control-label fs-4 fw-bold">Change</label>
        <input type="text" id="change" class="form-control form-control-lg text-end" value="0" disabled>
    </div>
    <div class="w-100 d-flex justify-content-end mt-2">
            <button class="btn btn-sm btn-primary me-2 rounded-0" type="button" id="save_trans">Save</button>
            <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Close</button>
        </div>
</div>
<script>
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            if($(this).find('#tender').length > 0)
            $('#tender').trigger('focus').select();

        })
        $('#tender').on('keydown',function(e){
            if(e.which == 13){
                e.preventDefault()
                $('#save_trans').trigger('click')
            }
        })
        $('#tender').on('keypress input',function(){
            var tender = $(this).val() > 0? $(this).val() : 0;
            var amount = $('#amount').val().replace(/,/gi,"")
            $('[name="tendered_amount"]').val(tender)
            var change = parseFloat(tender) - parseFloat(amount)
            $('#change').val(parseFloat(change).toLocaleString('en-US'))
            $('[name="change"]').val(parseFloat(change))
        })
        $('#tender').focusout(function(){
            if($(this).val() <=0)
            $(this).val(0);
        })
        $('#save_trans').click(function(){
            $('#change').removeClass('border-danger') 
            if($('[name="change"]').val() < 0){
                $('#change').addClass('border-danger')
            }else if($('[name="tendered_amount"]').val() <= 0){
                $('#tender').trigger('focus')
            }else{
                $('#uni_modal').modal('hide')
                $('#transaction-form').submit()
            }
        })
    })
</script>