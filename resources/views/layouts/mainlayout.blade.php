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
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!--Upto here-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    
    <style>
        body {
            font-family: inter;
            margin: 0;
            padding: 0;
            overflow:hidden;
        
        }
        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }
        .font-inter{
            font-family: 'Inter', sans-serif;
        }
        
        /* // styling placeholder for specific input with border-2 class */
        /* px-4::placeholder {
            color:#F5F8FF;
            font-size: 5px;
        } */
    </style>
</head>
<body class="">

    <!-- Toaster Notification Container -->
    <!-- For Success msg -->
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

    <!-- Toaster for Error -->
    @if(session('error'))
        <div id="toaster-error" class="fixed top-5 right-5 z-50 p-4 rounded-lg shadow-lg bg-red-500 text-white flex items-center space-x-2 transition-all duration-300 transform translate-x-full opacity-0">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p>{{ session('error') }}</p>
        </div>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toaster = document.getElementById('toaster-error');
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



    <nav>
        <!-- Main container -->
        <div class="flex flex-row justify-between items-center p-4 border-b border-gray-300 shadow-md bg-[#F8F8F8]">
            <!-- logo/Mark -->
            <div class="flex flex-row pl-10">
                <h1 class="text-2xl font-bold text-[#FF6767]">
                    To
                </h1>
                <h1 class="text-2xl font-bold text-black">
                    -Do
                </h1>
            </div>
            <!-- Search bar -->
            <div class="flex flex-row justify-center items-center border border-gray-300 shadow-md bg-[#F5F8FF] rounded-md w-[600px] h-[35px] pl-[2px]">
                <input type="text" name="SearchBar"  placeholder="Search your task here..." class="placeholder:text-[12px] w-[600px] h-[33px] py-2 px-4 bg-[#F5F8FF] focus:outline-none focus:ring-0 focus:border-transparent">
                <!-- <a href="#" class="flex justify-center items-center bg-[#FF6767] w-[35px] h-[35px] rounded-md"> -->
                <x-nav-icons href="#">
                    <i class="fa-solid fa-magnifying-glass text-white"></i>
                </x-nav-icons>    
                    
                <!-- </a> -->
            </div>
            <!-- Notification and calendar icon + current date and day -->
            <div class="flex flex-row justify-center items-center gap-6 pr-10">

                <!-- Icons -->
                <!-- Notification icon -->
                <x-nav-icons href="#">
                    <i class="fa-regular fa-bell text-white"></i>
                </x-nav-icons>
                <!-- Calender icon -->
                <x-nav-icons href="#">
                    <i class="fa-regular fa-calendar-days text-white"></i>
                </x-nav-icons>

                <!-- Date and Day -->
                
                <div class="flex flex-col justify-center items-center">
                    <p class="text-sm font-semibold text-gray-800">Monday,</p>
                    <p class="text-sm font-semibold text-[#3ABEFF]">12 June 2023</p>
                </div>  
            </div>           
        </div>
    </nav>

    <!-- Body after Nav bar -->

    <!-- Main-container -->
    <div class="flex flex-row w-full h-screen">

    <!-- Side bar -->
        <div class="w-[250px] h-full relative flex flex-col">
            @include('components.sidebar')
        </div>

    <!-- Main content -->  
        <div class="bg-[#F5F8FF] flex-1">
            @yield('mainsection')
        </div>  


</body>   
</html> 
