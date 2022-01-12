@component('mail::message')

@if ($chore_instance_groups->isEmpty())
  {{ __('# No chores due today!') }}
@else

@foreach($chore_instance_groups as $group => $chore_instance_group)
## {{ Str::snakeToLabel($group) }}

@foreach($chore_instance_group as $chore_instance)
{{"- [{$chore_instance['title']}]({$chore_instance['url']})"}}
@endforeach

@endforeach
@endif

@component('mail::button', ['url' => route('dashboard')])
{{ __('Open in Chore Manager') }}
@endcomponent

@endcomponent
