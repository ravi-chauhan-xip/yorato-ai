<div class="btn-group">
    <button type="button" class="btn btn-toggle dropdown-toggle waves-effect shadow-none"
            data-bs-toggle="dropdown" aria-expanded="false">
        <i class="mdi mdi-dots-vertical"></i>
    </button>
    <div class="dropdown-menu">
        @if(!$model->isBlocked())

            {{--            <a class="dropdown-item" href="{{ route('admin.users.log', $model) }}" >--}}
            {{--                <i class='bx bx-file' ></i> Member Status Log--}}
            {{--            </a>--}}
            {{--                <a class="dropdown-item" href="--}}
            {{--                {{ route('admin.users.edit', $model) }}--}}
            {{--                " >--}}
            {{--                    <i class="mdi mdi-circle-edit-outline me-2"></i> Edit--}}
            {{--                </a>--}}
            <form action="{{ route('admin.users.impersonate.store', $model) }}" method="post" target="_blank"
                  class="noLoader">
                @csrf
                <a href="#" class="dropdown-item" onclick="$(this).parent('form').submit();">
                    <i class="mdi mdi-login-variant me-2"></i> Login User
                </a>
            </form>
            <form action="{{ route('admin.users.topup-power', $model) }}">
                <button class="dropdown-item" href="javascript:void();">
                    <i class="fa-duotone fa-user-pen me-2"></i>&nbsp;TopUp Power
                </button>
            </form>
            <form action="{{ route('admin.users.stake-power', $model) }}">
                <button class="dropdown-item" href="javascript:void();">
                    <i class="fa-duotone fa-user-pen me-2"></i>&nbsp;Stake Power
                </button>
            </form>
            <a class="dropdown-item" href="{{ route('admin.sponsor-genealogy.show', $model->user->wallet_address) }}">
                <i class="mdi mdi-family-tree me-2"></i> Tree
            </a>
            <form action="{{ route('admin.users.block.store', $model) }}" method="post">
                @csrf
                <button class="dropdown-item" href="#">
                    <i class="fa-duotone fa-user-slash me-2"></i>&nbsp;Block
                </button>
            </form>
        @else
            <form action="{{ route('admin.users.block.destroy', $model) }}"
                  method="post">
                @csrf
                @method('delete')
                <button class="dropdown-item" href="#">
                    <i class="mdi mdi-account-cancel  mr-2"></i>&nbsp;UnBlock
                </button>
            </form>
        @endif
    </div>
</div>
