<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practitioner Profile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="text-gray-800 bg-[#F6F7F7] min-h-screen">

    <!-- Header -->
    <header class="max-w-[1400px] mx-auto px-4 lg:px-8 py-6 w-full flex justify-between items-center">
        <a href="#" class="flex items-center text-gray-500 hover:text-gray-800 text-sm font-medium transition-colors">
            <i class="ri-arrow-left-line mr-2"></i> Back
        </a>

        <div class="flex items-center space-x-5">
            <!-- Notification -->
            <div
                class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-600 relative shadow-sm cursor-pointer hover:bg-gray-50 transition-colors">
                <i class="ri-notification-3-line text-lg"></i>
                <div class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></div>
            </div>
            <!-- Profile Avatar -->
            <img src="https://i.pravatar.cc/150?img=11" alt="Profile"
                class="w-10 h-10 rounded-full object-cover shadow-sm">
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-[1400px] mx-auto px-4 lg:px-8 pb-16 w-full">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left Column: Profile Card & Gallery -->
            <div class="lg:col-span-4 xl:col-span-3 flex flex-col gap-8">

                <!-- Profile Card -->
                <div class="bg-white rounded-xl px-5 pt-12 pb-5 flex flex-col items-center border border-[#2E4B3D]/12">
                    <div class="relative mb-6">
                        <img src="{{ asset('frontend/assets/practitioner-profile-placeholder.png') }}"
                            alt="Dr. Maddy Ellison" class="w-38 h-38 rounded-full object-cover">
                        <div
                            class="absolute -bottom-1 right-0 w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-secondary cursor-pointer hover:bg-gray-200 transition-colors border-2 border-white shadow-sm">
                            <i class="ri-pencil-line text-lg"></i>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold font-sans! text-secondary mb-1">Dr. Maddy Ellison</h2>
                    <p class="text-lg text-gray-400 font-normal mb-10">Ayurveda Educator</p>

                    <div class="w-full px-4 space-y-4">
                        <a href="#"
                            class="flex items-center text-gray-400 hover:text-gray-700 transition-colors text-lg">
                            <i class="ri-lock-line mr-3 text-lg"></i>
                            <span class="font-normal">Change Password</span>
                        </a>
                        <a href="#"
                            class="flex items-center text-gray-400 hover:text-gray-700 transition-colors text-lg">
                            <i class="ri-logout-box-line mr-3 text-lg"></i>
                            <span class="font-normal">Logout</span>
                        </a>
                    </div>

                    <!-- Healing Sanctuary -->
                    <div class="w-full mt-20">
                        <h3 class="text-xl font-medium font-sans! text-gray-800 mb-6">Healing Sanctuary</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&q=80&w=600"
                                alt="Sanctuary Main"
                                class="col-span-2 w-full h-[140px] object-cover rounded-xl shadow-sm">
                            <img src="https://images.unsplash.com/photo-1629140727571-9b5c6f6267b4?auto=format&fit=crop&q=80&w=300"
                                alt="Sanctuary 1" class="w-full h-[110px] object-cover rounded-xl shadow-sm">
                            <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&q=80&w=300"
                                alt="Sanctuary 2" class="w-full h-[110px] object-cover rounded-xl shadow-sm">
                        </div>
                    </div>
                </div>



            </div>

            <!-- Right Column: Details & Stats -->
            <div class="lg:col-span-8 xl:col-span-9 flex flex-col gap-8">

                <!-- Personal Details Card -->
                <div class="bg-white rounded-xl p-12 border border-[#2E4B3D]/12 relative">
                    <button class="absolute top-8 right-8 text-gray-400 hover:text-secondary transition-colors">
                        <i class="ri-pencil-line text-2xl"></i>
                    </button>

                    <h2 class="text-2xl font-medium font-sans! text-secondary mb-8">Personal Details</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-y-8 gap-x-6">
                        <div>
                            <p class="text-lg text-gray-400 mb-1">Age</p>
                            <p class="text-lg font-normal text-gray-800">28 Years</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-400 mb-1">Gender</p>
                            <p class="text-lg font-normal text-gray-800">Male</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-400 mb-1">DOB</p>
                            <p class="text-lg font-normal text-gray-800">May 01, 1998</p>
                        </div>

                        <div>
                            <p class="text-lg text-gray-400 mb-1">Nationality</p>
                            <p class="text-lg font-normal text-gray-800">Indian</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-400 mb-1">Phone</p>
                            <p class="text-lg font-normal text-gray-800">+91 77743 66612</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-400 mb-1">Email</p>
                            <p class="text-lg font-normal text-gray-800">maddy013@ai.com</p>
                        </div>

                        <div class="md:col-span-3">
                            <p class="text-lg text-gray-400 mb-1">Address</p>
                            <p class="text-lg font-normal text-gray-800 leading-relaxed">No.49, NIC Road,
                                Kazhakkuttam,<br>Trivandrum, Kerala.</p>
                        </div>
                    </div>
                </div>

                <!-- Specialities & Conditions Card -->
                <div class="bg-white rounded-xl p-12 border border-[#2E4B3D]/12">
                    <!-- Specialities -->
                    <div class="relative mb-8">
                        <button class="absolute top-0 right-0 text-gray-400 hover:text-secondary transition-colors">
                            <i class="ri-pencil-line text-2xl"></i>
                        </button>

                        <h2 class="text-2xl font-medium font-sans! text-secondary mb-8">Specialities</h2>
                        <div class="flex flex-wrap gap-2.5">
                            <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">Lifestyle</span>
                            <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">Nutritional
                                Guidance</span>
                            <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">Immunity &
                                Vitality Coaching</span>
                            <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">Mindfulness
                                Support</span>
                        </div>
                    </div>

                    <hr class="border-[#C5C5C5] mb-8">

                    <!-- Conditions I support -->
                    <div class="relative">
                        <button class="absolute top-0 right-0 text-gray-400 hover:text-[#2B4C3B] transition-colors">
                            <i class="ri-pencil-line text-2xl"></i>
                        </button>

                        <h2 class="text-2xl font-medium font-sans! text-secondary mb-8">Conditions I support</h2>
                        <div class="flex flex-wrap gap-2.5">
                            <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">Systemic
                                Balance</span>
                            <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">Hormonal
                                Balance</span>
                            <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">Insomnia</span>
                            <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">Stress &
                                Anxiety</span>
                            <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">Toxic
                                Accumulation</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- 4 Stats Banner -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8">
            <div
                class="bg-white rounded-xl px-4 py-8 flex flex-col items-center justify-center text-center border border-[#2E4B3D]/12">
                <h3 class="text-5xl font-medium font-sans! text-[#1A1A1A] mb-4">100+</h3>
                <p class="text-xl text-gray-400 font-normal">Total Sessions</p>
            </div>
            <div
                class="bg-white rounded-xl px-4 py-8 flex flex-col items-center justify-center text-center border border-[#2E4B3D]/12">
                <h3 class="text-5xl font-medium font-sans! text-[#1A1A1A] mb-4">80+</h3>
                <p class="text-xl text-gray-400 font-normal">Total No.of Clients</p>
            </div>
            <div
                class="bg-white rounded-xl px-4 py-8 flex flex-col items-center justify-center text-center border border-[#2E4B3D]/12">
                <h3 class="text-5xl font-medium font-sans! text-[#1A1A1A] mb-4">3</h3>
                <p class="text-xl text-gray-400 font-normal">Today's Session</p>
            </div>
            <div
                class="bg-white rounded-xl px-4 py-8 flex flex-col items-center justify-center text-center border border-[#2E4B3D]/12">
                <h3 class="text-5xl font-medium font-sans! text-[#1A1A1A] mb-4">2</h3>
                <p class="text-xl text-gray-400 font-normal">Upcoming Sessions</p>
            </div>
        </div>

        <!-- History Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">

            <!-- Services History -->
            <div class="bg-white rounded-xl p-8 border border-[#2E4B3D]/12 flex flex-col">
                <h2 class="text-2xl font-medium font-sans! text-[#2B4C3B] mb-8">Services History</h2>

                <div class="flex-1 space-y-6">
                    <!-- Item 1 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 w-[198px]">
                            <img src="https://i.pravatar.cc/150?img=5" class="w-13 h-13 rounded-full object-cover">
                            <div>
                                <p class="text-base font-medium text-gray-800">Isabella</p>
                                <p class="text-sm text-gray-400 mt-0.5">Yesterday</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400 hidden sm:block">
                            11:30 AM - 12:30 PM
                        </div>
                        <div class="w-[120px] text-right">
                            <span
                                class="bg-[#38C683] text-white text-sm font-medium px-4 py-1.5 rounded-full inline-block text-center w-full">Completed</span>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 w-[198px]">
                            <img src="https://i.pravatar.cc/150?img=1" class="w-13 h-13 rounded-full object-cover">
                            <div>
                                <p class="text-base font-medium text-gray-800">Priyanka</p>
                                <p class="text-sm text-gray-400 mt-0.5">Yesterday</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400 hidden sm:block">
                            11:30 AM - 12:30 PM
                        </div>
                        <div class="w-[120px] text-right">
                            <span
                                class="bg-[#38C683] text-white text-sm font-medium px-4 py-1.5 rounded-full inline-block text-center w-full">Completed</span>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 w-[198px]">
                            <img src="https://i.pravatar.cc/150?img=12" class="w-13 h-13 rounded-full object-cover">
                            <div>
                                <p class="text-base font-medium text-gray-800">Stevenson</p>
                                <p class="text-sm text-gray-400 mt-0.5">Jan 09, 2026</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400 hidden sm:block">
                            10:00 AM - 11:00 AM
                        </div>
                        <div class="w-[120px] text-right">
                            <span
                                class="bg-[#38C683] text-white text-sm font-medium px-4 py-1.5 rounded-full inline-block text-center w-full">Completed</span>
                        </div>
                    </div>

                    <!-- Item 4 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 w-[198px]">
                            <img src="https://i.pravatar.cc/150?img=16" class="w-13 h-13 rounded-full object-cover">
                            <div>
                                <p class="text-base font-medium text-gray-800">Lisa Thomas</p>
                                <p class="text-sm text-gray-400 mt-0.5">Nov 17, 2025</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400 hidden sm:block">
                            10:00 AM - 11:00 AM
                        </div>
                        <div class="w-[120px] text-right">
                            <span
                                class="bg-[#38C683] text-white text-sm font-medium px-4 py-1.5 rounded-full inline-block text-center w-full">Completed</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center pt-2">
                    <a href="#"
                        class="text-lg text-gray-400 hover:text-gray-600 transition-colors font-normal">View
                        More...</a>
                </div>
            </div>

            <!-- Upcoming Services -->
            <div class="bg-white rounded-xl p-8 border border-[#2E4B3D]/12">
                <h2 class="text-2xl font-medium font-sans! text-secondary mb-8">Upcoming Services</h2>

                <div class="space-y-6">
                    <!-- Item 1 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 w-[198px]">
                            <img src="https://i.pravatar.cc/150?img=33" class="w-13 h-13 rounded-full object-cover">
                            <div>
                                <p class="text-base font-medium text-gray-800">Cody Fisher</p>
                                <p class="text-sm text-gray-400 mt-0.5">Yesterday</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400 hidden sm:block">
                            11:30 AM - 12:30 PM
                        </div>
                        <div class="w-[120px] text-right">
                            <span
                                class="bg-[#38C683] text-white text-sm font-medium px-4 py-1.5 rounded-full inline-block text-center w-full">Confirmed</span>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 w-[198px]">
                            <img src="https://i.pravatar.cc/150?img=60" class="w-13 h-13 rounded-full object-cover">
                            <div>
                                <p class="text-base font-medium text-gray-800">John Doe</p>
                                <p class="text-sm text-gray-400 mt-0.5">Jan 09, 2026</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400 hidden sm:block">
                            10:00 AM - 11:00 AM
                        </div>
                        <div class="w-[120px] text-right">
                            <span
                                class="bg-[#38C683] text-white text-[12px] font-medium px-4 py-1.5 rounded-full inline-block text-center w-full">Confirmed</span>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 w-[198px]">
                            <img src="https://i.pravatar.cc/150?img=43" class="w-13 h-13 rounded-full object-cover">
                            <div>
                                <p class="text-base font-medium text-gray-800">Courtney Henry</p>
                                <p class="text-sm text-gray-400 mt-0.5">Nov 17, 2025</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400 hidden sm:block">
                            10:00 AM - 11:00 AM
                        </div>
                        <div class="w-[120px] text-right">
                            <span
                                class="bg-[#F04B59] text-white text-sm font-medium px-4 py-1.5 rounded-full inline-block text-center w-full">Cancelled</span>
                        </div>
                    </div>

                    <!-- Item 4 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 w-[198px]">
                            <img src="https://i.pravatar.cc/150?img=68" class="w-13 h-13 rounded-full object-cover">
                            <div>
                                <p class="text-base font-medium text-gray-800">Jane Cooper</p>
                                <p class="text-sm text-gray-400 mt-0.5">Yesterday</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400 hidden sm:block">
                            11:30 AM - 12:30 PM
                        </div>
                        <div class="w-[120px] text-right">
                            <span
                                class="bg-[#F04B59] text-white text-sm font-medium px-4 py-1.5 rounded-full inline-block text-center w-full">Cancelled</span>
                        </div>
                    </div>

                    <!-- Item 5 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 w-[198px]">
                            <img src="https://i.pravatar.cc/150?img=35" class="w-13 h-13 rounded-full object-cover">
                            <div>
                                <p class="text-base font-medium text-gray-800">Bessie Cooper</p>
                                <p class="text-sm text-gray-400 mt-0.5">Jan 09, 2026</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400 hidden sm:block">
                            10:00 AM - 11:00 AM
                        </div>
                        <div class="w-[120px] text-right">
                            <span
                                class="bg-[#38C683] text-white text-[12px] font-medium px-4 py-1.5 rounded-full inline-block text-center w-full">Confirmed</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </main>

</body>

</html>