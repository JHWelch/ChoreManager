<button {{ $attributes->merge([
  'type' => 'button',
  'class' => 'px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-md text-gray-600 tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150 w-full sm:w-1/2 text-center'
]) }}>
  {{ $slot }}
</button>
