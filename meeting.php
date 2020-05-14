
<!DOCTYPE html>
<head>
    <title>Zoom WebSDK</title>
    <meta charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.7.7/css/bootstrap.css"/>
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.7.7/css/react-select.css"/>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>
<style>
    body {
        padding-top: 50px;
    }
</style>

<?php 

if($_REQUEST['meeting_status']){
    $meeting_status = $_REQUEST['meeting_status'];
    $meeting_id = $_REQUEST['meeting_id'];    ?>

    <nav id="nav-tool" class="navbar navbar-inverse navbar-fixed-top">
        <input type="hidden" name="display_name" id="display_name" value="<?php echo 'display_name'.$meeting_id;?>" placeholder="Meeting Number" class="form-control" required>
        <input type="hidden" name="meeting_number" id="meeting_number" value="<?php echo $meeting_id;?>" placeholder="Meeting Number" class="form-control" required>

        <input type="hidden" id="meeting_status" value="<?php echo $meeting_status;?>" />
    </nav>

<?php } ?>


<script src="https://source.zoom.us/1.7.7/lib/vendor/react.min.js"></script>
<script src="https://source.zoom.us/1.7.7/lib/vendor/react-dom.min.js"></script>
<script src="https://source.zoom.us/1.7.7/lib/vendor/redux.min.js"></script>
<script src="https://source.zoom.us/1.7.7/lib/vendor/redux-thunk.min.js"></script>
<script src="https://source.zoom.us/1.7.7/lib/vendor/jquery.min.js"></script>
<script src="https://source.zoom.us/1.7.7/lib/vendor/lodash.min.js"></script>

<script src="https://source.zoom.us/zoom-meeting-1.7.7.min.js"></script>
<script src="js/tool.js"></script>
<script src="js/index.js"></script>

<script>
   
</script>
</body>
</html>