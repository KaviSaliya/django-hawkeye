<section class="terms_condition mt_120 xs_mt_100">
    <div class="container">
        <div class="row wow fadeInUp">
            <div class="col-12">
                <div class="privacy_policy_text mb-5">
                    @foreach(collect($privacy_policy['multiple'])->toArray() as $item)
                        <h4>{!! $item['heading'] !!}</h4>
                        <p>{!! $item['description'] !!}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
