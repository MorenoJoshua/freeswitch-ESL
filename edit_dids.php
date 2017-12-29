<?php
$db = new PDO('mysql:host=crm.crdff.net', 'josh', 'espada98');
$query = <<<MYSQL
select did, ext from crm.users where did != '' and status = 1 group by did order by id desc ;
MYSQL;
$dids = '';
$todump = <<<HTML
<?php
\$did = array(

HTML;
foreach ($db->query($query)->fetchAll() as $row) {
    $todump .= <<<HTML
1{$row['did']} => {$row['ext']},

HTML;
    $dids .= <<<HTML
        			<tr>
        				<td>{$row['did']}</td>
        				<td>{$row['ext']}</td>
        			</tr>

HTML;
}
$todump .= ');';

$myFile = "dids.php";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $todump);
fclose($fh);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Did updater</title>
        <meta charset="UTF-8">
        <meta name=description content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" media="screen">
    </head>
    <body>
        <div class="container">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>Success!!</strong> DIDs were updated
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>DID</th>
                            <th>Extension</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $dids ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Bootstrap JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </body>
</html>
