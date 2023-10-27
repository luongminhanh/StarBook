<?php
// if (empty($_SESSION['cart'])) {
//     $_SESSION['cart'] = array();
// }
// if (isset($_GET['action'])) {
//     $id = isset($_GET['id']) ? $_GET['id'] : '';
//     switch ($_GET['action']) {
//         case 'add':
//             // if product is already in cart we increase its quantity else the quantity of it is 1
//             if (array_key_exists($id, array_keys($_SESSION['cart']))) {
//                 $_SESSION['cart'][$id]++;
//                 // change link or when reload page the quantity will increase
//                 header("location: ?option=cart");
//             } else {
//                 $_SESSION['cart'][$id] = 1;
//                 header("location: ?option=cart");
//             }
//             break;
//         case 'delete':
//             unset($_SESSION['cart'][$id]);
//             break;
//         case 'delete_all':
//             unset($_SESSION['cart']);
//             break;
//         case 'update':
//             if ($_GET['type'] == 'asc')
//                 $_SESSION['cart'][$id]++;
//             else if ($_GET['type'] == 'dec')
//                 if ($_SESSION['cart'][$id] > 1) $_SESSION['cart'][$id]--;
//             header("location: ?option=cart");
//             break;
//         case 'order':
//             if (isset($_SESSION['member'])) {
//                 header("location: ?option=order");
//             } else {
//                 header("location: ?option=signin&order=1");
//             }
//             break;
//     }
// }
//
if (isset($_SESSION['member'])) {
    $query  = "select * from `member` where `username`='" . $_SESSION['member'] . "'";
    $member = mysqli_fetch_array($connect->query($query));
    $memberId = $member['id'];
    if (isset($_GET['action'])) {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $queryProducts = "select * from `products` where `id` = $id";
        $result1 = $connect->query($queryProducts);
        if ($result1 && mysqli_num_rows($result1) > 0) {
            $product = mysqli_fetch_array($result1);
            $productId = $product['id'];
            $productName = $product['name'];
            $productImage = $product['image'];
            $productPrice = $product['price'];
        }
        switch ($_GET['action']) {
            case 'add':
                $queryProductsInCart = "select * from `cart` where `product_id` = $productId and `member_id` = $memberId";
                if (mysqli_num_rows($connect->query($queryProductsInCart)) != 0) {
                    $connect->query("update `cart` " . " set `quantity` = `quantity` + 1 where `product_id` = " . $productId . " and `member_id` = " . $memberId);
                    header("location: ?option=cart");
                } else {
                    $connect->query("insert into cart (product_id, product_name, product_price, product_image, member_id, quantity) values ($productId, '$productName', $productPrice, '$productImage', $memberId, 1)");
                    header("location: ?option=cart");
                }
                break;
            case 'delete':
                $connect->query("delete from cart where product_id = " . $_GET['id'] . " and member_id = " . $memberId);
                break;
            case 'delete_all':
                echo $memberId;
                $result = $connect->query("delete from cart where member_id = " . $memberId);
                break;
            case 'update':
                if ($_GET['type'] == 'asc')
                    $connect->query("update cart " . " set quantity = quantity + 1 where product_id = " . $_GET['id'] . " and member_id = " . $memberId);
                else if ($_GET['type'] == 'dec')
                    $connect->query("update cart " . " set quantity = quantity - 1 where product_id = " . $_GET['id'] . " and member_id = " . $memberId);
                header("location: ?option=cart");
                break;
        }
    }
} else {
    header("location: ?option=signin");
}
?>

<?php
$queryCart = "select * from cart where member_id = $memberId";
$productsInCart = $connect->query($queryCart);
?>


<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="../images/background_show_products.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Giỏ Hàng</h2>
                    <div class="breadcrumb__option">
                        <a href="?option=home">Trang Chủ</a>
                        <span>Giỏ hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Shoping Cart Section Begin -->
<section class="shoping-cart spad">
    <?php
    $total = 0;
    // if (!empty($_SESSION['id'])) :
    //     // $ids below must be a string
    //     $ids = implode(',', array_keys($_SESSION['cart']));
    //     $query = "Select * from products where id in ($ids)";
    //     $result = $connect->query($query);
    if (mysqli_num_rows($connect->query($queryCart)) != 0) :
        // 
    ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="shoping__product">Tên Sách</th>
                                    <th>Giá</th>
                                    <th>Số Lượng</th>
                                    <th>Thành Tiền</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productsInCart as $item) : ?>
                                    <tr>
                                        <td class="shoping__cart__item">
                                            <img width="100px" src="../images/<?= $item['product_image'] ?>" alt="">
                                            <a href="?option=detail_product&id=<?= $item['product_id'] ?>">
                                                <h5><?= $item['product_name'] ?></h5>
                                            </a>
                                        </td>
                                        <td class="shoping__cart__price">
                                            <?= number_format($item['product_price'], 0, ',', '.') ?>đ
                                        </td>
                                        <td class="shoping__cart__quantity">
                                            <div class="quantity">
                                                <div class="pro-qty">
                                                    <span class="dec qtybtn" onclick="location='?option=cart&action=update&type=dec&id=<?= $item['product_id'] ?>';">-</span>
                                                    <input type="text" value="<?= $item['quantity'] ?>" disabled>
                                                    <span class="inc qtybtn" onclick="location='?option=cart&action=update&type=asc&id=<?= $item['product_id'] ?>';">+</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="shoping__cart__total">
                                            <?= number_format($subTotal = $item['product_price'] * $item['quantity'], 0, ',', '.') ?>đ
                                            <?php $total += $subTotal; ?>
                                        </td>
                                        <td class="shoping__cart__item__close">
                                            <span class="icon_close" onclick="if(confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) location='?option=cart&action=delete&id=<?= $item['product_id'] ?>';"></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__btns">
                        <a href="?option=show_products" class="primary-btn cart-btn">Tiếp tục mua sắm</a>
                        <a href="" onclick="if(confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi giỏ hàng?')) location='?option=cart&action=delete_all';" class="primary-btn cart-btn cart-btn-right">
                            Xóa tất cả</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping__continue">
                        <div class="shoping__discount">
                            <h5>Nhập mã giảm giá</h5>
                            <form action="#">
                                <input type="text" placeholder="Enter your coupon code">
                                <button type="submit" class="site-btn">ÁP DỤNG</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping__checkout">
                        <h5>Tổng Tiền</h5>
                        <ul>
                            <li>Tạm Tính <span><?= number_format($total, 0, ',', '.') ?>đ</span></li>
                            <li>Phí Ship <span><?= number_format(30000, 0, ',', '.') ?>đ</span></li>
                            <li>Tổng <span><?= number_format($total + 30000, 0, ',', '.') ?>đ</span></li>
                        </ul>
                        <a href="?option=order" class="primary-btn">ĐẶT HÀNG</a>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?> <section style="text-align: center; font-size: 30px;">Giỏ hàng trống</section>
    <?php endif; ?>
</section>
<!-- Shoping Cart Section End -->