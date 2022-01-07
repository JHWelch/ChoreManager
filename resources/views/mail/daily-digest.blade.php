<div>
  <h1>Today's Chores</h1>

  <ul>
    @foreach($chore_instances as $chore_instance)
      <li>
        {{ $chore_instance }}
      </li>
    @endforeach
  </ul>
</div>
