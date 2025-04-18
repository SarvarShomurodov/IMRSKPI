<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TaskAssignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ClientAllController extends Controller
{
    // public function filterByDate($query, $from, $to)
    // {
    //     // If from_date and to_date are provided, filter the query
    //     if ($from && $to) {
    //         $query->whereBetween('addDate', [$from, $to]);
    //     }

    //     return $query; // Return the filtered query
    // }

    public function index(Request $request)
    {
        $year = $request->input('year', 2025); // Default 2025
        $month = $request->input('month');
    
        $from = null;
        $to = null;
    
        if ($year && $month) {
            $from = Carbon::createFromFormat('Y-m-d', "$year-$month-01")->subMonth()->day(26)->toDateString();
            $to = Carbon::createFromFormat('Y-m-d', "$year-$month-01")->day(25)->toDateString();
        }
    
        $query = TaskAssignment::with('subtask.task');
    
        if ($from && $to) {
            $query->whereBetween('addDate', [$from, $to]);
        }
    
        $assignments = $query->get()->groupBy('user_id')->map(function ($assignments) {
            $groupedTasks = $assignments->groupBy(function ($a) {
                return $a->subtask->task->taskName ?? 'Noma\'lum';
            });
        
            $tasks = $groupedTasks->map(function ($group, $taskName) {
                return [
                    'task_name' => $taskName,
                    'total_rating' => $group->sum('rating'),
                ];
            })->values();
        
            return [
                'total_rating' => $assignments->sum('rating'),
                'tasks' => $tasks,
            ];
        });
    
        $currentUser = auth()->user();
        $currentUserAssignment = $assignments[$currentUser->id] ?? null;
    
        // KPI with bonus for 2025
        $monthlyKpis = collect();
    
        $baseUsers = User::all()->filter(function ($u) {
            $roles = $u->getRoleNames();
            return !$roles->contains('Admin') && !$roles->contains('Super Admin');
        });
    
        for ($m = 1; $m <= 12; $m++) {
            $from = Carbon::createFromDate($year, $m, 1)->subMonth()->day(26)->toDateString();
            $to = Carbon::createFromDate($year, $m, 1)->day(25)->toDateString();
        
            $assignmentsMonth = TaskAssignment::with(['subtask.task', 'user'])
                ->whereBetween('addDate', [$from, $to])
                ->get();
        
            $monthlyRatingsWithBonus = $baseUsers->mapWithKeys(function ($user) use ($assignmentsMonth, $baseUsers) {
                $userAssignments = $assignmentsMonth->where('user_id', $user->id);
                $totalRating = $userAssignments->sum('rating');
            
                $projectUsers = $baseUsers->filter(fn($u) => $u->project_id == $user->project_id);
            
                $projectAvg = $projectUsers->map(fn($u) => $assignmentsMonth->where('user_id', $u->id)->sum('rating'))->avg();
                $globalAvg = $baseUsers->map(fn($u) => $assignmentsMonth->where('user_id', $u->id)->sum('rating'))->avg();
            
                $bonus = 0;
                if ($projectAvg > $globalAvg) {
                    $bonus = $totalRating > $projectAvg
                        ? round($projectAvg * 0.10, 2)
                        : round($projectAvg * 0.05, 2);
                }
            
                $totalWithBonus = $totalRating + $bonus;
            
                return [
                    $user->id => $totalWithBonus
                ];
            });
        
            $maxTotalWithBonus = $monthlyRatingsWithBonus->max();
        
            $userTotal = $monthlyRatingsWithBonus[$currentUser->id] ?? 0;
        
            $kpi = $maxTotalWithBonus > 0 ? round(($userTotal / $maxTotalWithBonus) * 100, 2) : 0;
        
            $monthlyKpis->push([
                'month' => Carbon::create()->month($m)->format('F'),
                'kpi' => $kpi,
            ]);
        }
    
        return view('client.view.test', compact(
            'assignments',
            'from',
            'to',
            'currentUserAssignment',
            'monthlyKpis'
        ));
    }
    // public function index(Request $request)
    // {
    //     $year = $request->input('year');
    //     $month = $request->input('month');

    //     $from = null;
    //     $to = null;

    //     // Agar yil va oy tanlangan bo'lsa
    //     if ($year && $month) {
    //         // Hozirgi oydan oldingi oyning 26-sanasini hisoblash
    //         $from = Carbon::createFromFormat('Y-m-d', "$year-$month-01")
    //                     ->subMonth() // Oldingi oyga o'tish
    //                     ->day(26)    // 26-sana
    //                     ->toDateString();

    //         // Hozirgi oyni 25-sanasini hisoblash
    //         $to = Carbon::createFromFormat('Y-m-d', "$year-$month-01")
    //                     ->day(25)    // 25-sana
    //                     ->toDateString();
    //     }

    //     // So'rovni qurish
    //     $query = TaskAssignment::with('subtask.task');

    //     // Agar from va to mavjud bo'lsa, sana bo'yicha filter
    //     if ($from && $to) {
    //         $query->whereBetween('addDate', [$from, $to]);
    //     }

    //     // Ma'lumotlarni olish va guruhlash
    //     $assignments = $query->get()
    //         ->groupBy('user_id')
    //         ->map(function ($assignments) {
    //             $groupedTasks = $assignments->groupBy(function ($a) {
    //                 return $a->subtask->task->taskName ?? 'Noma\'lum';
    //             });

    //             $tasks = $groupedTasks->map(function ($group, $taskName) {
    //                 return [
    //                     'task_name' => $taskName,
    //                     'total_rating' => $group->sum('rating'),
    //                 ];
    //             })->values();

    //             return [
    //                 'total_rating' => $assignments->sum('rating'),
    //                 'tasks' => $tasks,
    //             ];
    //         });

    //     $currentUser = auth()->user();
    //     $currentUserAssignment = $assignments[$currentUser->id] ?? null;

    //     return view('client.view.index', compact(
    //         'assignments',
    //         'from',
    //         'to',
    //         'currentUserAssignment'
    //     ));
    // }

    // subtask metodini o'zgartirish
    public function subtask(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $year = $request->input('year');
        $month = $request->input('month');

        $from = null;
        $to = null;

        if ($year && $month) {
            $from = Carbon::createFromFormat('Y-m-d', "$year-$month-01")
                        ->subMonth() // Oldingi oyga o'tish
                        ->day(26)    // 26-sana
                        ->toDateString();

            $to = Carbon::createFromFormat('Y-m-d', "$year-$month-01")
                        ->day(25)    // 25-sana
                        ->toDateString();
        }

        $query = TaskAssignment::with('subtask.task')
            ->where('user_id', $user->id);

        if ($from && $to) {
            $query->whereBetween('addDate', [$from, $to]);
        }

        $assignments = $query->get();

        $userAssignment = $assignments->groupBy(function ($item) {
            return $item->subtask->task->taskName ?? 'Nomaʼlum';
        });

        $taskStats = $userAssignment->map(function ($group) {
            return [
                'sum' => $group->sum('rating'),
                'avg' => round($group->avg('rating'), 2),
                'assignments' => $group,
            ];
        });

        $totalSum = $assignments->sum('rating');
        $totalAvg = round($assignments->avg('rating'), 2);
        $totalCount = $assignments->count();

        return view('client.view.dataset', compact(
            'user',
            'taskStats',
            'totalSum',
            'totalAvg',
            'totalCount',
            'from',
            'to'
        ));
    }

    public function allsubtask(Request $request)
    {
        $user = auth()->user();
    
        if (!$user) {
            return redirect()->route('login');
        }
    
        // Fetch year and month from request
        $year = $request->input('year');
        $month = $request->input('month');
    
        $from = null;
        $to = null;
    
        if ($year && $month) {
            $from = Carbon::createFromFormat('Y-m-d', "$year-$month-01")
                        ->subMonth()->day(26)->toDateString();
    
            $to = Carbon::createFromFormat('Y-m-d', "$year-$month-01")
                        ->day(25)->toDateString();
        }
    
        // $today = now()->format('Y-m-d');
        // $oneMonthAgo = now()->subMonth()->format('Y-m-d');
    
        $tasks = Task::all();
    
        $positions = User::whereNotNull('position')
            ->where('position', '!=', '')
            ->select('position')
            ->distinct()
            ->orderBy('position')
            ->pluck('position');
    
        // Hozirgi user position
        $userPosition = $user->position;
    
        // Barcha userlar (filtrlash uchun hammasi kerak)
        $staffUsers = User::all();
    
        // Faqatgina shu user positioniga teng userlar
        $samePositionUsers = User::where('position', $userPosition)->get();
    
        // KPI natijalari
        $query = TaskAssignment::with('subtask.task', 'user');
    
        // if ($from && $to) {
        //     $query->whereBetween('addDate', [$from, $to]);
        // } else {
        //     $query->whereBetween('addDate', [$oneMonthAgo, $today]);
        // }
        if ($from && $to) {
            $query->whereBetween('addDate', [$from, $to]);
        }
    
        $assignments = $query->get()
            ->groupBy('user_id')
            ->map(function ($assignments) {
                return [
                    'total_rating' => $assignments->sum('rating'),
                    'tasks' => $assignments->map(function ($a) {
                        return [
                            'task_id' => $a->subtask->task->id ?? null,
                            'task_name' => $a->subtask->task->taskName ?? null,
                            'rating' => $a->rating,
                        ];
                    }),
                ];
            });
    
        return view('client.view.userstats', compact(
            'tasks',
            'staffUsers',
            'samePositionUsers',
            'assignments',
            'positions',
            'from',
            'to',
            'userPosition',
        ));
    }
    
    public function editProfile()
    {
        return view('client.view.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'old_password' => 'required',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Eski parol noto‘g‘ri']);
        }

        $user->email = $request->email;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil yangilandi!');
    }
}
