@props([
    // toggle the animation on or off
    'animate' => true,
])

<div class="@if($animate == true) ripple-animate @endif ripple">
    <div class="ripple-circle"></div>
    <div class="ripple-circle"></div>
    <div class="ripple-circle"></div>
    <div class="ripple-circle"></div>
    <div class="ripple-circle"></div>
</div>
