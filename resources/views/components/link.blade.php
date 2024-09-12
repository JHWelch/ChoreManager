<a {{
  $attributes->class([
    'inline-flex items-center rounded-md border border-transparent bg-purple-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2',
    'dark:bg-purple-800 dark:hover:bg-purple-900',
  ])
}}>
    {{ $slot }}
</a>
