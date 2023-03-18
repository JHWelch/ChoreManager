<button {{ $attributes->merge([
  'type'  => 'submit',
  'class' => 'rounded-md border border-transparent bg-purple-600 px-4 py-2 text-md font-semibold text-white shadow-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 w-full sm:w-1/2 text-center'
]) }}>
  {{ $slot }}
</button>
