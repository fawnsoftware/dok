@props([
    // toggle the animation on or off
    'animate' => true
])

<div class="@if($animate == true) aurora-animate @endif aurora-bg absolute inset-0 overflow-hidden"></div>
