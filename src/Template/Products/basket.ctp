<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;
// page for the shopping cart
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            Shopping cart
        </title>
        <?= $this->Html->meta('icon') ?>
        <?= $this->Html->css('base.css') ?>
        <?= $this->Html->css('style.css') ?>
        <?= $this->Html->css('product.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                <?php if (!isset($items) || count($items) === 0): // disable order button if cart is empty?>
                    $('#order').attr("disabled", true);
                <?php endif;?>
            });
            // remove item from shopping cart
            function remove(value){
                var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
                $.ajax({
                    type:"POST",
                    data:{id:value, amount:0}, 
                    url:"/products/addToCart",
                    headers: { 'X-CSRF-Token': csrfToken },
                    success : function(data) {
                        location.reload();
                    },
                    error : function(data) {
                        alert("Could not remove item from shopping cart");
                    }
                });
            }
            // clear the cart compleately
            function removeAll(){
                var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
                $.ajax({
                    type:"POST",
                    data:{all:''}, 
                    url:"/products/clearCart",
                    headers: { 'X-CSRF-Token': csrfToken },
                    success : function(data) {
                        location.reload();
                    },
                    error : function(data) {
                        alert("Could not empty shopping cart");
                    }
                });
            }
            // try to order all items in cart
            function orderItems(){
                <?php if($items): ?>
                    <?php if(!$authUser): // allow to order only if logged in ?>
                        return alert("Please login to order!"); 
                    <?php else: ?>
                        var items = '<?php echo json_encode($items) ?>';
                        var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
                        $.ajax({
                            type:"POST",
                            data:{items}, 
                            url:"/products/sendOrder",
                            headers: { 'X-CSRF-Token': csrfToken },
                            success : function(data) {
                                // message returned from server, can also be a ordering failure
                                alert(data);
                                location.reload();
                            },
                            error : function(data) {
                                alert("Could not order");
                            }
                        });
                    <?php endif;?>
                <?php endif;?>
            }
        </script>
    </head>
    <body>
        <?php echo $this->element('topbar'); //add our top bar to the page ?>
        <div class="main-div">
            Shopping cart:
            <!-- display a table containing all selected items -->
            <table>
                <tr>
                    <td>Image</td>
                    <td>ID</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Amount left</td>
                    <td>Amount Selected</td>
                    <td>Remove</td>
                </tr>
                <?php
                    $tot_price = 0;
                    $tot_amount = 0;
                    foreach ($prod as $row):
                        if(isset($items["$row->id"]) &&$items["$row->id"]>0){ // display only selected items
                        $tot_amount+=$items["$row->id"]; // increment total amount and price
                        $tot_price+=$items["$row->id"]*$row->price;
                        // generate table row
                        echo "<tr class='alltr' onClick= \"window.location='/products/".htmlspecialchars($row->id,ENT_QUOTES)."';\"><td><img src='".htmlspecialchars($row->picture,ENT_QUOTES)."' alt='".htmlspecialchars($row->name,ENT_QUOTES)."' width='40' height='40'></td> "; // turn the whole row to a link
                        echo "<td>".htmlspecialchars($row->id,ENT_QUOTES)."</td>";
                        echo "<td>".htmlspecialchars($row->name,ENT_QUOTES)."</td>";
                        echo "<td>".htmlspecialchars($row->description,ENT_QUOTES)."</td>";
                        echo "<td>".htmlspecialchars($row->price,ENT_QUOTES)."</td>";
                        echo "<td>".htmlspecialchars(($row->amount-$items["$row->id"]),ENT_QUOTES)."</td>";
                        echo "<td>".htmlspecialchars($items["$row->id"],ENT_QUOTES)."</td>";
                        echo "<td> ".$this->Form->button('X',["type"=>"submit", "class"=>"remove-button","onclick"=>"event.stopPropagation(); remove(".$row->id.")"])."</td></tr>";
                        }
                    endforeach;
                    // generate table row for total values
                    echo "<tr style='background-color:#b9b9b9;'><td> <b>TOTAL</b></td> ";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td><b>".$tot_price."</b></td>";
                    echo "<td></td>";
                    echo "<td><b>".$tot_amount."</b></td>";
                    echo "<td></td></tr>";
                ?>
            </table>
            <?php echo $this->Form->button('Order',["type"=>"submit","id"=>"order","onclick"=>"orderItems()","style"=>"float: right; margin-right:2em;"]); //our order button ?>
            <?php echo $this->Form->button('Empty Cart',["type"=>"submit","onclick"=>"removeAll()","style"=>"float: right; margin-right:2em;"]); //our clear cart button ?>
        </div>
    </body>
</html>
