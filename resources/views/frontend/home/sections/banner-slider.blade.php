<section id="wsus__banner">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="wsus__banner_content">
                    <div class="row banner_slider">

                        @foreach ($sliders as $slider)



                        {{-- <div class="col-xl-12">
                            <div class="wsus__single_slider" style="background: url({{asset($slider->banner)}});">
                                <div class="wsus__single_slider_text">
                                    <h3>{{$slider->type}}</h3>
                                    <h1>{{$slider->title}}</h1>
                                    <h6>Bắt đầu {{ $settings->currency_icon}}{{$slider->starting_price	}}</h6>
                                    <a class="common_btn" href="{{$slider->btn_url}}">Mua ngay</a> --}}

                            <div class="col-xl-12">
                                <div class="wsus__single_slider" style="background: url({{ asset($slider->banner) }}); background-size: contain; background-position: center; height: 500px;" >
                                    <div class="wsus__single_slider_text">
                                        <h3 class="text-primary fw-semibold">{{ $slider->type }}</h3>
                                        <h1 class="text-white fw-bold display-4"
                                            style="text-shadow: 1px 1px 4px rgba(8, 228, 107, 0.5);">
                                            {{ $slider->title }}
                                        </h1>
                                        <h6 class="mt-2 text-lg">
                                            Bắt đầu 
                                            <span class="text-green-400 font-extrabold text-2xl drop-shadow-md">
                                                <span style=" color: #fff;">{{ $slider->starting_price }}{{ $settings->currency_icon }}</span>
                                            </span>
                                        </h6>
                                        <a class="common_btn" href="{{ $slider->btn_url }}">shop now</a>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
