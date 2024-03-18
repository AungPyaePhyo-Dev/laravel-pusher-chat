<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Chat</title>
    <style type="text/css">
        .message p{
            border-radius: 10px;
            padding: 10px 20px 10px 8px;
            margin-top: 5px; 
            display: inline-block;
            width: auto;
            margin: 0px;  
        }
        .message-receive p{ 
            background: #e0e3e6;
            color: #2f2d2d;
        }
        .message-receive{
            margin-top: 5px;
        }
        .message-send p{
            background: #435f7a;
            color: #f5f5f5;
        }
        .message-send{
            text-align: right;
            margin-top: 5px;
        }

        .message-send p{
            text-align: left;
        }

        .scrollable {
            overflow: hidden;
            overflow-y: scroll;
            height: calc(100vh - 25vh);
            scroll-behavior: smooth;
        }

        .message-input{
            border: none;
            border-radius: 0px;
            background: #f2f2f2;
        }

        .search-input {
            /* background: #bbb; */
            background: #cdd5dd;
            border:none;
            font-size: 12px;
            /* padding: 5px 10px; */
        }

        .search-input-group {
            background: #cdd5dd;
            padding: 0;
            border-radius: 5px;
        }

        .search-input:focus {
            outline: none;
        }
        
    </style>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div id="app">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html><?php /**PATH /home/aung/Project/Practice/laravel-pusher-chat/resources/views/layouts/app.blade.php ENDPATH**/ ?>