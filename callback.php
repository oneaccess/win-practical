<?php
session_start();

if (isset($_GET['clear'])) {
    session_destroy();
    header('Location: ./');
}

if (!empty($_POST['token'])) {
    $_SESSION['ACCESS_TOKEN'] = $_POST['token'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LIFF EDITOR</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<style>
    .lifflink { cursor: copy; }
    .btn-clear {position: fixed; top:10px; right: 10px;}
</style>
</head>

<body>
<div class="container">
    <a class="btn btn-xs btn-danger btn-clear" href="?clear=yes">[ clear session ]</a>

    <?php if (!empty($_SESSION['ACCESS_TOKEN'])) : ?>

    <h1 class="text-center">LIFF EDITOR</h1>
    <p class="add-comment"></p>

    <?php

        $GET_MODE = (isset($_GET['mode'])) ? $_GET['mode'] : '';

        $select = [
            'compact' => '50% compact',
            'tall' => '75% tall',
            'full' => '100% full',
            'cover' => 'No title cover'
        ];

        // INIT
        $curl = curl_init();

        if ($GET_MODE == 'delete') {

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.line.me/liff/v1/apps/".$_GET['liffid'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$_SESSION['ACCESS_TOKEN'],
                )
            ));

            $response = curl_exec($curl);

            if (curl_error($curl)) {
                echo "cURL Error #:" . curl_error($curl);
            } else {
                echo '
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Deleted!</strong> '.$_GET['liffid'].'
                </div>
                <script>setTimeout(function(){location.href="./"} , 3000);</script>';
            }
        }

        if (!empty($_POST['mode']) && $_POST['mode'] === 'update') {

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.line.me/liff/v1/apps/".$_POST['liffid']."/view",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => '{"type": "'.$_POST['type'].'", "url": "'.$_POST['url'].'"}',
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$_SESSION['ACCESS_TOKEN'],
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);

            if (curl_error($curl)) {
                echo "cURL Error #:" . curl_error($curl);
            } else {
                echo '
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Updated!</strong> '.$_POST['liffid'].'
                </div>';
            }
        }

        if (!empty($_POST['mode']) && $_POST['mode'] === 'add') {

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.line.me/liff/v1/apps",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => '{"view":{"type": "'.$_POST['type'].'", "url": "'.$_POST['url'].'"}}',
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$_SESSION['ACCESS_TOKEN'],
                    "Content-Type: application/json"
                ),
            ));
            $response = curl_exec($curl);

            if (curl_error($curl)) {
                echo "cURL Error #:" . curl_error($curl);
            } else {
                echo '
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>NEW LIFF!</strong> '.$response.'
                </div>';
            }
        }

        //  LIST ALL LIFF APP
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.line.me/liff/v1/apps",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$_SESSION['ACCESS_TOKEN'],
            ),
        ));

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            echo "cURL Error #:" . curl_error($curl);
        } else {
            $json = json_decode($response);
            // echo '<pre>',print_r($json),'</pre> ';
            foreach ($json->apps as $key => $value) {

                $opt = '';
                foreach ($select as $val => $name) {
                    $selected = ($value->view->type == $val) ? 'selected' : '';
                    $opt .= '<option value="'.$val.'" '.$selected.'>'.$name.'</option>';
                }
                echo '
                <div class="list-group-item" id="row'.$key.'">
                    <div class="row">
                        <form action="" method="POST" name="form'.$value->liffId.'">
                            <input type="hidden" name="mode" value="update">
                            <div class="col-sm-3">
                                <input id="liffid'.$key.'" name="liffid" type="text" class="form-control tt lifflink" value="'.$value->liffId.'" readonly
                                    data-toggle="tooltip" data-placement="top" title="Click to copy">
                            </div>
                            <div class="col-sm-2">
                                <select id="type'.$key.'" name="type" class="form-control">
                                    '.$opt.'
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <input id="url'.$key.'" name="url" type="text" class="form-control" value="'.$value->view->url.'">
                            </div>
                            <div class="col-sm-2 text-right">
                                <div class="btn-group btn-group-justified" role="group">
                                    <div class="btn-group " role="group ">
                                        <button type="submit" class="update btn btn-warning">Edit</button>
                                    </div>
                                    <div class="btn-group " role="group ">
                                        <a href="?mode=delete&liffid='.$value->liffId.' " class="delete btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>';
            }
        }

        curl_close($curl);
    ?>
        <!-- ADD LIFF -->
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Add LIFF</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <form action="" method="POST" role="form">
                        <input type="hidden" name="mode" value="add">
                        <div class="col-sm-3">
                            <select name="type" id="type" class="form-control" required="required">
                            <?php
                                foreach ($select as $val => $name) {
                                    echo '<option value="'.$val.'">'.$name.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-7">
                            <div class="form-group">
                                <input type="text" class="form-control" name="url" placeholder="https://">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-block btn-success  ">Submit</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--// ADD LIFF -->

    <?php else: ?>

        <!-- ADD ACCESS TOKEN -->
        <div class="jumbotron">
        <h1>Your Channel access token</h1>
        <p><a href="https://developers.line.me/console/">https://developers.line.me/console/</a></p>
        <form action="./" method="POST" class="form-inline" role="form">
            <input type="text" class="form-control input-lg" id="token" name="token" placeholder="Access Token">
            <button type="submit" class="btn btn-lg btn-primary">Submit</button>
        </form>
        </div>
        <!--// ADD ACCESS TOKEN -->

    <?php endif; ?>

</div>

<!-- Latest compiled and minified JS -->
<script src="//code.jquery.com/jquery.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
$(function () {
    $('.lifflink').on('click', function () {
        let liff = $(this).val();
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(`line://app/${liff}`).select();
        document.execCommand("copy");
        $temp.remove();

        $('<span />', { style: 'display:none' })
            .html( `<span class="label label-success label-copy">copied! line://app/${liff}</span>` )
            .appendTo($(this).offsetParent())
            .fadeIn('slow', function () {
                    var el = $(this);
                    setTimeout( function () {
                        el.fadeOut('slow',
                            function () {
                                $(this).remove();
                            });
                    }, 3000);
                });
    });
    $(document).on('mouseenter', '.tt', function () {
        $('.tt').tooltip();
    });
});
</script>
</body>
</html>
