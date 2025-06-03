@extends('layouts.app')

@section('title', 'Create Booking')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Create Booking</h1>
            <p class="mt-2 text-gray-600">Fill in the details below to create your booking.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data" id="bookingForm">
                    @csrf
                    <input type="hidden" name="bookable_type" value="{{ get_class($item) }}">
                    <input type="hidden" name="bookable_id" value="{{ $item->id }}">

                    @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <ul class="mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Item Details -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Item Details</h2>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center space-x-4">
                                @if($item->images && count($item->images) > 0)
                                    <img src="{{ Storage::url($item->images[0]) }}" alt="{{ $item->title }}" 
                                         class="w-20 h-20 object-cover rounded-lg"
                                         onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                                @endif
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $item->title }}</h3>
                                    <p class="text-gray-600">{{ $item->category->name }}</p>
                                    <p class="text-blue-600 font-semibold">{{ $item->formatted_price }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Dates -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Booking Dates</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('start_date') border-red-500 @enderror"
                                       min="{{ date('Y-m-d') }}" required value="{{ old('start_date') }}">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            @if(get_class($item) === 'App\Models\Residence')
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('end_date') border-red-500 @enderror"
                                       min="{{ date('Y-m-d') }}" required value="{{ old('end_date') }}">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="booking_data_full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="booking_data[full_name]" id="booking_data_full_name" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.full_name') border-red-500 @enderror"
                                       value="{{ old('booking_data.full_name', auth()->user()->name) }}" required>
                                @error('booking_data.full_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="tel" name="booking_data[phone]" id="booking_data_phone" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.phone') border-red-500 @enderror"
                                       value="{{ old('booking_data.phone', auth()->user()->phone) }}" required>
                                @error('booking_data.phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    @if(get_class($item) === 'App\Models\Residence')
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Additional Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="booking_data_emergency_contact" class="block text-sm font-medium text-gray-700">Emergency Contact Name</label>
                                <input type="text" name="booking_data[emergency_contact]" id="booking_data_emergency_contact" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.emergency_contact') border-red-500 @enderror"
                                       value="{{ old('booking_data.emergency_contact') }}" required>
                                @error('booking_data.emergency_contact')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_emergency_phone" class="block text-sm font-medium text-gray-700">Emergency Contact Phone</label>
                                <input type="tel" name="booking_data[emergency_phone]" id="booking_data_emergency_phone" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.emergency_phone') border-red-500 @enderror"
                                       value="{{ old('booking_data.emergency_phone') }}" required>
                                @error('booking_data.emergency_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_occupation" class="block text-sm font-medium text-gray-700">Occupation</label>
                                <input type="text" name="booking_data[occupation]" id="booking_data_occupation" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.occupation') border-red-500 @enderror"
                                       value="{{ old('booking_data.occupation') }}" required>
                                @error('booking_data.occupation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Required Documents -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Required Documents</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="files_ktp" class="block text-sm font-medium text-gray-700">KTP (ID Card)</label>
                                <input type="file" name="files[ktp]" id="files_ktp" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('files.ktp') border-red-500 @enderror"
                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                @error('files.ktp')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">PDF, JPG, or PNG. Max 2MB</p>
                            </div>
                            <div>
                                <label for="files_agreement" class="block text-sm font-medium text-gray-700">Agreement (Optional)</label>
                                <input type="file" name="files[agreement]" id="files_agreement" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('files.agreement') border-red-500 @enderror"
                                       accept=".pdf">
                                @error('files.agreement')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">PDF only. Max 2MB</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Additional Information</h2>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="booking_data_university" class="block text-sm font-medium text-gray-700">University</label>
                                <input type="text" name="booking_data[university]" id="booking_data_university" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.university') border-red-500 @enderror"
                                       value="{{ old('booking_data.university', auth()->user()->university) }}">
                                @error('booking_data.university')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_major" class="block text-sm font-medium text-gray-700">Major</label>
                                <input type="text" name="booking_data[major]" id="booking_data_major" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.major') border-red-500 @enderror"
                                       value="{{ old('booking_data.major', auth()->user()->major) }}">
                                @error('booking_data.major')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                                <input type="text" name="booking_data[student_id]" id="booking_data_student_id" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.student_id') border-red-500 @enderror"
                                       value="{{ old('booking_data.student_id') }}">
                                @error('booking_data.student_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_motivation" class="block text-sm font-medium text-gray-700">Motivation</label>
                                <textarea name="booking_data[motivation]" id="booking_data_motivation" rows="4" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.motivation') border-red-500 @enderror"
                                          placeholder="Tell us why you want to join this activity...">{{ old('booking_data.motivation') }}</textarea>
                                @error('booking_data.motivation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Discount Code -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Discount Code (Optional)</h2>
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <input type="text" name="discount_code" id="discount_code" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('discount_code') border-red-500 @enderror"
                                       placeholder="Enter discount code" value="{{ old('discount_code') }}">
                                @error('discount_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="button" id="checkDiscountBtn"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Check
                            </button>
                        </div>
                        <div id="discountResult" class="mt-2 text-sm"></div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-8">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes (Optional)</label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('notes') border-red-500 @enderror"
                                  placeholder="Any additional information you'd like to share...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Create Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const ktpFileInput = document.getElementById('files_ktp');
    const agreementFileInput = document.getElementById('files_agreement');

    // Validate dates
    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
        });
    }

    // Validate file size and type
    function validateFile(file, maxSize, allowedTypes) {
        if (file.size > maxSize) {
            throw new Error(`File size must be less than ${maxSize / 1024 / 1024}MB`);
        }
        if (!allowedTypes.includes(file.type)) {
            throw new Error('Invalid file type');
        }
    }

    // Handle file uploads
    if (ktpFileInput) {
        ktpFileInput.addEventListener('change', function() {
            try {
                const file = this.files[0];
                if (file) {
                    validateFile(file, 2 * 1024 * 1024, ['application/pdf', 'image/jpeg', 'image/png']);
                }
            } catch (error) {
                alert(error.message);
                this.value = '';
            }
        });
    }

    if (agreementFileInput) {
        agreementFileInput.addEventListener('change', function() {
            try {
                const file = this.files[0];
                if (file) {
                    validateFile(file, 2 * 1024 * 1024, ['application/pdf']);
                }
            } catch (error) {
                alert(error.message);
                this.value = '';
            }
        });
    }

    // Handle discount code check
    const checkDiscountBtn = document.getElementById('checkDiscountBtn');
    const discountCode = document.getElementById('discount_code');
    const discountResult = document.getElementById('discountResult');

    if (checkDiscountBtn && discountCode) {
        checkDiscountBtn.addEventListener('click', async function() {
            if (!discountCode.value) {
                discountResult.innerHTML = '<span class="text-red-600">Please enter a discount code</span>';
                return;
            }

            try {
                const response = await fetch(`/api/discounts/check/${discountCode.value}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    discountResult.innerHTML = `<span class="text-green-600">${data.message}</span>`;
                } else {
                    discountResult.innerHTML = `<span class="text-red-600">${data.message}</span>`;
                }
            } catch (error) {
                discountResult.innerHTML = '<span class="text-red-600">Error checking discount code</span>';
            }
        });
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate required fields
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });

        if (!isValid) {
            alert('Please fill in all required fields');
            return;
        }

        // Submit form
        this.submit();
    });
});
</script>
@endpush
@endsection 