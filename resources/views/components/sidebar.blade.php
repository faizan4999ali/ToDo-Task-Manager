
<!-- Top small container -->
<div class="bg-[#F5F8FF] w-full h-[50px]">
    
</div>

<!-- Main Content container/ Side bar starts here -->

<div class="bg-black w-full h-full rounded-br-lg rounded-tr-lg flex flex-col pt-[90px]" ">
    <div class="p-[30px] flex flex-col gap-3" >
        <!-- Dashboard button -->
        <x-sidebar-buttons href="#" icon="fa-solid fa-house" text="Dashboard" :active="request()->is('/')"/>
        <!-- Vital Tasks -->
        <x-sidebar-buttons href="#" icon="fa-solid fa-exclamation" text="Vital Tasks" :active="request()->is('vitaltasks')"/>
        <!-- My Task button -->
        <x-sidebar-buttons href="{{route('viewtasks')}}" icon="fa-regular fa-square-check" text="My Task" :active="request()->routeIs('viewtasks')"/>
        <!-- Task Categories -->
        <x-sidebar-buttons href="{{route('categories')}} " icon="fa-solid fa-list" text="Task Categories" :active="request()->routeIs('categories')"/>
        <!-- Setting -->
        <x-sidebar-buttons href="#" icon="fa-solid fa-gear" text="Setting" :active="request()->is('settings')"/>
        <!-- Logout -->
        <x-sidebar-buttons href="{{route('logout')}}" icon="fa-solid fa-right-from-bracket" text="Logout" :active="false"/>
    </div>
</div>




<!-- Profile Image at the top above both containers used absolute -->
<!-- Its down because its overlapped/its above other two containers -->
<div class="absolute inset-x-0 mt-[10px] flex flex-col justify-center items-center">
    <img src="{{ asset('images/Myphoto.jpg') }}" alt="Profile" class="w-20 h-20 rounded-full border-2 border-white">
    <h3 class="text-white text-sm" >Faizan Ali</h3>
    <h3 class="text-white text-[10px]" >Faizan4999ali@gmail.com</h3>
</div>