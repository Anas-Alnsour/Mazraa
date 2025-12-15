{{-- resources/views/about.blade.php --}}
@extends('layouts.app')
@section('title','About Us')

@section('content')
<div
  class="relative w-screen min-h-[calc(100vh-5rem)] overflow-hidden
         left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] flex items-center justify-center"
  style="
    background-image: url('{{ asset('backgrounds/Contact&about.JPG') }}');
    background-repeat: repeat;
    background-size: auto;
    background-position: top left;
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
  "
>
  <div class="relative max-w-6xl mx-auto px-4 py-12">
    <div class="flex justify-center">
      <div class="w-full max-w-3xl rounded-3xl p-10 md:p-12
                  bg-white/70 backdrop-blur-sm border border-white/40 shadow-2xl">
        <h2 class="text-4xl font-extrabold text-green-700 mb-6 text-center">About Us</h2>

        <p class="text-gray-800 mb-8 text-center leading-relaxed">
          Mazraa.com is a modern digital platform designed to simplify farm booking in one seamless experience…
        </p>

        <h3 class="text-2xl font-semibold text-green-700 mb-3">Our Vision</h3>
        <p class="text-gray-800 mb-6 leading-relaxed">
          To become the leading digital marketplace for farms, providing convenience, transparency, and trust…
        </p>

        <h3 class="text-2xl font-semibold text-green-700 mb-3">What We Offer</h3>
        <ul class="list-disc list-inside space-y-2 text-gray-800 mb-8">
          <li>Explore and book farms with detailed information and photos.</li>
          <li>Order supplies before arrival or during your stay.</li>
          <li>Book transportation to and from the farm.</li>
          <li>Secure online payments and favorites list.</li>
        </ul>

        <h3 class="text-2xl font-semibold text-green-700 mb-3">Our Values</h3>
        <ul class="list-disc list-inside space-y-2 text-gray-800">
          <li>Customer satisfaction is our top priority.</li>
          <li>Trust, transparency, and reliability.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection