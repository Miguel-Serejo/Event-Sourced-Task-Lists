<div class="row">
  <div class="col">
    <h1>Milestones</h1>
  </div>
</div>
<div class="row">
  <div class="col">
    <table class="table">
      <tbody>
        @forelse ($milestones as $milestone)
          <tr>
            <th>{{$milestone->text}}</th>
            <td>{{$milestone->timestamp}}</td>
          </tr>
        @empty
          <tr>
            <td colspan=2>No milestones have been achieved.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
