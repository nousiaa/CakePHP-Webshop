<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;
// page for showing specific information about a order
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            Show order No.   <?php echo $orderInfo->id ?>
        </title>
        <?= $this->Html->meta('icon') ?>
        <?= $this->Html->css('base.css') ?>
        <?= $this->Html->css('style.css') ?>
        <?= $this->Html->css('product.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <?php echo $this->element('topbar'); //add our top bar to the page ?>
        <div class="main-div">
            This order: <br />
            <b>Order ID: </b><?php echo " ".$orderInfo->id." " ?> <b>Order date:</b><?php echo " ".$orderInfo->created->setTimezone('Europe/Helsinki')->i18nFormat('dd/MM/YYYY HH:mm') ?>
            <!-- generate table containing all items that were ordered -->
            <table>
                <tr>
                    <td>Image</td>
                    <td>ID</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Amount Ordered</td>
                </tr>
                    <?php
                    // generate table rows
                    foreach ($prod as $row):
                        if(isset($orders["$row->id"]) &&$orders["$row->id"]>0){ // select only products that are in this order
                        echo "<tr class='alltr' onClick= \"window.location='/products/".htmlspecialchars($row->id,ENT_QUOTES)."';\"><td><img src='".htmlspecialchars($row->picture,ENT_QUOTES)."' alt='".htmlspecialchars($row->name,ENT_QUOTES)."' width='40' height='40'></td> "; // turn the whole row to a link
                        echo "<td>".htmlspecialchars($row->id,ENT_QUOTES)."</td>";
                        echo "<td>".htmlspecialchars($row->name,ENT_QUOTES)."</td>";
                        echo "<td>".htmlspecialchars($row->description,ENT_QUOTES)."</td>";
                        echo "<td>".$orders["$row->id"]."</td>";    
                        echo "</tr>";
                        }
                        endforeach;
                    ?>
            </table>
        </div>
    </body>
</html>
