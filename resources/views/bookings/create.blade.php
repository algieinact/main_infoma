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
                <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data"
                    id="bookingForm">
                    @csrf
                    <input type="hidden" name="bookable_type" value="{{ get_class($item) }}">
                    <input type="hidden" name="bookable_id" value="{{ $item->id }}">

                    @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
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
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start
                                    Date</label>
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
                                <label for="booking_data_full_name" class="block text-sm font-medium text-gray-700">Full
                                    Name</label>
                                <input type="text" name="booking_data[full_name]" id="booking_data_full_name"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.full_name') border-red-500 @enderror"
                                    value="{{ old('booking_data.full_name', auth()->user()->name) }}" required>
                                @error('booking_data.full_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_phone" class="block text-sm font-medium text-gray-700">Phone
                                    Number</label>
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
                                <label for="booking_data_emergency_contact"
                                    class="block text-sm font-medium text-gray-700">Emergency Contact Name</label>
                                <input type="text" name="booking_data[emergency_contact]"
                                    id="booking_data_emergency_contact"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.emergency_contact') border-red-500 @enderror"
                                    value="{{ old('booking_data.emergency_contact') }}" required>
                                @error('booking_data.emergency_contact')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_emergency_phone"
                                    class="block text-sm font-medium text-gray-700">Emergency Contact Phone</label>
                                <input type="tel" name="booking_data[emergency_phone]" id="booking_data_emergency_phone"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.emergency_phone') border-red-500 @enderror"
                                    value="{{ old('booking_data.emergency_phone') }}" required>
                                @error('booking_data.emergency_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_occupation"
                                    class="block text-sm font-medium text-gray-700">Occupation</label>
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
                                <label for="files_ktp" class="block text-sm font-medium text-gray-700">KTP (ID
                                    Card)</label>
                                <input type="file" name="files[ktp]" id="files_ktp"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('files.ktp') border-red-500 @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png" required>
                                @error('files.ktp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">PDF, JPG, or PNG. Max 2MB</p>
                            </div>
                            <div>
                                <label for="files_agreement" class="block text-sm font-medium text-gray-700">Agreement
                                    (Optional)</label>
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
                                <label for="booking_data_university"
                                    class="block text-sm font-medium text-gray-700">University</label>
                                <input type="text" name="booking_data[university]" id="booking_data_university"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.university') border-red-500 @enderror"
                                    value="{{ old('booking_data.university', auth()->user()->university) }}">
                                @error('booking_data.university')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_major"
                                    class="block text-sm font-medium text-gray-700">Major</label>
                                <input type="text" name="booking_data[major]" id="booking_data_major"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.major') border-red-500 @enderror"
                                    value="{{ old('booking_data.major', auth()->user()->major) }}">
                                @error('booking_data.major')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_student_id"
                                    class="block text-sm font-medium text-gray-700">Student ID</label>
                                <input type="text" name="booking_data[student_id]" id="booking_data_student_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('booking_data.student_id') border-red-500 @enderror"
                                    value="{{ old('booking_data.student_id') }}">
                                @error('booking_data.student_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="booking_data_motivation"
                                    class="block text-sm font-medium text-gray-700">Motivation</label>
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

                    <!-- Voucher Code -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Voucher Code (Optional)</h2>

                        <!-- Available Vouchers -->
                        <div id="availableVouchers" class="mb-4" style="display: none;">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Voucher Tersedia:</h3>
                            <div id="voucherList" class="space-y-2"></div>
                        </div>

                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <input type="text" name="voucher_code" id="voucher_code"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('voucher_code') border-red-500 @enderror"
                                    placeholder="Enter voucher code" value="{{ old('voucher_code') }}">
                                @error('voucher_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="button" id="checkVoucherBtn"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Apply Voucher
                            </button>
                        </div>
                        <div id="voucherResult" class="mt-2 text-sm"></div>
                        <input type="hidden" name="voucher_id" id="voucher_id" value="">
                        <input type="hidden" name="discount_amount" id="discount_amount" value="0">
                    </div>

                    <!-- Notes -->
                    <div class="mb-8">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes
                            (Optional)</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('notes') border-red-500 @enderror"
                            placeholder="Any additional information you'd like to share...">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Information</h2>

                        <!-- Price Summary -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Base Price:</span>
                                    <span class="font-medium" id="basePrice">Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                                @if(get_class($item) === 'App\Models\Residence')
                                <div class="flex justify-between" id="durationRow" style="display: none;">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-medium" id="durationText">-</span>
                                </div>
                                <div class="flex justify-between" id="totalBaseRow" style="display: none;">
                                    <span class="text-gray-600">Total Base Price:</span>
                                    <span class="font-medium" id="totalBasePrice">-</span>
                                </div>
                                @endif
                                <div class="flex justify-between" id="voucherDiscountRow" style="display: none;">
                                    <span class="text-gray-600">Voucher Discount:</span>
                                    <span class="font-medium text-green-600" id="voucherDiscountAmount">-Rp 0</span>
                                </div>
                                <div class="border-t border-gray-200 my-2"></div>
                                <div class="flex justify-between">
                                    <span class="text-gray-900 font-semibold">Total Amount:</span>
                                    <span class="text-gray-900 font-bold" id="totalAmount">Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Payment Methods</h3>

                            <!-- Bank Transfer -->
                            <div class="border rounded-lg p-4">
                                <div class="flex items-center">
                                    <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500" checked>
                                    <label for="bank_transfer" class="ml-3 block">
                                        <span class="text-sm font-medium text-gray-900">Bank Transfer</span>
                                        <span class="text-sm text-gray-500 block">Transfer to our bank account</span>
                                    </label>
                                </div>
                                <div id="bankDetails" class="mt-3 ml-7 space-y-2">
                                    <div class="text-sm">
                                        <span class="font-medium">Bank BCA</span>
                                        <p class="text-gray-600">Account Number: 1234567890</p>
                                        <p class="text-gray-600">Account Name: PT Infoma</p>
                                    </div>
                                </div>
                            </div>

                            <!-- E-Wallet -->
                            <div class="border rounded-lg p-4">
                                <div class="flex items-center">
                                    <input type="radio" name="payment_method" id="e_wallet" value="e_wallet"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                    <label for="e_wallet" class="ml-3 block">
                                        <span class="text-sm font-medium text-gray-900">E-Wallet</span>
                                        <span class="text-sm text-gray-500 block">Pay using e-wallet</span>
                                    </label>
                                </div>
                                <div id="eWalletDetails" class="mt-3 ml-7 space-y-2" style="display: none;">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <input type="radio" name="e_wallet_type" id="gopay" value="gopay"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                            <label for="gopay" class="ml-2 text-sm text-gray-900">GoPay</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="e_wallet_type" id="ovo" value="ovo"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                            <label for="ovo" class="ml-2 text-sm text-gray-900">OVO</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="e_wallet_type" id="dana" value="dana"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                            <label for="dana" class="ml-2 text-sm text-gray-900">DANA</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="e_wallet_type" id="linkaja" value="linkaja"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                            <label for="linkaja" class="ml-2 text-sm text-gray-900">LinkAja</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Instructions -->
                        <div class="mt-6 bg-blue-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-blue-900">Payment Instructions:</h3>
                            <ol class="mt-2 text-sm text-blue-700 list-decimal list-inside space-y-1">
                                <li>Complete the booking form and click "Create Booking"</li>
                                <li>You will receive a booking confirmation with payment details</li>
                                <li>Make the payment using your chosen payment method</li>
                                <li>Upload your payment proof in the booking details page</li>
                                <li>Wait for payment confirmation from our team</li>
                            </ol>
                        </div>
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

    // Validate dates and update pricing
    if (startDateInput && endDateInput) {
        function updatePricing() {
            const bookableType = document.querySelector('input[name="bookable_type"]').value;
            if (bookableType === 'App\\Models\\Residence') {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;

                if (startDate && endDate) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);
                    const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                    const months = Math.ceil(days / 30);

                    const basePrice = parseFloat(document.getElementById('basePrice').textContent.replace(
                        /[^0-9.-]+/g, ''));
                    const totalBasePrice = basePrice * months;

                    // Update display
                    document.getElementById('durationRow').style.display = 'flex';
                    document.getElementById('totalBaseRow').style.display = 'flex';
                    document.getElementById('durationText').textContent = `${months} bulan (${days} hari)`;
                    document.getElementById('totalBasePrice').textContent =
                        `Rp ${totalBasePrice.toLocaleString('id-ID')}`;
                    document.getElementById('totalAmount').textContent =
                        `Rp ${totalBasePrice.toLocaleString('id-ID')}`;

                    // Reset voucher if applied
                    const voucherId = document.getElementById('voucher_id');
                    if (voucherId.value) {
                        voucherId.value = '';
                        document.getElementById('discount_amount').value = '0';
                        document.getElementById('voucherDiscountRow').style.display = 'none';
                        document.getElementById('voucher_code').disabled = false;
                        document.getElementById('checkVoucherBtn').disabled = false;
                        document.getElementById('checkVoucherBtn').innerHTML = 'Apply Voucher';
                        document.getElementById('checkVoucherBtn').classList.remove('bg-green-600',
                            'hover:bg-green-700');
                        document.getElementById('checkVoucherBtn').classList.add('bg-blue-600',
                            'hover:bg-blue-700');
                        document.getElementById('voucherResult').innerHTML = '';
                    }
                } else {
                    document.getElementById('durationRow').style.display = 'none';
                    document.getElementById('totalBaseRow').style.display = 'none';
                }
            }
        }

        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
            updatePricing();
        });

        endDateInput.addEventListener('change', function() {
            updatePricing();
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

    // Payment method selection
    const bankTransfer = document.getElementById('bank_transfer');
    const eWallet = document.getElementById('e_wallet');
    const bankDetails = document.getElementById('bankDetails');
    const eWalletDetails = document.getElementById('eWalletDetails');

    bankTransfer.addEventListener('change', function() {
        if (this.checked) {
            bankDetails.style.display = 'block';
            eWalletDetails.style.display = 'none';
        }
    });

    eWallet.addEventListener('change', function() {
        if (this.checked) {
            bankDetails.style.display = 'none';
            eWalletDetails.style.display = 'block';
        }
    });

    // Voucher code handling
    const voucherCode = document.getElementById('voucher_code');
    const checkVoucherBtn = document.getElementById('checkVoucherBtn');
    const voucherResult = document.getElementById('voucherResult');
    const voucherDiscountRow = document.getElementById('voucherDiscountRow');
    const voucherDiscountAmount = document.getElementById('voucherDiscountAmount');
    const totalAmount = document.getElementById('totalAmount');
    const basePrice = document.getElementById('basePrice');
    const voucherId = document.getElementById('voucher_id');
    const discountAmountInput = document.getElementById('discount_amount');
    const availableVouchers = document.getElementById('availableVouchers');
    const voucherList = document.getElementById('voucherList');

    // Load available vouchers on page load
    function loadAvailableVouchers() {
        fetch(
                `/vouchers/available?bookable_type=${encodeURIComponent(document.querySelector('input[name="bookable_type"]').value)}&bookable_id=${document.querySelector('input[name="bookable_id"]').value}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    availableVouchers.style.display = 'block';
                    voucherList.innerHTML = '';

                    data.data.forEach(voucher => {
                        const voucherItem = document.createElement('div');
                        voucherItem.className =
                            'bg-blue-50 border border-blue-200 rounded-lg p-3 cursor-pointer hover:bg-blue-100';
                        voucherItem.innerHTML = `
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-medium text-blue-900">${voucher.code}</div>
                                    <div class="text-sm text-blue-700">${voucher.formatted_discount} ${voucher.description ? '- ' + voucher.description : ''}</div>
                                    ${voucher.min_purchase ? `<div class="text-xs text-blue-600">Min: Rp ${voucher.min_purchase.toLocaleString('id-ID')}</div>` : ''}
                                </div>
                                <div class="text-xs text-blue-600">Berlaku hingga ${voucher.end_date}</div>
                            </div>
                        `;

                        voucherItem.addEventListener('click', function() {
                            voucherCode.value = voucher.code;
                            checkVoucherBtn.click();
                        });

                        voucherList.appendChild(voucherItem);
                    });
                } else {
                    availableVouchers.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading available vouchers:', error);
            });
    }

    // Load available vouchers when page loads
    loadAvailableVouchers();

    checkVoucherBtn.addEventListener('click', function() {
        const code = voucherCode.value.trim();
        if (!code) {
            voucherResult.innerHTML = '<p class="text-red-600">Please enter a voucher code</p>';
            return;
        }

        // Show loading state
        checkVoucherBtn.disabled = true;
        checkVoucherBtn.innerHTML = 'Checking...';
        voucherResult.innerHTML = '<p class="text-gray-600">Checking voucher code...</p>';

        // Get total amount from the displayed total (which includes duration calculation)
        let totalAmount = parseFloat(document.getElementById('totalAmount').textContent.replace(
            /[^0-9.-]+/g, ''));

        // If total amount is not calculated yet, use base price
        if (!totalAmount || totalAmount === 0) {
            totalAmount = parseFloat(basePrice.textContent.replace(/[^0-9.-]+/g, ''));
        }

        // Debug logging
        console.log('Voucher validation request:', {
            code: code,
            bookable_type: document.querySelector('input[name="bookable_type"]').value,
            bookable_id: document.querySelector('input[name="bookable_id"]').value,
            amount: totalAmount,
            basePrice: parseFloat(basePrice.textContent.replace(/[^0-9.-]+/g, '')),
            calculatedTotal: totalAmount
        });

        // Make API call to validate voucher
        fetch(`/vouchers/validate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    code: code,
                    bookable_type: document.querySelector('input[name="bookable_type"]')
                        .value,
                    bookable_id: document.querySelector('input[name="bookable_id"]').value,
                    amount: totalAmount
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    voucherResult.innerHTML = '<p class="text-green-600">' + data.message + '</p>';
                    voucherDiscountRow.style.display = 'flex';
                    voucherDiscountAmount.textContent = '-Rp ' + data.data.discount_amount
                        .toLocaleString('id-ID');
                    totalAmount.textContent = 'Rp ' + data.data.final_amount.toLocaleString(
                    'id-ID');

                    // Store voucher data
                    voucherId.value = data.data.voucher_id;
                    discountAmountInput.value = data.data.discount_amount;

                    // Disable voucher input after successful application
                    voucherCode.disabled = true;
                    checkVoucherBtn.disabled = true;
                    checkVoucherBtn.innerHTML = 'Applied';
                    checkVoucherBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    checkVoucherBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                } else {
                    voucherResult.innerHTML = '<p class="text-red-600">' + data.message + '</p>';
                    voucherDiscountRow.style.display = 'none';

                    // Reset to correct total amount (including duration if residence)
                    const bookableType = document.querySelector('input[name="bookable_type"]')
                    .value;
                    if (bookableType === 'App\\Models\\Residence') {
                        const totalBasePrice = document.getElementById('totalBasePrice');
                        if (totalBasePrice && totalBasePrice.style.display !== 'none') {
                            totalAmount.textContent = totalBasePrice.textContent;
                        } else {
                            totalAmount.textContent = basePrice.textContent;
                        }
                    } else {
                        totalAmount.textContent = basePrice.textContent;
                    }

                    // Reset voucher data
                    voucherId.value = '';
                    discountAmountInput.value = '0';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                voucherResult.innerHTML = '<p class="text-red-600">Error checking voucher code: ' +
                    error.message + '</p>';
            })
            .finally(() => {
                if (!voucherId.value) {
                    checkVoucherBtn.disabled = false;
                    checkVoucherBtn.innerHTML = 'Apply Voucher';
                }
            });
    });

    // Allow removing voucher
    voucherCode.addEventListener('input', function() {
        if (this.value === '') {
            // Reset voucher
            voucherId.value = '';
            discountAmountInput.value = '0';
            voucherDiscountRow.style.display = 'none';

            // Reset to correct total amount (including duration if residence)
            const bookableType = document.querySelector('input[name="bookable_type"]').value;
            const totalAmountElement = document.getElementById('totalAmount');
            const basePriceElement = document.getElementById('basePrice');

            if (bookableType === 'App\\Models\\Residence') {
                const totalBasePriceElement = document.getElementById('totalBasePrice');
                if (totalBasePriceElement && totalBasePriceElement.style.display !== 'none') {
                    totalAmountElement.textContent = totalBasePriceElement.textContent;
                } else {
                    totalAmountElement.textContent = basePriceElement.textContent;
                }
            } else {
                totalAmountElement.textContent = basePriceElement.textContent;
            }

            // Re-enable voucher input
            this.disabled = false;
            checkVoucherBtn.disabled = false;
            checkVoucherBtn.innerHTML = 'Apply Voucher';
            checkVoucherBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            checkVoucherBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');

            voucherResult.innerHTML = '';
        }
    });

    // Form submission handling
    form.addEventListener('submit', function(e) {
        const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked')
            .value;

        if (selectedPaymentMethod === 'e_wallet') {
            const selectedEWallet = document.querySelector('input[name="e_wallet_type"]:checked');
            if (!selectedEWallet) {
                e.preventDefault();
                alert('Please select an e-wallet type');
                return;
            }
        }
    });
});
</script>
@endpush
@endsection