<a {{
  $attributes->class([
    'inline-flex items-center rounded-md border border-transparent bg-violet-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2',
    'dark:bg-violet-800 dark:hover:bg-violet-900',
  ])
}}>
    {{ $slot }}
</a>
