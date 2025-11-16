<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
    <h3 class="text-2xl font-bold mb-6 text-center">Upload Payment Receipt</h3>

    <form wire:submit="upload" class="space-y-6">
        <div>
            <label class="block text-sm font-medium mb-2">Receipt Image / PDF</label>
            <input type="file" wire:model="receipt" accept="image/*,application/pdf"
                   class="w-full file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:bg-gradient-to-r file:from-blue-600 file:to-purple-600 file:text-white hover:file:from-blue-700 hover:file:to-purple-700">
            @error('receipt') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Reference Number</label>
            <input type="text" wire:model="reference" placeholder="e.g. GCash 09123456789"
                   class="w-full px-4 py-3 rounded-xl border dark:bg-gray-900 focus:ring-4 focus:ring-blue-500">
            @error('reference') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-4 rounded-xl text-lg font-bold hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition">
            <i class="fas fa-upload mr-2"></i> Upload & Submit Receipt
        </button>
    </form>

    @if (session('success'))
        <div class="mt-6 bg-green-100 dark:bg-green-900/50 border border-green-400 text-green-800 dark:text-green-300 px-6 py-4 rounded-xl text-center font-semibold">
            {{ session('success') }}
        </div>
    @endif
</div>