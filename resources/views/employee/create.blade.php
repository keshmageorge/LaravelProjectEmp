<head>
    <title>Laravel Ajax jquery Validation Tutorial</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>

<body>
    <div id="ajax-alert" class="alert alert-success" style="display:none"></div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('employee.store') }}" method="POST" id='user-form' enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email:</strong>
                    <input type="email" name="email" id='email' class="form-control" placeholder="Email">
                    <span id='email-error' class='alert-danger'></span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Full Name:</strong>
                    <input type="text" name="name" id='name' class="form-control" placeholder="Name">
                    <span id='name-error' class='alert-danger'></span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Date Of Joining:</strong>
                    <input type="date" name="jdate" id='jdate' class="form-control" placeholder="Joining Date" max="<?php echo date('Y-m-d')?>">
                    <span id='jdate-error' class='alert-danger'></span>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="row">
                    <label class="col-xs-12">Date Of Leaving:</label>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-9">
                        <input type="date" name="ldate" id='ldate' class="form-control" placeholder="Leaving Date">
                    </div>
                    <div class="col-xs-12 col-sm-3">
                        <input class="form-control-input" type="checkbox" value="1" name="working" id='working'> <b>Still Working</b>
                    </div>
                </div>
                <span id='ldate-error' class='alert-danger'></span>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Image:</strong>
                    <input type="file" name="image" id='image' class="form-control" placeholder="image">
                    <span id='image-error' class='alert-danger'></span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

    <script type="text/javascript">
        $('#user-form').on('submit', function(event) {
            event.preventDefault();
            $('#name-error').text('');
            $('#email-error').text('');
            $('#mobile-number-error').text('');
            $('#subject-error').text('');
            $('#message-error').text('');

            ldate = $('#ldate').val();

            var checkedValue = $('#working:checked').val();
            if(checkedValue == '1'){
                working = '1';
            }else{
                working = '';
            }
            if(working == '' && ldate == ''){
                $('#ldate-error').text("Last or still working date cannot be empty");
                return false;
            }else if(working != '' && ldate != ''){
                $('#ldate-error').text('Choose any one field');
            }else{
                $('#ldate-error').text('');

            }

            url = $('#user-form').attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                contentType: false,
                processData: false,
                cache: false,
                data: new FormData(this),
                success: function(response) {
                    console.log(response);
                    if (response) {
                        window.location.assign("{{ route('employee.index') }}")
                    }
                },
                error: function(response) {
                    $('#email-error').text(response.responseJSON.errors.email);
                    $('#name-error').text(response.responseJSON.errors.name);
                    $('#jdate-error').text(response.responseJSON.errors.jdate);
                    $('#image-error').text(response.responseJSON.errors.image);
                }
            });
        });
    </script>
</body>