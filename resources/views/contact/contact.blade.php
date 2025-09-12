@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-5xl font-extrabold text-center text-[#FF4B00] mb-16 tracking-tight">
        Être recontacté
    </h1>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col lg:flex-row">

        <!-- Section image  -->
        <div class="lg:w-1/2 bg-cover bg-center bg-no-repeat"
     style="background-image: url('{{ asset('images/image7.png') }}'); min-height: 450px;">
</div>


        <!-- Section formulaire -->
        <div class="lg:w-1/2 p-10 md:p-14 space-y-8">
            <h2 class="text-2xl md:text-3xl font-bold text-[#FF4B00]">
                Nous sommes disponibles sur :
            </h2>

            <ul class="text-gray-700 text-base leading-relaxed space-y-1">
                <li><strong>Téléphone :</strong> <a href="tel:0184806832" class="text-[#FF4B00] hover:underline">01 84 80 68 32</a></li>
                <li><strong>Whatsapp :</strong> <a href="tel:0184806832" class="text-[#FF4B00] hover:underline">01 84 80 68 32</a></li>
                <li><strong>Mail :</strong> <a href="elmarfoussiwebart@exemple.fr" class="text-[#FF4B00] underline">elmarfoussiwebart@exemple.fr</a></li>
                <li><strong>Disponibilité :</strong> du Lundi au Samedi de 9h à 18h</li>
            </ul>

            @if(session('success'))
                <div class="bg-green-50 text-green-700 px-4 py-2 rounded-lg border border-green-200 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('contact.send') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom :</label>
                    <input type="text" name="name" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#FF4B00]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email :</label>
                    <input type="email" name="email" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#FF4B00]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message :</label>
                    <textarea name="message" rows="4" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#FF4B00]"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-[#FF4B00] text-white font-semibold py-3 rounded-xl hover:bg-opacity-90 transition duration-200">
                    Envoyer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
