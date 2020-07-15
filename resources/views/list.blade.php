<!DOCTYPE html>
<html>
<head>
    <title>Cuestomer Records</title>
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>       
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
</head>
<body>
    <div class="container">
    <br />
    <h3 align="center">Datatables Server Side Processing in Laravel</h3>
    <div align="left">
        <a href="{{URL::route('export.customers')}}" class="btn btn-primary btn-sm">Export Customers</a>
    </div>
    <div align="right">
        <button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Add</button>
    </div>
    <br />
    <table id="customer_table" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Age</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

<!-- add data -->
<div id="customerModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="customer_form">
                <div class="modal-header bg-success">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h4 class="modal-title">Add Data</h4>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <span id="form_output"></span>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="name" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Age</label>
                        <input type="text" name="age" id="age" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" id="address" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="customer_id" id="customer_id" value="" />
                    <input type="hidden" name="button_action" id="button_action" value="insert" />
                    <input type="submit" name="submit" id="action" value="Add" class="btn btn-info" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end add data -->

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    $(document).ready(function(){
        $('#customer_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('customers.list') }}",
            "columns":[
                { "data": "id" },
                { "data": "name" },
                { "data": "age" },
                { "data": "address" },
                { "data": "phone" },
                { "data": "action", orderable:false, searchable: false}
            ]
        });


        $('#add_data').click(function(){
            $('#customerModal').modal('show');
            $('#customer_form')[0].reset();
            $('#form_output').html('');
            $('#button_action').val('insert');
            $('#action').val('Add');
            $('.modal-title').text('Add Data');
        });

        $('#customer_form').on('submit', function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
           
            $.ajax({
                url:"{{ route('customer.add') }}",
                method:"POST",
                data:form_data,
                dataType:"json",
                success:function(data)
                {
                    if(data.error.length > 0)
                    {
                        var error_html = '';
                        for(var count = 0; count < data.error.length; count++)
                        {
                            error_html += '<div class="alert alert-danger">'+data.error[count]+'</div>';
                        }
                        $('#form_output').html(error_html);
                    }
                    else
                    {
                        $('#form_output').html(data.success);
                        $('#customer_form')[0].reset();
                        $('#action').val('Add');
                        $('.modal-title').text('Add Data');
                        $('#button_action').val('insert');
                        $('#customer_table').DataTable().ajax.reload();
                    }
                }
            })
        });

        //edit record
        $(document).on('click', '.edit', function(){
            var id = $(this).attr("id");
            $('#form_output').html('');
            $.ajax({
                url:"{{route('customer.edit')}}",
                method:'get',
                data:{id:id},
                dataType:'json',
                success:function(data)
                {
                    $('#name').val(data.name);
                    $('#age').val(data.age);
                    $('#address').val(data.address);
                    $('#phone').val(data.phone);
                    $('#customer_id').val(id);
                    $('#customerModal').modal('show');
                    $('#action').val('Edit');
                    $('.modal-title').text('Edit Data');
                    $('#button_action').val('update');
                }
            })
        });

        //delete record
        $(document).on('click', '.delete', function(){
            var id = $(this).attr('id');
            if(confirm("Are you sure you want to Delete this data?"))
            {
                $.ajax({
                    url:"{{route('customer.delete')}}",
                    mehtod:"get",
                    data:{id:id},
                    success:function(data)
                    {   
                        toastr.success("Customer has been deleted.", "Success");
                        $('#customer_table').DataTable().ajax.reload();
                    }
                })
            }
            else
            {
                return false;
            }
        }); 
    });
</script>
</body>
</html>
