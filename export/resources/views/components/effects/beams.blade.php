@props([
    // toggle the animation on or off
    'animate' => true,

    // The number of beams to show
    'beam_count' => 5,
])

<div class="@if($animate == true) beams-animate @endif beams">
    @for ($i = 0; $i < $beam_count; $i++)
          <div class="beam-wrap">
            <div class="beam-path"></div>
            <div class="beam"></div>
        </div>
    @endfor
</div>
