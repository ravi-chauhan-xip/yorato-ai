<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
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
            <h4 class="page-title">
                @if(isset($crumbTitle))
                    @if (is_callable($crumbTitle))
                        {!! $crumbTitle() !!}
                    @else
                        {{ $crumbTitle }}
                    @endif
                @else
                    {{ $crumb }}
                @endif
            </h4>
        </div>
    </div>
</div>
