@extends('layouts.store', ['title' => __('Home')])
@section('content')
@include('layouts.store/left-sidebar-default-template')
<style>
    .pr-50{
        padding-right: 50px;
    }
    .pt-70{
        padding-top: 70px;
    }
    .customnav-active{
        font-weight: bold !important; 
        color: rgb(9, 84, 228) !important;
    }
</style>
<style>

    /*hover effect*/
    .grow img{
        transition: 1s ease;
    }

    .grow img:hover{
        -webkit-transform: scale(1.2);
        -ms-transform: scale(1.2);
        transform: scale(1.2);
        transition: 1s ease;
    }
    /*hover effect*/

    .gradient-text{
        font-weight: 900;
        text-align: center;
        background: linear-gradient(to right, #04286D, #084DD3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .image-container {
        position: relative;
    }

    .image-container img {
        width: 100%;
    }

    .overlay1 {    
        position: absolute;
        top: 22%;
        left: 20%;
        z-index: 2;
        max-width: 35%;
        max-height: 25%;
    }

    .overlay2 {
        position: absolute;
        top: 45%;
        right: 22%;
        z-index: 1;
        max-width: 35%;
        max-height: 25%;
    }

    /* @keyframes slidy {
        0% { left: 0%; }
        20% { left: 0%; }
        25% { left: -100%; }
        45% { left: -100%; }
        50% { left: -200%; }
        70% { left: -200%; }
        75% { left: -300%; }
        95% { left: -300%; }
        100% { left: -400%; }
    }

    div#slider { 
        overflow: hidden; 
    }
    div#slider figure img { 
        width: 20%; 
        float: left; 
    }
    div#slider figure { 
        position: relative;
        width: 500%;
        margin: 0;
        left: 0;
        text-align: left;
        font-size: 0;
        animation: 30s slidy infinite; 
        padding-top: 4%;
    } */
    @keyframes slidy {
        0% { left: 0%; }
        33% { left: 0%; }
        38% { left: -100%; }
        71% { left: -100%; }
        76% { left: -200%; }
        100% { left: -200%; }
    }

    div#slider { 
        overflow: hidden; 
    }
    div#slider figure img { 
        width: 33.33%; 
        float: left; 
    }
    div#slider figure { 
        position: relative;
        width: 300%;
        margin: 0;
        left: 0;
        text-align: left;
        font-size: 0;
        animation: 30s slidy infinite; 
        padding-top: 4%;
    }
    @media (max-width: 767px) {
        div#slider figure img { 
            border-radius: 5%;
        }
    }

</style>

<div class="p-md-4 p-xs-2">
    <div id="slider">
        <figure>
            @if(count($banners))
                @foreach($banners as $banner)
                    @php
                    $url = '';
                    if($banner->link == 'category'){
                        if($banner->category != null){
                            $url = route('categoryDetail', $banner->category->slug);
                        }
                    }
                    else if($banner->link == 'vendor'){
                        if($banner->vendor != null){
                            $url = route('vendorDetail', $banner->vendor->slug);
                        }
                    }
                    @endphp
                    @if($url)
                        <a href="{{$url}}">
                    @endif
                    <img src="{{$banner->image['image_fit'] . '1920/1080' . $banner->image['image_path']}}" style="border-radius: 30px">
                    @if($url)
                        </a>
                    @endif
                @endforeach
            @endif
        </figure>
    </div>
    <section id="banner" class="pt-70 mb-4">
        <div class="banner">
            <!-- Image banner -->
            <img src="{{asset('images/web-banner.jpg')}}" alt="Banner Image" class="img-fluid">
        </div>
    </section>
    <section class="pt-70 mb-4">
        <div class="image-container">
            <img src="{{asset('images/second-section.png')}}" alt="Base image">
            <div class="grow">
                <a href="{{ route('courier')}}">
                    <img src="{{asset('images/delivery.png')}}" alt="Overlay image 1" class="overlay1">
                </a>
            </div>
            <div class="grow">
                <a href="{{ route('recovery')}}">
                    <img src="{{asset('images/recovery.png')}}" alt="Overlay image 2" class="overlay2">
                </a>
            </div>
        </div>
    </section>

    <section id="motto" class="pt-70 mb-4">
        <div class="card"> 
            <div class="card-content">
              <h2 class="gradient-text" style="font-size: 48px;">About Wayno</h2>
              <h4 class="gradient-text">Delivering Excellence, Every Mile</h4>
              <h4 style="font-weight: 500;text-align: center;">We are driven by a commitment to excellence in every delivery we make. From parcels to packages, we ensure swift, secure, and reliable transportation, embodying the spirit of efficiency and dedication. With a focus on customer satisfaction and operational excellence, we strive to exceed expectations, connecting people and businesses seamlessly across Abu Dhabi and beyond.</h4>
              <img src="{{asset('images/about.png')}}" alt="wayno about" class="img-fluid">
            </div>
        </div>
          
    </section>
    
    {{-- <section id="faq" class="pt-70 mb-4">
        <div class="faq">
            <!-- FAQ content here -->
            <h2 class="text-secondary">Frequently Asked Questions</h2>
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Question 1: What is Wayno?
                            </button>
                        </h2>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            Wayno is an ecommerce application based in UAE which initially provide courier service
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Question 2: How can I place a courier in wayno?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <p>Follow these simple steps:</p>
                            <ul style="padding-left: inherit;">
                                <li style="display: list-item;">Create an account with your mobile number.</li>
                                <li style="display: list-item;">You can enter the pickup and drop off location.</li>
                                <li style="display: list-item;"> You can add pickup and drop off contact number , and tap on the "Confirm Shipment" button.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Question 3: What days do you deliver?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <p>Delivery is available 7 days a week.</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Question 4: What modes of payment are available?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <ul style="padding-left: inherit;">
                                <li style="display: list-item;">Cash on delivery (CoD).</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFive">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Question 5: How do I contact Wayno customer support?
                            </button>
                        </h2>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <p>In case your order is not up to the mark, do not hesitate to reach out to us via email us at <b>support@wayno.ae</b>.</p>
                        </div>
                    </div>
                </div>
                <!-- Add more FAQ items -->
            </div>
        </div>
    </section> --}}
</div>
<section id="journey" class="pt-70 mb-4">
    <div class="banner">
        <!-- Image banner -->
        <img src="{{asset('images/wayno-for-abudhabi.jpg')}}" alt="Banner Image" class="img-fluid">
    </div>
</section>
@endsection
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const links = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('section');

        const observerOptions = {
            rootMargin: '-50% 0px -50% 0px',
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const targetId = entry.target.getAttribute('id');
                    links.forEach(link => {
                        if (link.getAttribute('href').substring(1) === targetId) {
                            link.classList.add('customnav-active');
                        } else {
                            link.classList.remove('customnav-active');
                        }
                    });
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            observer.observe(section);
        });

        // Close mobile menu on click
        $('.navbar-nav>li>a').on('click', function(){
            $('.navbar-collapse').collapse('hide');
        });

        // JavaScript to scroll to sections smoothly
        links.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                
                links.forEach(function(link) {
                    link.classList.remove("customnav-active");
                });
                
                this.classList.add("customnav-active");
                
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
@endsection
