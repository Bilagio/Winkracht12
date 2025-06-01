<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Delete Customer') }}
            </h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                Admin Portal
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-medium text-red-800 dark:text-red-400 mb-4">Warning: Delete Customer Account</h3>
                        
                        <p class="mb-4 text-red-700 dark:text-red-300">
                            You are about to permanently delete the customer account for <strong>{{ $customer->name }}</strong> with email <strong>{{ $customer->email }}</strong>.
                        </p>
                        
                        <p class="mb-4 text-red-700 dark:text-red-300">
                            This action cannot be undone. All associated data, including reservation history, will be permanently removed from our system.
                        </p>
                        
                        <div class="mt-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-600 dark:text-red-400 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <span class="font-medium text-red-700 dark:text-red-300">
                                Are you sure you want to proceed?
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Yes, Delete Customer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
