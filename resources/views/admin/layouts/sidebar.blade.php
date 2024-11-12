<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo ">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img class="logo-lg" src="{{ settings()->getFileUrl('logo', asset(env('LOGO'))) }}" alt="">
                <img class="logo-sm" src="{{ settings()->getFileUrl('favicon', asset(env('FAVICON'))) }}" alt="">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
                    fill="currentColor" fill-opacity="0.6"/>
                <path
                    d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
                    fill="currentColor" fill-opacity="0.38"/>
            </svg>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    {{--    For sidebar Icons : https://materialdesignicons.com/--}}

    <ul class="menu-inner py-1 pb-lg-0 pb-5">
        <!-- Dashboards -->
        <li class="menu-item">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        @if(settings('admin_roles'))
            @can('Admins-read')
                <li class="menu-item">
                    <a href="{{ route('admin.admins.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-shield-crown-outline"></i>
                        <div data-i18n="Dashboards">Admins</div>
                    </a>
                </li>
            @endcan
        @endif
        @can('Members-read')
            <li class="menu-item">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-group-outline"></i>
                    <div data-i18n="Members">Users</div>
                </a>
            </li>
        @endcan
        <li class="menu-item">
            <a href="{{ route('admin.company-wallet.index') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-shield-crown-outline"></i>
                <div data-i18n="Dashboards">Company Wallet</div>
            </a>
        </li>

        {{--        @can('KYCs-read')--}}
        {{--            <li class="menu-item">--}}
        {{--                <a href="{{ route('admin.kycs.index') }}" class="menu-link">--}}
        {{--                    <i class="menu-icon tf-icons mdi mdi-image-multiple-outline"></i>--}}
        {{--                    <div data-i18n="KYCs">KYCs</div>--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--        @endcan--}}

        @can('Packages-read')
            <li class="menu-item">
                <a href="{{ route('admin.packages.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-package-variant-closed-plus"></i>
                    <div data-i18n="Packages">Packages</div>
                </a>
            </li>
        @endcan
        <li class="menu-item">
            <a href="{{ route('admin.genealogy.show') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-family-tree"></i>
                <div data-i18n="Genealogy Tree">Genealogy Tree</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('admin.sponsor-genealogy.show') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-family-tree"></i>
                <div data-i18n="Genealogy Tree">Sponsor Genealogy Tree</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('admin.reports.top-up') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-hand-coin-outline"></i>
                <div data-i18n="Wallet Transfers">Top Up</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('admin.stake.index') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-currency-usd"></i>
                <div data-i18n="Wallet Transfers">Stake Coin</div>
            </a>
        </li>
        @can('Wallet-read')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-wallet-plus-outline"></i>
                    <div data-i18n="Wallet">Wallet</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('admin.wallet-transactions.index') }}" class="menu-link">
                            <div data-i18n="Collapsed menu">Transactions</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('admin.wallet-transactions.create') }}" class="menu-link">
                            <div data-i18n="Collapsed menu">Credit & Debits</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <li class="menu-item">
            <a href="{{ route('admin.income-withdrawal-requests.index') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-swap-horizontal-circle"></i>
                <div data-i18n="Wallet Transfers">Withdrawal Requests</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-wallet-bifold"></i>
                <div data-i18n="Wallet">Incomes</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.incomes.direct-wallet-income') }}" class="menu-link">
                        <div data-i18n="Collapsed menu">Direct Wallet Income</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.incomes.team-matching-wallet-income') }}" class="menu-link">
                        <div data-i18n="Collapsed menu">Team Matching Wallet Income</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.incomes.staking-income') }}" class="menu-link">
                        <div data-i18n="Collapsed menu">Staking Income</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.incomes.direct-sponsor-stake-income') }}" class="menu-link">
                        <div data-i18n="Collapsed menu">Direct Sponsor Staking Income</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.incomes.team-matching-staking-income') }}" class="menu-link">
                        <div data-i18n="Collapsed menu">Team Matching Staking Income</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.incomes.leadership-income') }}" class="menu-link">
                        <div data-i18n="Collapsed menu">Leadership Income</div>
                    </a>
                </li>
            </ul>
        </li>
        @can('Reports-read')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-chart-box-outline"></i>
                    <div data-i18n="Report">Report</div>
                </a>

                <ul class="menu-sub">
                    {{--                    <li class="menu-item">--}}
                    {{--                        <a href="{{ route('admin.reports.top-up') }}" class="menu-link">--}}
                    {{--                            <div data-i18n="Collapsed menu">Topup</div>--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}
                    <li class="menu-item">
                        <a href="{{ route('admin.reports.top-earners') }}" class="menu-link">
                            <div data-i18n="Collapsed menu">Top Earners</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('admin.reports.most-active-user') }}" class="menu-link">
                            <div data-i18n="Collapsed menu">Most Active User</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('admin.reports.admin-bv') }}" class="menu-link">
                            <div data-i18n="Collapsed menu">Admin Power Log</div>
                        </a>
                    </li>

                </ul>
            </li>
        @endif
        @can('Exports-read')
            <li class="menu-item">
                <a href="{{ route('admin.exports.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-export-outline"></i>
                    <div data-i18n="Exports">Exports</div>
                </a>
            </li>
        @endif
        @can('Support Ticket-read')
            <li class="menu-item">
                <a href="{{ route('admin.support-ticket.get') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-chat-plus-outline"></i>
                    <div data-i18n="Support Ticket"> Support Ticket</div>
                    <div class="badge bg-primary rounded-pill ms-auto">
                        {{ \App\Models\SupportTicketMessage::where('messageable_type',\App\Models\Member::class)->where('is_read',0)->count() }}
                    </div>
                </a>
            </li>
        @endcan
        {{--        @can('Banking Partner-read')--}}
        {{--            <li class="menu-item">--}}
        {{--                <a href="{{ route('admin.banking.show') }}" class="menu-link">--}}
        {{--                    <i class="menu-icon tf-icons mdi mdi-bank-outline"></i>--}}
        {{--                    <div data-i18n="Banking Partner"> Banking Partner</div>--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--        @endcan--}}
        @can('Website Setting-read')
            {{--            <li class="menu-item">--}}
            {{--                <a href="javascript:void(0);" class="menu-link menu-toggle">--}}
            {{--                    <i class="menu-icon tf-icons mdi mdi-tune-vertical-variant"></i>--}}
            {{--                    <div data-i18n="Website Setting ">Website Setting</div>--}}
            {{--                </a>--}}

            {{--                <ul class="menu-sub">--}}
            {{--                    <li class="menu-item">--}}
            {{--                        <a href="{{ route('admin.news.index') }}" class="menu-link">--}}
            {{--                            <div data-i18n="Collapsed menu">News Feed</div>--}}
            {{--                        </a>--}}
            {{--                    </li>--}}
            {{--                </ul>--}}
            {{--            </li>--}}
        @endif
    </ul>
</aside>
