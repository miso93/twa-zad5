<?php require_once "function.php" ?>
<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Zadanie č.5 | Michal Čech</title>

    <!-- Bootstrap -->
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">

    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico'/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-header">
                <h1>Zadanie č.5 - CURL
                    <small>vypracoval Michal Čech</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-sm-offset-2">
            <form class="form form-horizontal" id="form" method="post">
                <div class="form-group">
                    <label class="control-label" for="psc">PSC</label>
                    <input class="form-control" type="text" placeholder="08001" name="psc" id="psc"
                           value="<?php echo(isset($_POST['psc']) ? $_POST['psc'] : '') ?>">
                </div>
                <div class="form-group text-right">
                    <input type="submit" value="Hľadať benzínove pumpy" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
    <?php
    if (isset($_POST['psc'])):
        $city = getCityByPscPsc($_POST['psc']);

        $stations = getFillingStationByCityName($city);

        ?>

        <h1>Vyhľadávanie benzínovej pumpy pre mesto: <?php echo $city ?></h1>

        <table class="table table-responsive table-striped">
            <thead>
            <tr>
                <th>Spoločnosť</th>
                <th>Ulica</th>
                <th>Cena</th>
                <th>Aktualizované dňa</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($stations as $station): ?>
                <tr>
                    <td><?php echo $station->company; ?></td>
                    <td><strong><?php echo $station->place; ?></strong><br><?php echo $station->street; ?></td>
                    <td><img src="http://benzin.sk/<?php echo $station->price_img_url ?>"> €</td>
                    <td><?php echo $station->updated_at; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>


    <?php endif; ?>

</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

    });
</script>
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>
</body>
</html>