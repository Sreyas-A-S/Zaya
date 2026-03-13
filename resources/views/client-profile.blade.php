<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Profile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media (max-width: 1023px) {
            .mobile-hidden {
                display: none !important;
            }
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden text-gray-800 bg-white">

    <!-- Sidebar -->
    <aside class="w-[288px] bg-[#FFFFFF] border-r border-[#2E4B3D]/12 hidden lg:flex lg:flex-col h-full shrink-0">
        <div>
            <a href="#"
                class="flex items-center pt-8 ps-8 pe-2 pb-2 text-gray-500 hover:text-gray-800 text-sm font-medium mb-4">
                <i class="ri-arrow-left-line mr-2"></i> Back
            </a>

            <nav class="relative">
                <a href="#" class="flex items-center px-8 py-3 bg-[#F6F6F6] text-[#2B4C3B] font-normal ">
                    <i class="ri-user-line mr-3 text-lg"></i> Dashboard
                </a>
                <a href="#"
                    class="flex items-center px-8 py-3 text-[#8F8F8F] hover:bg-[#F6F6F6] hover:text-secondary  font-normal transition-colors">
                    <i class="ri-pulse-line mr-3 text-lg"></i> Health Journey
                </a>
                <a href="#"
                    class="flex items-center px-8 py-3 text-[#8F8F8F] hover:bg-[#F6F6F6] hover:text-secondary  font-normal transition-colors">
                    <i class="ri-calendar-event-line mr-3 text-lg"></i> Bookings
                </a>
                <a href="#"
                    class="flex items-center px-8 py-3 text-[#8F8F8F] hover:bg-[#F6F6F6] hover:text-secondary  font-normal transition-colors">
                    <i class="ri-wallet-3-line mr-3 text-lg"></i> Transaction Vault
                </a>
            </nav>
        </div>
        <img src="{{ asset('frontend/assets/client-profile-floating-img.png') }}" alt="Floating Image"
            class="w-[248px] h-auto absolute bottom-0 left-0 pointer-events-none">
    </aside>

    <!-- Main Content -->
    <main class="flex-1 h-full overflow-y-auto bg-[#F6F7F7]">
        <div class="max-w-6xl mx-auto px-5 py-4 lg:px-10 lg:py-10">

            <!-- Header -->
            <header
                class="flex flex-col lg:flex-row flex-wrap gap-y-5 justify-center lg:justify-between lg:items-center mb-10">
                <!-- mobile top header -->
                <div class="flex flex-1 justify-between items-center pt-3 lg:hidden">
                    <a href="#" class="text-gray-500 hover:text-gray-800 text-sm font-medium">
                        <i class="ri-arrow-left-line mr-2"></i> Back
                    </a>
                    <div class="flex gap-5">
                        <!-- Coin -->
                        <div
                            class="w-10 h-10 rounded-full bg-[#FFD166] flex items-center justify-center text-white relative shadow-sm cursor-pointer hover:bg-yellow-400 transition-colors ">
                            <span class="font-bold text-lg text-yellow-100">€</span>
                            <div class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full">
                            </div>
                        </div>

                        <!-- Notification -->
                        <div
                            class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 relative shadow-sm cursor-pointer hover:bg-gray-50 transition-colors">
                            <i class="ri-notification-3-line text-lg"></i>
                            <div class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-y-4 justify-center  text-center items-center gap-8">
                    <img src="https://i.pravatar.cc/150?img=32" alt="Profile"
                        class="w-25 lg:w-20 h-25 lg:h-20 rounded-full object-cover p-1 bg-white">
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold font-sans! text-secondary mb-2">Quinn Emerson</h1>
                        <div class="flex flex-wrap gap-y-1 items-center text-gray-500 text-sm space-x-4">
                            <span class="flex items-center"><i class="ri-map-pin-line mr-1"></i> London, UK</span>
                            <span class="flex items-center"><i class="ri-mail-line mr-1"></i> Client ID: Z-99821</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center items-center flex-wrap gap-y-4 space-x-4">
                    <!-- Coin -->
                    <div
                        class="w-10 h-10 rounded-full bg-[#FFD166] hidden lg:flex items-center justify-center text-white relative shadow-sm cursor-pointer hover:bg-yellow-400 transition-colors ">
                        <span class="font-bold text-lg text-yellow-100">€</span>
                        <div class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full"></div>
                    </div>

                    <!-- Notification -->
                    <div
                        class="w-10 h-10 rounded-full bg-white border border-gray-200 hidden lg:flex items-center justify-center text-gray-600 relative shadow-sm cursor-pointer hover:bg-gray-50 transition-colors">
                        <i class="ri-notification-3-line text-lg"></i>
                        <div class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full"></div>
                    </div>

                    <button
                        class="bg-[#2B4C3B] hover:bg-[#1f372a] text-white px-5 py-2.5 rounded-full font-normal text-base transition-colors shadow-sm cursor-pointer">
                        Book a New Consultation
                    </button>
                </div>
            </header>

            <!-- Mobile Tab Navigation -->
            <div class="lg:hidden flex space-x-6 overflow-x-auto scrollbar-hide mb-5">
                <button onclick="switchMobileTab('dashboard')" id="m-tab-dashboard"
                    class="leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors">Dashboard</button>
                <button onclick="switchMobileTab('health')" id="m-tab-health"
                    class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Health
                    Journey</button>
                <button onclick="switchMobileTab('bookings')" id="m-tab-bookings"
                    class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Bookings</button>
                <button onclick="switchMobileTab('transactions')" id="m-tab-transactions"
                    class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Transaction
                    Vault</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 md:gap-8 md:mb-8 mb-5">
                <!-- Left Column -->
                <div id="col-left" class="lg:col-span-5 xl:col-span-4 space-y-5 md:space-y-8">
                    <!-- Identity Hub -->
                    <div id="section-identity" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-medium font-sans! text-secondary">Identity Hub</h2>
                            <button class="text-gray-400 hover:text-gray-600"><i class="ri-pencil-line"></i></button>
                        </div>

                        <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-6">
                            <div>
                                <p class="text-base text-gray-400 mb-1">Age</p>
                                <p class="text-base font-normal text-gray-800">28 Years</p>
                            </div>
                            <div>
                                <p class="text-base text-gray-400 mb-1">Gender</p>
                                <p class="text-base font-normal text-gray-800">Female</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-base text-gray-400 mb-1">DOB</p>
                                <p class="text-base font-normal text-gray-800">May 01, 1998</p>
                            </div>
                        </div>

                        <hr class="border-[#DDDDDD] mb-6">

                        <div class="space-y-6">
                            <div>
                                <p class="text-base text-gray-400 mb-1">Email</p>
                                <p class="text-base font-normal text-gray-800">aquinnem@design.com</p>
                            </div>
                            <div>
                                <p class="text-base text-gray-400 mb-1">Phone</p>
                                <p class="text-base font-normal text-gray-800">+44 7700 800077</p>
                            </div>
                            <div>
                                <p class="text-base text-gray-400 mb-1">Address</p>
                                <p class="text-base font-normal text-gray-800 leading-snug">No. 49, Featherstone
                                    St.<br>London, EC1Y 8SY, UK.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Vault Snippet -->
                    <div id="section-transactions" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
                        <h2 class="text-xl font-sans! font-medium text-secondary mb-6">Transaction Vault</h2>
                        <div class="space-y-5">
                            <!-- Invoice 1 -->
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-normal text-gray-800 mb-0.5">Invoice #88751</p>
                                    <p class="text-xs text-gray-400">Dec 7, 2025</p>
                                </div>
                                <span
                                    class="px-3 py-1 bg-[#EEF2EF] text-[#2B4C3B] text-sm font-normal rounded-full">Open</span>
                            </div>
                            <!-- Invoice 2 -->
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-normal text-gray-800 mb-0.5">Invoice #13742</p>
                                    <p class="text-xs text-gray-400">Nov 28, 2025</p>
                                </div>
                                <span
                                    class="px-3 py-1 bg-[#EEF2EF] text-[#2B4C3B] text-sm font-normal rounded-full">Open</span>
                            </div>
                            <!-- Invoice 3 -->
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-normal text-gray-800 mb-0.5">Invoice #70159</p>
                                    <p class="text-xs text-gray-400">Feb 17, 2025</p>
                                </div>
                                <span
                                    class="px-3 py-1 bg-[#EEF2EF] text-[#2B4C3B] text-sm font-normal rounded-full">Open</span>
                            </div>
                        </div>
                        <div class="mt-6 text-center">
                            <a href="#" class="text-xs text-gray-400 hover:text-gray-800 font-normal tracking-wide">See
                                all</a>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div id="col-right" class="lg:col-span-7 xl:col-span-8 space-y-5 md:space-y-8">
                    <!-- Consultations -->
                    <div id="section-consultations" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
                        <h2 class="text-xl  font-sans! font-medium text-secondary mb-5">Consultations</h2>

                        <!-- Tabs -->
                        <div class="flex space-x-2 mb-4">
                            <button id="tab-upcoming" onclick="switchConsultationTab('upcoming')"
                                class="px-4 py-1.5 w-1/2 text-center bg-[#CFFAD8] text-[#2FA749] text-sm font-normal rounded-lg transition-all cursor-pointer">Upcoming</button>
                            <button id="tab-completed" onclick="switchConsultationTab('completed')"
                                class="px-4 py-1.5 w-1/2 text-center bg-[#F9F9F9] text-[#8C8C8C] text-sm font-normal rounded-lg transition-all cursor-pointer">Completed</button>
                        </div>

                        <!-- Upcoming Sessions -->
                        <div id="content-upcoming" class="space-y-6">
                            <!-- Session 1 -->
                            <div class="flex gap-2 justify-between items-center">
                                <div>
                                    <div class="flex flex-wrap items-center space-x-2 mb-1">
                                        <p class="text-base font-normal text-gray-800">Life Coach</p>
                                        <span class="text-gray-800 text-base">•</span>
                                        <p class="text-xs text-gray-600 font-normal">(Session with Dr. Evelyn Reed)</p>
                                    </div>
                                    <p class="text-xs text-gray-400">Mar 07, 2026 - 11:30 AM</p>
                                </div>
                                <button
                                    class="px-5 py-2 bg-[#D1EBE1] text-[#2B4C3B] hover:bg-[#bce0d2] rounded-full text-xs font-normal transition-colors">Reschedule</button>
                            </div>
                            <!-- Session 2 -->
                            <div class="flex gap-2 justify-between items-center">
                                <div>
                                    <div class="flex flex-wrap items-center space-x-2 mb-1">
                                        <p class="text-base font-normal text-gray-800">Naturopathy</p>
                                        <span class="text-gray-800 text-base">•</span>
                                        <p class="text-xs text-gray-600 font-normal">(Session with Dr. Nahala Nazim)</p>
                                    </div>
                                    <p class="text-xs text-gray-400">Mar 28, 2026 - 5:30 PM</p>
                                </div>
                                <button
                                    class="px-5 py-2 bg-[#D1EBE1] text-[#2B4C3B] hover:bg-[#bce0d2] rounded-full text-xs font-normal transition-colors">Reschedule</button>
                            </div>
                        </div>

                        <!-- Completed Sessions -->
                        <div id="content-completed" class="space-y-6 hidden">
                            <!-- Completed Session 1 -->
                            <div
                                class="flex gap-2 justify-between items-center pb-6 border-b border-gray-50 last:border-0 last:pb-0">
                                <div>
                                    <div class="flex flex-wrap items-center space-x-2 mb-1">
                                        <p class="text-base font-normal text-gray-800">Art Therapy</p>
                                        <span class="text-gray-800 text-base">•</span>
                                        <p class="text-xs text-gray-600 font-normal">(Session with Dr. Bennett)</p>
                                    </div>
                                    <p class="text-xs text-gray-400">Feb 15, 2026 - 10:30 AM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Clinical Document Portal -->
                    <div id="section-clinical" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
                        <h2 class="text-xl font-sans! font-medium text-secondary mb-6">Clinical Document Portal</h2>

                        <!-- Upload Area -->
                        <div
                            class="border-2 border-dashed border-[#8FC0A8] rounded-xl p-6 md:p-8 text-center bg-white mb-8">
                            <p class="text-lg font-medium text-secondary mb-1.5">Drag and Drop files here</p>
                            <p class="text-xs text-gray-500 mb-1 leading-relaxed">Upload X-Rays, MRIs, Blood tests
                                and other clinical documents</p>
                            <p class="text-xs text-gray-400 mb-6 leading-relaxed">JPG, JPEG, PNG, WPS, DOC & PDF (Max
                                20MB)</p>
                            <button
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 bg-white rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">
                                <i class="ri-upload-2-line mr-2"></i> Upload
                            </button>
                        </div>

                        <!-- Uploaded Documents -->
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-medium font-sans! text-secondary">Uploaded Documents</h3>
                            <a href="#"
                                class="text-[11px] text-gray-400 hover:text-gray-700 font-medium tracking-wide">See
                                all</a>
                        </div>

                        <div class="swiper document-swiper pb-4 w-full">
                            <div class="swiper-wrapper">
                                <!-- Doc Card 1 -->
                                <div
                                    class="swiper-slide w-[133px]! bg-white px-3 md:px-5 pt-6 md:pt-16 pb-3 md:pb-5 rounded-xl relative flex flex-col items-center justify-center border border-gray-200">
                                    <button
                                        class="absolute top-1 md:top-2.5 right-1 md:right-2.5 w-8 h-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center hover:bg-red-100 transition-colors"><i
                                            class="ri-delete-bin-line text-sm"></i></button>
                                    <div
                                        class="w-10 h-10 justify-self-center bg-[#E1EAF2] text-[#3E7CB1] flex items-center justify-center rounded-lg mb-3">
                                        <i class="ri-file-text-fill text-lg"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-gray-800 truncate w-full text-center select-none">
                                        X-RayJan.DOC</p>
                                </div>
                                <!-- Doc Card 2 -->
                                <div
                                    class="swiper-slide w-[133px]! bg-white px-3 md:px-5 pt-6 md:pt-16 pb-3 md:pb-5 rounded-xl relative flex flex-col items-center justify-center border border-gray-200">
                                    <button
                                        class="absolute top-1 md:top-2.5 right-1 md:right-2.5 w-8 h-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center hover:bg-red-100 transition-colors"><i
                                            class="ri-delete-bin-line text-sm"></i></button>
                                    <div
                                        class="w-10 h-10 justify-self-center bg-[#FEE2E2] text-[#EF4444] flex items-center justify-center rounded-lg mb-3">
                                        <i class="ri-file-pdf-fill text-lg"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-gray-800 truncate w-full text-center select-none">
                                        Bloodtst.PDF</p>
                                </div>
                                <!-- Doc Card 3 -->
                                <div
                                    class="swiper-slide w-[133px]! bg-white px-3 md:px-5 pt-6 md:pt-16 pb-3 md:pb-5 rounded-xl relative flex flex-col items-center justify-center border border-gray-200">
                                    <button
                                        class="absolute top-1 md:top-2.5 right-1 md:right-2.5 w-8 h-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center hover:bg-red-100 transition-colors"><i
                                            class="ri-delete-bin-line text-sm"></i></button>
                                    <div
                                        class="w-10 h-10 justify-self-center bg-[#FEF3C7] text-[#D97706] flex items-center justify-center rounded-lg mb-3">
                                        <i class="ri-image-2-fill text-lg"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-gray-800 truncate w-full text-center select-none">
                                        MRIScan.PNG</p>
                                </div>
                                <!-- Doc Card 4 -->
                                <div
                                    class="swiper-slide w-[133px]! bg-white px-3 md:px-5 pt-6 md:pt-16 pb-3 md:pb-5 rounded-xl relative flex flex-col items-center justify-center border border-gray-200">
                                    <button
                                        class="absolute top-1 md:top-2.5 right-1 md:right-2.5 w-8 h-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center hover:bg-red-100 transition-colors"><i
                                            class="ri-delete-bin-line text-sm"></i></button>
                                    <div
                                        class="w-10 h-10 justify-self-center bg-[#E1EAF2] text-[#3E7CB1] flex items-center justify-center rounded-lg mb-3">
                                        <i class="ri-file-text-fill text-lg"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-gray-800 truncate w-full text-center select-none">
                                        CPRRep.DOC</p>
                                </div>
                                <!-- Doc Card 5 -->
                                <div
                                    class="swiper-slide w-[133px]! bg-white px-3 md:px-5 pt-6 md:pt-16 pb-3 md:pb-5 rounded-xl relative flex flex-col items-center justify-center border border-gray-200">
                                    <button
                                        class="absolute top-1 md:top-2.5 right-1 md:right-2.5 w-8 h-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center hover:bg-red-100 transition-colors"><i
                                            class="ri-delete-bin-line text-sm"></i></button>
                                    <div
                                        class="w-10 h-10 justify-self-center bg-[#F3E8FF] text-[#9333EA] flex items-center justify-center rounded-lg mb-3">
                                        <i class="ri-file-pdf-fill text-lg"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-gray-800 truncate w-full text-center select-none">
                                        Bloodtst.PDF</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div id="section-reviews" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12 mb-5 md:mb-8">
                <h2 class="text-xl font-medium font-sans! text-secondary mb-6">Your Reviews</h2>
                <div class="space-y-6">
                    <!-- Review 1 -->
                    <div class="border-b border-[#DDDDDD] pb-6">
                        <div class="flex items-center space-x-3 mb-3">
                            <h3 class="font-sans! text-base font-medium text-gray-800">Art Therapy</h3>
                            <span class="text-xs md:text-sm text-gray-400">Just now</span>
                            <div class="flex items-center gap-3 ml-auto shrink-0">
                                <button
                                    class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center text-[#2B4C3B] hover:bg-gray-50 transition-colors cursor-pointer"><i
                                        class="ri-pencil-line"></i></button>
                                <button
                                    class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center bg-red-50 text-red-400 hover:bg-red-100 transition-colors cursor-pointer"><i
                                        class="ri-delete-bin-line"></i></button>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600 mb-2.5 leading-relaxed">Comment: "Dr. Bennett was
                                    incredibly attentive and provided excellent care."</p>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-500 mr-3">Rating:</span>
                                    <div class="flex text-[#FFD166] space-x-0.5">
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-half-fill text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review 2 -->
                    <div class="border-b border-[#DDDDDD] pb-6">
                        <div class="flex items-center space-x-3 mb-3">
                            <h3 class="font-sans! text-base font-medium text-gray-800">Hypnotherapy</h3>
                            <span class="text-xs md:text-sm text-gray-400">2h ago</span>
                            <div class="flex items-center gap-3 ml-auto shrink-0">
                                <button
                                    class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center text-[#2B4C3B] hover:bg-gray-50 transition-colors cursor-pointer"><i
                                        class="ri-pencil-line"></i></button>
                                <button
                                    class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center bg-red-50 text-red-400 hover:bg-red-100 transition-colors cursor-pointer"><i
                                        class="ri-delete-bin-line"></i></button>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600 mb-2.5 leading-relaxed">Comment: "The session was
                                    helpful, but the waiting time was a bit long."</p>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-500 mr-3">Rating:</span>
                                    <div class="flex text-[#FFD166] space-x-0.5">
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-line text-sm text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review 3 -->
                    <div class="">
                        <div class="flex items-center space-x-3 mb-3">
                            <h3 class="font-sans! text-base font-medium text-gray-800">Life Coach</h3>
                            <span class="text-xs md:text-sm text-gray-400">2w ago</span>
                            <div class="flex items-center gap-3 ml-auto shrink-0">
                                <button
                                    class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center text-[#2B4C3B] hover:bg-gray-50 transition-colors cursor-pointer"><i
                                        class="ri-pencil-line"></i></button>
                                <button
                                    class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center bg-red-50 text-red-400 hover:bg-red-100 transition-colors cursor-pointer"><i
                                        class="ri-delete-bin-line"></i></button>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600 mb-2.5 leading-relaxed">Comment: "They didn't just
                                    give me supplements; they gave me a lifestyle shift. I'll definitely be booking a
                                    follow-up."</p>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-500 mr-3">Rating:</span>
                                    <div class="flex text-[#FFD166] space-x-0.5">
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-fill text-sm"></i>
                                        <i class="ri-star-fill text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <a href="#" class="text-sm text-gray-400 hover:text-gray-700 font-normal">See
                        all</a>
                </div>
            </div>

            <!-- GDPR Center -->
            <div id="section-gdpr"
                class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12 flex flex-col md:flex-row flex-wrap gap-4 items-center justify-between">
                <div class="flex flex-1 items-center space-x-3">
                    <i class="ri-shield-check-fill text-secondary text-xl"></i>
                    <h2 class="text-sm md:text-lg font-sans! font-medium text-secondary leading-snug">General Data
                        Protection Regulation Control
                        Center</h2>
                </div>
                <div class="flex flex-1 items-center justify-end space-x-4 lg:border-l lg:border-gray-100 lg:h-8">
                    <span class="text-base md:text-lg text-gray-600">Data sharing with Practitioners</span>
                    <!-- Toggle Switch -->
                    <button
                        onclick="this.classList.toggle('bg-secondary'); this.classList.toggle('bg-gray-300'); this.children[0].classList.toggle('translate-x-5')"
                        class="w-10 h-5 bg-gray-300 rounded-full relative flex items-center transition-colors cursor-pointer">
                        <div
                            class="w-4 h-4 bg-white rounded-full absolute left-0.5 shadow-sm transition-transform duration-300">
                        </div>
                    </button>
                </div>
            </div>

            <!-- Padding for scroll -->
            <div class="h-10"></div>
        </div>
    </main>

    <script>
        function switchConsultationTab(tab) {
            const upcomingBtn = document.getElementById('tab-upcoming');
            const completedBtn = document.getElementById('tab-completed');
            const upcomingContent = document.getElementById('content-upcoming');
            const completedContent = document.getElementById('content-completed');

            const activeBtnClasses = 'px-4 py-1.5 w-1/2 text-center bg-[#CFFAD8] text-[#2FA749] text-sm font-normal rounded-lg transition-all cursor-pointer';
            const inactiveBtnClasses = 'px-4 py-1.5 w-1/2 text-center bg-[#F9F9F9] text-[#8C8C8C] text-sm font-normal rounded-lg transition-all cursor-pointer';

            if (tab === 'upcoming') {
                upcomingBtn.className = activeBtnClasses;
                completedBtn.className = inactiveBtnClasses;
                upcomingContent.classList.remove('hidden');
                completedContent.classList.add('hidden');
            } else {
                completedBtn.className = activeBtnClasses;
                upcomingBtn.className = inactiveBtnClasses;
                completedContent.classList.remove('hidden');
                upcomingContent.classList.add('hidden');
            }
        }

        function switchMobileTab(selectedTab) {
            const tabs = ['dashboard', 'health', 'bookings', 'transactions'];
            tabs.forEach(tab => {
                const btn = document.getElementById('m-tab-' + tab);
                if (btn) {
                    btn.className = (tab === selectedTab)
                        ? "text-lg text-secondary font-normal whitespace-nowrap leading-none cursor-pointer transition-colors"
                        : "text-lg text-[#8F8F8F] font-normal whitespace-nowrap leading-none cursor-pointer transition-colors";
                }
            });

            const allSections = [
                'section-identity', 'section-gdpr',
                'section-clinical', 'section-reviews',
                'section-consultations', 'section-transactions',
                'col-left', 'col-right'
            ];

            allSections.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.add('mobile-hidden');
            });

            const tabMap = {
                'dashboard': [
                    'section-identity', 'section-gdpr',
                    'section-clinical', 'section-reviews',
                    'section-consultations', 'section-transactions',
                    'col-left', 'col-right'
                ],
                'health': ['section-clinical', 'section-reviews', 'col-right'],
                'bookings': ['section-consultations', 'col-right'],
                'transactions': ['section-transactions', 'col-left']
            };

            if (tabMap[selectedTab]) {
                tabMap[selectedTab].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.classList.remove('mobile-hidden');
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swiper !== 'undefined') {
                new Swiper('.document-swiper', {
                    slidesPerView: 'auto',
                    spaceBetween: 16,
                    grabCursor: true,
                    freeMode: true,
                });
            }
        });
    </script>
</body>

</html>