<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;
//register page
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            Register account
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
            <?php echo $this->Form->create($user,['action' => 'register']);  // our register form
                echo __('Add User');
                echo $this->Form->input('username', ['autofocus' => true]);
                echo $this->Form->input('password');
                echo $this->Form->input('name');
                echo $this->Form->input('address');
                echo $this->Form->button(__('Submit'));
                echo $this->Form->end(); 
            ?>
        </div>
    </body>
</html>

