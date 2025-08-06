<section class="tf-section pt-105 pb-72">
    <div class="container w_1374 relative flex flex-column justify-center">
        <div class="swiper-btn btn-next-product-5 r-0">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512"
                height="512" x="0" y="0" viewBox="0 0 24 24" style="enable-background: new 0 0 512 512"
                xml:space="preserve">
                <g>
                    <path fill="#0c0c0c" fill-rule="evenodd"
                        d="M12.346 7.507a.75.75 0 0 1 1.059-.072l4.588 4a.75.75 0 0 1 0 1.13l-4.588 4a.75.75 0 1 1-.986-1.13l3.08-2.685H6.5a.75.75 0 0 1 0-1.5h8.998l-3.08-2.685a.75.75 0 0 1-.072-1.058z"
                        clip-rule="evenodd" data-original="#000000" class="" opacity="1"></path>
                </g>
            </svg>
        </div>
        <div class="swiper-btn reverse btn-prev-product-5 l-0">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512"
                height="512" x="0" y="0" viewBox="0 0 24 24" style="enable-background: new 0 0 512 512"
                xml:space="preserve">
                <g>
                    <path fill="#0c0c0c" fill-rule="evenodd"
                        d="M12.346 7.507a.75.75 0 0 1 1.059-.072l4.588 4a.75.75 0 0 1 0 1.13l-4.588 4a.75.75 0 1 1-.986-1.13l3.08-2.685H6.5a.75.75 0 0 1 0-1.5h8.998l-3.08-2.685a.75.75 0 0 1-.072-1.058z"
                        clip-rule="evenodd" data-original="#000000" class="" opacity="1"></path>
                </g>
            </svg>
        </div>
        <div class="row">
            <div class="col-md-12 wow fadeIn" data-wow-delay=".3s">
                <div class="sl-product-wrapper">
                    <div class="swiper-container sl-product-5 visible">
                        <div class="swiper-wrapper">
                            <?php foreach ($bannerProducts as $product): ?>
                                <div class="swiper-slide">
                                    <div class="product-box">
                                        <div class="overlay">
                                            <img class="radius-20" 
                                                src="<?= htmlspecialchars($product->imageUrl) ?>" 
                                                alt="<?= htmlspecialchars($product->name) ?>">
                                        </div>
                                        <div class="content relative">
                                            <p class="uppercase color-main mb8 text-spacing-1_5">
                                                Special food
                                            </p>
                                            <h2 class="text-spacing-1 mb-24">
                                                <?= htmlspecialchars($product->name) ?>
                                            </h2>
                                            <p class="mb-34 white pr-55">
                                                <?= htmlspecialchars($product->description) ?>
                                            </p>
                                            <div class="flex">
                                                <ul class="flex items-center ml--20">
                                                    <li class="style white">
                                                        <?= $product->gram ?? '---' ?> Gr / <?= $product->calo ?? '---' ?> Cal
                                                    </li>
                                                    <li class="color-main">
                                                        <?= number_format($product->price, 0, ',', '.') ?> đ
                                                    </li>
                                                    <li>
                                                        <a href="product-detail.php?id=<?= $product->productId ?>" class="icon">
                                                            <i class="fal fa-plus"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
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