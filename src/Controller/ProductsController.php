<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use App\Controller\AppController;
use Cake\Event\Event;


class ProductsController extends AppController
{

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // set pages that can be accessed without login
        $this->Auth->allow(['index','product','search','basket','addToCart','results','clearCart']);
    }

    // our products page
    public function index()
    {
        // get the current shopping cart
        $cart = $this->getRequest()->getSession()->read('cart'); 
        $this->set('cart', $cart);

        // add sorting
        $this->paginate = array( 
            'fields' => array('picture','id','name','description','price','amount'), 
        );
        $product = $this->paginate('Product');
        $this->set('pId', $product);
        
        // get the current user
        $this->set('authUser', $this->Auth->user());
    }

    // product page
    public function product()
    {
        // get product id
        $pId =  $this->request->params['id'];

        // get the current shopping cart
        $cart = $this->getRequest()->getSession()->read('cart');
        $decCount = 0;
        //set the product amount that is available
        if(isset($cart["$pId"])){
            $decCount = $cart["$pId"];
        } 
        // get the wanted product
        $product = TableRegistry::get('product')->find()->where(['id'=>$pId])->first();
        if(!$product) return $this->redirect(['action' => 'index']);
        $this->set('pId', $product);
        $this->set('decCount', $decCount);

        // get the current user
        $this->set('authUser', $this->Auth->user());
    }

    // search page
    public function search()
    {
        $product = TableRegistry::get('product')->find();
        $this->set('pId', $product);
        // get the current user
        $this->set('authUser', $this->Auth->user());
    }

    // search results page
    public function results()
    {
        $cart = $this->getRequest()->getSession()->read('cart');
        $this->set('cart', $cart);
        $query = $this->request->query('query');
        $searchBy = $this->request->query('searchBy');
        $filter = 'name';
        $queryString="%".$query."%";
        if ($searchBy=="1") {
            $filter ='id';
            $queryString= (int)$query;
        }
        $this->set('prm', $query);
        //$product = TableRegistry::get('product')->find('all', ['conditions'=>['product.name LIKE'=>'%'.$query.'%']]);
        $this->paginate = array(
            'fields' => array('picture','id','name','description','price','amount'), 
            'conditions' => array($filter." LIKE" => $queryString),
        );
        $product = $this->paginate('Product');
        //$this->set(compact('data'));
    
        $this->set('pId', $product);
        // get the current user
        $this->set('authUser', $this->Auth->user());

    }

    // add new product page
    public function add()
    {
        // allow adding products only if the user's role is set as admin (A), this must be set manually to the database at this time
        if($this->request->is('post') && $this->Auth->user('role') === 'A'){
            // get the parameters after they are submitted
            $name = $this->request->data('name');
            $description =  $this->request->data('description');
            $picture  = $this->request->data('picture');
            // if no picture url is given, default to missing image backup
            if($picture == "") $picture = "/notfound.jpg";
            $price  = $this->request->data('price');
            $amount  = $this->request->data('amount');

            // get the product table
            $product_table = TableRegistry::get('product');
            // create new row
            $nprod = $product_table->newEntity();
            // add the wanted data
            $nprod->name = $name;
            $nprod->description = $description;
            $nprod->picture = $picture;
            $nprod->price = $price;
            $nprod->amount = $amount;
            // save the product
            if($product_table->save($nprod)) echo "Product added.";
         }
         // get the current user
         $this->set('authUser', $this->Auth->user());
    }
    
    //shopping cart page
    public function basket()
    {
        // get all products
        $product = TableRegistry::get('product')->find();
        $this->set('prod', $product);
        // get the user's shopping cart
        $cart = $this->getRequest()->getSession()->read('cart');
        $this->set('items', $cart);
        // get the current user
        $this->set('authUser', $this->Auth->user());
    }

    // function for adding items to the shopping cart via ajax
    public function addToCart()
    {   
        // is the request ajax?
        if( $this->request->is('ajax') ) {
            // get parameters (product id and wanted amount)
            $id = $this->request->data('id');
            $amount = $this->request->data('amount');
            $msg = "cant add";
            // get the product
            $product = TableRegistry::get('product')->find()->where(['id'=>$id])->first();
            // get the cart
            $cart = $this->getRequest()->getSession()->read('cart');
            // if trying to add 0 or negative amount of items to cart, remove the item compleately from the cart
            if($amount<1){
                $cart["$id"] = null;
                unset($cart["$id"]);
            } else {
                // try to add the item to the cart if there is enough of the product available
                if(isset($cart["$id"])){
                    if($product->amount>=($cart["$id"]+$amount)){
                        $cart["$id"] += $amount;
                        $msg = "Added to cart";
                    }
                } else {
                    if($product->amount>=$amount){
                        $cart["$id"] = $amount;
                        $msg = "Added to cart";
                    } 
                }
            }
            // update the cart
            $this->getRequest()->getSession()->write('cart',$cart);
            echo json_encode(["msg"=>$msg,"amount"=>$product->amount-$cart["$id"]]);
            return;
        }
    }

    // page for showing all orders made by the user that is logged in
    public function orders()
    {
        // get all the user's orders
        $orders = TableRegistry::get('orders')->find()->where(['user_id'=>$this->Auth->user('id')]);
        $this->set('authUser', $this->Auth->user());
        $this->set('orders',$orders);
    }

    // page for showing information about a specific order
    public function showOrder(){
        // get parameter (order id)
        $oId =  $this->request->params['id'];
        // get the order if it is ordered by this user
        $orders = TableRegistry::get('orders')->find()->where(['user_id'=>$this->Auth->user('id'),'id'=>$oId])->first();
        if(!$orders) return $this->redirect(['action' => 'orders']);
        $order = json_decode($orders->products,true);
        // get list of all products
        $product = TableRegistry::get('product')->find();
        $this->set('prod', $product);
        $this->set('orderInfo',$orders);
        $this->set('orders',$order);
        // get the current user
        $this->set('authUser', $this->Auth->user());

    }

    // function for sending a order via ajax
    public function sendOrder()
    {
        // is request ajax?
        if( $this->request->is('ajax') ) {
            // get parameters for order
            $items = $this->request->data('items');
            // make a new order
            $order_table = TableRegistry::get('orders');
            $nprod = $order_table->newEntity();
            // fill the new order with data
            $nprod->user_id = $this->Auth->user('id');
            $nprod->products = $items;
            $nprod->created = date("Y-m-d H:i:s");
            $allItemIds = json_decode($items,true);
            $product_table = TableRegistry::get('product');
            
            // check if there is enough of all products needed in stock, stop if not 
            foreach (array_keys($allItemIds) as $item){
                $mprod = $product_table->get($item);
                if (($mprod->amount-$allItemIds["$item"])<0){
                    echo "Order failed item not available:".$mprod->name;
                    return null;
                }
            }
            // enough availability, decrement the products' amounts in the database
            foreach (array_keys($allItemIds) as $item){
                $mprod = $product_table->get($item);
                $mprod->amount =  $mprod->amount-$allItemIds["$item"];
                $product_table->save($mprod);
            }
            // save the order
            if($order_table->save($nprod)){
                $this->getRequest()->getSession()->write('cart',[]);
                echo "Ordered.";

            }
            else // something went wrong, couldn't compleate order
            echo  "order failed";
        }
    }

    // empty the shopping cart via ajax
    public function clearCart()
    {
        // is request ajax?
        if( $this->request->is('ajax') ) {
            // empty the cart
            $this->getRequest()->getSession()->write('cart',[]);
            echo "cleared";
        }
    }
}
