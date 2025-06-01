<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#3498db] to-[#2980b9] border border-transparent rounded-md font-medium text-white hover:from-[#2980b9] hover:to-[#2573a7] focus:outline-none focus:ring-2 focus:ring-[#3498db] focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
