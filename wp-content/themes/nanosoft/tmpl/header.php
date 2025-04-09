<!-- Google tag -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-8TQW6JYE43"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments)};
  gtag('js', new Date());

  gtag('config', 'G-8TQW6JYE43');
</script>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800;900&display=swap");


    a,
    .button {
        -webkit-transition: all .3s ease-out 0s;
        -moz-transition: all .3s ease-out 0s;
        -ms-transition: all .3s ease-out 0s;
        -o-transition: all .3s ease-out 0s;
        transition: all .3s ease-out 0s
    }

    a:focus,
    .button:focus {
        text-decoration: none;
        outline: none
    }

    a {
        color: #635c5c;
        text-decoration: none
    }

    a:hover {
        color: #fff
    }

    a:focus,
    a:hover {
        text-decoration: none
    }

    a,
    button {
        color: #fff;
        outline: medium none
    }

    button:focus,
    input:focus,
    input:focus,
    textarea,
    textarea:focus {
        outline: 0
    }

    input:focus::-moz-placeholder {
        opacity: 0;
        -webkit-transition: .4s;
        -o-transition: .4s;
        transition: .4s
    }

    h1 a,
    h2 a,
    h3 a,
    h4 a,
    h6 a {
        color: inherit
    }

    h5 a {
        color: #132E43 !important;
    }

    ul {
        margin: 0px;
        padding: 0px
    }

    li {
        list-style: none
    }

    hr {
        border-bottom: 1px solid #eceff8;
        border-top: 0 none;
        margin: 30px 0;
        padding: 0
    }


    ul {
        margin: 0px;
        padding: 0px
    }

    li {
        list-style: none
    }

    hr {
        border-bottom: 1px solid #eceff8;
        border-top: 0 none;
        margin: 30px 0;
        padding: 0
    }

    .animated-button {
        font-family: "Jost", sans-serif;
        color: #fff;
        background: #e95611;
        border: none;
        border-radius: 5px;
        padding: 12px 30px 12px 30px !important;
        text-transform: capitalize;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.25);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 200ms ease-in;
        width: fit-content;
    }

    .animated-button span {
        z-index: 2;
    }

    .animated-button span:last-child {
        text-transform: capitalize;
        font-size: 18px;
        font-weight: 400;
    }

    .animated-button:before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        transform: translate3d(-100%, 0, 0);
        background-color: #fff;
        z-index: 1;
        transition: all 200ms ease-in;
    }

    .animated-button:hover {
        color: #000;
    }

    .animated-button:hover:before {
        transform: translate3d(0, 0, 0);
    }

    .header-right-btn {
        padding: 8px 20px 7px 20px !important;
    }

    .header-right-btn span {
        font-size: 16px !important;
    }

    .ml-15 {
        margin-left: 15px;
    }

    .f-right {
        float: right;
    }

    .sticky-bar {
        left: 0;
        margin: auto;
        position: fixed;
        top: 0;
        width: 100%;
        -webkit-box-shadow: 0 10px 15px rgba(25, 25, 25, 0.1);
        box-shadow: 0 10px 15px rgba(25, 25, 25, 0.1);
        z-index: 9999;
        -webkit-animation: 300ms ease-in-out 0s normal none 1 running fadeInDown;
        animation: 300ms ease-in-out 0s normal none 1 running fadeInDown;
        -webkit-box-shadow: 0 10px 15px rgba(25, 25, 25, 0.1)
    }


    .btn {
        background: #e95611;
        font-family: "Jost", sans-serif;
        text-transform: inherit !important;
        padding: 22px 32px;
        color: #fff !important;
        cursor: pointer;
        display: inline-block;
        font-size: 18px !important;
        font-weight: 400 !important;
        border-radius: 0px;
        line-height: 1;
        line-height: 0;
        cursor: pointer;
        -moz-user-select: none;
        transition: color 0.4s linear;
        position: relative;
        z-index: 1;
        border: 0;
        overflow: hidden
    }

    .btn::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        width: 102%;
        height: 102%;
        background: #ec583a;
        z-index: 1;
        border-radius: 0px;
        transition: transform 0.5s;
        transition-timing-function: ease;
        transform-origin: 0 0;
        transition-timing-function: cubic-bezier(0.5, 1.6, 0.4, 0.7);
        transform: scaleX(0);
        border-radius: 0px
    }

    .btn i {
        padding-right: 11px
    }

    .btn:hover {
        color: #fff !important
    }

    .btn:hover::before {
        transform: scaleX(1);
        z-index: -1
    }

    .btn_10 {
        font-family: "Jost", sans-serif;
        color: #fff;
        background: #e95611;
        border: none;
        padding: 12px 30px 12px 30px !important;
        text-transform: capitalize;
        border-radius: 0px;
        font-size: 18px;
        font-weight: 400;
        display: inline-block;
        border-radius: 0px
    }

    .btn_10 i {
        color: #ffffff;
        font-size: 13px;
        font-weight: 500;
        position: relative;
        left: 26px;
        -webkit-transition: all .4s ease-out 0s;
        -moz-transition: all .4s ease-out 0s;
        -ms-transition: all .4s ease-out 0s;
        -o-transition: all .4s ease-out 0s;
        transition: all .4s ease-out 0s
    }

    .btn_10 img {
        display: inline-block;
        margin-left: 8px;
        -webkit-transition: all .4s ease-out 0s;
        -moz-transition: all .4s ease-out 0s;
        -ms-transition: all .4s ease-out 0s;
        -o-transition: all .4s ease-out 0s;
        transition: all .4s ease-out 0s
    }

    .btn_10:hover img {
        margin-left: 9px
    }

    .btn_10:hover i {
        left: 30px
    }

    .browse-btn2 {
        color: #e95611 !important;
        font-weight: 400;
        font-size: 16px;
        position: relative;
        display: inline-block
    }

    .browse-btn2 img {
        margin-left: 5px;
        display: inline-block
    }

    .header-area .header-top {
        background: #fff
    }

    .header-area .header-top .header-info-left ul li {
        color: #000;
        display: inline-block;
        margin-right: 30px;
        padding: 13px 15px 12px 32px;
        font-size: 14px;
        font-weight: 400;
        position: relative
    }

    .header-area .header-top .header-info-left ul li:last-child {
        margin-right: 0;
        padding-right: 0;
        border-right: 0
    }

    .header-area .header-top .header-info-left ul li::before {
        position: absolute;
        content: "";
        width: 20px;
        height: 1px;
        left: 0px;
        top: 50%;
        transform: translateY(-50%);
        background: #000
    }

    .header-area .header-top .header-info-left ul li i {
        margin-right: 12px;
        color: #000
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .header-area .header-top .header-info-left ul li {
            margin-right: 9px;
            padding: 13px 1px 12px 29px
        }
    }

    @media only screen and (min-width: 576px) and (max-width: 767px) {
        .header-area .header-top .header-info-left ul li {
            margin-right: 9px;
            padding: 13px 1px 12px 29px
        }
    }

    @media only screen and (min-width: 576px) and (max-width: 767px) {
        .header-area .header-top .header-info-left ul li {
            margin-right: 10px;
            font-size: 13px
        }
    }

    .header-transparent {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        z-index: 9;
    }

    .header-area .header-top .header-info-right .header-social li {
        display: inline-block
    }

    .header-area .header-top .header-info-right .header-social li a {
        color: #000;
        font-size: 16px;
        padding-left: 19px
    }

    .header-area .header-top .header-info-right .header-social li a i {
        -webkit-transition: all .4s ease-out 0s;
        -moz-transition: all .4s ease-out 0s;
        -ms-transition: all .4s ease-out 0s;
        -o-transition: all .4s ease-out 0s;
        transition: all .4s ease-out 0s;
        transform: rotateY(0deg);
        -webkit-transform: rotateY(0deg);
        -moz-transform: rotateY(0deg);
        -ms-transform: rotateY(0deg);
        -o-transform: rotateY(0deg)
    }

    .header-area .header-top .header-info-right .header-social li a:hover i {
        color: #e95611;
        transform: rotateY(180deg);
        -webkit-transform: rotateY(180deg);
        -moz-transform: rotateY(180deg);
        -ms-transform: rotateY(180deg);
        -o-transform: rotateY(180deg)
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .header-area .header-bottom {
            padding: 10px 20px
        }
    }

    @media only screen and (min-width: 576px) and (max-width: 767px) {
        .header-area .header-bottom {
            padding: 10px 20px
        }
    }

    @media (max-width: 575px) {
        .header-area .header-bottom {
            padding: 10px 0px
        }
    }

    .main-header .main-menu ul>li {
        display: inline-block;
        position: relative;
        z-index: 1;
        margin: 0px 14px
    }

    .main-header .main-menu ul>li:last-child {
        margin: 0
    }

    @media only screen and (min-width: 992px) and (max-width: 1199px) {
        .main-header .main-menu ul>li {
            margin: 0px 3px
        }
    }

    .main-header .main-menu ul>li a {
        font-family: "Jost", sans-serif;
        color: #1D2547;
        font-weight: 500;
        padding: 21px 7px;
        display: block;
        font-size: 15px;
        -webkit-transition: all .3s ease-out 0s;
        -moz-transition: all .3s ease-out 0s;
        -ms-transition: all .3s ease-out 0s;
        -o-transition: all .3s ease-out 0s;
        transition: all .3s ease-out 0s;
        text-transform: capitalize;
        position: relative
    }

    .main-header .main-menu ul>li a:hover {
        color: #e95611;
    }

    @media only screen and (min-width: 992px) and (max-width: 1199px) {
        .main-header .main-menu ul>li a {
            font-size: 16px
        }
    }

    .main-header .main-menu ul>li a::before {
        content: "";
        position: absolute;
        width: 0;
        bottom: 10px;
        right: 5px;
        left: -5px;
        height: 2px;
        z-index: 1;
        background: #fff;
        -webkit-transition: all .4s ease-out 0s;
        -moz-transition: all .4s ease-out 0s;
        -ms-transition: all .4s ease-out 0s;
        -o-transition: all .4s ease-out 0s;
        transition: all .4s ease-out 0s
    }

    .main-header .main-menu ul>li:hover>a {
        /* color: #fff */
    }

    .main-header .main-menu ul>li:hover>a::before {
        left: 0;
        right: auto;
        width: 100%;
        -webkit-transition: all .4s ease-out 0s;
        -moz-transition: all .4s ease-out 0s;
        -ms-transition: all .4s ease-out 0s;
        -o-transition: all .4s ease-out 0s;
        transition: all .4s ease-out 0s
    }

    .main-header .main-menu ul ul.submenu {
        position: absolute;
        width: 345px;
        background: #fff;
        left: 0;
        top: 120%;
        visibility: hidden;
        opacity: 0;
        box-shadow: 0 0 10px 3px rgba(0, 0, 0, 0.05);
        padding: 17px 0;
        -webkit-transition: all .3s ease-out 0s;
        -moz-transition: all .3s ease-out 0s;
        -ms-transition: all .3s ease-out 0s;
        -o-transition: all .3s ease-out 0s;
        transition: all .3s ease-out 0s
    }

    .main-header .main-menu ul ul.submenu>li {
        margin-left: 7px;
        display: block
    }

    .main-header .main-menu ul ul.submenu>li:last-child {
        margin: 0px 8px
    }

    .main-header .main-menu ul ul.submenu>li>a {
        padding: 6px 10px !important;
        font-size: 15px;
        color: #1D2547;
        /* font-weight: 700; */
        text-transform: capitalize
    }

    .main-header .main-menu ul ul.submenu>li>a::before {
        position: unset
    }

    .main-header .main-menu ul ul.submenu>li>a:hover {
        color: #e95611;
        background: none;
        letter-spacing: 0.3px
    }

    .main-header .logo {
        float: left
    }

    .main-header ul>li:hover>ul.submenu {
        visibility: visible;
        opacity: 1;
        top: 100%
    }

    .header-transparent {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        z-index: 9
    }

    .header-area .header-sticky {
        background: #fff
    }

    .header-area .header-sticky.sticky-bar {
        background: #fff
    }

    .slicknav_nav {
        background-color: #222222;
        padding-bottom: 1.5rem;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .header-area .header-sticky.sticky-bar {
            padding: 15px 0px
        }
    }

    @media only screen and (min-width: 576px) and (max-width: 767px) {
        .header-area .header-sticky.sticky-bar {
            padding: 15px 20px
        }
    }

    @media (max-width: 575px) {
        .header-area .header-sticky.sticky-bar {
            padding: 15px 0px
        }
    }

    .mobile_menu {
        position: absolute;
        right: 0px;
        top: 18px;
        width: 100%;
        z-index: 99
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .header-right-btn {
            float: left;
            margin-left: 0
        }
    }

    @media only screen and (min-width: 576px) and (max-width: 767px) {
        .header-right-btn {
            float: left;
            margin-left: 0
        }
    }

    @media (max-width: 575px) {
        .header-right-btn {
            float: left;
            margin-left: 0
        }

        .animated-button {
            padding: 5px 15px 5px 15px !important;
            margin-left: 15px;
        }
    }

    .header-right-btn .header-btn {
        font-family: "Jost", sans-serif;
        border: none;
        padding: 8px 18px !important;
        text-transform: capitalize !important;
        cursor: pointer;
        color: #fff !important;
        display: inline-block !important;
        font-size: 17px !important;
        font-weight: 500 !important;
        background: #e95611;
        border-radius: 0px;
        position: relative
    }

    /* .header-right-btn .header-btn::after {
    position: absolute;
    content: "";
    width: 20px;
    height: 1px;
    left: 34px;
    top: 50%;
    transform: translateY(-50%);
    background: #fff
} */

    .header-right-btn .header-btn:hover {
        color: #f2f2f2 !important
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .header-right-btn .header-btn:hover {
            background: #e95611 !important
        }
    }

    @media only screen and (min-width: 576px) and (max-width: 767px) {
        .header-right-btn .header-btn:hover {
            background: #e95611 !important
        }
    }

    @media (max-width: 575px) {
        .header-right-btn .header-btn:hover {
            background: #e95611 !important
        }
    }

    #navigation {
        display: flex;
        align-items: center
    }

    /* .nav-search {
        cursor: pointer;
        color: #fff;
        font-size: 22px
    } */

    .logo img {
        max-width: 75%;
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        /* .nav-search {
            margin-bottom: 8px;
            margin-left: 18px
        } */
    }

    @media only screen and (min-width: 576px) and (max-width: 767px) {
        /* .nav-search {
            margin-bottom: 8px;
            margin-left: 18px
        } */
    }

    @media (max-width: 575px) {

        /* .nav-search {
            margin-bottom: 8px;
            margin-left: 18px
        }

        img {
            max-width: 75%;
        } */
        .logo img {
            z-index: 10;
            position: relative;
        }
    }

    .slicknav_menu {
        padding: 0;
    }

    .slicknav_btn {
        margin: 0;
        margin-bottom: 1rem;
        margin-right: 5px;
        background-color: #132E43;
    }
</style>
<div class="header-area">
    <div class="main-header ">
        <div class="header-top d-none d-sm-block">
            <div class="container" style="max-width: 98%;">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="d-flex justify-content-between flex-wrap align-items-center">
                            <div class="header-info-left">
                                <ul>
                                    <li><a href="tel:(469) 418-9511" style="color: #000;">(469) 418-9511</a></li>
                                    <li><a href="mailto:info@reetechusa.com" style="color: #000;">info@reetechusa.com</a></li>
                                </ul>
                            </div>
                            <div class="header-info-right d-none d-md-block">
                                <ul class="header-social">
                                    <li><a href="https://www.facebook.com/reetechusa"><i class="fab fa-facebook"></i></a></li>
                                    <li> <a href="https://www.tumblr.com/reetechusa"><i class="fab fa-tumblr-square"></i></a></li>
                                    <li><a href="https://www.linkedin.com/company/reetechusa-dallas/"><i class="fab fa-linkedin"></i></a></li>
                                    <li> <a href="https://in.pinterest.com/reetechusa/"><i class="fab fa-pinterest"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom header-sticky">
            <div class="container" style="max-width: 98% !important;">
                <div class="row align-items-center">
                    <!-- Logo -->
                    <div class="col-xl-2 col-lg-2 col-sm-12 logoArea">
                        <div class="logo">
                            <a href="<?php echo esc_url(home_url('/')); ?>"><img src="https://reetechusa.com/wp-content/uploads/2024/11/logo.png" alt="" width="100%"></a>
                        </div>
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                    <div class="col-xl-10 col-lg-10">
                        <!-- Main-menu -->
                        <div class="main-menu f-right d-none d-lg-block">
                            <nav>
                                <ul id="navigation">
                                    <li><a href="about">About Us</a>
                                        <ul class="submenu">
                                            <li><a href="mission-vision-and-values">Who We Are</a></li>
                                            <li><a href="industries">Who We Serve</a></li>
                                            <li><a href="government-project">Government Contract</a></li>
                                            <li><a href="blogs">Blogs</a></li>
                                            <li><a href="careers">Career</a></li>
                                            <li><a href="contact-us">Contact Us</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="digital-transformation">Digital Transformation</a>
                                        <ul class="submenu">
                                            <li><a href="app-development">App Development</a></li>
                                            <li><a href="web-development">Web Development</a></li>
                                            <li><a href="intelligent-automation-ai">Intelligent Automation (AI)</a></li>
                                            <li><a href="digital-marketing">Digital Marketing</a></li>
                                            <li><a href="seo">SEO</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="managed-services">Managed Services</a>
                                        <ul class="submenu">
                                            <li><a href="data-migration">Data Migration</a></li>
                                            <li><a href="data-modernization">Data Modernization</a></li>
                                            <li><a href="data-entry-and-indexing">Data Entry and Indexing</a></li>
                                            <li><a href="data-discovery-visualization">Data Discovery & Visualization</a></li>
                                            <li><a href="master-data-data-quality-management-mdm">Master Data/Data Quality Management (MDM)</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="engineering-services">Engineering Services</a>
                                        <ul class="submenu">
                                            <li><a href="product-design">Product Design</a></li>
                                            <li><a href="simulation-analysis-fea">Simulation Analysis (FEA)</a></li>
                                            <li><a href="3d-modeling">3D Modeling</a></li>
                                            <li><a href="cad-data-migration">CAD Data Migration</a></li>
                                            <li><a href="pcb-design">PCB Design</a></li>
                                            <li><a href="validation-testing">Validation Testing</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="staff-augmentation">Staff Augmentation</a>
                                        <ul class="submenu">
                                            <li><a href="virtual-assistant">Virtual Assistant</a></li>
                                            <li><a href="outsourced-recruitment-support">Recruitment Process Outsourcing</a></li>
                                            <li><a href="it-team-on-demand">IT Team on Demand</a></li>
                                            <li><a href="process-management-services">Process Management Services</a></li>
                                            <li><a href="finance-accounting">Finance & Accounting</a></li>
                                            <li><a href="call-centre-services">Call Centre Services</a></li>
                                        </ul>
                                    </li>
                                    <!-- <li>
                                                <div class="nav-search search-switch">
                                                    <i class="ti-search"></i>
                                                </div>
                                            </li> -->
                                    <li>
                                        <div class="header-right-btn f-right ml-15 animated-button" onclick="openLink('contact-us', false)">
                                            <span>Get Free Quote</span>
                                        </div>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <!-- Mobile Menu -->
                </div>
            </div>
        </div>
    </div>
</div>