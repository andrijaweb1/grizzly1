<!-- tablet_afk.php -->
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablet - Čekanje skeniranja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .footer { display: none !important; }
        /* Ensure input is focusable but minimally visible */
        #checkInput { 
            position: absolute; 
            top: 10px; 
            left: 10px; 
            width: 100px; /* Wider for visibility during debugging */
            padding: 4px; 
            border: 2px solid red; /* Keep red border for now */
            z-index: 9999; /* Ensure it’s above other elements */
        }
    </style>
</head>
<body class="bg-black flex flex-col items-center justify-center h-screen text-center relative">
    <!-- Input for scanner -->
    <form action="check.php" method="GET" id="checkForm">
        <input 
            type="text" 
            name="user_code" 
            id="checkInput" 
            placeholder="Scan" 
            autofocus
        >
    </form>

    <!-- Veliki logo teretane -->
    <img src="public/images/logo.jpeg" alt="Logo teretane" class="mb-8 w-64 h-64 md:w-80 md:h-80">

    <!-- Veliki tekst "OBAVEZNO SKENIRANJE" -->
    <h1 class="text-4xl md:text-6xl font-bold text-white mb-12">OBAVEZNO SKENIRANJE</h1>

    <!-- Tekst "Andrija Gojaković Web" u donjem desnom uglu -->
    <p class="absolute bottom-4 right-4 text-md text-red-600 font-semibold">Developed By <br>Andrija Gojaković Web</p>

    <script>
        const input = document.getElementById('checkInput');

        // Function to focus the input
        function focusInput() {
            console.log('Attempting to focus input');
            // Ensure window is active
            if (document.hidden) {
                console.log('Window is hidden, focus may not work');
            }
            window.focus();
            // Ensure input is focusable
            input.removeAttribute('disabled');
            input.style.display = 'block'; // Ensure not hidden
            input.focus();
            input.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            // Check focus state after a short delay
            setTimeout(() => {
                if (document.activeElement === input) {
                    console.log('Input is focused');
                } else {
                    console.log('Input focus failed. Active element:', document.activeElement);
                }
            }, 100);
        }

        // Initial focus
        focusInput();

        // Refocus every 5 seconds
        setInterval(focusInput, 5000);

        // Handle click events
        document.addEventListener('click', (e) => {
            if (e.target !== input) {
                e.preventDefault();
                focusInput();
            }
        });

        // Handle blur event
        input.addEventListener('blur', () => {
            console.log('Input blurred, refocusing');
            setTimeout(focusInput, 0);
        });

        // Handle window focus and visibility
        window.addEventListener('focus', focusInput);
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                console.log('Tab visible, focusing input');
                focusInput();
            }
        });

        // Poll localStorage for focus signal
        let lastSignal = localStorage.getItem('focusInputSignal') || 0;
        console.log('Initial lastSignal:', lastSignal);
        setInterval(() => {
            const currentSignal = localStorage.getItem('focusInputSignal');
            console.log('Polling localStorage, current signal:', currentSignal, 'last signal:', lastSignal);
            if (currentSignal && currentSignal !== lastSignal) {
                console.log('New focus signal detected, focusing input');
                focusInput();
                lastSignal = currentSignal;
            }
        }, 500);

        // Auto-submit form on scan
        input.addEventListener('input', () => {
            if (input.value.length >= 6) { // Adjust based on barcode length
                console.log('Barcode scanned, submitting form');
                document.getElementById('checkForm').submit();
            }
        });
    </script>
</body>
</html>