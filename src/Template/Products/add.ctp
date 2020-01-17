<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;

// page for adding new products to the shop

?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            Add a product
        </title>

        <?= $this->Html->meta('icon') ?>
        <?= $this->Html->css('base.css') ?>
        <?= $this->Html->css('style.css') ?>
        <?= $this->Html->css('product.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
    </head>
    <body>
        <?php echo $this->element('topbar'); //add our top bar to the page ?>
        <div class="main-div" >
            Add product <br />
            <?php
                // our product adding form
                echo $this->Form->create(null,['url' => ['controller' => 'Products', 'action' => 'add']]); 
                echo $this->Form->input('name');
                echo $this->Form->input('description');
                echo $this->Form->input('picture');
                echo $this->Form->input('price');
                echo $this->Form->input('amount');
                echo $this->Form->button('Add product');
                echo $this->Form->end();
            ?>
        </div>
    </body>
</html>
