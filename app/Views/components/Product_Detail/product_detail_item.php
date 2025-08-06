<section class="tf-section pt-130 pb-0">
    <div class="container">
        <div class="row">
            <div class="col-md-6 wow fadeIn" data-wow-delay=".3s">
                <div class="flat-tabs style4 mobile-mb30">
                    <div class="content-tab">
                        <div class="content-inner active">
                            <div class="image">
                                <img src="<?= htmlspecialchars($images[0]['image_url']) ?>"
                                    alt="<?= htmlspecialchars($product->name) ?>">
                            </div>
                        </div>
                    </div>



                    <ul class="menu-tab">
                        <?php foreach (array_slice($subImages, 0, 3) as $index => $img): ?>
                            <li class="thumbnail <?= $index === 0 ? 'active' : '' ?>"
                                data-src="<?= htmlspecialchars($img['image_url']) ?>">
                                <img src="<?= htmlspecialchars($img['image_url']) ?>"
                                    alt="<?= htmlspecialchars($product->name) ?>">
                            </li>
                        <?php endforeach; ?>
                    </ul>


                </div>
            </div>
            <div class="col-md-6 wow fadeIn" data-wow-delay=".3s">
                <div class="info-details">
                    <div class="flex tag mb-26">
                        <span class="sale">Giảm giá!</span>
                    </div>
                    <h4 class="color-3 text-spacing-0_5 mb-22"><?= htmlspecialchars($product->name) ?></h4>
                    <div class="flex mb-28 boder-bottom pb-32">
                        <div class="price mr-30"><?= number_format($product->price, 0, ',', '.') ?> đ <!--<span>60.000
                                đ</span>--></div>
                        <ul class="rating rating_5">
                            <li>
                                <span><i class="fas fa-star"></i></span>
                            </li>
                            <li>
                                <span><i class="fas fa-star"></i></span>
                            </li>
                            <li>
                                <span><i class="fas fa-star"></i></span>
                            </li>
                            <li>
                                <span><i class="fas fa-star"></i></span>
                            </li>
                            <li>
                                <span><i class="fas fa-star"></i></span>
                            </li>
                        </ul>
                    </div>
                    <p class="boder-bottom pb-25 mb-27"><?= htmlspecialchars($product->description) ?></p>

                    <div class="boder-bottom mb-27">
                        <h6 class="color-3 mb-15 text-spacing-1">Giá trị dinh dưỡng</h6>
                        <ul class="info">
                            <li>Calo: <span><?= $product->calo !== null ? $product->calo . ' kcal' : 'N/A' ?></span>
                            </li>
                            <li>Carbohydrate:
                                <span><?= $product->carbohydrate !== null ? $product->carbohydrate . 'g' : 'N/A' ?></span>
                            </li>
                            <li>Protein:
                                <span><?= $product->protein !== null ? $product->protein . 'g' : 'N/A' ?></span></li>
                            <li>Chất béo: <span><?= $product->fat !== null ? $product->fat . 'g' : 'N/A' ?></span></li>
                            <li>Khối lượng: <span><?= $product->gram !== null ? $product->gram . 'g' : 'N/A' ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="boder-bottom pb-35 mb-26">
                        <h6 class="text-spacing-1 color-3 mb-20">Chọn tuỳ chọn của bạn:</h6>
                        <div class="flex">
                           <a href="<?= $baseUrl ?>/menu-restaurant" class="tf-button mr-12">Chọn món khác</a>

                            <form action="<?= BASE_URL ?>cart-add" method="POST" style="display:inline;">
                                <input type="hidden" name="id_products" value="<?= htmlspecialchars($product->productId) ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($product->name) ?>">
                                <input type="hidden" name="price" value="<?= $product->price ?>">
                                <input type="hidden" name="image_url" value="<?= htmlspecialchars($images[0]['image_url']) ?>">
                                <input type="hidden" name="quantity" value="1">

                                <button type="submit" class="mr-10 link">
                                    <img src="assets/images/icon/cart.png" alt="">
                                </button>
                            </form>

                            <a href="#" class="link"><img src="assets/images/icon/hear.png" alt=""></a>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <h6 class="mb-0 color-3 text-spacing-0_5 mr-20">Chia sẻ:</h6>
                        <ul class="social flex">
                            <li>
                                <a href="#" class=""><i class="fab fa-facebook-f"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fab fa-pinterest"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fab fa-skype"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-43 wow fadeIn" data-wow-delay=".3s">
                <div class="tab-style2">
                    <ul>
                        <li class="tabmenu active" data-tab="tab-1">Mô tả</li>
                        <li class="tabmenu" data-tab="tab-2">Thông tin bổ sung</li>
                        <li class="tabmenu" data-tab="tab-3">Đánh giá </li>
                    </ul>
                    <div id="tab-1" class="tabcontent active">
                        <p><?= nl2br(htmlspecialchars($product->description)) ?></p>
                    </div>

                    <div id="tab-2" class="tabcontent">
                        <p>Thành phần: ......</p>
                    </div>
                    <di id="tab-3" class="tabcontent">
                        <p>Anh Minh: "Thịt bò mềm, vị BBQ rất ngon, sẽ ủng hộ tiếp!"</p> <br>
                        <p>Nguyễn Văn A: "Món ăn tuyệt vời, rất hài lòng với chất lượng và dịch vụ."</p> <br>
                        <p>Trần Thị B: "Đã thử nhiều nơi, nhưng Basilico vẫn là số 1. Món ăn ngon, phục vụ tận tình."
                        </p> <br>
                        <p>Nguyễn Văn C: "Món ăn rất ngon, giá cả hợp lý. Sẽ quay lại ủng hộ."</p> <br>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</section>