<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Ajex Crud</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-3">Laravel Ajex Crud</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold h3">Student List</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table" id="dataTable" width="100%" cellspacing="0">
                        <a class="btn btn-success" id="createNewStudent" style="float:right" href="javascript:void(0)" >Add</a>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                              
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajaxModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="studentForm" name="studentForm" class="form-horizontal">@csrf  
                        <input type="hidden" name="student_id" id="student_id">
                        <div class="form-group">
                            Name: <br>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" required>
                        </div>
                        <div class="form-group">
                            Email: <br>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="" required>
                        </div>
                        <button type="submit" class="btn btn-success" id="saveBtn" value="Create">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
 <!-- JavaScript Bundle with Popper -->
 <script
 src="https://code.jquery.com/jquery-3.6.1.min.js"
 integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
 crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
</body>
<script type="text/javascript">
    $(function(){
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        var table = $(".data-table").DataTable({
            serverSide : true,
            processing : true,
            ajax : "{{route('students.index')}}",
                columns : [
                    {data : 'DT_RowIndex' , name : 'DT_RowIndex'},
                    {data : 'name' , name : 'name'},
                    {data : 'email' , name : 'email'},
                    {data : 'action' , name : 'action'},
                ]
            });
            $("#createNewStudent").click(function(){
                $("#student_id").val();
                $("#studentForm").trigger("reset");
                $("#modalHeading").html("Add Student");
                $("#ajaxModal").modal('show');
            });
            $("#saveBtn").click(function(e){
                e.preventDefault()
                $(this).html('Save');

                $.ajax({
                    data:$("#studentForm").serialize(),
                    url:"{{route('students.store')}}",
                    type:"POST",
                    dataType:'json',
                    success:function(data){
                        $("#studentForm").trigger("reset");
                        $('#ajaxModal').modal('hide');
                        table.draw();
                    },
                    error:function(data){
                        console.log('Error:',data);
                        $("#saveBtn").html('Save')
                    }
                });
            });
            $('body').on('click','.deleteStudent', function(){
                var student_id = $(this).data('id');
                confirm("Are you sure you want to delete?!");
                $.ajax({
                    type:"DELETE",
                    url:"{{route('students.store')}}"+'/'+student_id,
                    success:function(data){
                        table.draw();
                    },
                    error:function(data){
                        console.log('Error:',data);
                    }
                })
            });
            $('body').on('click','.editStudent', function(){
                var student_id = $(this).data('id');
                $.get("{{route('students.index')}}"+'/'+student_id+"/edit",function(data){
                    $("#modalHeading").html("Edit Student");
                    $("#ajaxModal").modal('show');
                    $("#student_id").val(data.id);
                    $("#name").val(data.name);
                    $("#email").val(data.email);
                })
            });
    }); 
</script>
</html>