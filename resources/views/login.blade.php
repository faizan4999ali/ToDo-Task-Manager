

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <!--Upto here-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
             /* Using the asset() helper to generate the correct URL */
            background-image: url('{{ asset('images/Background.png') }}');
            background-size: cover;
            background-repeat: no-repeat;
            font-family: Montserrat, sans-serif;
        
        }
        
        /* // styling placeholder for specific input with border-2 class */
        .my-1::placeholder {
            color:#999999;
            font-size: 12px; /* This is the same size as Tailwind's 'text-lg' */
        }
    </style>
</head>
<body class="">
 <!-- Toaster Notification Container -->
    @if(session('success'))
        <div id="toaster-success" class="fixed top-5 right-5 z-50 p-4 rounded-lg shadow-lg bg-green-500 text-white flex items-center space-x-2 transition-all duration-300 transform translate-x-full opacity-0">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p>{{ session('success') }}</p>
        </div>
    @endif

     @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toaster = document.getElementById('toaster-success');
                if (toaster) {
                    // Show the toaster
                    setTimeout(() => {
                        toaster.classList.remove('translate-x-full', 'opacity-0');
                    }, 100);

                    // Hide the toaster after 5 seconds
                    setTimeout(() => {
                        toaster.classList.add('translate-x-full', 'opacity-0');
                        // Remove the element from the DOM after it's hidden
                        setTimeout(() => toaster.remove(), 500); 
                    }, 5000);
                }
            });
        </script>
    @endif




        <!-- Main-container -->
        <div class="flex flex-row pt-5 pr-5 w-full">

            <!-- inside first container -->
            <div class="flex flex-col justify-start h-auto w-1/2 pl-[60px] pt-[110px]">
                <h1 class="text-xl font-bold pb-5" >Sign In</h1>
                <Form class="flex flex-col gap-6" action="{{route('login')}}" method="POST">
                    @csrf
                    <!-- input -->
                     <!-- Email -->
                    <div class="flex flex-row justify-center items-center border border-black rounded h-9 ">
                        
                        <i class="fa-solid fa-envelope text-xl pl-1 "></i>
                        <label for="email"></label>
                        <input type="email" name="email" placeholder="Enter Email" class=" my-1 mr-1 p-2 w-full h-8 focus:outline-none focus:border-transparent focus:ring-0"  required>
                    </div>
                    <!-- Password -->
                    <div class="flex flex-row justify-center items-center border border-black rounded h-9">
                        
                        <i class="fa-solid fa-lock text-xl pl-1 "></i>
                        <label for="password"></label>
                        <input type="password" name="password" placeholder="Enter Password" class=" my-1 mr-1 p-2 w-full h-8 focus:outline-none focus:border-transparent focus:ring-0" required>
                    </div>
                    <!-- Submit Button -->
                    <x-sign-button>
                        Sign In
                    </x-sign-button>
                    <p>
                         Don't have an account? <a href="{{ route('signup') }}" class="text-blue-500 underline">Register Now!</a>
                    </p>
                </Form>
        </div>     
            <!-- inside second container/Form -->
            <div class="flex justify-center items w-1/2 pt-6">
                <img src="{{ asset('images/signInImage.png') }}" alt="Login Image" class="w-[500px] h-[500px]">

            </div>
        </div>
        
</body>
</html>   
                
        
