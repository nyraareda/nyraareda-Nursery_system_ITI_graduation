<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Child;
use App\Http\Requests\StoreActivityRequest;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function index()
    {
        $activities = Activity::all();
        \Log::info('Fetched Activities: ', $activities->toArray()); // إضافة تسجيل
        return response()->json($activities);
    }

    public function show($id)
    {
        $activity = Activity::with('child')->find($id); // Eager load child relationship
        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }
        return response()->json($activity);
    }

    public function store(StoreActivityRequest $request)
    {
        // التحقق من صحة البيانات المستلمة من خلال StoreActivityRequest
        $validatedData = $request->validated();

        // التحقق مما إذا كان الطالب مسجل بالفعل في النشاط
        $existingActivity = Activity::where('child_id', $validatedData['child_id'])
            ->where('activity_name', $validatedData['activity_name'])
            ->first();

        if ($existingActivity) {
            return response()->json(['message' => 'Child is already in this activity'], 400);
        }

        // إنشاء نشاط جديد وربطه بالطفل المحدد
        $activity = new Activity();
        $activity->child_id = $validatedData['child_id'];
        $activity->activity_name = $validatedData['activity_name'];
        $activity->description = $validatedData['description'];
        $activity->save();

        return response()->json(['message' => 'Child added to activity successfully', 'activity' => $activity], 201);
    }

    public function storeActivityWithNameAndDescription(Request $request)
    {
        $validatedData = $request->validate([
            'activity_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $activity = Activity::create($validatedData);

        return response()->json(['message' => 'Activity created successfully', 'activity' => $activity], 201);
    }

    public function update(StoreActivityRequest $request, $id)
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $activity->update($request->validated());
        return response()->json($activity);
    }

    public function destroy($id)
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $activity->delete();
        return response()->json(['message' => 'Activity deleted successfully']);
    }

    public function getActivitiesForChild($childId)
    {
        $activities = Activity::where('child_id', $childId)->with('child')->get();
        return response()->json($activities);
    }

    public function addChildToActivity(Request $request)
    {
        $validatedData = $request->validate([
            'child_id' => 'required|integer|exists:children,id',
            'activity_id' => 'required|integer|exists:activities,id',
        ]);

        $activity = Activity::find($validatedData['activity_id']);
        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $childId = $validatedData['child_id'];

        $existingActivity = Activity::where('child_id', $childId)
            ->where('activity_name', $activity->activity_name)
            ->first();

        if ($existingActivity) {
            return response()->json(['message' => 'Child is already in this activity'], 400);
        }

        $activity->children()->attach($childId);

        return response()->json(['message' => 'Child added to activity successfully']);
    }

    public function deleteSimilarActivities($activityName)
{
    Activity::where('activity_name', $activityName)->delete();
    return response()->json(['message' => 'Similar activities deleted successfully']);
}


public function updateSimilarActivities(Request $request, $activityName)
{
    $validatedData = $request->validate([
        'activity_name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);
    Activity::where('activity_name', $activityName)->update($validatedData);
    return response()->json(['message' => 'Similar activities updated successfully']);
}



    public function getActivityDetails($id)
    {
        $activity = Activity::with('child')->find($id);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        return response()->json([
            'activity_name' => $activity->activity_name,
            'child_name' => $activity->child->full_name
        ]);
    }

    public function updateActivityDetails(Request $request, $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $activity->update($request->only(['activity_name']));

        if ($activity->child) {
            $activity->child->update($request->only(['full_name']));
        }

        return response()->json(['message' => 'Activity and child details updated successfully']);
    }
}
