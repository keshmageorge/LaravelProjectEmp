@extends('employee.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>User Records</h2>
        </div>
        <div class="pull-right">
            <!-- <a class="btn btn-success" href="{{ route('employee.create') }}"> Add New</a> -->
            <a class="btn btn-success text-light" data-toggle="modal" id="mediumButton" data-target="#mediumModal" data-attr="{{ route('employee.create') }}" title="Add New"> <i class="fas fa-plus-circle"></i> Add New
            </a>
        </div>
    </div>
</div>

<div id="ajax-alert" class="alert alert-success" style="display:none"></div>
@if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

<table class="table table-bordered">
    <tr>
        <th>Avatar</th>
        <th>Name</th>
        <th>Email</th>
        <th>Experience</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($employee as $emp)
    <tr id="tr_{{$emp->id}}">
        <td><img src="/uploads/{{ $emp->image }}" width="100px" style="      border: 1px solid #e1e1e1;
    border-radius: 50%;
    width: 100px;"></td>
        <td>{{ $emp->name }}</td>
        <td>{{ $emp->email }}</td>
        <td><?php
            if ($emp->is_working == '1') {
                $mytime = Carbon\Carbon::now();
                $leaving_date = $mytime->format('Y-m-d');
            } else {
                $leaving_date = $emp->leaving_date;
            }
            $date_diff = abs(strtotime($leaving_date) - strtotime($emp->joining_date));
            $years = floor($date_diff / (365 * 60 * 60 * 24));
            $months = floor(($date_diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($date_diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            echo $years . ' years ' . $months . ' months';
            ?></td>
        <td>
        <a data-toggle="modal" id="smallButton" data-target="#smallModal" data-attr="{{ route('delete', $emp->id) }}" title="Delete Project">
                   <span class="delete btn btn-danger">Delete</span>
                </a>
        </td>
    </tr>
    @endforeach
</table>

{!! $employee->links() !!}
<!-- small modal -->
<div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="smallBody">
                <div>
                    <!-- the result to be displayed apply here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- medium modal -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"> Add New Record</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div>
                    <!-- the result to be displayed apply here -->
                </div>
            </div>
        </div>
    </div>
</div>


<script>

// display a modal (small modal)
$(document).on('click', '#smallButton', function(event) {
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            url: href
            , beforeSend: function() {
                $('#loader').show();
            },
            // return the result
            success: function(result) {
                $('#smallModal').modal("show");
                $('#smallBody').html(result).show();
            }
            , complete: function() {
                $('#loader').hide();
            }
            , error: function(jqXHR, testStatus, error) {
                console.log(error);
                alert("Page " + href + " cannot open. Error:" + error);
                $('#loader').hide();
            }
            , timeout: 8000
        })
    });
    // display a modal (medium modal)

    $(document).on('click', '#mediumButton', function(event) {
        event.preventDefault();
        let href = $(this).attr('data-attr');
        $.ajax({
            url: href,
            beforeSend: function() {
                $('#loader').show();
            },
            // return the result
            success: function(result) {
                $('#mediumModal').modal("show");
                $('#mediumBody').html(result).show();
            },
            complete: function() {
                $('#loader').hide();
            },
            error: function(jqXHR, testStatus, error) {
                console.log(error);
                alert("Page " + href + " cannot open. Error:" + error);
                $('#loader').hide();
            },
            timeout: 8000
        })
    });

    $(document).ready(function() {
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function(event, element) {
                element.trigger('confirm');
            }
        });


        $(document).on('confirm', function(e) {
            var ele = e.target;
            e.preventDefault();
            var emp_id = $(this).data("id");
            _url = $('#deleteEmp').attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: _url,
                type: "POST",
                contentType: false,
                processData: false,
                cache: false,
                data: {
                    'id': emp_id,
                    "_token": $('#token').val()
                },
                success: function(data) {
                    $("#" + data['tr']).slideUp("slow");
                    // alert(data['success']);
                    $('#ajax-alert').addClass('alert-sucess').show(function() {
                        $(this).html(data['success']);
                    });
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
            return false;
        });
    });
</script>
@endsection