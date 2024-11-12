<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\ListBuilders\Member\AdminBVListBuilder;
use App\ListBuilders\Member\MemberLevelDetailListBuilder;
use App\ListBuilders\Member\MyDirectListBuilder;
use App\ListBuilders\Member\MyDownLineLeftListBuilder;
use App\ListBuilders\Member\MyDownLineListBuilder;
use App\ListBuilders\Member\MyDownLineRightListBuilder;
use App\Models\Member;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @throws Exception
     */
    public function direct(Request $request): RedirectResponse|Renderable|JsonResponse
    {
        return MyDirectListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    public function adminPower(Request $request): RedirectResponse|Renderable|JsonResponse
    {
        return AdminBVListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    /**
     * @throws Exception
     */
    public function myDownline(Request $request): RedirectResponse|Renderable|JsonResponse
    {
        return MyDownLineListBuilder::render([
            'path' => $this->member->path,
        ]);
    }

    public function level(Request $request)
    {
        $member = $this->member;
        $level = 1;

        do {
            $teamCount = Member::where('level', $level + $member->level)
                ->where('sponsor_path', 'like', $member->sponsor_path.'/%')
                ->count();

            $details[] = [
                'id' => $level,
                'level' => $level,
                'teamCount' => $teamCount,
            ];

            $level++;
        } while ($level <= 25);

        return view('member.reports.level', [
            'levelDetails' => $details,
        ]);
    }

    /**
     * @throws Exception
     */
    public function memberLevelDetail(Request $request, $level = 0): Renderable|JsonResponse|RedirectResponse
    {
        if ($request->get('level')) {
            $level = (int) $request->get('level');
        }

        return MemberLevelDetailListBuilder::render([
            'path' => $this->member->sponsor_path,
            'memberLevel' => $this->member->level,
            'level' => $level,
        ],
            name: 'My '.($level ? ' Level '.$level.' Team' : '')
        );
    }

    public function downLineLeft(Request $request): RedirectResponse|Renderable|JsonResponse
    {
        if ($this->member->left) {
            $path = optional($this->member->left)->path;
        } else {
            $path = $this->member->left_id.'/';
        }

        return MyDownLineLeftListBuilder::render([
            'path' => $path,
            'id' => $this->member->id,
        ]);
    }

    /**
     * @throws Exception
     */
    public function downLineRight(Request $request): RedirectResponse|Renderable|JsonResponse
    {
        if ($this->member->right) {
            $path = optional($this->member->right)->path;
        } else {
            $path = $this->member->right_id.'/';
        }

        return MyDownLineRightListBuilder::render([
            'path' => $path,
            'id' => $this->member->id,
        ]);
    }
}
