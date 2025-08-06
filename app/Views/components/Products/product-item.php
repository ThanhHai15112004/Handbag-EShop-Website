<section class="pb-100">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeIn" data-wow-delay=".3s">
                <div class="flat-tabs">
                    <ul class="menu-tab flex w-full menu-item-box">
                        <?php foreach ($categories as $index => $category): ?>
                            <li class="<?= $index === 0 ? 'active' : '' ?>">
                                <div class="item-box-2 style2">
                                   <div class="image mb-10" style="width: 70px; height: auto; overflow: hidden;  margin: 0 auto;">
                                        <img src="<?= htmlspecialchars($category['icon_image']) ?>" 
                                            alt="" 
                                            style="width: 100%; height: auto; object-fit: contain; display: block; margin: 0 auto;">
                                    </div>


                                    <h6 class="center color-3 mb-0"><?= htmlspecialchars($category['name']) ?></h6>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="content-tab">
                        <?php foreach ($categories as $index => $category): ?>
                            <?php 
                                $products = $productController->showProductsByCategory($category['id_categories']);
                            ?>
                            <div class="content-inner <?= $index === 0 ? 'active' : '' ?>">
                                <?php foreach ($products as $product): ?>
                                    <div class="product-item-style2 flex items-center">
                                        <div class="icon" style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f0f0f0;">
                                            <img src="<?= htmlspecialchars($product->imageUrl ?? 'assets/images/default.png') ?>"
                                                alt=""
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>

                                        <div class="content">
                                            <div class="top flex items-center mb-8">
                                                <h6 class="color-3 mb-0" 
                                                    style="max-width: 350px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    <?= htmlspecialchars($product->name) ?>
                                                </h6>

                                                <div style="flex-grow: 1; border-bottom: 1px dotted #ccc; margin: 0 10px;"></div>
                                                <span style="font-weight: 700;" class="pl4 price"><?= number_format($product->price, 0, ',', '.') ?>đ</span>
                                            </div>
                                           <p class="desc" style="
                                                display: -webkit-box;
                                                -webkit-line-clamp: 2;
                                                -webkit-box-orient: vertical;
                                                overflow: hidden;
                                                text-overflow: ellipsis;
                                            ">
                                                <?= htmlspecialchars($product->description ?? 'Đang cập nhật') ?>
                                            </p>
                                            <div class="flex items-center justify-between min-height-31 flex-wrap">
                                                <p class="color-3 pr-15 mobile-mb-10">
                                                    <?= $product->getNutritionText()  ?>
                                                </p>
                                                <form method="post" action="<?= BASE_URL ?>cart-add">
                                                    <input type="hidden" name="id_products" value="<?= $product->productId ?>">
                                                    <input type="hidden" name="name" value="<?= htmlspecialchars($product->name) ?>">
                                                    <input type="hidden" name="price" value="<?= $product->price ?>">
                                                    <input type="hidden" name="image_url" value="<?= htmlspecialchars($product->imageUrl) ?>">
                                                    <input type="hidden" name="quantity" value="1">

                                                    <button type="submit" class="link-item add-to-cart-btn" style="margin-left: 8px; background-image: linear-gradient(90deg, rgb(225, 118, 3) 0%, rgb(246, 179, 57) 100%);">Thêm vào giỏ</button>
                                                </form>
                                                <div class="flex">
                                                    <a href="product-detail?id=<?= $product->productId ?>" class="link-item seasonal mr8">Đặt ngay</a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>