<?php

header("Content-type: text/css; charset: UTF-8");

//-------------------------------------------------------------------

if (isset($_GET['theme_color_1'])) {
    $theme_color_1 = '#' . $_GET['theme_color_1'];
} else {
    $theme_color_1 = '#0f78f2';
}
if (isset($_GET['theme_color_2'])) {
    $theme_color_2 = '#' . $_GET['theme_color_2'];
} else {
    $theme_color_2 = '#000000';
}

if (isset($_GET['text_color_1'])) {
    $text_color_1 = '#' . $_GET['text_color_1'];
} else {
    $text_color_1 = '#02020c';
}

if (isset($_GET['text_color_2'])) {
    $text_color_2 = '#' . $_GET['text_color_2'];
} else {
    $text_color_2 = '#02020c';
}

if (isset($_GET['theme_color_3'])) {
    $theme_color_3 = '#' . $_GET['theme_color_3'];
} else {
    $theme_color_3 = '#02020c';
}

if (isset($_GET['theme_color_4'])) {
    $theme_color_4 = '#' . $_GET['theme_color_4'];
} else {
    $theme_color_4 = '#02020c';
}

if (isset($_GET['text_color_3'])) {
    $text_color_3 = '#' . $_GET['text_color_3'];
} else {
    $text_color_3 = '#02020c';
}

if (isset($_GET['text_color_4'])) {
    $text_color_4 = '#' . $_GET['text_color_4'];
} else {
    $text_color_4 = '#02020c';
}

//-------------------------------------------------------------------

?>

<!-- /* Cores personalizadas do tema */
    --theme-color-1: <?= $theme_color_1 ?>;
    --theme-color-2: <?= $theme_color_2 ?>;
    --text-color-1: <?= $text_color_1 ?>;
    --text-color-2: <?= $text_color_2 ?>;
    --theme-color-3: <?= $theme_color_3 ?>;
    --theme-color-4: <?= $theme_color_4 ?>;
    --text-color-3: <?= $text_color_3 ?>;
    --text-color-4: <?= $text_color_4 ?>; -->
*{
<!--  -->
}
html body{
background-color: <?= $theme_color_4 ?>;
}

h1, h2, h3, h4, h5, h6 {
color: <?= $text_color_1 ?>;
}

a {
color: <?= $text_color_1 ?>;
}

body.dark-mode h1,
body.dark-mode h2,
body.dark-mode h3,
body.dark-mode h4,
body.dark-mode h5,
body.dark-mode h6,
body.dark-mode .table {
color: <?= $theme_color_4 ?>;
}

a {
color: <?= $text_color_1 ?>;
}
body.dark-mode a {
color: <?= $theme_color_4 ?>;
}
p {
color: <?= $text_color_1 ?>;
}
body.dark-mode p {
color: <?= $theme_color_4 ?>;
}

body.dark-mode {
background-color: <?= $text_color_1 ?>;
font-family: "Montserrat", serif;
font-size: 16px;
margin: 0 !important;
padding: 0 !important;
color: <?= $theme_color_4 ?>;
overflow-x: hidden;
line-height: normal;
}

.checkbox:checked + .for-checkbox,
.checkbox:not(:checked) + .for-checkbox {
position: fixed;
width: 70px;
display: inline-block;
height: 6px;
border-radius: 4px;
background-image: linear-gradient(298deg, #000000, #ffffff);
margin: 100px auto;
cursor: pointer;
z-index: 999999;
}

@media (max-width:540px){

.align-fest-text h3 {
pointer-events: auto;
font-weight: 500;
text-transform: uppercase;
color: #333;
font-size: 1em;
}

.align-fest-text h2 {
pointer-events: auto;
font-weight: 500;
text-transform: uppercase;
color: #333;
font-size: 1em;
}
.checkbox:checked + .for-checkbox,
.checkbox:not(:checked) + .for-checkbox {
position: fixed;
width: 70px;
display: inline-block;
height: 6px;
border-radius: 4px;
background-image: linear-gradient(298deg, #000000, #ffffff);
top: 20em;
cursor: pointer;
z-index: 999999;
}
}
.checkbox:checked + .for-checkbox:before,
.checkbox:not(:checked) + .for-checkbox:before {
content: "";
position: absolute;
top: -17px;
font-size: 20px;
line-height: 40px;
text-align: center;
width: 40px;
height: 40px;
border-radius: 50%;
left: 0;
background-color: <?= $text_color_1 ?>;
transition: all 0.3s ease;
background-size: cover;
background-position: center;
}

.checkbox:not(:checked) + .for-checkbox:before {
background-image: url('/assets/images/theme15/dia.png');
background-size: unset;
background-repeat: no-repeat;
}

.checkbox:checked + .for-checkbox:before {
left: 30px;
background-image: url('/assets/images/theme15/noite.png');
background-size: unset;
background-repeat: no-repeat;
}

.categories_menu_inner_horizontal > ul > li > a, .categories_menu_inner_horizontal > ul > li span > a {
color: <?php echo $theme_color_1; ?> !important;
}

.top-header .content .right-content .list ul li .language-selector .language {
background: <?php echo $theme_color_2; ?>;}
.top-header .content .right-content .list ul li .currency-selector .currency {
background: <?php echo $theme_color_2; ?>;}

.logo-header .helpful-links ul li .wish i,
.logo-header .helpful-links ul li.my-dropdown .cart .icon i {
color: <?php echo $theme_color_2; ?>; }

.logo-header .search-box .categori-container .categoris option {
background: <?php echo $theme_color_2; ?>; }

.autocomplete-items div {
background: <?php echo "#fff"; ?>;
border: 0.5px solid <?php echo "#fff"; ?>;
z-index: 100;
}

.bottomtotop i {
color: <?php echo $text_color_1; ?>;
}

.mybtn1,
.bottomtotop i,
.logo-header .search-box .categori-container .categoris option:hover,
.trending .item .item-img .time,
.hero-area .hero-area-slider .intro-carousel.owl-carousel .owl-controls .owl-nav .owl-prev:hover,
.hero-area .hero-area-slider .intro-carousel.owl-carousel .owl-controls .owl-nav .owl-next:hover,
.hero-area .info-box:hover .icon,
.trending .item .item-img .sale,
.trending .item .item-img .discount,
.trending .item .item-img .extra-list ul li a ,
.trending .item .item-img .extra-list ul li a:hover,
.categori-item .item .item-img .sale,
.categori-item .item .item-img .discount,
.categori-item .item .item-img .extra-list ul li a,
.categori-item .item .item-img .extra-list ul li a:hover,
.flash-deals .flas-deal-slider .item .item-img .discount,
.flash-deals .flas-deal-slider .owl-controls .owl-dots .owl-dot.active,
.blog-area .aside .slider-wrapper .owl-controls .owl-dots .owl-dot.active,
.blog-area .blog-box .blog-images .img .date,
.blog-area .blog-box .details .read-more-btn,
.product-details-page .all-item .slidPrv4.slick-arrow,
.product-details-page .all-item .slidNext4.slick-arrow,
.product-details-page .right-area .product-info .contact-seller .list li a:hover,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare a:hover,
.product-details-page #coment-area .all-comments li .single-comment .right-area .replaybtn:hover,
.product-details-page #coment-area .write-comment-area .submit-btn,
.ui-widget-header,
.ui-slider .ui-slider-handle,
.sub-categori .left-area .filter-result-area .body-area .filter-btn,
.sub-categori .left-area .tags-area .body-area .taglist li a:hover,
.sub-categori .right-area .categori-item-area .item .item-img .time,
.sub-categori .right-area .categori-item-area .item .item-img .sale,
.sub-categori .right-area .categori-item-area .item .item-img .discount,
.sub-categori .right-area .categori-item-area .item .item-img .extra-list ul li a:hover,
.sub-categori .right-area .pagination-area .pagination .page-item .page-link.active,
.sub-categori .right-area .pagination-area .pagination .page-item .page-link:hover,
.sub-categori .modal .modal-dialog .modal-header,
.sub-categori .modal .contact-form .submit-btn,
.cartpage .left-area .table tbody tr td.quantity .qty ul li .qtminus:hover,
.cartpage .left-area .table tbody tr td.quantity .qty ul li .qtplus:hover,
.cartpage .right-area .order-box .cupon-box #coupon-form button:hover,
#freight-form button:hover,
.cartpage .right-area .order-box .order-btn,
.blogpagearea .blog-box .blog-images .img .date,
.blogpagearea .blog-box .details .read-more-btn,
.blog-details .blog-content .content .tag-social-link .social-links li a,
.blog-details .comments .comment-box-area li .comment-box .left .replay,
.blog-details .comments .comment-box-area li .comment-box .left .replay:hover,
.blog-details .comments .comment-box-area li .replay-form .replay-form-close:hover,
.blog-details .comments .comment-box-area li .replay-form .replay-comment-btn,
.blog-details .comments .comment-box-area li .replay-form .replay-comment-btn:hover,
.blog-details .write-comment .submit-btn,
.blog-details .write-comment .submit-btn:hover,
.blog-details .blog-aside .tags .tags-list li a:hover,
.contact-us .left-area .contact-form .submit-btn,
.contact-us .right-area .contact-info .left .icon,
.contact-us .right-area .social-links ul li a,
.contact-us .right-area .social-links ul li a:hover,
.ui-accordion .ui-accordion-header,
.compare-page-content-wrap .btn__bg,
.user-dashbord .user-profile-details .mycard,
.user-dashbord .user-profile-details .account-info .edit-info-area .edit-info-area-form .back:hover,
.user-dashbord .user-profile-details .account-info .edit-info-area .edit-info-area-form .submit-btn,
.single-wish .remove:hover,
.sub-categori .right-area .categori-item-area .item .item-img .extra-list ul li a
{
background: white;
color: black;
}

.section-top .link,
.input-field.error:-ms-input-placeholder,
.input-field.error::-moz-placeholder,
.input-field.error::-webkit-input-placeholder,
.breadcrumb-area .pages li a:hover,
.categories_menu_inner > ul > li > ul.categories_mega_menu > li > a:hover,
.categories_menu_inner_horizontal > ul > li > ul.categories_mega_menu > li > a:hover,
nav .menu li a:hover,
nav .menu li.dropdown.open > a,
.hero-area .hero-area-slider .intro-carousel .intro-content .slider-content .layer-1 .title,
.trending li.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab.ui-tabs-active.ui-state-active a,
.hero-area .info-box .icon,
.trending .item .info .stars ul li i,
.categori-item .item .info .stars ul li i,
.flash-deals .flas-deal-slider .item .stars ul li i,
.flash-deals .flas-deal-slider .item .price .new-price,
.hot-and-new-item .categori .item-list li .single-box .right-area .stars ul li i,
.footer .copy-bg .content .content a,
.footer .footer-widget ul li a:hover,
.info-link-widget .link-list li a:hover,
.info-link-widget .link-list li a:hover i,
.product-details-page .right-area .product-info .info-meta-1 ul li .stars li i,
.product-details-page .right-area .product-info .contact-seller .title,
.product-details-page .right-area .product-info .contact-seller .list li a,
.product-details-page .right-area .product-info .product-price .price,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare a,
.product-details-page #product-details-tab
li.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab.ui-tabs-active.ui-state-active a,
.product-details-page #product-details-tab ul li a i,
.product-details-page #product-details-tab ul li a:hover,
.product-details-page #coment-area .all-comments li .single-comment .right-area .header-area .posttime,
.sub-categori .left-area .service-center .body-area .list li i,
.sub-categori .left-area .service-center .footer-area .list li a:hover,
.sub-categori .right-area .categori-item-area .item .info .stars ul li i,
.sub-categori .right-area .pagination-area .pagination .page-item .page-link,
.blog-details .blog-content .content .post-meta li a:hover,
.blog-details .blog-content .content blockquote,
.blog-details .blog-aside .recent-post-widget .post-list li .post .post-details .post-title:hover,
.blog-details .blog-aside .archives .archives-list li a:hover,
.contact-us .contact-section-title .title,
.login-signup .login-area .social-area .title,
.vendor-top-header .content .single-box .icon,
.compare-page-content-wrap .pro-ratting i,
.user-dashbord .user-profile-info-area .links li:hover a,
.thankyou .content .icon,
.single-wish .right .stars li i,
.single-wish .right .store-name i
{
color: <?php echo $theme_color_1; ?>;
}

.input-field.error,
.trending .item .item-img .extra-list ul li a,
.categori-item .item .item-img .extra-list ul li a,
.product-details-page li.slick-slide,
.product-details-page .right-area .product-info .product-size .siz-list li.active .box,
.sub-categori .right-area .categori-item-area .item .item-img .extra-list ul li a
{
border: 1px solid <?php echo $theme_color_1; ?>;;
}

.input-field.error:focus,
.trending .item .item-img .extra-list ul li a:hover,
.categori-item .item .item-img .extra-list ul li a:hover,
.product-details-page .right-area .product-info .contact-seller .list li a:hover,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare a:hover,
.product-details-page #product-details-tab
li.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab.ui-tabs-active.ui-state-active a,
.sub-categori .right-area .categori-item-area .item .item-img .extra-list ul li a:hover,
.cartpage .left-area .table tbody tr td.quantity .qty ul li .qtminus:hover,
.cartpage .left-area .table tbody tr td.quantity .qty ul li .qtplus:hover,
.cartpage .right-area .order-box .cupon-box #coupon-form button:hover,
#freight-form button:hover
.blog-details .comments .comment-box-area li .replay-form .replay-form-close:hover,
.blog-details .blog-aside .tags .tags-list li a:hover
{
border-color: <?php echo $theme_color_1; ?>;
}
.loader-1 .loader-outter,
.loader-1 .loader-inner
{
border: 4px solid <?php echo $theme_color_1; ?>;
}

.trending .item .item-img .sale::before,
.trending .item .item-img .discount::before,
.categori-item .item .item-img .sale::before,
.categori-item .item .item-img .discount::before,
.sub-categori .right-area .categori-item-area .item .item-img .sale::before,
.sub-categori .right-area .categori-item-area .item .item-img .discount::before
{
border-bottom: 22px solid <?php echo $theme_color_1; ?>;
}
.flash-deals .flas-deal-slider .item .item-img .discount::before {
border-bottom: 30px solid <?php echo $theme_color_1; ?>;
}
.sub-categori .modal .contact-form ul li .input-field:focus,
.contact-us .left-area .contact-form ul li .input-field:focus,
.contact-us .left-area .contact-form .captcha-area li .input-field:focus,
.user-dashbord .user-profile-details .account-info .edit-info-area .edit-info-area-form .input-field:focus
{
border-bottom: 1px solid <?php echo $theme_color_1; ?> !important;
}

.blog-details .blog-content .content blockquote {
border-left: 5px solid <?php echo $theme_color_1; ?>;
}
.blog-details .comments .comment-box-area li .comment-box .left .img {
border: 2px solid <?php echo $theme_color_1; ?>;
}
.contact-us .right-area .contact-info {
border-bottom: 2px solid <?php echo $theme_color_1; ?>;
}
.page-center ul.pagination li {
background: <?php echo $theme_color_1; ?>1a;
}

.page-center ul.pagination li.active {
background: <?php echo $theme_color_1; ?>;
}

.blogpagearea .blog-box .name_team span::after {
border-bottom: 3px solid <?php echo $theme_color_1; ?>;
}

.logo-header .helpful-links ul li.compare .compare-product .icon span {
color: <?php echo $theme_color_2; ?>;
background: <?php echo $text_color_2; ?>;
}
.hero-area .info-box .icon {
background: <?php echo $theme_color_1; ?>;
}

.video-play-btn {
background-color: <?php echo $theme_color_1; ?>;

}

.product-details-page .right-area .product-info .contact-seller .title {
color: <?php echo $theme_color_1; ?>;}

.product-details-page .right-area .product-info .contact-seller .list li a {
color: <?php echo $theme_color_1; ?>; }
.product-details-page .right-area .product-info .contact-seller .list li a:hover {
background: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>; }

.product-details-page .right-area .product-info .product-size .siz-list li.active .box {
border: 1px solid <?php echo $theme_color_1; ?>; }

.product-details-page .right-area .product-info .product-color .color-list li .box.color5 {
background:<?php echo $theme_color_1; ?>; }

.product-details-page #product-details-tab.ui-tabs .ui-tabs-panel .heading-area .reating-area .stars {
background-color: <?php echo $theme_color_1; ?> ;
color: <?php echo $text_color_1; ?>;
}
.login-btn {
background-color: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>;
color: white !important;
}

.flash-deals .flas-deal-slider .item .price .new-price {
color: <?php echo $theme_color_1; ?>;}
.footer .footer-widget ul li a:hover {
color: <?php echo $theme_color_1; ?>; }
.info-link-widget .link-list li a:hover i {
color: <?php echo $theme_color_1; ?>; }

.login-area .header-area .title {
color: <?php echo $theme_color_1; ?>; 
}
body.dark-mode .login-area .header-area .title {
color: <?php echo $theme_color_4; ?>; 
}
.login-area .form-input i {
color: <?php echo $theme_color_2; ?>; }
.login-area .social-area .title {
color: <?php echo $theme_color_1; ?>;
}
body.dark-mode .login-area .social-area .title {
color: <?php echo $theme_color_4; ?>;
}
.blog-details .blog-aside .recent-post-widget .post-list li .post .post-details .post-title:hover {
color: <?php echo $theme_color_1; ?>; }
.blog-details .blog-aside .archives .archives-list li a:hover {
color: <?php echo $theme_color_1; ?>; }

.taglist a.active {
background: <?php echo $theme_color_1; ?>;
}
.login-area .submit-btn {
background:<?php echo $theme_color_2; ?>;
color: <?php echo $text_color_1; ?>;
}

body.dark-mode .comment-log-reg-tabmenu .nav-tabs .nav-link {
background: <?php echo $theme_color_4; ?>;
color: <?php echo $text_color_1; ?>;
}
.comment-log-reg-tabmenu .nav-tabs .nav-link.active {
background: transparent;
color: <?php echo $text_color_1; ?>;
border:1px solid <?php echo $text_color_1; ?>;
}
body.dark-mode .comment-log-reg-tabmenu .nav-tabs .nav-link.active {
background: transparent;
color: white;
border: 1px solid <?php echo $theme_color_4; ?>;
}
.comment-log-reg-tabmenu .nav-tabs .nav-link {
    border: 0;
    margin: 0;
    padding: 10px 0;
    width: 50%;
    text-align: center;
    background: <?php echo $text_color_1; ?>;
    border-radius: 0;
    color: #fff;
}
.user-dashbord .user-profile-info-area .links li:hover a {
color: <?php echo $theme_color_1; ?>;
}
.user-dashbord .user-profile-details .order-details .view-order-page .print-order a {
background: <?php echo $theme_color_1; ?>; }

.upload-img .file-upload-area .upload-file span {
background: <?php echo $theme_color_1; ?>;
color: white;
}
.thankyou .content .icon {
color: <?php echo $theme_color_1; ?>; }

#style-switcher h2 a {
background: <?php echo $theme_color_1; ?>;
}

.elegant-pricing-tables h3 .price-sticker,
.elegant-pricing-tables:hover,
.elegant-pricing-tables.active,
.elegant-pricing-tables:hover .price,
.elegant-pricing-tables.active .price,
.elegant-pricing-tables.style-2 .price,
.elegant-pricing-tables .btn {
background: <?php echo $theme_color_1; ?>;
}
.logo-header .helpful-links ul li.my-dropdown.profilearea .profile .img img{
border: 2px solid <?php echo $theme_color_1; ?>;
}
a.sell-btn {
background: <?php echo $theme_color_1; ?>;
}
.top-header .content .right-content .list ul li a.sell-btn:hover {
transition: 0.3s;
background: #fff;
color: <?php echo $theme_color_1; ?>;
}
.sub-categori .left-area .service-center .body-area .list li i {
color: <?php echo $theme_color_1; ?>;
}
.sub-categori .left-area .service-center .footer-area .list li a:hover {
color: <?php echo $theme_color_1; ?>; }
.breadcrumb-area .pages li a:hover {
color: <?php echo $theme_color_1; ?>; }
.cartpage .left-area .table tbody tr td.quantity .qty ul li .qtminus1:hover, .cartpage .left-area .table tbody tr
td.quantity .qty ul li .qtplus1:hover {
background: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>; }
.cupon-box #coupon-form button:hover,
#freight-form button:hover {
background: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>; }
.cupon-box #check-coupon-form button:hover {
background: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>; }
.categories_menu_inner > ul > li > ul.categories_mega_menu > li > a:hover {
color: <?php echo $theme_color_1; ?>; }
.contact-us .left-area .contact-form .form-input i {
color: <?php echo $theme_color_1; ?>;
}
.message-modal .modal .modal-dialog .modal-header {
background: <?php echo $theme_color_1; ?>; }

.message-modal .modal .contact-form .submit-btn {
background: <?php echo $theme_color_1; ?>; }

.logo-header .search-box .categori-container .categoris option:focus{
background: <?php echo $theme_color_1; ?>;
}
.product-details-page .right-area .product-info .mproduct-size .siz-list li.active .box {
border: 1px solid <?php echo $theme_color_1; ?>; }

.flash-deals .owl-carousel .owl-controls .owl-nav .owl-prev,
.flash-deals .owl-carousel .owl-controls .owl-nav .owl-next {
color: <?php echo $theme_color_2; ?>;
}

.trending .owl-carousel .owl-controls .owl-nav .owl-prev,
.trending .owl-carousel .owl-controls .owl-nav .owl-next {

color: <?php echo $text_color_1; ?>;
}

.section-top .section-title::after{
background: <?php echo $theme_color_1; ?>;
}

.item .add-to-cart-btn{
background: transparent;
border: 1px solid transparent;
color: #fff;
}
body.dark-mode .item .add-to-cart-btn{
background: <?php echo $theme_color_2; ?>;
border: 1px solid <?php echo $theme_color_2; ?>;
color: <?= $text_color_1 ?>;
}

.item .add-to-cart-btn:hover {
color: #fff;
background: <?php echo $theme_color_2; ?>;
}

.flash-deals .flas-deal-slider .item .price .new-price {
color: <?php echo $theme_color_1; ?>;}

.flash-deals .flas-deal-slider .item .deal-counter span {
color: <?php echo $theme_color_1; ?>;
}
.hot-and-new-item .owl-carousel .owl-controls .owl-nav .owl-prev,
.hot-and-new-item .owl-carousel .owl-controls .owl-nav .owl-next {
border: 1px solid <?php echo $theme_color_2; ?>;
color: <?php echo $theme_color_2; ?>;
display: none !important;
}
.hot-and-new-item .owl-carousel .owl-controls .owl-nav .owl-prev:hover,
.hot-and-new-item .owl-carousel .owl-controls .owl-nav .owl-next:hover {
background: <?php echo $theme_color_2; ?>!important;

}

.seller-info .content .title {
color: <?php echo $theme_color_1; ?>;
}

.seller-info .content .total-product p {
color: <?php echo $theme_color_1; ?>;
}

.seller-info .view-stor {
background: <?php echo $theme_color_1; ?>;

border: 1px solid <?php echo $theme_color_1; ?>;

}
.seller-info .view-stor:hover{
background: #fff;
color: <?php echo $theme_color_1; ?>;
}
.product-details-page .categori .owl-carousel .owl-controls .owl-nav .owl-prev,
.product-details-page .categori .owl-carousel .owl-controls .owl-nav .owl-next {
color: <?php echo $theme_color_1; ?>;
border: 1px solid <?php echo $theme_color_1; ?>;
}

.product-details-page .xzoom-container .owl-carousel .owl-controls .owl-nav .owl-prev,
.product-details-page .xzoom-container .owl-carousel .owl-controls .owl-nav .owl-next {
background: <?php echo $theme_color_2; ?>;
border: 1px solid <?php echo $theme_color_2; ?>;

}

.subscribePreloader__text {
background: <?php echo $theme_color_1; ?>c7;
}

.logo-header .helpful-links ul li.my-dropdown .my-dropdown-menu .dropdownmenu-wrapper .dropdown-cart-action .mybtn1 {
color: <?php echo $text_color_1; ?>;
border: 1px solid <?php echo $theme_color_2; ?>;
background: <?php echo $theme_color_2; ?>;
}

.logo-header .helpful-links ul li.my-dropdown .my-dropdown-menu .dropdownmenu-wrapper .dropdown-cart-action
.mybtn1:hover {
color: <?php echo $theme_color_1; ?>;
border: 1px solid <?php echo $theme_color_4; ?>;
background: <?php echo $theme_color_4; ?>;
}
.mybtn2:hover {
background: <?php echo $theme_color_1; ?>;
border: 1px solid <?php echo $theme_color_1; ?>;
}
.top-header .content .right-content .list li .track-btn:hover{
background: <?php echo $theme_color_1; ?>;
}
input[type=checkbox]:checked + label:before {
background-color: <?php echo $theme_color_2; ?>;
border-color: <?php echo $theme_color_2; ?>;
}
.radio-design .checkmark::after{
background: <?php echo $theme_color_2; ?>;
}
.order-box .order-btn {
background: <?php echo $theme_color_1; ?>;
}
.page-link {
color: <?= $text_color_1; ?> !important;
}
.page-item.active .page-link {
background-color: <?php echo $theme_color_1; ?>;
border-color: <?= $theme_color_1; ?>;
color: <?= $theme_color_4; ?> !important;
}
.mybtn1 {
border: 1px solid <?php echo $theme_color_2; ?>;
background: <?php echo $theme_color_2; ?>;
}
body.dark-mode .mybtn1 {
border: 1px solid <?php echo $theme_color_2; ?>;
background: transparent;
color: <?php echo $text_color_2; ?>;
}
.mybtn2 {
color: <?php echo $theme_color_1; ?> !important;
border: 1px solid <?php echo $theme_color_1; ?>;
}

.checkout-area .checkout-process li a:hover{
background: <?php echo $theme_color_1; ?>;

}
.checkout-area .checkout-process li a:hover::before{
border-left: 20px solid <?php echo $theme_color_1; ?>;
}
.checkout-area .checkout-process li a.active{
background: <?php echo $theme_color_1; ?>;
color: <?php echo $text_color_1; ?>;

}
.checkout-area .checkout-process li a.active::before{
border-left: 20px solid <?php echo $theme_color_1; ?>;
}
.checkout-area .content-box .content .billing-info-area .info-list li p i{
color: <?php echo $theme_color_2; ?>;
}
.checkout-area .content-box .content .payment-information .nav a span::after{
background: <?php echo $theme_color_2; ?>;
}
.hero-area .hero-area-slider .intro-carousel .intro-content .slider-content .layer-3 a {
border: 1px solid <?php echo $theme_color_1; ?>;
}
.hero-area .hero-area-slider .intro-carousel .intro-content .slider-content .layer-3 a:hover {
border: 1px solid <?php echo $theme_color_1; ?>;
color: <?php echo $theme_color_1; ?>;
}

.order-tracking-content .track-form .mybtn1{
border: 1px solid <?php echo $theme_color_1; ?>;

}
.order-tracking-content .tracking-form .mybtn1{
border: 1px solid <?php echo $theme_color_1; ?>;
}
.tracking-steps li.done:after,
.tracking-steps li.active:after,
.tracking-steps li.active .icon{
background: <?php echo $theme_color_1; ?>;
}
.modal-header .close:hover {
background-color: <?php echo $theme_color_1; ?>;
}
.logo-header .helpful-links ul li.wishlist .wish span {
color: #fff;
background: <?php echo $text_color_2; ?>;
}

.categories_title {
background: <?php echo $text_color_1; ?>;
}

.mainmenu-area .categories_menu .categories_title h2, .mainmenu-area .categories_menu .categories_title h2 i.arrow-down
{
color: <?php echo $theme_color_1; ?>;
}

nav .menu li:last-child a{
color: <?php echo $theme_color_1; ?>;
}

.blog-area .blog-box .details .read-more-btn {
color: #fff;
background: <?php echo $text_color_1; ?>;
border:1px solid <?php echo $text_color_1; ?>;
}

.blog-area .blog-box .details .read-more-btn:hover {
color: <?php echo $theme_color_1; ?>;
background: <?php echo $text_color_1; ?>;
border:1px solid <?php echo $text_color_1; ?>;
}

<!--Alterado do dinâmico $theme_color_1 com uma variável inexistente pois foi a melhor forma que encontrei de estilizar sem quebrar o resto do layout-->

.info-area .info-box .info .title {
color: <?php echo $theme_color_2; ?>;
}


.blogpagearea .blog-box .details .read-more-btn {
border: 1px solid <?php echo $theme_color_1; ?>;

}
.blogpagearea .blog-box .details .read-more-btn:hover {
color: <?php echo $theme_color_1; ?>;
}
.contact-us .left-area .contact-form .submit-btn {
border: 1px solid <?php echo $theme_color_1; ?>;
}
.contact-us .left-area .contact-form .submit-btn:hover {
color: <?php echo $theme_color_1; ?>;
}
.process-steps li.done:after,
.process-steps li.active:after,
.process-steps li.active .icon{
background: <?php echo $theme_color_1; ?>;
}
.sub-categori .left-area .filter-result-area .body-area .filter-btn {
border: 1px solid <?php echo $theme_color_1; ?>;
}
.sub-categori .left-area .filter-result-area .body-area .filter-btn:hover {
color: <?php echo $theme_color_1; ?>;
}
.category-page .bg-white .sub-category-menu .category-name a{
color: <?php echo $theme_color_1; ?>;
}
body.dark-mode .category-page .bg-white .sub-category-menu .category-name a{
color: <?= $theme_color_4; ?>;
}
.category-page .bg-white .sub-category-menu ul li a:hover{
color: <?php echo $theme_color_1; ?>;
}
.category-page .bg-white .sub-category-menu ul li ul li a:hover i,
.category-page .bg-white .sub-category-menu ul li ul li a:hover
{
color: <?php echo $theme_color_1; ?>;
}

@media (max-width: 991px) {
nav .nav-header .toggle-bar {
color: <?php echo $theme_color_1; ?>;
}
}

.product-details-page .xzoom-container .owl-carousel .owl-controls .owl-nav .owl-prev:hover,
.product-details-page .xzoom-container .owl-carousel .owl-controls .owl-nav .owl-next:hover {
color: <?php echo $text_color_1; ?>;
border:1px solid <?php echo $text_color_1; ?>;
}

.product-details-page .categori .owl-carousel .owl-controls .owl-nav .owl-prev:hover,
.product-details-page .categori .owl-carousel .owl-controls .owl-nav .owl-next:hover {
background: <?php echo $theme_color_1; ?> !important;

}

.custom-control-input:checked~.custom-control-label::before {
border-color: <?php echo $theme_color_1; ?>;
background-color: <?php echo $theme_color_1; ?>;
}
#product-details-tab #comment-area .all-comment li .single-comment .left-area img {

border: 2px solid <?php echo $theme_color_1; ?>;
}

#product-details-tab #replay-area .all-replay li .single-review .left-area img {

border: 2px solid <?php echo $theme_color_1; ?>;
}

.wholesell-details-page{
background: <?php echo $theme_color_1; ?>0f;
}
.sub-categori .left-area .filter-result-area .body-area .filter-list li a:hover{
color: <?php echo $theme_color_1; ?>;
}
.contact-us .right-area .contact-info .content a:hover {
color: <?php echo $theme_color_1; ?>;
}
#product-details-tab #comment-area .all-comment li .replay-area button {
background: <?php echo $theme_color_1; ?>;

}

.customize-title{
font-size: 14px;
font-weight: 600;
color: <?php echo $theme_color_1; ?>;
}

.textureOverlay{
width: 82px;
height: 82px;
margin-top: -81px;
margin-left: 4px;
border-radius: 50px;
opacity: 0;
transition: .5s ease;
background-color: <?php echo $theme_color_1; ?>;
}
.textureOverlayModal{
width: 100px;
height: 100px;
margin-top: -99px;
margin-left: 8px;
border-radius: 50px;
opacity: 0;
transition: .5s ease;
background-color: <?php echo $theme_color_1; ?>;
}

.textureCurrentOverlay{
width: 102px;
height: 100px;
margin-left: -165px;
margin-top: -6px;
opacity: 0.5;
border-radius: 50px;
background-color: <?php echo $theme_color_1; ?>;
border: 3px solid #fff;
}

.allOptionsAnchor p{
color: <?php echo $theme_color_1; ?>;
font-size: 14px;
}

.uploadLogoBtn{
border-radius: 30px;
background-color: <?php echo $theme_color_1; ?>;
border: 1px solid <?php echo $theme_color_1; ?>;
}
.uploadLogoBtn:hover{
border-radius: 30px;
color: <?php echo $theme_color_1; ?>;
transition: 0.4s ease;
background-color: #fff;
border: 1px solid <?php echo $theme_color_1; ?>;
}
.uploadLogoBtn:hover p{
color: <?php echo $theme_color_1; ?>;
}
.uploadLogoBtn p {
color: #fff;
font-size: 14px;
margin-bottom: -1px;
font-weight: 600;
}

.mainmenu-area {
background-color: <?php echo $text_color_1; ?>;
}

.hero-area .hero-area-slider .owl-controls .owl-dots .owl-dot {
background: <?php echo $theme_color_1; ?>;
opacity: .4;
}

.hero-area .hero-area-slider .owl-controls .owl-dots .owl-dot.active{
background: <?php echo $theme_color_1; ?>;
opacity: 1;
}

.info-area .info-box .icon .title {
color: <?php echo $text_color_1; ?>;
}

.section-title #post-title::after,.section-title #post-title::before {
border: 1px solid <?php echo $theme_color_1; ?>;
}

.flash-deals .flas-deal-slider .card-product-flash:hover .deal-counter {
background-color: <?php echo $theme_color_2; ?>;
transition: all .4s;
}

.hot-and-new-item .categori .item-list li .single-box .right-area .price {
color: <?php echo $theme_color_1; ?>;
}

.blog-area .owl-carousel .owl-controls .owl-nav .owl-prev, .blog-area .owl-carousel .owl-controls .owl-nav .owl-next {
color: <?php echo $theme_color_1; ?>;
}

.blog-area .aside .coments {
border-left: 1px solid <?php echo $theme_color_1; ?>;
}

.logo-header .helpful-links ul li.my-dropdown .cart .icon span {
color: #fff;
background: #3FC600;
}

.item .item-img .sell-area .sale {
color: white;
background: <?php echo $theme_color_1; ?>;
}


.blog-area .blog-box .blog-title, .blog-area .blog-box .details .blog-text {
color: <?php echo $text_color_1; ?>;
}

.blog-area .blog-box .blog-images .img .date p {
color: <?php echo $text_color_1; ?>;
}

.blog-area .blog-box .blog-images .img .date {
border: 1px solid <?php echo $text_color_1; ?>;
}

.item .info .price {
color: <?php echo $theme_color_2; ?>;
}

.section-top .section-title::before, .section-top .section-title::after {
background-color:<?php echo $theme_color_1; ?>;
}
body.dark-mode .section-top .section-title::before,
body.dark-mode .section-top .section-title::after {
background-color:<?php echo $theme_color_4; ?>;
}

.item:hover .extra-list {
background-color: transparent;
}

.item .item-img .extra-list ul li span:hover {
filter: brightness(80%);
color: <?php echo $theme_color_2; ?>;
}

.blog-area .aside .slider-wrapper .slide-item .top-area .right .content .name,
.blog-area .aside .slider-wrapper .slide-item .top-area .right .content .dagenation,
.blog-area .aside .slider-wrapper .slide-item .review-text::after,
.blog-area .aside .slider-wrapper .slide-item .review-text::before {
color: <?php echo $theme_color_1; ?>;
}

.footer .footer-info-area .text p, .footer .footer-widget ul li a,
.recent-post-widget .post-list li .post .post-details .date,
.footer .footer-widget .title,
.footer .footer-widget ul li,
.footer .title
{
color: <?= $theme_color_4; ?>;
}

.footer .copy-bg .content .content p {
color: #fff;
opacity: .7;
}

.footer .fotter-social-links ul li a {
color: <?php echo $text_color_1; ?>;
background-color: #fff;
border-color: transparent;
justify-content: center;
align-items: center;
display: flex;
}
.footer .fotter-social-links ul li a:hover {
color: <?php echo $text_color_1; ?>;
background-color: <?php echo $theme_color_1; ?>;
border-color: <?php echo $text_color_1; ?>;

}

#services-carousel .owl-controls .owl-nav .owl-prev, #services-carousel .owl-controls .owl-nav .owl-next {
color: <?php echo $theme_color_2; ?>;
}

.icon-filter {
background-color: <?php echo $theme_color_1; ?>;
fill: white;
}

.sub-categori .left-area .tags-area .body-area .sub-title {
color: <?php echo $text_color_1; ?>;
}
.cookie-alert .bg-custom{
background-color: <?php echo $theme_color_1; ?>;
}

.cookie-alert h4{
color: <?php echo $text_color_1; ?>;
}
.cookie-alert p{
font-size: 14px;
color: <?php echo $text_color_1; ?>;
}

.cookie-alert .button-fixed{
bottom: 0;
position: fixed;
right: 0;
border: 1px solid <?php echo $theme_color_1; ?>;
background-color: <?php echo $theme_color_1; ?>;
}
.cookie-alert .fas{
cursor: pointer;
font-size: 24px;
color: <?php echo $text_color_1; ?>;
}

.cookie-alert .btn{
background-color: <?php echo $text_color_1; ?>;
color: <?php echo $theme_color_1; ?>;
border: none;
}

.cookie-alert .btn:focus{
background-color: <?php echo $theme_color_1; ?>;
color: <?php echo $text_color_1; ?>;
border: 1px solid <?php echo $text_color_1; ?>;
}

.cookie-alert a{
color: <?php echo $text_color_1; ?>;
font-weight: 600;
}

.box-center-text.infos-internas {
background-color: <?php echo $theme_color_2; ?>;
}

.box-center-text.infos-internas .title, .box-center-text.infos-internas .count {
color: <?php echo $text_color_2; ?>;
}

.badge-primary{
background-color: <?php echo $theme_color_2; ?>;
color: white;
}

.top-header {
background-color: <?= $theme_color_4 ?>;
}

.top-header.dark-mode {
background-color: <?= $text_color_1  ?>;
}

.saxnavigation .menu-navigation li .navlink {
padding: 0 20px;
text-transform: uppercase;
font-size: 1.1rem;
color: <?= $text_color_1 ?>;
font-family: "Montserrat", serif;
}

.saxnavigation .menu-navigation li .navlink.dark-mode {
color: <?= $theme_color_4 ?>;
}
.categoryLink {
color: <?= $text_color_1 ?>;
text-decoration:none;
}

body.dark-mode .categoryLink {
color: <?= $theme_color_4 ?>;
}

body.dark-mode .categoryLink:hover {
color: <?= $theme_color_4 ?> !important;
text-decoration:none;
}

.menu-navigation .submenu-cat ul {
background-color: <?= $theme_color_4 ?>;
}

body.dark-mode .menu-navigation .submenu-cat ul {
background-color: <?= $text_color_1 ?>;
}
.saxnavigation .menu-navigation .submenu-cat .subcat-link.subcat_open .boxsubcat {
background-color: <?= $theme_color_4 ?>;
box-shadow: 0 3px 5px #cccccc8f;
border-top: 1px solid #ececec;
}

body.dark-mode .saxnavigation .menu-navigation .submenu-cat .subcat-link.subcat_open .boxsubcat {
background-color: <?= $text_color_1 ?>;
box-shadow: 0 3px 5px #cccccc8f;
border-top: 1px solid #ececec;
}
.link-seemore{
color: <?= $text_color_1 ?>;
}
body.dark-mode .link-seemore{
color: <?= $theme_color_4 ?>;
}

#profile-icon, .logo-header .search-box .search-form button {
color: <?php echo $theme_color_1; ?>;
transition: all .3s;
}

#profile-icon:hover {
filter: brightness(70%);
transition: all .3s;
}

.box-button-site {
background-color: <?php echo $theme_color_1; ?>;
}

.box-button-site > *{
color: <?php echo $text_color_1; ?>;
}

[data-menu-toggle-main] .container-menu {
background-color: <?php echo $text_color_2; ?>;
}

[data-menu-toggle-main] .container-menu li a{
color: <?php echo $theme_color_2; ?>;
}

.saxnavigation .menu-navigation .submenu-cat .subcat-link.subcat_open {
color: <?php echo $theme_color_2; ?>;
}

.product-attributes .form-group .custom-control-input:checked ~ .custom-control-label {
background-color: <?php echo $theme_color_2; ?>;
border: 1px solid <?php echo $theme_color_2; ?>;
}

.product-attributes .form-group .attribute-normal {
background-color: <?php echo $theme_color_2; ?>;
border: 1px solid <?php echo $theme_color_2; ?>;
transition: all .3s;
color: #fff;
padding: 5px 10px;
font-size: 15px;
}

#product-details-tab #comment-area .all-comment li .single-comment {
border: 1px solid <?php echo $theme_color_2; ?>63;
}

.saxnavigation .submenu-cat .subcat-link:hover .categoryLink {
color: <?php echo $theme_color_2; ?>;
}
.trending {
background: #F6F6F6;
}
body.dark-mode .trending {
background: <?php echo $text_color_1; ?>;
}
.section-top .section-title{
color: #333;
}
body.dark-mode .section-top .section-title{
color: <?= $theme_color_4 ?>;
}
.section-top h5 {
color: #333;
}
body.dark-mode .section-top h5 {
color: <?= $theme_color_4 ?>;
}

.info-area {
background: #fff;
padding: 5rem 0;
}
body.dark-mode .info-area {
background: <?php echo $text_color_1; ?>;
padding: 5rem 0;
}

.footer {
background-color: <?php echo $text_color_1; ?>;
}

body.dark-mode .footer {
background-color: <?= $theme_color_1 ?>;
}
.btn-style-1 {
text-transform: uppercase;
border: 1px solid #333;
color: #333;
padding: 15px;
border-radius: 0;
display: block;
}
body.dark-mode .btn-style-1 {
text-transform: uppercase;
border: 1px solid <?= $theme_color_4 ?>;;
color: <?= $theme_color_4 ?>;
padding: 15px;
border-radius: 0;
display: block;
}

.item .info .name {
height: auto;
font-weight: 300;
line-height: normal;
margin-bottom: 10px;
font-size: 20px;
color: #333;
overflow: hidden;
text-overflow: ellipsis;
display: -webkit-box;
-webkit-line-clamp: 2;
-webkit-box-orient: vertical;
}
body.dark-mode .item .info .name {
height: auto;
font-weight: 300;
line-height: normal;
margin-bottom: 10px;
font-size: 20px;
color: <?= $theme_color_4 ?>;
overflow: hidden;
text-overflow: ellipsis;
display: -webkit-box;
-webkit-line-clamp: 2;
-webkit-box-orient: vertical;
}
.menufixed.nav-fixed {
position: fixed;
top: 0;
left: 0;
width: 100%;
z-index: 99;
transition: all 0.5s;
-webkit-box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
box-shadow: 0 6px 9px rgba(0, 0, 0, 0.3);
background-color: <?= $theme_color_4 ?>;
}
body.dark-mode .menufixed.nav-fixed {
position: fixed;
top: 0;
left: 0;
width: 100%;
z-index: 99;
transition: all 0.5s;
-webkit-box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
box-shadow: 0 6px 9px rgba(0, 0, 0, 0.3);
background-color: <?php echo $text_color_1; ?>;
}
.flex-column a h5{
color:<?php echo $text_color_1; ?>;
}
body.dark-mode .flex-column a h5{
color:<?= $theme_color_4 ?>;
}
.item .item-cart-area {
position: relative;
transition: all linear .3s;
padding-bottom: 0;
background: <?php echo $theme_color_1; ?>;
}
body.dark-mode .item .item-cart-area {
position: relative;
transition: all linear .3s;
padding-bottom: 0;
background: <?php echo $theme_color_2; ?>;
}

.info .m-0{
color:<?php echo $text_color_1; ?>;
}

body.dark-mode .info .m-0{
color:<?= $theme_color_4 ?>;
}

.item .item-img .extra-list ul li span {
color:<?php echo $text_color_1; ?>;
transition: .3s all;
}

body.dark-mode .item .item-img .extra-list ul li span {
color:<?= $theme_color_4 ?>;
transition: .3s all;
}
.product-details-page .right-area .product-info .product-name {
font-size: 35px;
font-weight: 600;
color:<?php echo $text_color_1; ?>;
}
body.dark-mode .product-details-page .right-area .product-info .product-name {
font-size: 35px;
font-weight: 600;
color:<?= $theme_color_4 ?>;
}
.py-4 h3 {
color:<?php echo $text_color_1; ?>;
}
body.dark-mode .py-4 h3 {
color:<?= $theme_color_4 ?>;
}
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.favorite a,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare a,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.favorite:hover a,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare:hover a {
border: 1px solid transparent;
background: transparent;
color: <?= $text_color_1 ?>;
}

body.dark-mode .product-details-page .right-area .product-info .info-meta-3 .meta-list li.favorite a,
body.dark-mode .product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare a,
body.dark-mode .product-details-page .right-area .product-info .info-meta-3 .meta-list li.favorite:hover a,
body.dark-mode .product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare:hover a {
border: 1px solid transparent;
background: transparent;
color: <?= $theme_color_4 ?>;
}


.product-details-page .right-area .product-info .info-meta-3 .meta-list li.addtocart a,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.addtocart:hover a {
color: <?= $text_color_1 ?>;
border: 1px solid <?= $text_color_1 ?>;
background: transparent;
}

body.dark-mode .product-details-page .right-area .product-info .info-meta-3 .meta-list li.addtocart a,
body.dark-mode .product-details-page .right-area .product-info .info-meta-3 .meta-list li.addtocart:hover a {
color: <?= $theme_color_4 ?>;
border: 1px solid <?= $theme_color_4 ?>;
background: transparent;
}

.review-area .title{
color: <?= $text_color_1 ?>;
}
body.dark-mode .review-area .title{
color: <?= $theme_color_4 ?>;
}
.product-details-page #product-details-tab.ui-tabs .ui-tabs-panel p{
color: <?= $text_color_1 ?>;
}
body.dark-mode .product-details-page #product-details-tab.ui-tabs .ui-tabs-panel p{
color: <?= $theme_color_4 ?>;
}
.product-details-page #product-details-tab .top-menu-area ul li a {
color: <?= $text_color_1 ?>;
font-weight: 300;
font-family: "Montserrat", serif;
font-size: 18px;
text-transform: uppercase;
padding: 0 15px;
margin: 0 5px;
}

.product-details-page .right-area .product-info .product-price .price {
color: <?= $text_color_1 ?>;
}

body.dark-mode .product-details-page .right-area .product-info .product-price .price {
color: <?= $theme_color_4 ?>;
}

body.dark-mode .product-details-page #product-details-tab .top-menu-area ul li a {
color: <?= $theme_color_4 ?>;
font-weight: 300;
font-family: "Montserrat", serif;
font-size: 18px;
text-transform: uppercase;
padding: 0 15px;
margin: 0 5px;
}

#product-details-tab #replay-area .write-comment-area .submit-btn {
background: <?= $text_color_1 ?>;
border: 0;
color: <?= $theme_color_4 ?>;
padding: 14px 30px 11px;
border-radius: 50px;
font-size: 14px;
font-weight: 600;
border: 1px solid <?= $text_color_1 ?>;
cursor: pointer;
-webkit-transition: all 0.3s ease-in;
-o-transition: all 0.3s ease-in;
transition: all 0.3s ease-in;
}

body.dark-mode #product-details-tab #replay-area .write-comment-area .submit-btn {
background: transparent;
border: 0;
color: <?= $theme_color_4 ?>;
padding: 14px 30px 11px;
border-radius: 50px;
font-size: 14px;
font-weight: 600;
border: 1px solid <?= $theme_color_4 ?>;
cursor: pointer;
-webkit-transition: all 0.3s ease-in;
-o-transition: all 0.3s ease-in;
transition: all 0.3s ease-in;
}

#product-details-tab #replay-area .write-comment-area textarea,
#product-details-tab #comment-area .write-comment-area textarea,
#product-details-tab #comment-area .all-comment li .replay-area textarea {
padding: 15px;
font-size: 16px;
font-family: 'Montserrat', sans-serif;
border: 1px solid #dfdfdf;
color: #333;
font-weight: 300;
width: 100%;
border-radius: 4px;
background: transparent;
height: 100px;
}

.product-details-page .right-area .product-info .info-meta-3 .meta-list li.count .qty ul li .qtminus,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.count .qty ul li .qtplus,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.count .qty ul li .qttotal {
display: inline-block;
width: 30px;
height: 30px;
border: 1px solid <?= $text_color_1 ?>;
color:<?= $text_color_1 ?>;
text-align: center;
line-height: 30px;
font-size: 14px;
cursor: pointer;
font-weight: 500;
}
body.dark-mode .product-details-page .right-area .product-info .info-meta-3 .meta-list li.count .qty ul li .qtminus,
body.dark-mode .product-details-page .right-area .product-info .info-meta-3 .meta-list li.count .qty ul li .qtplus,
body.dark-mode .product-details-page .right-area .product-info .info-meta-3 .meta-list li.count .qty ul li .qttotal {
display: inline-block;
width: 30px;
height: 30px;
border: 1px solid <?= $theme_color_4 ?>;
color:<?= $theme_color_4 ?>;
text-align: center;
line-height: 30px;
font-size: 14px;
cursor: pointer;
font-weight: 500;
}
/* Estilo padrão */
#product-details-tab #comment-area .write-comment-area .submit-btn {
background: #000; /* Fundo preto */
border: 1px solid #000;
color: #fff; /* Texto branco */
padding: 14px 30px;
border-radius: 50px;
font-size: 14px;
font-weight: 600;
cursor: pointer;
-webkit-transition: all 0.3s ease-in;
-o-transition: all 0.3s ease-in;
transition: all 0.3s ease-in;
}

#product-details-tab #comment-area .write-comment-area .submit-btn:hover {
background: transparent; /* Fundo transparente ao passar o mouse */
color: #000; /* Texto preto no hover */
}

/* Estilo para o modo escuro */
body.dark-mode #product-details-tab #comment-area .write-comment-area .submit-btn {
background: transparent; /* Fundo transparente */
border: 1px solid #fff; /* Borda branca */
color: #fff; /* Texto branco */
}

body.dark-mode #product-details-tab #comment-area .write-comment-area .submit-btn:hover {
background: transparent;
color: #fff; /* Texto branco no hover */
border-color: #fff; /* Borda branca no hover */
}
.nav-pills .nav-link {
border-radius: 0.25rem;
color: <?= $text_color_1 ?>;
}
body.dark-mode .nav-pills .nav-link {
border-radius: 0.25rem;
color: <?= $theme_color_4 ?>;
}
body.dark-mode .slider-buttom-category .single-category::before,
body.dark-mode .slider-buttom-category .single-category {
background: transparent;
border: 1px solid white;
}

.slider-buttom-category .single-category .left .title,
.slider-buttom-category .single-category .left .count {
color: <?= $text_color_1; ?>;
}
body.dark-mode .slider-buttom-category .single-category .left .title,
body.dark-mode .slider-buttom-category .single-category .left .count {
color: <?= $theme_color_4 ?>;
}

.sub-categori .left-area .filter-result-area .header-area .title,
.sub-categori .left-area .tags-area .header-area .title {
font-weight: 400;
text-transform: uppercase;
font-family: 'Montserrat';
color: <?= $text_color_1; ?>;
margin: 0;
}

body.dark-mode .sub-categori .left-area .filter-result-area .header-area .title,
body.dark-mode .sub-categori .left-area .tags-area .header-area .title {
font-weight: 400;
text-transform: uppercase;
font-family: 'Montserrat';
color: <?= $theme_color_4 ?>;
margin: 0;
}
.contact-us .contact-form .form-input textarea {
width: 100%;
height: calc(150px + (15px * 4));
background: transparent;
padding: 25px 30px 0 45px;
color: <?= $text_color_1; ?>;
border: 1px solid <?= $text_color_1; ?>;
outline: 0;
font-size: 14px;
margin-bottom: 0;
box-shadow: 2px 1px 12px 4px rgba(225, 201, 163, 0.125);
}
body.dark-mode .contact-us .contact-form .form-input textarea {
width: 100%;
height: calc(150px + (15px * 4));
background: transparent;
padding: 25px 30px 0 45px;
color: <?= $theme_color_4 ?>;
border: 1px solid <?= $theme_color_4 ?>;
outline: 0;
font-size: 14px;
margin-bottom: 0;
box-shadow: 2px 1px 12px 4px rgba(225, 201, 163, 0.125);
}
.contact-us .contact-form .form-input input {
width: 100%;
height: 60px;
background: transparent;
border: 1px solid <?= $text_color_1; ?>;
padding: 0 30px 0 45px;
font-size: 14px;
margin-bottom: 0;
color: <?= $text_color_1; ?>;
box-shadow: 2px 1px 12px 4px rgba(225, 201, 163, 0.125);
}
body.dark-mode .contact-us .contact-form .form-input input {
width: 100%;
height: 60px;
background: transparent;
border: 1px solid <?= $theme_color_4 ?>;
padding: 0 30px 0 45px;
font-size: 14px;
margin-bottom: 0;
color: <?= $theme_color_4 ?>;
box-shadow: 2px 1px 12px 4px rgba(225, 201, 163, 0.125);
}
.contact-us .contact-form .form-input i {
position: absolute;
top: 50%;
-webkit-transform: translateY(-50%);
-ms-transform: translateY(-50%);
transform: translateY(-50%);
left: 15px;
color: <?= $text_color_1 ?>;
}
body.dark-mode .contact-us .contact-form .form-input i {
position: absolute;
top: 50%;
-webkit-transform: translateY(-50%);
-ms-transform: translateY(-50%);
transform: translateY(-50%);
left: 15px;
color: <?= $theme_color_4 ?>;
}
.contact-us .contact-form .submit-btn {
max-width: 350px;
width: 350px;
height: 50px;
background: <?= $text_color_1 ?>;
color: <?= $theme_color_4 ?>;
font-size: 16px;
line-height: 47px;
text-align: center;
border: 1px solid <?= $text_color_1 ?>;
cursor: pointer;
-webkit-box-shadow: 4px 4px 18px rgba(119, 119, 119, 0.125);
box-shadow: 4px 4px 18px rgba(119, 119, 119, 0.125);
-webkit-transition: all 0.3s ease-in;
-o-transition: all 0.3s ease-in;
transition: all 0.3s ease-in;
}
body.dark-mode .contact-us .contact-form .submit-btn {
max-width: 350px;
width: 350px;
height: 50px;
background: transparent;
color: <?= $theme_color_4 ?>;
font-size: 16px;
line-height: 47px;
text-align: center;
border: 1px solid <?= $theme_color_4 ?>;
cursor: pointer;
-webkit-box-shadow: 4px 4px 18px rgba(119, 119, 119, 0.125);
box-shadow: 4px 4px 18px rgba(119, 119, 119, 0.125);
-webkit-transition: all 0.3s ease-in;
-o-transition: all 0.3s ease-in;
transition: all 0.3s ease-in;
}
.logo-dark {
display: none;
}

body.dark-mode .logo-light {
display: none;
}

body.dark-mode .logo-dark {
display: block;
}
.fa-search:before {
content: "\f002";
color: <?= $text_color_1 ?>;
}
body.dark-mode .fa-search:before {
content: "\f002";
color: <?= $theme_color_4 ?>;
}
.bi-search::before {
content: "\f52a";
color: <?= $text_color_1 ?>;
}
body.dark-mode .bi-search::before {
content: "\f52a";
color: <?= $theme_color_4 ?>;
}
.logo-header .search-box.open-search {
width: 100%;
display: block;
position: absolute;
left: 0;
bottom: 0;
height: 4em;
z-index: 999999;
box-shadow: 0 0 30px -10px #3e3e3e4f;
background: <?= $theme_color_4 ?>;
color: white;
animation: .4s linear showSearch;
}
body.dark-mode .logo-header .search-box.open-search {
width: 100%;
display: block;
position: absolute;
left: 0;
bottom: 0;
height: 4em;
z-index: 999999;
box-shadow: 0 0 30px -10px #3e3e3e4f;
background: <?= $text_color_1 ?>;
color: white;
animation: .4s linear showSearch;
}

body.dark-mode .info-area .info-box .icon .img-fluid-service {
height: auto;
width: 100%;
filter: brightness(0) invert(1);
max-width: 50px;
}
.my-dropdown-menu .mt-1{
color:<?= $text_color_1 ?>;
}
body.dark-mode .my-dropdown-menu .mt-1{
color:<?= $text_color_1 ?>;
}

div.dataTables_wrapper div.dataTables_filter input {
margin-left: 0.5em;
display: inline-block;
width: auto;
}
body.dark-mode div.dataTables_wrapper div.dataTables_filter input {
margin-left: 0.5em;
display: inline-block;
border: 1px solid <?= $theme_color_4 ?>;
width: auto;
background: transparent;
}
body.dark-mode .user-dashbord .user-profile-info-area {
border: 1px solid <?= $theme_color_4 ?>;
}
body.dark-mode .user-dashbord .user-profile-details .account-info {
border: 1px solid <?= $theme_color_4 ?>;
padding: 20px 30px 30px;
}
body.dark-mode .user-dashbord .user-profile-details .account-info .header-area {
border-bottom: 1px solid <?= $theme_color_4 ?>;
padding-bottom: 12px;
}
body.dark-mode .user-dashbord .user-profile-details .account-info .edit-info-area .edit-info-area-form .input-field {
margin-bottom: 20px;
background: 0 0;
border-radius: 0;
border: 0 !important;
padding: 0 0 !important;
font-size: 14px;
color:<?= $theme_color_4 ?>;
border-bottom: 1px solid <?= $theme_color_4 ?> !important;
}
.form-control {
display: block;
width: 100%;
height: calc(1.5em + 0.75rem + 2px);
padding: 0.375rem 0.75rem;
font-size: 1rem;
font-weight: 400;
line-height: 1.5;
color: <?= $theme_color_4 ?>;
background-color: transparent;
background-clip: padding-box;
border: 1px solid <?= $theme_color_4 ?>;
border-radius: 0.25rem;
transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
body.dark-mode .text-left{
color:<?= $text_color_1 ?>;
}
body.dark-mode .user-dashbord .user-profile-info-area .links li {
border-bottom: 1px dashed <?= $theme_color_4 ?>;
}
.toggle-password{
float: right;
margin-right: 20px;
position: relative;
margin-top: -35px;
color: <?= $theme_color_1; ?>;
}
body.dark-mode .toggle-password{
float: right;
margin-right: 20px;
position: relative;
margin-top: -35px;
color: <?= $theme_color_4 ?>;
}
.user-dashbord .user-profile-details .order-history {
border: 1px solid rgba(0, 0, 0, 0.2);
padding: 20px 30px 30px;
}
.user-dashbord .user-profile-details .order-history .header-area {
border-bottom: 1px solid rgba(0, 0, 0, 0.2);
padding-bottom: 10px;
}

body.dark-mode .user-dashbord .user-profile-details .order-history {
border: 1px solid <?= $theme_color_4 ?>;
padding: 20px 30px 30px;
}
body.dark-mode .user-dashbord .user-profile-details .order-history .header-area {
border-bottom: 1px solid <?= $theme_color_4 ?>;
padding-bottom: 10px;
}
body.dark-mode .upload-img .file-upload-area .upload-file span {
background: transparent;
color: white;
border: 1px solid <?= $theme_color_4 ?>;
}
.item .info .price {
line-height: 20px;
margin-bottom: 10px;
color:<?= $text_color_1 ?>;
font-size: 18px;
font-weight: 500;
font-family: 'Montserrat', sans-serif;
}
body.dark-mode .item .info .price {
line-height: 20px;
margin-bottom: 10px;
color: <?= $theme_color_4 ?>;
font-size: 18px;
font-weight: 500;
font-family: 'Montserrat', sans-serif;
}
body.dark-mode a .mr-1,
body.dark-mode a .mx-1 {
margin-right: 0.25rem !important;
filter: brightness(0) invert(1);
}

body.dark-mode .favorite a img{
filter: brightness(0) invert(1);
}
#comment-area h3 .btn.login-btn, body.dark-mode #replay-area h5 .btn.login-btn {
padding: 5px 15px !important;
background: transparent;
border: 1px solid <?= $theme_color_4 ?>;
}
body.dark-mode .cartpage .right-area .order-box .order-btn{
background: transparent;
border: 1px solid <?= $theme_color_4 ?>;
color:<?= $theme_color_4 ?>;
}

.cartpage .left-area .table tbody tr {
border-left: 1px solid rgba(0, 0, 0, 0.2);
border-right: 1px solid rgba(0, 0, 0, 0.2);
border-bottom: 1px solid rgba(0, 0, 0, 0.2);
}

cartpage .left-area .table thead {
border: 1px solid rgba(0, 0, 0, 0.2);
}

.order-box {
box-shadow: 3px 3px 5px 0 rgba(0, 0, 0, 0.05);
border: 1px solid rgba(0, 0, 0, 0.1);
padding: 40px 25px 40px;
}

body.dark-mode .order-box {
box-shadow: 3px 3px 5px 0 rgba(0, 0, 0, 0.05);
border: 1px solid <?= $theme_color_4 ?>;
padding: 40px 25px 40px;
}
body.dark-mode .cartpage .left-area .table thead {
border: 1px solid <?= $theme_color_4 ?>;
}
body.dark-mode .cartpage .left-area .table tbody tr {
border-left: 1px solid <?= $theme_color_4 ?>;
border-right: 1px solid <?= $theme_color_4 ?>;
border-bottom: 1px solid <?= $theme_color_4 ?>;
}
body.dark-mode .logo-header .helpful-links ul li.my-dropdown .my-dropdown-menu .dropdownmenu-wrapper
.dropdown-cart-total span{
font-size:14px;
font-weight:600;
color:<?= $text_color_1 ?>;
}
body.dark-mode .logo-header .helpful-links ul li.my-dropdown .my-dropdown-menu .dropdownmenu-wrapper
.dropdown-cart-action .mybtn1 {
color:<?= $text_color_1 ?>;
border: 1px solid <?php echo $theme_color_2; ?>;
background: <?php echo $theme_color_2; ?>;
}
.order-box .order-list {
border-bottom: 1px solid rgba(0, 0, 0, 0.3);
}
body.dark-mode .order-box .order-list {
border-bottom: 1px solid <?= $theme_color_4 ?>;
}
body.dark-mode .icofont-close{
color:<?= $text_color_1 ?>;
}
body.dark-mode .modal-title h3{
color:<?= $text_color_1 ?>;
}
body.dark-mode .algin-simplify-checkout h5{
color:<?= $text_color_1 ?>;
}
body.dark-mode .col-lg-12 label{
color:<?= $text_color_1 ?>;
}
body.dark-mode svg rect{
color:<?= $theme_color_4 ?>;
fill:<?= $theme_color_4 ?>;
}
@media (max-width: 992px) {

.saxnavigation .menu-navigation {
background-color: <?= $text_color_1 ?>;
box-shadow: 0 0 20px #6464643b;
}
}
body.dark-mode .trending .item, 
body.dark-mode .flash-deals .item {
min-height: auto;
background:<?= $text_color_2 ?>;
}
body.dark-mode .phone-and-accessories .item{
background:<?= $text_color_2 ?>;
border: 2px solid <?= $text_color_1 ?> !important;
}
@media (min-width:768px) {
.trending .item, .flash-deals .item {
min-height: auto;
}
}
.partners .owl-carousel .owl-item img {
max-width: 120px;
}
body.dark-mode .partners .owl-carousel .owl-item img {
max-width: 120px;
filter: brightness(0) invert(1);
}
.top-header .left-content .list ul li .nice-select::after,
.right-content .nice-select::after {
border-bottom: 2px solid <?= $text_color_1; ?>;
border-right: 2px solid <?= $text_color_1; ?>;
}
body.dark-mode .top-header .left-content .list ul li .nice-select::after,
body.dark-mode .right-content .nice-select::after {
border-bottom: 2px solid <?= $theme_color_4; ?>;
border-right: 2px solid <?= $theme_color_4; ?>;
}
.top-header .left-content .list ul li .nice-select,
.top-header .content .right-content .list li.login .links,
.top-header .left-content .list ul li .currency-selector span,
.top-header ul li.my-dropdown.profilearea .profile .text,
.top-header .content .right-content .list li .nice-select,
.top-header .left-content .list ul li .language-selector i
{
color: <?php echo $text_color_1; ?>;
}
body.dark-mode .top-header .left-content .list ul li .nice-select,
body.dark-mode .top-header .content .right-content .list li.login .links,
body.dark-mode .top-header .left-content .list ul li .currency-selector span,
body.dark-mode .top-header ul li.my-dropdown.profilearea .profile .text,
body.dark-mode .top-header .content .right-content .list li .nice-select,
body.dark-mode .top-header .left-content .list ul li .language-selector i
{
color: <?= $theme_color_4; ?>;
}
@media (max-width: 992px) {
.saxnavigation .menu-navigation.showNav {
display: flex;
animation: 0.5s showNav;
background:<?= $theme_color_4; ?>;
}
body.dark-mode .saxnavigation .menu-navigation.showNav {
display: flex;
animation: 0.5s showNav;
background:<?= $text_color_1; ?>;
}

}
body.dark-mode .content p span font font{
color:<?= $theme_color_4; ?> !important;
}
.blog-details .blog-aside .serch-form button {
position: absolute;
top: 0;
right: 0;
height: 50px;
width: 50px;
color: <?= $text_color_1; ?>;
border: none;
background: 0 0;
cursor: pointer;
}
body.dark-mode .blog-details .blog-aside .serch-form button {
position: absolute;
top: 0;
right: 0;
height: 50px;
width: 50px;
color: <?= $theme_color_4; ?>;
border: none;
background: 0 0;
cursor: pointer;
}
.blog-details .blog-aside .serch-form input {
width: 100%;
height: 50px;
border: 2px solid #f2f2f2;
background: white;
padding: 0 50px 0 20px;
}
body.dark-mode .blog-details .blog-aside .serch-form input {
width: 100%;
height: 50px;
border: 2px solid #f2f2f2;
background: transparent;
padding: 0 50px 0 20px;
}
.blog-details .blog-content .content .tag-social-link {
background: #f3f6ff;
display: -webkit-box;
display: -ms-flexbox;
display: flex;
-webkit-box-pack: justify;
-ms-flex-pack: justify;
justify-content: space-between;
padding: 20px 20px 20px;
}
body.dark-mode .blog-details .blog-content .content .tag-social-link {
background: transparent;
display: -webkit-box;
border:1px solid <?= $theme_color_4; ?>;
display: -ms-flexbox;
display: flex;
-webkit-box-pack: justify;
-ms-flex-pack: justify;
justify-content: space-between;
padding: 20px 20px 20px;
}
.category-page .bg-white {
box-shadow: 3px 3px 5px 0 rgba(0, 0, 0, 0.05);
border: 1px solid rgba(0, 0, 0, 0.1);
padding: 30px 30px 30px;
}
body.dark-mode .category-page .bg-white {
box-shadow: 3px 3px 5px 0 rgba(0, 0, 0, 0.05);
border: 1px solid <?= $theme_color_4; ?>;
padding: 30px 30px 30px;
}
.category-page .bg-white .sub-category-menu .parent-category li ul li a i {
font-size: 13px;
color: #888;
-webkit-transition: all 0.3s ease-in;
-o-transition: all 0.3s ease-in;
transition: all 0.3s ease-in;
}
body.dark-mode .category-page .bg-white .sub-category-menu .parent-category li ul li a i {
font-size: 13px;
color: <?= $theme_color_4; ?>;
-webkit-transition: all 0.3s ease-in;
-o-transition: all 0.3s ease-in;
transition: all 0.3s ease-in;
}
.info-area .info-box .info .details .text {
color: <?= $text_color_1; ?>;
}
body.dark-mode .info-area .info-box .info .details .text {
color: <?= $theme_color_4; ?>;
}
.contact-us .contact-info {
max-width: 280px;
max-height: 180px;
padding: 20px 0 30px;
margin-bottom: 30px;
-webkit-transition: all 0.3s ease-in;
-o-transition: all 0.3s ease-in;
transition: all 0.3s ease-in;
display: -webkit-box;
display: -ms-flexbox;
display: flex;
flex-direction: column;
justify-content: space-between;
background: transparent;
border: 2px solid <?= $text_color_1; ?>;
box-shadow: 4px 4px 18px <?= $text_color_1; ?>;
align-items: center;
}
body.dark-mode .contact-us .contact-info {
max-width: 280px;
max-height: 180px;
padding: 20px 0 30px;
margin-bottom: 30px;
-webkit-transition: all 0.3s ease-in;
-o-transition: all 0.3s ease-in;
transition: all 0.3s ease-in;
display: -webkit-box;
display: -ms-flexbox;
display: flex;
flex-direction: column;
justify-content: space-between;
background: transparent;
border: 2px solid <?= $theme_color_4; ?>;
box-shadow: 4px 4px 18px <?= $theme_color_4; ?>;
align-items: center;
}

body.dark-mode .item {
overflow: visible !important;
position: relative;
margin: 5px;
overflow: hidden;
display: block;
-webkit-transition: unset;
-o-transition: unset;
transition: unset;
transition: all .3s;
height: 100%;
}
body.dark-mode .categori-item-area .row .remove-padding .item{
background:<?= $text_color_2 ?>;
}

.receipt-content .receipt-form .mybtn1 {
margin-left: 10px;
margin-right: 10px;
color:<?= $text_color_1; ?>;
}
body.dark-mode .receipt-content .receipt-form .mybtn1 {
margin-left: 10px;
margin-right: 10px;
color:<?= $theme_color_4; ?>;
}
.receipt-content .receipt-form .box-form label {
float: left;
padding-left: 20px;
color:<?= $text_color_1; ?>;
}
body.dark-mode .receipt-content .receipt-form .box-form label {
float: left;
padding-left: 20px;
color:<?= $theme_color_4; ?>;
}
.icofont-search-1:before{
color:<?= $text_color_1; ?>;
}
.slider-buttom-category .single-category .left .title {
font-size: 1rem;
word-wrap: anywhere;
margin-left: 1em;
}

.slider-buttom-category .single-category .right {
max-height: 10em;
filter: none !important;
}

.slider-buttom-category .single-category {
align-self: stretch;
height: 11em;
border: 1px solid <?= $text_color_1; ?>;
}

.item .info {
margin-top: 10px;
}

.item:hover .info {
top: -5px;
}

.marcas-page #title-brands {
padding-left: 20px;
}

@media (min-width:768px) {
.marcas-page #title-brands {
margin-left: 20px;
padding-left: 0;
}
}
.slider-buttom-category .single-category .right img {
width: auto;
height: auto;
-webkit-transition: 0.3s all;
-moz-transition: 0.3s all;
-o-transition: 0.3s all;
transition: 0.3s all;
object-fit: fill;
}
body.dark-mode .hot-and-new-item .categori .item-list li .single-box{
background: <?= $text_color_1; ?>;
border:3px solid <?= $text_color_1; ?>;
}
.hot-and-new-item .categori .item-list li .single-box .right-area {
-webkit-box-flex: 1;
-ms-flex: 1;
flex: 1;
height: 16vh;
display: grid;
background:<?= $theme_color_4; ?>;
}
body.dark-mode .hot-and-new-item .categori .item-list li .single-box .right-area {
-webkit-box-flex: 1;
-ms-flex: 1;
flex: 1;
height: 16vh;
display: grid;
background:transparent;
}
body.dark-mode .hot-and-new-item .categori .item-list li .single-box .right-area .text a {
color: <?= $theme_color_4; ?>;
}
.add-to-cart-btn-affiliate{
background: black;
color: white;
text-align: center;
width: 100%;
display: flex;
justify-content: center;
font-weight: 600;
padding: 1em;
align-items: center;
}
body.dark-mode .add-to-cart-btn-affiliate{
background: <?= $theme_color_4; ?>;
color: <?= $text_color_1; ?>;
text-align: center;
width: 100%;
display: flex;
justify-content: center;
font-weight: 600;
padding: 1em;
align-items: center;
}
.add-to-cart-btn-affiliate:hover{
cursor: pointer;
}
.alert-checkout{
color:#ff062d;
}
body.dark-mode .alert-checkout{
color:#ff062d;
}
#freight-form input {
width: 190px;
height: 35px;
background: #f3f8fc;
border: 1px solid rgba(0, 0, 0, 0.1);
padding: 0 10px;
font-size: 14px
}

#freight-form button {
height: 35px;
background: #fff;
border: 1px solid rgba(0, 0, 0, 0.15);
font-size: 14px;
text-transform: uppercase;
cursor: pointer;
-webkit-transition: all 0.3s ease-in;
-o-transition: all 0.3s ease-in;
transition: all 0.3s ease-in
}

#freight-form button:hover {
color: #fff
}

#shipping-area-aex .radio-design,
#shipping-area .radio-design {
border: 1px solid #ccc;
border-radius: 10px;
padding-top: 10px;
padding-right: 10px;
cursor: default
}

#shipping-area-aex .radio-design input,
#shipping-area .radio-design input {
cursor: default
}

#shipping-area-aex .radio-design .checkmark,
#shipping-area .radio-design .checkmark {
display: none
}
.logo-header .helpful-links ul li.my-dropdown .my-dropdown-menu .dropdownmenu-wrapper .dropdown-cart-total{
display:-webkit-box;
display:-ms-flexbox;
display:grid;
-webkit-box-pack:justify;
-ms-flex-pack:justify;
padding-top:5px;
}
.logo-header .helpful-links ul li.my-dropdown .my-dropdown-menu .dropdownmenu-wrapper .dropdown-cart-total span{
font-size:14px;
font-weight:600;
}
.logo-header .helpful-links ul li.my-dropdown .my-dropdown-menu .dropdownmenu-wrapper .dropdown-cart-action .mybtn1{
width:100%;
border-radius:50px;
text-align:center;
margin-top:10px;
margin-bottom:10px;
font-size:14px;
}
.login-area {
    padding: 30px 30px 39px;
    background: #fff;
    -webkit-box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}
body.dark-mode .login-area {
    padding: 30px 30px 39px;
    background: transparent;
    -webkit-box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid <?= $theme_color_4; ?>;
}
.login-area .form-input input {
    width: 100%;
    height: 50px;
    background: #f3f8fc;
    padding: 0 30px 0 45px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    font-size: 14px;
}
body.dark-mode .login-area .form-input input {
    width: 100%;
    height: 50px;
    background: transparent;
    padding: 0 30px 0 45px;
    border: 1px solid <?= $theme_color_4; ?>;
    font-size: 14px;
    color: <?= $theme_color_4; ?>;
}
body.dark-mode input[type="checkbox"] + label:before{
    border: 1px solid <?= $theme_color_4; ?>;
}
.sub-categori .right-area .item-filter .filter-list li select {
    height: 30px;
    border: 1px solid rgba(0, 0, 0, 0.2);
    padding: 0 15px;
    color: <?= $text_color_1; ?>;
}
body.dark-mode .sub-categori .right-area .item-filter .filter-list li select {
    height: 30px;
    border: 1px solid <?= $theme_color_4; ?>;
    padding: 0 15px;
    color:<?= $theme_color_4; ?>;
}
.login-area .social-area .text {
    color: <?= $text_color_1; ?>;
}
body.dark-mode .login-area .social-area .text {
    color: <?= $theme_color_4; ?>;
}
.item-filter .filter-list li select {
    margin: 0 15px;
    font-weight: 300;
    width: auto;
    color: <?= $text_color_1; ?>;
    border: 1px solid #e8e8e8;
    font-size: 14px;
}
body.dark-mode .item-filter .filter-list li select {
    margin: 0 15px;
    font-weight: 300;
    width: auto;
    color:<?= $theme_color_4; ?>;
    border: 1px solid #e8e8e8;
    font-size: 14px;
}