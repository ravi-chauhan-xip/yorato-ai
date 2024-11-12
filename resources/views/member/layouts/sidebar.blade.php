<div class="deznav">
    <div class="deznav-scroll">
        <div class="main-profile">
            <div class="image-bx">
                <img src="{{ Auth::user()->member->present()->profileImage() }}" alt="">
            </div>
            <h5 class="name"><span class="font-w400">Hello,</span> {{ Auth::user()->name }}</h5>
            <p class="email">
                {{ view('admin.web3-address', ['address' => Auth::user()->member->user->wallet_address]) }}
            </p>
        </div>
        <ul class="metismenu" id="menu">
            <li>
                <a href="{{ route('user.dashboard.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="mdi mdi-view-dashboard-outline"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="mdi mdi-family-tree"></i>
                    <span class="nav-text">Genealogy Tree</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('user.genealogy.show') }}">Binary</a></li>
                    <li><a href="{{ route('user.sponsor-genealogy.show') }}">Sponsor</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('user.top-up.show') }}" class="ai-icon" aria-expanded="false">
                    <i class="mdi mdi-hand-coin-outline"></i>
                    <span class="nav-text">Topup</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.stake.show') }}" class="ai-icon" aria-expanded="false">
                    <i class="mdi mdi-currency-usd"></i>
                    <span class="nav-text">Stake</span>
                </a>
            </li>
            <li class="nav-label">Request & Transactions</li>
            <li>
                <a href="{{ route('user.income-withdrawals.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="mdi mdi-cash-multiple"></i>
                    <span class="nav-text">Withdrawal Request</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.wallet-transactions.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="mdi mdi-wallet-bifold-outline"></i>
                    <span class="nav-text">Wallet Transaction</span>
                </a>
            </li>
            <li class="nav-label">Income & Reports</li>
            <li>
                <a class="has-arrow ai-icon" href="javascript:void(0);">
                    <i class="mdi mdi-briefcase-variant-outline"></i>
                    <span class="nav-text">Incomes</span>
                </a>
                <ul aria-expanded="false">
                    <li>
                        <a href="{{ route('user.incomes.direct-wallet-income') }}">
                            Direct Wallet Income
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.incomes.team-matching-wallet-income') }}">
                            Team Matching Wallet Income
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.incomes.staking-income') }}">
                            Staking Income
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.incomes.direct-sponsor-stake-income') }}">
                            Direct Sponsor Staking Income
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.incomes.team-matching-staking-income') }}">
                            Team Matching Staking Income
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.incomes.leadership-income') }}">
                            Leadership Income
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item">
                <a class="has-arrow ai-icon" href="javascript:void(0);">
                    <i class="mdi mdi-text-box-multiple-outline"></i>
                    <span class="nav-text">Reports</span>
                </a>
                <ul class="menu-sub">
                    <li>
                        <a href="{{ route('user.reports.direct') }}">
                            My Direct
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.reports.downline') }}">
                            My Downline
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.reports.downline-left') }}">
                            My Left Downline
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.reports.downline-right') }}">
                            My Right Downline
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.reports.my-team') }}">
                            My Team
                        </a>
                    </li>
{{--                    <li>--}}
                    {{--                        <a href="{{ route('user.reports.admin-power') }}">--}}
                    {{--                            Admin Power Log--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}
                </ul>
            </li>
            <li class="nav-label">General</li>
            <li>
                <a href="{{ route('user.support.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="mdi mdi-headphones-settings"></i>
                    <span class="nav-text">Support</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.exports.index') }}" class="ai-icon" aria-expanded="false">
                    <i class="mdi mdi-export"></i>
                    <span class="nav-text">Export</span>
                </a>
            </li>
        </ul>
        <div class="copyright">
            <p class="fs-12">Made with <span class="heart"></span> by {{ settings('company_name') }}</p>
        </div>
    </div>
</div>
