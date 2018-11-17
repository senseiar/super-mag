<?php

class CartController
{
    public function actionAdd($id)
    {
        Cart::addProduct($id);

        $referer = $_SERVER['HTTP_REFERER'];
        header("Location: $referer");
    }

    public function actionDelete($id)
    {
        Cart::deleteProduct($id);
        header("Location: /cart");
    }

    public function actionAddAjax($id)
    {
        //add product to cart
        echo Cart::addProduct($id);
        return true;
    }

    public function actionIndex()
    {
        $categories = array();
        $categories = Category::getCategoriesList();

        $productsInCart = false;

        $productsInCart = Cart::getProducts();

        if ($productsInCart) {
            $productsIds = array_keys($productsInCart);
            $products = Product::getProductByIds($productsIds);

            $totalPrice = Cart::getTotalPrice($products);
        }

        require_once(ROOT . '/views/cart/index.php');

        return true;
    }

    public function actionCheckout()
    {
        $categories = array();
        $categories = Category::getCategoriesList();

        //Статус успешного оформления заказа
        $result = false;

        if (isset($_POST['submit'])) {
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];

            //Валидация
            $errors = false;
            if (!User::checkName($userName))
            {
                $errors[] = "Неправильное имя";
            }
            elseif (!User::checkPhone($userPhone))
            {
                $errors[] = "Неправильный телефон";
            }

            //Если форма заполнена корректно
            if ($errors == false) {
                $productsInCart = Cart::getProducts();

                if (User::isGuest()) {
                    $userId = false;
                } else {
                    $userId = User::checkLogged();
                }

                $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);

                if ($result) {
                    //оповещаем админа и чистим корзину
                    $adminEmail = 'aratkin77@gmail.com';
                    $message = '/admin/orders';
                    $subject = 'Новый заказ!';
                    mail($adminEmail, $subject ,$message);

                    Cart::clear();

                }
            } else {
                
                $productsInCart = Cart::getProducts();
                $productsIds = array_keys($productsInCart);
                $products = Product::getProductByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();

            }

        } else {

            $productsInCart = Cart::getProducts();

            if ($productsInCart == false) {
                header("Location: /");
            } else {

                $productsIds = array_keys($productsInCart);
                $products = Product::getProductByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();

                
                $userName = false;
                $userPhone = false;
                $userComment = false;

                if (User::isGuest()){
                    //Значения формы пустые
                } else {

                    $userId = User::checkLogged();
                    $user = User::getUserById($userId);

                    $userName = $user['name'];
                }
            }
        }

        require_once(ROOT . '/views/cart/checkout.php');

        return true;

    }
}