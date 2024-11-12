<div class="hv-item-child">
    @if($level < 3)
        <div class="hv-item">
            <div class="hv-item-parent">
                @include('member.genealogy.person', ['member' => $member])
            </div>
            <div class="hv-item-children">
                @if($level==0)
                    @include('member.genealogy.member', ['member' => $member->left ?? null, 'level' => $level + 1,'parent' => $member, 'side' => $side ?  $side : \App\Models\Member::PARENT_SIDE_LEFT])
                    @include('member.genealogy.member', ['member' => $member->right ?? null, 'level' => $level + 1,'parent' => $member, 'side' => $side ?  $side : \App\Models\Member::PARENT_SIDE_RIGHT])
                @else
                    @include('member.genealogy.member', ['member' => $member->left ?? null, 'level' => $level + 1,'parent' => $member, 'side' => $side])
                    @include('member.genealogy.member', ['member' => $member->right ?? null, 'level' => $level + 1,'parent' => $member, 'side' => $side])
                @endif
            </div>
        </div>
    @else
        @include('member.genealogy.person', ['member' => $member])
    @endif
</div>




