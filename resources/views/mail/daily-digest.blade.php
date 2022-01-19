@component('mail::message')

@forelse($chore_instance_groups as $group => $chore_instance_group)
## {{ Str::snakeToLabel($group) }}

@foreach($chore_instance_group as $chore_instance)
{{"- [{$chore_instance['title']}]({$chore_instance['url']})"}}
@endforeach

@empty
{{ __('# No chores due today!') }}
@endforelse

@component('mail::button', ['url' => route('dashboard')])
{{ __('Open in Chore Manager') }}
@endcomponent

@endcomponent
