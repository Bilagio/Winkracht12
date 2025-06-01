<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Delete Instructor') }}
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
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-medium text-red-800 dark:text-red-400 mb-4">Warning: Delete Instructor Account</h3>
                        
                        <p class="mb-4 text-red-700 dark:text-red-300">
                            You are about to permanently delete the instructor account for <strong>{{ $instructor->name }}</strong> with email <strong>{{ $instructor->email }}</strong>.
                        </p>
                        
                        <p class="mb-4 text-red-700 dark:text-red-300">
                            This action cannot be undone. All associated data will be permanently removed from the system.
                        </p>
                        
                        @if($hasUpcomingLessons)
                            <div class="p-4 mb-4 bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800/30 rounded text-yellow-800 dark:text-yellow-400">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    <strong>Warning:</strong>
                                </div>
                                <p class="ml-7 mt-1">
                                    This instructor has upcoming lessons scheduled. Please reassign these lessons before deleting the instructor.
                                </p>
                            </div>
                        @endif
                        
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
                        <a href="{{ route('admin.instructors.show', $instructor) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        
                        <form action="{{ route('admin.instructors.destroy', $instructor) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" {{ $hasUpcomingLessons ? 'disabled' : '' }}>
                                Yes, Delete Instructor
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
