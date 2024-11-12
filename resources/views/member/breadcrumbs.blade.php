<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>
                @if(isset($crumbTitle))
                    @if (is_callable($crumbTitle))
                        {!! $crumbTitle() !!}
                    @else
                        {{ $crumbTitle }}
                    @endif
                @endif
            </h4>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard.index') }}">Home</a></li>
            @foreach($crumbs as $link => $crumb)
                <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                    @if (is_int($link))
                        @if (is_callable($crumb))
                            {!! $crumb() !!}
                        @else
                            {{ $crumb }}
                        @endif
                    @else
                        <a href="{{ $link }}">
                            @if (is_callable($crumb))
                                {!! $crumb() !!}
                            @else
                                {{ $crumb }}
                            @endif
                        </a>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</div>
