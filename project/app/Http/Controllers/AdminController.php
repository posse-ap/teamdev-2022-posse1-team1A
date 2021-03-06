<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AccountStatus;
use App\Models\ScheduleStatus;
use App\Models\InterviewSchedule;
use App\Models\Chat;
use App\Models\CallingEvaluation;
use App\Models\Calling;
use App\Models\Reward;
use App\Models\ChatRecord;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountStopped;
use App\Mail\UnfrozedAccount;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $total_count_users = User::count(); //総会員数
        $total_count_matched = Chat::count(); // 総マッチ数
        $total_count_call = Calling::count(); // 総通話数
        $avg_calling_time = Calling::avg('calling_time'); // 平均通話時間
        $total_count_cancelled = InterviewSchedule::where('schedule_status_id', ScheduleStatus::getCancelId())->count(); // 日程調整失敗数
        return view('admin.index', compact('total_count_users', 'total_count_matched', 'total_count_call', 'avg_calling_time', 'total_count_cancelled'));
    }

    public function userlist()
    {
        $users = User::paginate(10);
        $keyword = null;

        return view('admin.user-list', compact('users', 'keyword'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = User::query();

        if (!empty($keyword)) {

            // 全角スペースを半角に変換
            $spaceConversion = mb_convert_kana($keyword, 's');

            // 単語を半角スペースで区切り、配列にする（例："山田 翔" → ["山田", "翔"]）
            $wordArraySearched = preg_split('/[\s,]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);

            // 単語をループで回し、ユーザーネームと部分一致するものがあれば、$queryとして保持される
            foreach ($wordArraySearched as $value) {
                $query->where(function ($query) use ($value) {
                    $query->where('name', 'LIKE', "%{$value}%")
                        ->orWhere('nickname', 'LIKE', "%{$value}%")
                        ->orWhere('company', 'LIKE', "%{$value}%")
                        ->orWhere('department', 'LIKE', "%{$value}%")
                        ->orWhere('email', 'LIKE', "%{$value}%");
                });
            }

            $users = $query->paginate(10);
        } else {
            $users = User::paginate(10);
        }

        return view('admin.user-list', compact('users', 'keyword'));
    }

    public function accountStop(Request $request)
    {
        $user = User::find($request->id);
        $user->account_status_id = AccountStatus::getStoppedId();
        $user->save();

        // anoveybot送信（ユーザーが依頼者だったルームに送信）
        $chats = User::find($request->id)->client_chats;
        foreach ($chats as $chat) {
            $chat_record = new ChatRecord;
            $chat_record->chat_id = $chat->id;
            $chat_record->user_id = Role::getBotId();
            $chat_record->comment = $user->nickname . "さんのアカウントが停止されました。恐れ入りますが、他の方をお探しください。";
            $chat_record->save();
        }

        // anoveybot送信（ユーザーが回答者だったルームに送信）
        $chats = User::find($request->id)->respondent_chats;
        foreach ($chats as $chat) {
            $chat_record = new ChatRecord;
            $chat_record->chat_id = $chat->id;
            $chat_record->user_id = Role::getBotId();
            $chat_record->comment = $user->nickname . "さんのアカウントが停止されました。恐れ入りますが、他の方をお探しください。";
            $chat_record->save();
        }

        // メール
        Mail::to($user->email)->send(new AccountStopped($user));

        return redirect()->route('admin.userlist');
    }

    public function accountActive(Request $request)
    {
        $user = User::find($request->id);
        $user->account_status_id = AccountStatus::getActiveId();
        $user->save();

        // メール
        Mail::to($user->email)->send(new UnfrozedAccount($user));

        return redirect()->route('admin.userlist');
    }

    public function callEvaluation()
    {
        // 総合満足度表示
        $all = CallingEvaluation::count();
        $isSatisfied = CallingEvaluation::where('is_satisfied', true)->count();
        $comprehensive = round($isSatisfied / $all * 100);

        // 依頼者満足度表示
        $respondent = CallingEvaluation::where('is_respondent', false)->count();
        $respondentIsSatisfied = CallingEvaluation::where('is_respondent', false)->where('is_satisfied', true)->count();
        $respondentComprehensive = round($respondentIsSatisfied / $respondent * 100);

        // 匿名回答者満足度表示
        $client = CallingEvaluation::where('is_respondent', true)->count();
        $clientIsSatisfied = CallingEvaluation::where('is_respondent', true)->where('is_satisfied', true)->count();
        $clientComprehensive = round($clientIsSatisfied / $client * 100);

        // 評価詳細表示
        $callings = Calling::paginate(5);

        return view('admin.call-evaluation', compact('comprehensive', 'respondentComprehensive', 'clientComprehensive', 'callings'));
    }

    public function rewardList(Request $request)
    {
        if ($request->search_key === 'unpaid') {
            $rewards = Reward::where('is_paid', false)->latest()->paginate(10);
        } else if ($request->search_key === 'last_month') {
            $today = new Carbon();
            $last_month = $today->subMonth();
            $rewards = Reward::whereYear('created_at', $last_month->year)->whereMonth('created_at', $last_month->month)->latest()->paginate(10);
        } else {
            $rewards = Reward::latest()->paginate(10);
        }

        return view('admin.reward-list', compact('rewards'));
    }

    public function rewardListPaid(Request $request)
    {
        $reward = Reward::find($request->id);
        $reward->is_paid = true;
        $reward->save();

        return redirect()->route('admin.reward_list');
    }

    public function withdrawalList()
    {
        $users = User::where('account_status_id', AccountStatus::getWithdrawnId())->paginate(10);

        return view('admin.withdrawal-list', compact('users'));
    }
}
