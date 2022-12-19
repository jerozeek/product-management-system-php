function add_product() {
    var product = $('#product').val(),
        quantity = $('#quantity').val(),
        c_price = $('#c_price').val(),
        s_price = $('#s_price').val(),
        manufacturer = $('#manufacturer').val(),
        expire = $('#expiry').val(),
        cate = $('#category_name').val();
    ;
    $.ajax({
        url:'../process.php',
        method:'post',
        data:{product:product,quan:quantity,cost_price:c_price,selling_price:s_price,manufacturer:manufacturer,expiry:expire,category_name:cate},
        dataType:'json',
        success:function (data) {
            if (data.status == false){
                //error found
                swal('Error',data.result,'error');
            } else if (data.status == true){
                //success message
                swal('Success',data.result,'success');
                setTimeout(function () {
                    location.reload(true);
                },5000)
            }
        }
    })
}
function product(id,status) {
    $('.loader').addClass('csspinner traditional');
    setTimeout(function () {
        $('.loader').removeClass('csspinner traditional');
        var record;
        $.ajax({
            url:'../process.php',
            method:'post',
            data:{product_id:id,status:status},
            dataType:'json',
            success:function (data) {
                if (data.status == false){
                    //error found
                    swal('Error',data.result,'error');
                } else if (data.status == true){
                    //success message
                    if (data.location == 'view'){
                        record = data.result.split(',');
                        $('#get_id').text(record[0]);
                        $('#get_name').text(record[1]);
                        $('#get_quantity').text(record[2]);
                        $('#get_cost').text(record[3]);
                        $('#get_sell').text(record[4]);
                        $('#get_manufacturer').text(record[5]);
                        $('#get_expiry').text(record[6]);
                        $('#get_status').html(record[7]);
                        $('#get_category').html(record[8]);
                        $('#barcode').html(record[9]);
                        $('#display').click();
                    } else if (data.location == 'edit'){
                        record = data.result.split(',');
                        $('#update_id').val(record[0]);
                        $('#update_product').val(record[1]);
                        $('#update_quantity').val(record[2]);
                        $('#update_cost').val(record[3]);
                        $('#update_sales').val(record[4]);
                        $('#update_manufacturer').val(record[5]);
                        $('#update_expiry').val(record[6]);
                        $('#update_category').val(record[7]);
                        $('#update_view').click();
                    }
                    else {
                        swal('Success', data.result, 'success');
                        setTimeout(function () {
                            location.reload(true);
                        }, 5000);
                    }
                }
            }
        })
    },2000);
}

function product_update() {
    var id = $('#update_id').val(),
        p_name = $('#update_product').val(),
        p_quantity = $('#update_quantity').val(),
        p_cost = $("#update_cost").val(),
        p_sell = $('#update_sales').val(),
        p_manu = $('#update_manufacturer').val(),
        p_expiry = $('#update_expiry').val(),
        p_category = $('#update_category').val();
    $.ajax({
        url:'../process.php',
        method:'post',
        dataType:'json',
        data:{update_id:id,name_update:p_name, category_update:p_category, quantity_update:p_quantity,cost_update:p_cost,sell_update:p_sell,manufacturer_update:p_manu,expiry_update:p_expiry},
        success:function (data) {
            if (data.success == true){
                //return true
                swal('Success',data.message, 'success');
                setTimeout(function () {
                    location.reload(true);
                },2000);
            }else{
                //throw back an error message
                swal('Error',data.message, 'error');
            }
        }
    })
}
function create_category() {
    var cat = $('#category').val();
    if (cat !== '') {
        $.ajax({
            url: '../process.php',
            method: 'post',
            dataType: 'json',
            data: {category:cat,category_add: 1},
            success: function (data) {
                if (data.status == true) {
                    //return true
                    swal('Success', data.message, 'success');
                    setTimeout(function () {
                        location.reload(true);
                    },2000);
                } else {
                    swal('Error', data.message, 'error');
                }
            }
        })
    }
}

function printContent(el){
    var restorepage = $('body').html();
    var printcontent = $('#' + el).clone();
    $('body').empty().html(printcontent);
    window.print();
    $('body').html(restorepage);
}
//employee
function remove_employee(employeee_id){
        swal({
                title: "Are you sure?",
                text: "Are you sure you want to delete this employee",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'Yes, delete it!',
                closeOnConfirm: false,
                //closeOnCancel: false
            },
            function(){
                 $(document).load('../process1.php?employee_id='+employeee_id, function (d,s) {
           if (s){
              //went in successfully
               swal("Deleted!", "Account have been deleted!", "success");
              setTimeout(function () {
                  location.reload(true);
              },2000);
           }
       })
    });
}

//Add employee
function add_employee(){
    var username = $('#username').val(), password = $('#password').val(), role = $('#role').val();
    if (username !== '' && password !== ''){
        //submit the data
        $.ajax({
            url:'../process1.php',
            method:'post',
            dataType:'json',
            data:{employee_username:username,employee_password:password,employee_role:role},
            success:function (data) {
                if (data.success == true){
                    swal('Success',data.message, 'success');
                    setTimeout(function () {
                        window.location = '../permissions/update.php?employee_id='+data.employee;
                    },2000);
                }else{
                    swal('Error',data.message, 'error');
                }
            }
        })
    }
}
//load employee details
function load_employee(id) {
    $.ajax({
        url:'../process1.php',
        method:'post',
        dataType:'json',
        data:{employee_id:id,status:'load'},
        success:function (data) {
            if (data.success){
                //get the properties and set their values
                results = data.result.split(',');
                $('#update_id').val(results[0]);
                $('#update_username').val(results[1]);
                $('#update_password').val(results[2]);
                $('#update_role').val(results[3]);
                $('#load_update').click();
            }else{
                swal('Error', data.message,'error');
            }
        }
    })
}
//request view
function view_employee(id) {
    $('.spinner').addClass('csspinner traditional');
    setTimeout(function () {
        $.ajax({
            url:'../process1.php',
            method:'get',
            dataType:'json',
            data:{employee:'view_employee',view_id:id},
            success:function (data) {
                if (data.status == true){
                    $('.spinner').removeClass('csspinner traditional');
                    //load the employee details here
                    r = data.result.split(',');
                    $('#get_employee_name').text(r[1]);
                    $('#get_employee_password').text(r[2]);
                    $('#get_employee_role').text(r[3]);
                    $('#get_employee_last_login').text(r[4]);
                    $('#load_employee_view').click();
                }
            }
        })
    },2000);
}
//update employee.....
function update_employee(){
    var id = $('#update_id').val(), username = $('#update_username').val(), password = $('#update_password').val(), role = $('#update_role').val();
    if (username !== '' && password !== ''){
        //submit the data
        $.ajax({
            url:'../process1.php',
            method:'post',
            dataType:'json',
            data:{id:id,username_update:username,password_update:password,role_update:role,employee_update:1},
            success:function (data) {
                if (data.success == true){
                    swal('Success',data.message, 'success');
                    setTimeout(function () {
                        location.reload(true);
                    },2000);
                }else{
                    swal('Error',data.message, 'error');
                }
            }
        })
    }
}

//Customers script
function add_customer(){
    var c_name = $('#customer_name').val(), c_email = $('#customer_email').val(), c_address = $('#customer_address').val(), c_phone = $('#customer_phone').val();
    if (c_name !== '' && c_email !== '' && c_phone !== '' && c_address !== ''){
        $.ajax({
            url:'../process1.php',
            method: 'post',
            dataType: 'json',
            data:{customer_name:c_name,customer_email:c_email,customer_address:c_address,customer_phone:c_phone},
            success:function (data) {
                if (data.success == true){
                    //throw a success message
                    swal('Success',data.message, 'success');
                    setTimeout(function () {
                        location.reload(true);
                    },2000);
                }else{
                    swal('Error',data.message, 'error');
                }
            }
        })
    }
}

function customer_delete(id) {
    swal({
            title: "Are you sure?",
            text: "Are you sure you want to delete this customer",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            //closeOnCancel: false
        },
        function(){
            $(document).load('../process1.php?customer_id='+id, function (d,s) {
                if (s){
                    //went in successfully
                    swal("Deleted!", "Account have been deleted!", "success");
                    setTimeout(function () {
                        location.reload(true);
                    },2000);
                }
            })
        });
}
function customer_report(id){
    $(document).load('../process1.php?customer_report='+id, function (d,s) {
        if (s){
            r = d.split(',');
            $('#get_customer_name').text(r[1]);
            $('#get_customer_email').text(r[2]);
            $('#get_customer_phone').text(r[3]);
            $('#get_customer_address').text(r[4]);
            $('#get_balance_in').text('₦'+r[5]);
            $('#number_of_purchase').text(r[6]);
            $('#created').text(r[7]);
            $('#display_customer_info').click();
        }
    })
}
//fetch the records to the form
function customer_display(id){
    $(document).load('../process1.php?customer_display='+id, function (d,s) {
        if (s){
            r = d.split(',');
            $('#update_customer_id').val(r[0]);
            $('#update_customer_name').val(r[1]);
            $('#update_customer_email').val(r[2]);
            $('#update_customer_mobile').val(r[3]);
            $('#update_customer_address').val(r[4]);
            $('#update_customer').click();
        }
    });
}
//update the customer records
function update_customer(){
    var u_id = $('#update_customer_id').val(),
        u_name = $('#update_customer_name').val(),
        u_email = $('#update_customer_email').val(),
        u_mobile = $('#update_customer_mobile').val(),
        u_address = $('#update_customer_address').val();
    $.ajax({
        url:'../process1.php',
        method:'post',
        dataType:'json',
        data:{customer_id:u_id,customer_name:u_name,customer_email:u_email,customer_mobile:u_mobile,customer_address:u_address},
        success:function (data) {
            if (data.success == true){
                //update and send a set swal message
                swal('Success', data.result,'success');
                setTimeout(function () {
                    location.reload(true);
                },2000);
            }
        }
    })
}

//Permissions
function reset_permission(form_id){
   $('input:checkbox').removeAttr('checked');
}
function load_items(elm,item){
    if (item !== ''){
        $.ajax({
            url:'../process1.php',
            method:'post',
            dataType:'json',
            data:{selected_item:item,status:'search_item'},
            success:function (data) {
                if (data.success == true){
                    var result = data.result.split('/');
                    $(elm).closest('tr').find('#sale_category').val(result[0]);
                    $(elm).closest('tr').find('#price').val(result[1]);
                    $(elm).closest('tr').find('#profit').val(result[2]);
                }
            }
        })
    }
}
function remove_expenses(id){
    swal({
            title: "Are you sure?",
            text: "Are you sure you want to delete this expenses",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            //closeOnCancel: false
        },
        function() {
            $(document).load('../process1.php?expenses_id=' + id, function (d, s) {
                if (s) {
                    swal("Deleted!", "Expenses have been deleted!", "success");
                    setTimeout(function () {
                        location.reload(true);
                    },2000);
                }
            })
        });
}

function view_expenses(id){
    $(document).load("../process1.php?expenses_view="+id,function (d,s) {
        if (s){
            splitter = d.split('@');
            $('#get_expenses_purpose').text(splitter[0]);
            $('#get_expenses_amount').text(splitter[1]);
            $('#get_expenses_assigned_by').text(splitter[2]);
            $('#get_expenses_assigned_to').text(splitter[3]);
            $('#get_expenses_date').text(splitter[4]);
            $('#load_expenses_view').click();
        }
    });
}
function load_expenses(id){
    $(document).load("../process1.php?expenses_view="+id,function (d,s) {
        if (s){
            splitter = d.split('@');
            $('#expenses_purpose').val(splitter[0]);
            $('#expenses_amount').val(splitter[1]);
            $('#expenses_assigned_to').val(splitter[3]);
            $('#expenses_id').val(splitter[5]);
            $('#load_expenses_update').click();
        }
    });
}
function sales(id,status){
    swal({
            title: "Are you sure?",
            text: "Are you sure you want to delete this sales? this cannot be undone",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false,
            //closeOnCancel: false
        },
        function() {
            $(document).load('../process1.php?bill_id='+id+'&status='+status, function (d, s) {
                if (s) {
                    swal("Deleted!", d, "success");
                    setTimeout(function () {
                        location.reload(true);
                    },2000);
                }else{
                    swal("Error!", d, "error");
                }
            })
        });
}


$(document).ready(function () {
    //add permissions
    $('#permission_form').on('submit', function (event) {
        event.preventDefault();
        var that = $(this), contents = that.serialize();
        $.ajax({
            url:'../permission.php',
            method:'post',
            dataType:'json',
            data:contents,
            success:function (data) {
                if (data.status == true){
                    swal('Success',data.message, 'success');
                    setTimeout(function () {
                        location.assign('../employee/employee.php');
                    },2000);
                } else{
                    swal('Error','Something went wrong','error');
                }
            }
        })

    });
    //Expenses script by json
    $('#add_expenses').on('submit', function (e) {
        e.preventDefault();
        var content = $(this).serialize();
        $.ajax({
            url:'../process.php',
            method:'post',
            dataType:'json',
            data:content,
            success:function (data) {
                if (data.status == true){
                    swal('Success',data.result, 'success');
                    setTimeout(function () {
                        location.reload(true);
                    },2000);
                }else{
                    swal('Error', data.result, 'error');
                }
            }
        })
    });

//SALE CALUCULATOR
    $('body').on('click','.addBtn' ,function() {
        var body = $(this).closest("tbody");
        var itemData = body.children("tr");
        body.append("<tr>"+itemData.html()+"</tr>");
    });

    $('body').on('click','.removeBtn' ,function() {
        var itemData = $(this).closest("tr");
        var body = itemData.parent("tbody");
        var b = body.children("tr");
        //clear invoice...
        var subtotal = itemData.find(".subtotal").val();
        var total = $("#invoice tfoot").find("#total").val();
        var current = parseFloat(total) - parseFloat(subtotal);
        $("#invoice").find("#total").val(current+".00");
        $("#invoice").find("#grand_total").val(current+".00");
        $("#invoice").find("#due").val(current+".00");
        //ends here
        itemData.remove();
    });

    $('body').on('click','.removeItem' ,function() {
        var itemData = $(this).closest("tr");
        var body = itemData.parent("tbody");
        var b = body.children("tr");
        //clear invoice...
        var quantity = itemData.find('#quantity').val();
        var product = itemData.find('#product').val();
        swal({
                title: "Are you sure?",
                text: "Are you sure you want to return this item?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'Yes, return to inventory!',
                closeOnConfirm: false,
                //closeOnCancel: false
            },
            function() {
                //var bill_id = $("input:hidden[name=bill_id]").val();
                $(document).load('../process1.php?quantity='+quantity+'&product='+product, function (d,s) {
                    if (s){
                        swal('Success',d,'success');
                        //clear invoice...
                        var subtotal = itemData.find(".subtotal").val();
                        var total = $("#invoice tfoot").find("#total").val();
                        var current = parseFloat(total) - parseFloat(subtotal);
                        $("#invoice").find("#total").val(current+".00");
                        $("#invoice").find("#grand_total").val(current+".00");
                        //ends here
                        itemData.remove();
                    } else{
                        swal('Error',d,'error');
                    }
                });

            });
    });



    //calculate total
    $('body').on('keyup', '.totalCal', function() {
        var totalCal = $(this).parent().parent();
        var quantity  = totalCal.children().next().next().children().val();
        var price  = totalCal.children().next().next().next().children().val();
        totalCal.children().next().next().next().next().children().val(
            (quantity*price).toFixed(2));
        /*calculate total invoice*/
        //total
        var total = 0;
        $('.subtotal').each(function(){
            total  += parseFloat($(this).val());
            $('#total').val(total.toFixed(2));
        });
        //grand total
        $("#grand_total").val(((parseFloat(total))).toFixed(2));
        // paid and due
        var grand_total = $('#grand_total').val();
        var paid        = $('#paid').val();
        $('#due').val((parseFloat(grand_total)-parseFloat(paid)).toFixed(2));
        // paid and due
        var grand_total = $('#grand_total').val();
        var paid        = $('#paid').val();
        $('#due').val((parseFloat(grand_total)-parseFloat(paid)).toFixed(2));

    });


    // paid and due
    $('body').on('keyup change', '.paidDue', function() {
        var grand_total = $('#grand_total').val();
        var paid        = $('#paid').val();
        $('#due').val((parseFloat(grand_total)-parseFloat(paid)).toFixed(2));
    });

    var clearInvoice = function(){
        var subtotal = $("#invoice tbody tr:last-child").find(".subtotal").val();
        var itemData = $(this).closest("tr");
        var body = itemData.parent("tbody");
        var b = body.children("tr");
        alert(subtotal);
      /*  var total = $("#invoice tfoot").find("#total").val();
        var current = parseFloat(total) - parseFloat(subtotal);
        //var current = parseFloat(total) - parseFloat(medsubtotal);
        $("#invoice").find("#total").val(current+".00");
        $("#invoice").find("#grand_total").val(current+".00"); */
    };






});


