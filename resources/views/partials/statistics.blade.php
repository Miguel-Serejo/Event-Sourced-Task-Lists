<div class="row">
  <div class="col">
    <h1>Statistics</h1>
  </div>
</div>
<div class="row">
  <div class="col">
    <table class="table">
      <tbody>
        @forelse ($statistics as $statistic)
          <tr>
            <th>{{$statistic->name}}</th>
            <td>{{$statistic->value}}</td>
          </tr>
        @empty
          <tr>
            <td colspan=2>There are no statistics</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
