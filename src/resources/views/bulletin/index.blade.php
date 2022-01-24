@extends('common.layout')

@section('content')

    <div class="col-md-7 col-lg-8">

        <div class="py-5 text-center">
            <h2>Hello {{ $username }}</h2>
        </div>

        @if ($errors)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{$errors}}
                <button type="button" class="btn btn-close" data-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif

        <form method="post" action="/bulletin?token={{ $token }}">
            <div class="col-12">
                <div class="input-group has-validation">
                </div>

            </div>
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="message"
                       name="message"
                       placeholder="Post a message to the board"
                       required>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Post</button>
                </div>
            </div>
        </form>

        <hr class="my-4">

        <div class="row">
            <div class="col-md-12 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Messages</span>
                    <span class="badge badge-secondary badge-pill">3</span>
                </h4>
                <ul class="list-group mb-3">
                    @forelse ($messages as $message)
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <table class="table table-borderless table-sm table-striped">
                            <tbody>
                            <tr>
                                <td width="100%">
                                    <h6 class="my-0">{{'@'}}{{ $message->username }}</h6>
                                </td>
                                <td>
                                    <small class="text-muted badge badge-pill badge-primary">{{ Carbon\Carbon::createFromTimeString($message->created_at)->format('j M y, h:i A') }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="text-muted">{{ $message->content }}</span></td>
                            </tr>
                        </table>
                    </li>
                    @empty
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">No messages</h6>
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

@endsection
