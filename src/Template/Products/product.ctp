<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;

// page for displaying a product
?>
<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            Product page - <?php echo $pId->name ?>
        </title>
        <?= $this->Html->meta('icon') ?>
        <?= $this->Html->css('base.css') ?>
        <?= $this->Html->css('style.css') ?>
        <?= $this->Html->css('product.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
        <script>
            $(document).ready(function () {
                <?php if (($pId->amount-$decCount)<=0): // if the product is out of stock, disable adding it to the cart?>
                    $('.order').attr("disabled", true);
                <?php endif;?>
            });
            // function for adding a product to the shopping cart (and the wanted amount)
            function add(){
                var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
                $.ajax({
                    type:"POST",
                    data:{id:<?php echo $pId->id ?>,amount:$('#amount').val()}, 
                    url:"/products/addToCart",
                    headers: { 'X-CSRF-Token': csrfToken },
                    success : function(data) {
                        var obj = JSON.parse(data);
                        $('#lAmount').html(obj.amount); // decrement the amount left that is visible on the page
                        if(obj.amount === 0) {
                            $('.order').attr("disabled", true);
                        }
                        alert(obj.msg);
                    },
                    error : function(data) {
                        alert("Could not add item to shopping cart");
                    }
                });
            }
        </script>
    </head>
    <body>
        <?php echo $this->element('topbar'); //add our top bar to the page ?>
        This product: <br />

        <div class="main-div">
            <div style= "display: inline-block; float: left;">

                <?php // show the product picture
                    echo "<img src='".$pId->picture."' alt='".$pId->name."' width='400' height='400'>";
                ?>
            </div>
            <div style= "display: inline-block;float: left; margin-left: 10px;">
                <table style = "width: auto; margin-top: 0; border: solid; border-color: #F5F5F5;">
                    <?php // generate the product information
                        echo "<tr><td>Id:</td><td>".htmlspecialchars($pId->id,ENT_QUOTES)."</td></tr>";
                        echo "<tr><td>Name:</td><td>".htmlspecialchars($pId->name,ENT_QUOTES)."</td></tr>";
                        echo "<tr><td>Description:</td><td>".htmlspecialchars($pId->description,ENT_QUOTES)."</td></tr>";
                        echo "<tr><td>Price:</td><td>".htmlspecialchars($pId->price,ENT_QUOTES)."</td></tr>";
                        echo "<tr><td>Amount left:</td><td id='lAmount'>".htmlspecialchars(($pId->amount-$decCount),ENT_QUOTES)."</td></tr>";
                    ?> 
                </table>
                <?php 
                    // controls for adding products to the cart
                    echo $this->Form->input('amount',["type"=>"number","class"=>"order","min"=>1,"value"=>1, "max"=>$pId->amount]);
                    echo $this->Form->button('Add to cart',["class"=>"order","onclick"=>"add()"]);
                ?>
            </div>
        </div>
    </body>
</html>
