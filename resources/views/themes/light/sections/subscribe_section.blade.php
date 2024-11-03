<!-- subscribe 2 -->
<section class="subscribe subscribe-2" >
    <div class="container">
        <div class="subscribe-container-2" style=" background: url('{{isset($subscribe_section['single']['media']->image)?getFile($subscribe_section['single']['media']->image->driver,$subscribe_section['single']['media']->image->path):''}}') no-repeat">
            <div class="subscribe-content">
                <h3>{!! styleSentence($subscribe_section['single']['heading']??null,5) !!}</h3>
                <p>{!! $subscribe_section['single']['short_text'] !!}</p>
                <div class="subscribe-btn">
                    <a href="{{$subscribe_section['single']['media']->button_link??'#'}}" class="btn-1">{{$subscribe_section['single']['button_name']??''}} <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
                </div>
            </div>
        </div>
    </div>
</section>

