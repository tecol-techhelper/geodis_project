<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>403 Denied Access</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="body_403">
    <div class="hover">
        <div class="background">
            <div class="door">403</div>
            <div class="rug"></div>
        </div>
        <div class="foreground">
            <div class="bouncer">
                <div class="head">
                    <div class="neck"></div>
                    <div class="eye left"></div>
                    <div class="eye right"></div>
                    <div class="ear"></div>
                </div>
                <div class="body"></div>
                <div class="arm"></div>
            </div>
            <div class="poles">
                <div class="pole left"></div>
                <div class="pole right"></div>
                <div class="rope"></div>
            </div>
        </div>
        <div class="speech-bubble hidden" id="speechBubble">
            <a href="{{ Auth::check() ? route('dashboard') : route('login') }}">
                Please Go Home
            </a>
        </div>
    </div>
    <script>
        const hoverArea = document.querySelector('.hover');
        const speechBubble = document.getElementById('speechBubble');
        let timeoutId;

        hoverArea.addEventListener('mouseenter', function() {
            // Mostramos la burbuja después de la animación
            timeoutId = setTimeout(() => {
                speechBubble.classList.remove('hidden');
                speechBubble.classList.add('show');
            }, 1500); // 1.5s = duración de la animación
        });

        hoverArea.addEventListener('mouseleave', function() {
            // Cancelamos si el usuario sale antes de que aparezca
            clearTimeout(timeoutId);

            // Ocultamos la burbuja
            speechBubble.classList.remove('show');
            speechBubble.classList.add('hidden');
        });
    </script>

</body>

</html>
