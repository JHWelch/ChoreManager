<div>
  <h1>Today's Chores</h1>

  @if ($chore_instances->isEmpty())
    <h2>No chores due today!</h2>
  @else
    <ul>
      @foreach($chore_instances as $chore_instance)
        <li>
          {{ $chore_instance }}
        </li>
      @endforeach
    </ul>
  @endif
</div>
