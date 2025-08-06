<section class="tf-section pt-118 pb-58">
    <div class="container w_1450">
        <div class="row">
            <div class="col-md-12">
                <div class="tf-title center pb-33 mb-70 wow fadeInUp" data-wow-delay=".2s">
                    <p class="sub-title">Khám phá thêm</p>
                    <h2 class="mb-16">Sản phẩm liên quan</h2>
                    <p>Khám phá thêm các món ăn hấp dẫn khác tại Basilico.</p>
                </div>
            </div>
            <div class="col-md-12 relative flex items-center wow fadeIn" data-wow-delay=".3s">
                <div class="swiper-btn btn-next-team btn-next-product-6 r-26 absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="512" height="512" x="0" y="0" viewBox="0 0 24 24"
                        style="enable-background: new 0 0 512 512" xml:space="preserve">
                        <g>
                            <path fill="#0c0c0c" fill-rule="evenodd"
                                d="M12.346 7.507a.75.75 0 0 1 1.059-.072l4.588 4a.75.75 0 0 1 0 1.13l-4.588 4a.75.75 0 1 1-.986-1.13l3.08-2.685H6.5a.75.75 0 0 1 0-1.5h8.998l-3.08-2.685a.75.75 0 0 1-.072-1.058z"
                                clip-rule="evenodd" data-original="#000000" class="" opacity="1"></path>
                        </g>
                    </svg>
                </div>
                <div class="swiper-btn reverse btn-prev-team btn-prev-product-6 l-26 absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="512" height="512" x="0" y="0" viewBox="0 0 24 24"
                        style="enable-background: new 0 0 512 512" xml:space="preserve">
                        <g>
                            <path fill="#0c0c0c" fill-rule="evenodd"
                                d="M12.346 7.507a.75.75 0 0 1 1.059-.072l4.588 4a.75.75 0 0 1 0 1.13l-4.588 4a.75.75 0 1 1-.986-1.13l3.08-2.685H6.5a.75.75 0 0 1 0-1.5h8.998l-3.08-2.685a.75.75 0 0 1-.072-1.058z"
                                clip-rule="evenodd" data-original="#000000" class="" opacity="1"></path>
                        </g>
                    </svg>
                </div>
                <div class="wrapper-product-6 relative">
                    <div class="swiper-container sl-product-6">
                        <div class="swiper-wrapper">
                            <?php foreach ($relatedProducts as $product): ?>
                            <div class="swiper-slide">
                                <div class="product-4">
                                    <div class="image">
                                        <div class="flex info">
                                            <a href="#" class="seasonal">Theo mùa</a>
                                            <a href="#" class="new">Mới</a>
                                        </div>
                                        <img src="<?= htmlspecialchars($product->imageUrl ?? 'assets/images/common/default.jpg') ?>" 
                                            alt="<?= htmlspecialchars($product->name) ?>">

                                        <p class="price">
                                            <?= $product->gram ?? '0' ?>gr / <?= $product->calo ?? '0' ?> cal
                                            <span class="pl-15"><?= number_format($product->price, 0, ',', '.') ?>đ</span>
                                        </p>
                                    </div>
                                    <h5><?= htmlspecialchars($product->name) ?></h5>
                                    <p class="desc"><?= htmlspecialchars($product->description) ?></p>
                                    <div class="flex">
                                        <a href="#" class="tf-button mr-12">Chọn món</a>
                                        <a href="#" class="mr-10 action"><img src="assets/images/icon/cart.png"
                                                alt=""></a>
                                        <a href="#" class="action"><img src="assets/images/icon/hear.png" alt=""></a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>