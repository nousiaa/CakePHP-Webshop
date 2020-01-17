<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;
// page for searching
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            Search
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
            Search
            <?php
                // our search form (supports searching by name or id)
                echo $this->Form->create(null,['url' => ['controller' => 'Products', 'action' => 'results'],'type' => 'get']); 
                echo $this->Form->input('query');
                echo $this->Form->input('searchBy', array('label'=>'Search by', 'type'=>'select', 'options'=>['name','id']));
                echo $this->Form->button('Submit Search');
                echo $this->Form->end();
            ?>
        </div>
    </body>
</html>
