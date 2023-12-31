<div class="card {{ $full ? '' : 'col-3 mx-3 my-3' }}" style="padding: 0">
  <img src="{{ $event->poster }}" class="card-img-top" alt="{{ $event->title }} - Poster">
  <div class="card-body">
    <h5 class="card-title">{{ $event->title }}</h5>
    @if($full)
        <p class="card-text">{!! $event->description !!}</p>
    @else
    <p class="card-text">{!! $event->highlight !!}</p>
    @endif
  </div>
  <ul class="list-group list-group-flush" style="border: 0">
    <li class="list-group-item small text-end"><a data-bs-toggle='modal' href='#event-{{$event->id}}'>Detail</a></li>
    <li class="list-group-item small">Price: <span class="text-end" >Rp {{ $event->price }}</span></li>
    <li class="list-group-item small">Mulai: {{ to_date($event->start_at) }} {{ to_time($event->start_at) }}</li>
    <li class="list-group-item small">Selesai: {{ to_date($event->end_at) }} {{ to_time($event->end_at) }}</li>
    <li class="list-group-item small">Lokasi: {{ $event->location }}</li>
    @if(!Auth::user()->promoter)
        @if($bought)
            <div class="text-center m-2">
                {!! QrCode::size($full ? 200 : 75)
                ->errorCorrection('M')
                ->generate(env('APP_URL'). '/tickets/' . $event->getUserTicket()->id . '/use') !!}
            </div>
        @endif
    @endif
  </ul>
  <div class="card-body">
    @if(Auth::user()->promoter)
        <form action="/events/{{$event->id}}" method="POST">
            @csrf
            @method('DELETE')
            <button title="Delete Event" class="btn btn-danger btn-sm">Delete</button>
        </form>
    @else
        @if($bought)
            <form action="/tickets/{{$event->getUserTicket()->id}}/refund" method="POST">
              @csrf
              @method('PUT')
              <button title="Refund Ticket" class="btn btn-warning btn-sm">Refund</button><br/>
              <i class="small">Refund: 75% of ticket price (approx: Rp {{ $event->price * 0.75}})</i>
            </form>
        @else
            <form action="/events/{{$event->id}}/buy-ticket" method="POST">
              @csrf
              @method('PUT')
              <button title="Buy Ticket" class="btn btn-primary btn-sm">Buy</button>
            </form>
        @endif
    @endif
  </div>
</div>