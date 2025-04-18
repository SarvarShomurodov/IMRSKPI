<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Bonus;
use App\Models\SubTask;
use Illuminate\Http\Request;
use App\Models\TaskAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ClientTaskController extends Controller
{
    // Barcha tasksni ko'rsatish
    public function index()
    {
        $tasks = Task::all();
        return view('client.tasks.index', compact('tasks'));
    }

    // Taskga bosganda, uning xodimlar ro'yxatini ko'rsatish
    public function show($taskId)
    {
        $task = Task::findOrFail($taskId);
        $staffUsers = User::all();  // Xodimlar ro'yxatini olish
    
        // Har bir user_id uchun umumiy rating
        $ratings = TaskAssignment::select('user_id', DB::raw('SUM(rating) as total_rating'))
            ->groupBy('user_id')
            ->pluck('total_rating', 'user_id'); // [user_id => total_rating]
    
        return view('client.tasks.show', compact('task', 'staffUsers', 'ratings'));
    }

    // Foydalanuvchiga baho berish formasi
    public function assignTask($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);
        $staffUser = User::findOrFail($userId);
        $subtasks = SubTask::where('task_id', $taskId)->get(); 
        return view('client.tasks.assign', compact('task', 'staffUser', 'subtasks'));
    }

    // Baho saqlash
    public function storeRating(Request $request, $taskId, $userId)
    {
        // 1. To'g'ri validation qilish
        $request->validate([
            'subtask_id' => 'required|exists:sub_tasks,id',
            'rate' => 'required|numeric',         // ✅ formdan `rate` kelyapti
            'comment' => 'nullable|string',
            'date' => 'required|date',            // ✅ formdan `date` kelyapti
        ]);
    
        // 2. Task va userni olish
        $task = Task::findOrFail($taskId);
        $staffUser = User::findOrFail($userId);
        $subtask = SubTask::findOrFail($request->subtask_id);
    
        // 3. Agar rate min va max oralig'ida bo'lishini tekshirish kerak bo'lsa
        // if ($request->rate < $subtask->min || $request->rate > $subtask->max) {
        //     return back()->withErrors(['rate' => 'Baho SubTaskning belgilangan minimal va maksimal qiymatlariga mos kelmaydi.']);
        // }
    
        // 4. Ma'lumotni saqlash
        TaskAssignment::create([
            'subtask_id' => $request->subtask_id,
            'user_id' => $staffUser->id,
            'rating' => $request->rate,          // ✅ formdagi `rate` ni bazadagi `rating` ga
            'comment' => $request->comment,
            'addDate' => $request->date,         // ✅ formdagi `date` ni bazadagi `addDate` ga
        ]);
    
        // 5. Redirect qilish
        return redirect()->route('tasks.assign', ['taskId' => $taskId, 'staffId' => $userId])
                     ->with('success', 'Baho muvaffaqiyatli saqlandi');
    }

    public function swod(Request $request)
    {
        $from = $request->input('from_date');
        $to = $request->input('to_date');
        $position = $request->input('position');
    
        $tasks = Task::all();
        $staffUsers = User::all();
    
        // Faqat Admin va Super Admin bo'lmaganlar
        $baseUsers = $staffUsers->filter(function ($u) {
            $roles = $u->getRoleNames();
            return !$roles->contains('Admin') && !$roles->contains('Super Admin');
        });
    
        // Agar position bo'yicha filter bo'lsa
        $normalUsers = $position
            ? $baseUsers->filter(fn($u) => $u->position == $position)
            : $baseUsers;
    
        // Date filtriga mos keluvchi assignmentlar
        $query = TaskAssignment::with(['subtask.task', 'user']);
        if ($from && $to) {
            $query->whereBetween('addDate', [$from, $to]);
        }
        $rawAssignments = $query->get();
    
        // KPI, bonus, rating hisoblash
        $assignments = $normalUsers->mapWithKeys(function ($user) use ($rawAssignments, $baseUsers) {
            $userAssignments = $rawAssignments->where('user_id', $user->id);
            $totalRating = $userAssignments->sum('rating');
    
            // Project bo'yicha foydalanuvchilar (Admin bo'lmaganlar orasida)
            $projectUsers = $baseUsers->filter(function ($u) use ($user) {
                return $u->project_id == $user->project_id;
            });
    
            $projectAvg = $projectUsers->map(function ($u) use ($rawAssignments) {
                return $rawAssignments->where('user_id', $u->id)->sum('rating');
            })->avg();
    
            $globalAvg = $baseUsers->map(function ($u) use ($rawAssignments) {
                return $rawAssignments->where('user_id', $u->id)->sum('rating');
            })->avg();
    
            // Bonus hisoblash
            $bonus = 0;
            if ($projectAvg > $globalAvg) {
                $bonus = $totalRating > $projectAvg
                    ? round($projectAvg * 0.10, 2)
                    : round($projectAvg * 0.05, 2);
            }
    
            $totalWithBonus = $totalRating + $bonus;
    
            return [
                $user->id => [
                    'user_id' => $user->id,
                    'globalAvg' => $globalAvg,
                    'total_rating' => $totalRating,
                    'bonus' => $bonus,
                    'total_with_bonus' => $totalWithBonus,
                    'tasks' => $userAssignments->map(function ($a) {
                        return [
                            'task_id' => $a->subtask->task->id ?? null,
                            'task_name' => $a->subtask->task->taskName ?? null,
                            'rating' => $a->rating,
                        ];
                    }),
                ],
            ];
        });
    
        // KPI uchun maksimal qiymatni olish
        $maxTotalWithBonus = $assignments->max('total_with_bonus');
    
        // KPI ni hisoblash
        $assignments = $assignments->map(function ($data) use ($maxTotalWithBonus) {
            $kpi = $maxTotalWithBonus > 0
                ? round(($data['total_with_bonus'] / $maxTotalWithBonus) * 100, 2)
                : 0;
    
            return array_merge($data, ['kpi' => $kpi]);
        });
    
        $positions = $staffUsers->pluck('position')->unique();
    
        return view('client.swod.swod', compact(
            'tasks',
            'staffUsers',
            'assignments',
            'from',
            'to',
            'maxTotalWithBonus',
            'positions'
        ));
    }   

    public function grafik(Request $request)
    {
        // Formdan kelgan filterlar
        $from = $request->input('from_date');
        $to = $request->input('to_date');
        $position = $request->input('position');
    
        // Default sanalar
        $today =Carbon::now()->day(25)->format('Y-m-d') ;
        $oneMonthAgo = Carbon::now()->subMonth()->day(26)->format('Y-m-d');
    
        // Barcha topshiriqlar (tasklar)
        $tasks = Task::all();
    
      // Takroriy va bo'sh positionlarni yo'qotish
        $positions = User::whereNotNull('position')
        ->where('position', '!=', '')
        ->select('position')
        ->distinct()
        ->orderBy('position') // optional: alfavit bo'yicha
        ->pluck('position');
        
        // Filtering: xodimlar ro'yxati
        $staffUsers = User::when($position && $position !== 'all', function ($query) use ($position) {
            $query->where('position', $position);
        })->get();
    
        // KPI natijalari (TaskAssignment) – addDate va position bo'yicha filtering
        $query = TaskAssignment::with('subtask.task', 'user');
    
        // Sana filtering
        if ($from && $to) {
            $query->whereBetween('addDate', [$from, $to]);
        } elseif (!$from && !$to) {
            // Default sanalar bo'lsa (hozirgi sanadan 1 oylik oraliq)
            $query->whereBetween('addDate', [$oneMonthAgo, $today]);
        }
    
        // Position filtering
        if ($position && $position !== 'all') {
            $query->whereHas('user', function ($q) use ($position) {
                $q->where('position', $position);
            });
        }
    
        // Assignments ma'lumotlarini olish
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
    
        // Bladega ma'lumotlarni yuborish
        return view('client.swod.index', compact(
            'tasks',
            'staffUsers',
            'assignments',
            'positions',
            'today',
            'oneMonthAgo',
            'from',       // from_date qiymatini yuborish
            'to' 
        ));
    }
    
    public function showAssign($userId)
    {
        $user = User::findOrFail($userId);

        $assignments = TaskAssignment::with('subtask.task')
            ->where('user_id', $userId)
            ->get();

        return view('client.swod.show', compact('user', 'assignments'));
    }

    public function taskDetails($userId, $taskId, Request $request)
    {
        $user = User::findOrFail($userId);
        $task = Task::findOrFail($taskId);
    
        $from = $request->input('from_date');
        $to = $request->input('to_date');
    
        $query = TaskAssignment::with('subtask.task')
            ->where('user_id', $userId)
            ->whereHas('subtask', function ($q) use ($taskId) {
                $q->where('task_id', $taskId);
            });
        
        // Sana bo‘yicha filterlash (agar mavjud bo‘lsa)
        if ($from && $to) {
            $query->whereBetween('addDate', [$from, $to]);
        }
    
        $assignments = $query->get();
    
        return view('client.swod.task-details', compact('user', 'task', 'assignments', 'from', 'to'));
    }

    public function staff(){
        $staffUsers = User::all();  // Xodimlar ro'yxatini olish
        return view('client.tasks.staff', compact('staffUsers'));
    }
       
    public function kpi(User $user)
    {
        $year = 2025;

        $tasks = Task::all();

        $normalUsers = User::all()->filter(function ($u) {
            $roles = $u->getRoleNames();
            return !$roles->contains('Admin') && !$roles->contains('Super Admin');
        });

        $kpiResults = collect();

        for ($month = 1; $month <= 12; $month++) {
            $from = Carbon::create($year, $month, 26)->subMonthNoOverflow()->startOfDay();
            $to = Carbon::create($year, $month, 25)->endOfDay();
            $kpiMonthName = Carbon::create($year, $month, 1)->format('F');

            $rawAssignments = TaskAssignment::with(['subtask.task', 'user'])
                ->whereBetween('addDate', [$from, $to])
                ->get();

            // Barcha userlar bo‘yicha rating
            $globalUserRatings = $normalUsers->mapWithKeys(function ($u) use ($rawAssignments) {
                $rating = $rawAssignments->where('user_id', $u->id)->sum('rating');
                return [$u->id => $rating];
            });

            $globalAvg = $globalUserRatings->avg() ?? 0;

            // Hozirgi userning project_id bo‘yicha foydalanuvchilar
            $projectUsers = $normalUsers->filter(fn($u) => $u->project_id == $user->project_id);

            $userRatings = $projectUsers->mapWithKeys(function ($u) use ($rawAssignments) {
                $rating = $rawAssignments->where('user_id', $u->id)->sum('rating');
                return [$u->id => $rating];
            });

            $projectAvg = $userRatings->avg() ?? 0;
            $totalRating = $userRatings[$user->id] ?? 0;

            // BONUS – faqat hozirgi user project_idsi bo‘yicha hisoblanadi
            $bonus = 0;
            if ($projectAvg > $globalAvg) {
                $bonus = $totalRating > $projectAvg
                    ? round($projectAvg * 0.10, 2)
                    : round($projectAvg * 0.05, 2);
            }

            $totalWithBonus = $totalRating + $bonus;

            // 🟡 GLOBAL eng yuqori foydalanuvchini aniqlaymiz
            $maxUserId = $globalUserRatings->sortDesc()->keys()->first();
            $maxUser = $normalUsers->firstWhere('id', $maxUserId);
            $maxUserProjectUsers = $normalUsers->filter(fn($u) => $u->project_id == $maxUser->project_id);

            $maxUserProjectRatings = $maxUserProjectUsers->mapWithKeys(function ($u) use ($rawAssignments) {
                $rating = $rawAssignments->where('user_id', $u->id)->sum('rating');
                return [$u->id => $rating];
            });

            $maxUserProjectAvg = $maxUserProjectRatings->avg() ?? 0;
            $maxUserRating = $maxUserProjectRatings[$maxUserId] ?? 0;

            $maxUserBonus = 0;
            if ($maxUserProjectAvg > $globalAvg) {
                $maxUserBonus = $maxUserRating > $maxUserProjectAvg
                    ? round($maxUserProjectAvg * 0.10, 2)
                    : round($maxUserProjectAvg * 0.05, 2);
            }

            $maxTotalWithBonus = $maxUserRating + $maxUserBonus;

            $kpi = $maxTotalWithBonus > 0
                ? round(($totalWithBonus / $maxTotalWithBonus) * 100, 2)
                : 0;

            // Foydalanuvchi shu davrda ishtirok etgan tasklar va ballari
            $taskRatings = [];

            foreach ($rawAssignments as $assignment) {
                if (
                    $assignment->user_id === $user->id &&
                    $assignment->subtask &&
                    $assignment->subtask->task
                ) {
                    $taskId = $assignment->subtask->task->id;
                    $taskName = $assignment->subtask->task->taskName;

                    if (!isset($taskRatings[$taskName])) {
                        $taskRatings[$taskName] = 0;
                    }

                    $taskRatings[$taskName] += $assignment->rating;
                }
            }

            $kpiResults->push([
                'month' => $kpiMonthName,
                'task_ratings' => $taskRatings,
                'kpi' => $kpi,
                'total_rating' => $totalRating,
                'bonus' => $bonus,
                'total_with_bonus' => $totalWithBonus,
                'from' => $from->format('Y-m-d'),
                'to' => $to->format('Y-m-d'),
            ]);
        }

        return view('client.tasks.grafikstaff', [
            'user' => $user,
            'kpiResults' => $kpiResults,
        ]);
    }
}
