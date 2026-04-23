@extends('layouts.client')

@section('title', 'My Bookings')

@section('content')

<!-- Bookings Content -->
<div id="bookings-wrapper">
    @include('partials.bookings-table')
</div>

<!-- Booking Details Modal -->
<div id="booking-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div id="modal-backdrop" class="fixed inset-0 bg-black/50 transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div id="booking-modal-container" class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <!-- Modal panel -->
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-medium text-secondary" id="modal-title">Booking Details</h3>
                    <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div id="modal-body" class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                    <!-- Content will be loaded here -->
                    <div class="flex justify-center items-center py-10">
                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-secondary"></div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 text-right">
                    <button onclick="closeBookingModal()" class="px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-full text-sm font-medium hover:bg-gray-50 transition-colors">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.refer-modal-scripts')

<div class="h-10"></div>
@endsection

@section('scripts')
<script>
    // Search & AJAX logic
    (function() {
        let searchTimer;
        const searchInput = document.getElementById('bookings-search');
        const loader = document.getElementById('search-loader');
        const container = document.getElementById('bookings-wrapper');

        function fetchBookings(url) {
            if (loader) loader.classList.remove('hidden');
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                container.innerHTML = html;
                // Re-bind search input after table replacement
                rebindSearch();
            })
            .finally(() => {
                if (loader) loader.classList.add('hidden');
            });
        }

        function rebindSearch() {
            const newSearch = document.getElementById('bookings-search');
            if (newSearch) {
                newSearch.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        const val = this.value;
                        const url = new URL(window.location.href);
                        url.searchParams.set('search', val);
                        url.searchParams.delete('page'); // Reset to page 1
                        
                        window.history.pushState({}, '', url);
                        fetchBookings(url.toString());
                    }, 500);
                });
                // Focus at end of text
                newSearch.focus();
                const val = newSearch.value;
                newSearch.value = '';
                newSearch.value = val;
            }
            
            // Handle pagination clicks
            document.querySelectorAll('.pagination-links a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.history.pushState({}, '', this.href);
                    fetchBookings(this.href);
                });
            });

            // Re-bind dropdowns
            bindDropdowns();
        }

        function bindDropdowns() {
            document.querySelectorAll('.dropdown-trigger').forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;
                    const isHidden = menu.classList.contains('hidden');
                    
                    document.querySelectorAll('.action-dropdown .dropdown-menu').forEach(m => m.classList.add('hidden'));
                    
                    if (isHidden) {
                        menu.classList.remove('hidden');
                    }
                });
            });
        }

        // Global click to close dropdowns
        document.addEventListener('click', () => {
            document.querySelectorAll('.action-dropdown .dropdown-menu').forEach(m => m.classList.add('hidden'));
        });

        // Initialize
        rebindSearch();
    })();

    function openBookingModal() {
        document.getElementById('booking-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    

    function closeBookingModal() {
        document.getElementById('booking-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close on outside click
    document.getElementById('modal-backdrop').addEventListener('click', closeBookingModal);
    document.getElementById('booking-modal-container').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookingModal();
        }
    });

    async function viewBookingDetails(id) {
        openBookingModal();
        const body = document.getElementById('modal-body');
        body.innerHTML = '<div class="flex justify-center items-center py-10"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-secondary"></div></div>';

        try {
            const response = await fetch(`/bookings/${id}/details`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Failed to fetch details');

            const html = await response.text();
            body.innerHTML = html;
        } catch (error) {
            console.error('Error:', error);
            body.innerHTML = '<div class="text-center py-10 text-red-500">Failed to load booking details. Please try again.</div>';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Action Dropdown Logic
        const allMenus = document.querySelectorAll('.action-dropdown .dropdown-menu');
        
        function closeAllMenus() {
            document.querySelectorAll('.action-dropdown .dropdown-menu').forEach(m => {
                m.classList.add('hidden');
                m.style.position = '';
                m.style.top = '';
                m.style.left = '';
                m.style.right = '';
                m.style.bottom = '';
            });
        }

        window.addEventListener('scroll', closeAllMenus, true);
        window.addEventListener('resize', closeAllMenus);

        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.dropdown-trigger');
            
            if (trigger) {
                const menu = trigger.nextElementSibling;
                const isOpen = !menu.classList.contains('hidden');
                
                closeAllMenus();
                
                if (!isOpen) {
                    menu.classList.remove('hidden');
                    
                    // Use fixed positioning to escape overflow-hidden container
                    const triggerRect = trigger.getBoundingClientRect();
                    menu.style.position = 'fixed';
                    menu.style.right = (window.innerWidth - triggerRect.right) + 'px';
                    
                    // Collision check
                    menu.style.top = 'auto';
                    menu.style.bottom = 'auto';
                    
                    // We need layout context to check height
                    const menuRect = menu.getBoundingClientRect();
                    if ((triggerRect.bottom + menuRect.height + 10) > window.innerHeight && triggerRect.top > menuRect.height) {
                        // Pop upwards
                        menu.style.bottom = (window.innerHeight - triggerRect.top + 8) + 'px';
                    } else {
                        // Pop downwards
                        menu.style.top = (triggerRect.bottom + 8) + 'px';
                    }
                }
                e.stopPropagation();
            } else {
                closeAllMenus();
            }
        });

        // Tab toggle
        const tabButtons = document.querySelectorAll('.tab-trigger');
        const tabPanels = document.querySelectorAll('[data-tab-panel]');

        function activateTab(targetId) {
            tabButtons.forEach(btn => {
                const isActive = btn.dataset.tabTarget === targetId;
                btn.classList.toggle('tab-active', isActive);
                btn.classList.toggle('bg-secondary', isActive);
                btn.classList.toggle('text-white', isActive);
                btn.classList.toggle('shadow-sm', isActive);
                btn.classList.toggle('border', !isActive);
                btn.classList.toggle('border-secondary', !isActive);
                btn.classList.toggle('text-secondary', !isActive);
            });

            tabPanels.forEach(panel => {
                panel.classList.toggle('hidden', panel.id !== targetId);
            });
        }

        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => activateTab(btn.dataset.tabTarget));
        });

        activateTab(document.querySelector('.tab-trigger.tab-active')?.dataset.tabTarget || 'tab-bookings');

        // Pagination AJAX for bookings table
        const wrapper = document.getElementById('bookings-wrapper');

        if (wrapper) {
            wrapper.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination-links a');
                if (link) {
                    e.preventDefault();
                    const url = link.getAttribute('href');
                    fetchBookings(url);
                }
            });
        }

        async function fetchBookings(url) {
            try {
                // Optional: Add a loading state
                wrapper.style.opacity = '0.5';
                wrapper.style.pointerEvents = 'none';

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const html = await response.text();
                wrapper.innerHTML = html;
                
                // Update URL in browser without reload
                window.history.pushState({}, '', url);

                // Restore state
                wrapper.style.opacity = '1';
                wrapper.style.pointerEvents = 'auto';
                
                // Scroll to top of table
                wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });

            } catch (error) {
                console.error('Error fetching bookings:', error);
                wrapper.style.opacity = '1';
                wrapper.style.pointerEvents = 'auto';
                alert('Failed to load bookings. Please try again.');
            }
        }
    });
</script>
@endsection
