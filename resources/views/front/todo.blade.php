@extends('front/layouts.app')

@section('title','')

@section('content')

<div class="container todo-list-container">
    <div class="row justify-content-center align-items-center main-row">
        <div class="col-md-6 shadow main-col bg-white mt-200">
            <div class="row text-primary">
                <div class="col p-2">
                    <h4>PHP - Simple To Do List App</h4>
                    <hr>
                </div>
            </div>
            <div id="validation_message"></div>
            <form class="form-inline">
                @csrf
                <div class="row justify-content-between text-white p-2">
                    <div class="form-group offset-md-2 col-md-5">
                        <input name="name" id="name" type="text" class="form-control" value="">
                    </div>    
                    <div class="form-group col-md-5">
                        <button id="add_task" class="btn btn-primary mb-2 ml-2">Add Task</button>
                        <button id="all_task" class="btn btn-success mb-2 ml-2">Show All Tasks</button>
                    </div>
                </div>
            </form>
            <div class="row p-2" id="todo-container">
                <table class="table">
                    <thead>
                        <th>#</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js_scripts')

<script>
    // Get All Items
    fetchItems();
    function fetchItems() {
        $.ajax({
            type: "GET",
            url: "/fetch",
            dataType: "json",
            success: function (response) {
                // console.log(response);
                if (response) {
                    $('tbody').html("");
                    $('tbody').append(response.items);
                }
            }
        });
    }

    // Add Item
    $(document).on('click', '#add_task', function (e) {
        e.preventDefault();
        $(this).text('Sending..');
        var data = {
            'name': $('#name').val(),
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "{{ route("todo.store") }}",
            data: data,
            dataType: "json",
            success: function (response) {
                //console.log(response);
                $('#validation_message').html("");
                if (response.status == 200) {
                        $('#validation_message').removeClass('alert alert-danger');
                        $('#validation_message').addClass('alert alert-success');
                        $('#validation_message').text(response.message);
                        fetchItems();
                    } else {
                        $('#validation_message').removeClass('alert alert-success');
                        $('#validation_message').addClass('alert alert-danger');
                        $.each(response.errors, function (key, err_value) {
                            $('#validation_message').append('<p>' + err_value + '</p>');
                        });
                        
                }
                $('#add_task').text('Save');
                $('#name').val('');
                $('.alert').delay(9000).fadeOut('slow');
            }
        });

    });

    // Delete Item
    $(document).on('click', '.deletebtn', function (e) {
        e.preventDefault();
        var confirmation = confirm("are you sure you want to remove the item?");

        if (confirmation) {
            var id = $(this).data('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "GET",
                url: "/destroy/" + id,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.status == 404) {
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                    } else {
                        $('#success_message').html("");
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        fetchItems();
                    }
                }
            });
        }
    });

    // Delete Item
    $(document).on('click', '.statusbtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "get",
            url: "/update-status/" + id,
            dataType: "json",
            success: function (response) {
                // console.log(response);
                if (response.status == 404) {
                    $('#success_message').addClass('alert alert-success');
                    $('#success_message').text(response.message);
                } else {
                    $('#success_message').html("");
                    $('#success_message').addClass('alert alert-success');
                    $('#success_message').text(response.message);
                    fetchItems();
                }
            }
        });
    });

    $(document).on('click', '#all_task', function (e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "/fetch",
            data: 'fetch=all',
            dataType: "json",
            success: function (response) {
                // console.log(response);
                if (response) {
                    $('tbody').html("");
                    $('tbody').append(response.items);
                }
            }
        });
    });
</script>
@endsection