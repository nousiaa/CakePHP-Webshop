<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;

// profile page
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            My Profile
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
            My information:
            <!-- table for showing the user's own information -->
            <table style="width: auto; border: solid; border-color: #F5F5F5;">
                <tr>
                    <td>Username:</td>
                    <td><?php echo $authUser["username"] ?></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><?php echo $authUser["name"] ?></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><?php echo $authUser["address"] ?></td>
                </tr>
            </table>
        </div>
    </body>
</html>
