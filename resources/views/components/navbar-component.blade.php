<nav x-data="{ isOpen: false, DNnavOpen: false }" style="z-index: 9999" class="bg-gray-800 container rounded-b-md relative">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-10 md:h-16 items-center justify-between">
      <div class="flex items-center">
        <div class="shrink-0">
          <h2 class="font-bold text-base md:text-xl text-gray-300">{{$slot}}</h2>
        </div>
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-4">
            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
              <x-nav-link :active="request()->is('/')" href='/'>Home</x-nav-link>
              <x-nav-link :active="request()->is('dn/adm/sap')" href='/dn/adm/sap'>Upload PCC</x-nav-link>
              {{-- <button class="bg-red-400">dsad</button> --}}
                {{-- <div style="width: 140px" class="text-white relative ">
                  <button @click="DNnavOpen = !DNnavOpen" style="border-width: 1px; outline:none; border-color: #6b7280" class="overflow-hidden px-3 py-2 border-gray-500 rounded-full text-left w-full text-sm font-medium flex items-center gap-2 text-white" href='/dn/adm/sap'>
                    DN ADM 
                    <i :class="{ '-rotate-90': !DNnavOpen, '': DNnavOpen}" class="duration-200 fa-solid fa-caret-down">
                    </i>
                  </button>

                    <div x-show="DNnavOpen" :class="{'scale-y-0 duration-200 opacity-0': !DNnavOpen,'scale-y-100 origin-top duration-200 opacity-100': DNnavOpen}" style="top: 45px" class="bg-gray-800 h-fit pb-3 absolute left-0 w-full rounded-br-md rounded-bl-md ">
                  <div  :class="{'scale-y-0 duration-100 origin-top opacity-0': !DNnavOpen,'scale-y-100 origin-top opacity-100 duration-200': DNnavOpen}" class="bg-gray-800 h-fit pb-3 absolute top-[50px] left-0 w-full rounded-br-md rounded-bl-md ">
                    <div class="flex flex-col"> 
                      <x-nav-link :active="request()->is('dn/adm/sap')" href='/dn/adm/sap'>DN ADM SAP</x-nav-link>
                      <x-nav-link :active="request()->is('dn/adm/kep')" href='/dn/adm/kep'>DN ADM KEP</x-nav-link>
                      <x-nav-link :active="request()->is('dn/adm/kap')" href='/dn/adm/kap'>DN ADM KAP</x-nav-link>
                    </div>
                  </div>
                </div> --}}
                {{-- <button @click="DNnavOpen = !DNnavOpen" type="button" class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white " aria-controls="mobile-menu" aria-expanded="false">
                  <span class="absolute -inset-0.5"></span>
                  <span class="sr-only">Open main menu</span>
                  <!-- Menu open: "hidden", Menu closed: "block" -->
                  <svg :class="{ 'hidden': DNnavOpen, 'block': !DNnavOpen}"  class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                  </svg>
                  <!-- Menu open: "block", Menu closed: "hidden" -->
                  <svg :class="{ 'block': DNnavOpen, 'hidden': !DNnavOpen}" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                  </svg>
                </button>
                <div x-show="DNnavOpen" class=" absolute top-full mt-1 z-50 right-7 rounded-b-md overflow-hidden" id="mobile-menu">
                  <div class="flex flex-col space-y-1 px-2 pb-3 pt-2 sm:px-3 bg-gray-800">
                    <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                            <x-nav-link :active="request()->is('/')" href='/'>Home</x-nav-link>
                            <x-nav-link :active="request()->is('dn/adm/sap')" href='/dn/adm/sap'>DN ADM SAP</x-nav-link>
                            <x-nav-link :active="request()->is('dn/adm/kep')" href='/dn/adm/kep'>DN ADM KEP</x-nav-link>
                            <x-nav-link :active="request()->is('dn/adm/kap')" href='/dn/adm/kap'>DN ADM KAP</x-nav-link>
                  </div>
                </div> --}}
          </div>
        </div>
      </div>
      <div class="hidden md:block">
        <div class="ml-4 flex items-center md:ml-6">

          <!-- Profile dropdown -->
          <div class="relative ml-3">
            <div class="h-9 w-9 rounded-full flex items-center justify-center bg-white">
                <img class="h-8 w-8 rounded-full" src="https://media.licdn.com/dms/image/v2/D560BAQFAz4zMQsnRAQ/company-logo_200_200/company-logo_200_200/0/1683249151464?e=2147483647&v=beta&t=uMypPyvfsUZc0cit7ztKSxlYPNXGLY2a0I1RO-0v4IQ" alt="">
            </div>
          </div>
        </div>
      </div>
      <div class="-mr-2 flex md:hidden">
        <!-- Mobile menu button -->
        <button @click="isOpen = !isOpen" type="button" class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white " aria-controls="mobile-menu" aria-expanded="false">
          <span class="absolute -inset-0.5"></span>
          <span class="sr-only">Open main menu</span>
          <!-- Menu open: "hidden", Menu closed: "block" -->
          <svg :class="{ 'hidden': isOpen, 'block': !isOpen}"  class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
          <!-- Menu open: "block", Menu closed: "hidden" -->
          <svg :class="{ 'block': isOpen, 'hidden': !isOpen}" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile menu, show/hide based on menu state. -->
  <div x-show="isOpen" class="md:hidden absolute top-full mt-1 z-50 right-7 rounded-b-md overflow-hidden" id="mobile-menu">
    <div class="flex flex-col space-y-1 px-2 pb-3 pt-2 sm:px-3 bg-gray-800">
      <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
              <x-nav-link :active="request()->is('/')" href='/'>Home</x-nav-link>
              <x-nav-link :active="request()->is('dn/adm/sap')" href='/dn/adm/sap'>Upload PCC</x-nav-link>
              {{-- <x-nav-link :active="request()->is('dn/adm/sap')" href='/dn/adm/sap'>DN ADM SAP</x-nav-link>
              <x-nav-link :active="request()->is('dn/adm/kep')" href='/dn/adm/kep'>DN ADM KEP</x-nav-link>
              <x-nav-link :active="request()->is('dn/adm/kap')" href='/dn/adm/kap'>DN ADM KAP</x-nav-link> --}}
    </div>
  </div>
  </nav>