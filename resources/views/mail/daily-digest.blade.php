@component('mail::message')

{{ __('# Today\'s Chores') }}

@if ($chore_instances->isEmpty())
{{ __('## No chores due today!') }}
@else

@foreach($chore_instances as $chore_instance)
{{"- [{$chore_instance['title']}]({$chore_instance['url']})"}}
@endforeach

@endif

@component('mail::button', ['url' => route('register')])
{{ __('Open in Chore Manager') }}
@endcomponent

@endcomponent
