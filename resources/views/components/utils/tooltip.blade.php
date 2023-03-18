{{--
Adds x-tooltip directive to AlpineJS

Usage:
<span
  x-data
  x-tooltip="Some tooltip"
>
  Regular String
</span>
--}}

@push('head')
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="https://unpkg.com/tippy.js@6"></script>
@endpush

<script>
  document.addEventListener('alpine:init', () => {
    // Magic: $tooltip
    Alpine.magic('tooltip', el => message => {
      let instance = tippy(el, { content: message, trigger: 'manual' })

      instance.show()

      setTimeout(() => {
        instance.hide()

        setTimeout(() => instance.destroy(), 150)
      }, 2000)
    })

    // Directive: x-tooltip
    Alpine.directive('tooltip', (el, { expression }) => {
      tippy(el, { content: expression })
    })
  })
</script>
