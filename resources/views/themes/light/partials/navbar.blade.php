
@if(basicControl()->navbar_style)
    @include($theme.'partials.'.basicControl()->navbar_style)
@else
    @include($theme.'partials.navbar_1')
@endif

