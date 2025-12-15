{{-- resources/views/contact.blade.php --}}
@extends('layouts.app')
@section('title','Contact Us')

@section('content')
<div
  class="relative w-screen min-h-[calc(100vh-5rem)] overflow-hidden
         left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] flex items-center justify-center"
  style="
    background-image: url('{{ asset('backgrounds/contact&about.JPG') }}');
    background-repeat: repeat;         /* مهم: تكرار الباترن */
    background-size: auto;             /* بلا تكبير/تصغير للحفاظ على الدرجة */
    background-position: top left;
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
  "
>
  {{-- صندوق زجاجي خفيف فقط، ما في Overlay على الصفحة --}}
  <div class="relative z-10 w-full max-w-xl mx-4 rounded-3xl p-8 md:p-10
              bg-white/70 backdrop-blur-sm border border-white/40 shadow-2xl">
    <h2 class="text-3xl md:text-4xl font-extrabold text-green-700 mb-2 text-center">Contact Us</h2>
    <p class="text-gray-700 mb-6 text-center">Have questions or feedback? Send us a message!</p>

    @if(session('success'))
      <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl text-center">
        {{ session('success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
      @csrf
      <div>
        <label class="block font-semibold text-gray-800 mb-2" for="name">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required
               class="w-full px-5 py-3 rounded-2xl border border-green-300 bg-white/80
                      focus:outline-none focus:ring-4 focus:ring-green-300/40 focus:border-green-500 transition"/>
      </div>

      <div>
        <label class="block font-semibold text-gray-800 mb-2" for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required
               class="w-full px-5 py-3 rounded-2xl border border-green-300 bg-white/80
                      focus:outline-none focus:ring-4 focus:ring-green-300/40 focus:border-green-500 transition"/>
      </div>

      <div>
        <label class="block font-semibold text-gray-800 mb-2" for="message">Message</label>
        <textarea id="message" name="message" rows="5" required
                  class="w-full px-5 py-3 rounded-2xl border border-green-300 bg-white/80
                         focus:outline-none focus:ring-4 focus:ring-green-300/40 focus:border-green-500 transition">{{ old('message') }}</textarea>
      </div>

      <button type="submit"
              class="w-full px-6 py-3 rounded-2xl bg-green-600 text-white font-semibold
                     shadow-lg hover:bg-green-700 hover:shadow-xl transition">
        Send Message
      </button>
    </form>
  </div>
</div>
@endsection