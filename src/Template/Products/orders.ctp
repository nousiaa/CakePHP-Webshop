<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;

// page for displaying all orders made by the user
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            My orders
        </title>
        <?= $this->Html->meta('icon') ?>
        <?= $this->Html->css('base.css') ?>
        <?= $this->Html->css('style.css') ?>
        <?= $this->Html->css('product.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
    </head>
    <body>
        <?php echo $this->element('topbar'); //add our top bar to the page ?>
        <div class="main-div">
            My orders:
            <!-- generate a table containing all the orders made by the user viewing the page --> 
            <table>
                <tr>
                    <td>ID</td>
                    <td>Status</td>
                    <td>Order date</td>
                </tr>
                <?php
                    // generate the table rows based on order information
                    foreach ($orders as $row):
                        echo "<tr class='alltr' onClick= \"window.location='/products/orders/".$row->id."';\">"; // make rows links to the specific orders
                        echo "<td>".$row->id."</td>";
                        echo "<td>".$row->status."</td>";
                        echo "<td>".$row->created->setTimezone('Europe/Helsinki')->i18nFormat('dd/MM/YYYY HH:mm')."</td>";
                        echo "</tr>";
                    endforeach;
                ?>
            </table>
        </div>
    </body>
</html>
