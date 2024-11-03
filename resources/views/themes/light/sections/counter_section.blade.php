
<!-- counter -->
<section class="counter-section">
    <div class="leaf-shape">
        <img src="{{asset($themeTrue.'images/shape/leaf.png')}}" alt="shape">
    </div>
    <div class="counter-doller">
        <img src="{{asset($themeTrue.'images/shape/dollor-3.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="counter-container">
            <div class="row">
                @foreach(collect($counter_section['multiple'])->toArray() as $item)
                    <div class="col-lg-3 col-md-6 mt-3">
                        <div class="counter">
                            <div class="odometer-box">
                                <h5 class="odometer" data-count="{{(int)$item['media']->count??0}}">00</h5>
                                <div class="odometer-text">{{$item['prefix']??''}}</div>
                            </div>
                            <p>{{$item['countable_item_name']}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- counter -->
