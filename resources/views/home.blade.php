@extends('layouts.app')

@section('content')
    <div class="container">
        @if(auth()->user()->isOnGracePeriod())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Grace Period</div>

                        <div class="card-body">
                            <p>
                                Your subscription will fully expired
                                on {{auth()->user()->subscription_end_at->format('Y-m-d')}}
                            </p>
                            <form action="/subscriptions" method="POST">
                                @csrf
                                {{method_field('PATCH')}}
                                <div class="checkbox">
                                    <label for="resume">
                                        <input type="checkbox" name="resume" id="resume">
                                        Resume My Subscription
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        @elseif(!auth()->user()->isActive())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Dashboard</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <checkout-component :plans="{{$plans}}"/>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(count(auth()->user()->payments))
                <br>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Payments</div>

                        <div class="card-body">
                            <ul class="list-group">
                                @foreach(auth()->user()->payments as $payment)
                                    <li class="list-group-item">
                                        {{$payment->created_at->diffForHumans()}} :
                                        <strong>
                                            $ {{number_format($payment->amount / 100, 2)}}
                                        </strong>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->isSubscribed())
            <br>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Cancel Subscription</div>

                        <div class="card-body">
                            <form method="POST" action="{{route('subscription.delete')}}">
                                @csrf
                                {{method_field('DELETE')}}
                                <button type="submit" class="btn btn-danger">
                                    Cancel My Subscription
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
