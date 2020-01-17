<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;
// page for the main listing of all products
?>
<!DOCTYPE html>
<html>
<head>
   <?= $this->Html->charset() ?>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>
      All products
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
      All products <br />
      <!-- generate table consisting of all products that are in the shop, supports sorting-->
      <table>
         <tr>
            <td>Image</td>
            <td><?php echo $this->Paginator->sort('id',"ID")?></td>
            <td><?php echo $this->Paginator->sort('name',"Name")?></td>
            <td><?php echo $this->Paginator->sort('description',"Description")?></td>
            <td><?php echo $this->Paginator->sort('price',"Price")?></td>
            <td><?php echo $this->Paginator->sort('amount',"Amount left")?></td>
         </tr>
         <?php
            // generate the rows for the table
            foreach ($pId as $row):
               $curAmount = $row->amount; // show only the max amount the user can still add to their cart
               if(isset($cart["$row->id"]) &&$cart["$row->id"]>0)$curAmount-=$cart["$row->id"];
               echo "<tr class='alltr' onClick= \"window.location='/products/".htmlspecialchars($row->id,ENT_QUOTES)."';\"><td><img src='".htmlspecialchars($row->picture,ENT_QUOTES)."' alt='".htmlspecialchars($row->name,ENT_QUOTES)."' width='40' height='40'></td> "; // turn the whole row to a link
               echo "<td>".htmlspecialchars($row->id,ENT_QUOTES)."</td>";
               echo "<td>".htmlspecialchars($row->name,ENT_QUOTES)."</td>";
               echo "<td>".htmlspecialchars($row->description,ENT_QUOTES)."</td>";
               echo "<td>".htmlspecialchars($row->price,ENT_QUOTES)."</td>";
               echo "<td>".htmlspecialchars($curAmount,ENT_QUOTES)."</td></tr>";
            endforeach;
         ?>
      </table>
   </div>
</body>
</html>
