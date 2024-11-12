<div class="hv-item-child">
    @if($level < 3)
        <div class="hv-item">
            <div class="hv-item-parent">
                @include('admin.genealogy.person', ['member' => $member])
            </div>
            <div class="hv-item-children">
                @include('admin.genealogy.member', ['member' => $member->left ?? null, 'level' => $level + 1,'parent' => $member, 'side' => \App\Models\Member::PARENT_SIDE_LEFT])
                @include('admin.genealogy.member', ['member' => $member->right ?? null, 'level' => $level + 1,'parent' => $member, 'side' => \App\Models\Member::PARENT_SIDE_RIGHT])
            </div>
        </div>
    @else
        @include('admin.genealogy.person', ['member' => $member])
    @endif
</div>
