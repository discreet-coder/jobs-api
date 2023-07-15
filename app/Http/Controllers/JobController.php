<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobApplications;
use App\Models\WorkExp;
use App\Models\EduDetail;
use App\Models\KeySkills;
use App\Models\KnownLang;
use Symfony\Component\HttpFoundation\Response;
use DB, Log, Auth, Validator, Exception;

class JobController extends Controller
{
    /**
     * Display the specified job application.
     */
    public function viewJobApplication(string $id = null)
    {
        try {
            $jobAppication = null;
            if ($id) {
                $jobAppication = JobApplications::with('workExp', 'eduDetail', 'keySkills', 'knownLang')->where('id', $id)->whereNull('deleted_at')->first();
            } else {
                $jobAppication = JobApplications::with('workExp', 'eduDetail', 'keySkills', 'knownLang')->whereNull('deleted_at')->get();
            }

            if ($jobAppication) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fetched job application successfully',
                    'result' => $jobAppication
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No job application found',
                    'result' => null
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'result' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create or Update the specified job application in storage.
     */
    public function createOrUpdateJobApplication(Request $request)
    {
        DB::beginTransaction();
        try {
            $emailVal = 'required|email|unique:job_applications';
            if ($request->has('id')) {
                $emailVal = 'required|email|unique:job_applications,email,' . $request->id;
            }

            $validation = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:100',
                'email' => $emailVal,
                'address' => 'required|string|max:500',
                'gender' => 'required|string|max:10',
                'contact' => 'required|string|min:10|max:15',
                'pref_loc' => 'required|string|min:2|max:25',
                'expected_ctc' => 'required|integer|min:0',
                'current_ctc' => 'required|integer|min:0',
                'notice' => 'required|integer|min:0',
                'work_exp.*.comp_name' => 'required|string|min:3|max:200',
                'work_exp.*.designation' => 'required|string|min:3|max:200',
                'work_exp.*.from_date' => 'required|date',
                'work_exp.*.to_date' => 'required|date',
                'edu_detail.*.board' => 'required|string|min:3|max:100',
                'edu_detail.*.pass_year' => 'required',
                'edu_detail.*.result' => 'required|numeric',
                'key_skills.*.skill' => 'required',
                'key_skills.*.level' => 'required',
                'known_lang.*.lang' => 'required',
                'known_lang.*.ability' => 'required',
            ]);

            if ($validation->fails()) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => $validation->messages()->first(),
                    'result' => null
                ], Response::HTTP_BAD_REQUEST);
            }

            $requestData = [
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'gender' => $request->gender,
                'contact' => $request->contact,
                'pref_loc' => $request->pref_loc,
                'expected_ctc' => $request->expected_ctc,
                'current_ctc' => $request->current_ctc,
                'notice' => $request->notice,
            ];

            $user = Auth::user();
            $jobAppication = null;
            $message = '';
            if ($request->has('id') && $request->id > 0) {
                $jobAppication = JobApplications::where('id', $request->id)->first();
            }

            if ($jobAppication && $user) {
                JobApplications::where('id', $request->id)->update($requestData);
                $message = "Job application updated successfully";
            } else {
                $jobAppication = JobApplications::create($requestData);
                $message = "Job application created successfully";
            }

            if ($jobAppication) {
                if ($request->has('remove_work_exp') &&
                    is_array($request->remove_work_exp) &&
                    !empty($request->remove_work_exp)
                ) {
                    WorkExp::whereIn('id', $request->remove_work_exp)->delete();
                }

                if ($request->has('work_exp') &&
                    is_array($request->work_exp) &&
                    !empty($request->work_exp)
                ) {
                    foreach($request->work_exp as $workExp) {
                        if (isset($workExp['id'])) {
                            WorkExp::where('id', $workExp['id'])->update([
                                'comp_name' => $workExp['comp_name'],
                                'designation' => $workExp['designation'],
                                'from_date' => $workExp['from_date'],
                                'to_date' => $workExp['to_date'],
                            ]);
                        } else {
                            WorkExp::create([
                                'job_application' => $jobAppication->id,
                                'comp_name' => $workExp['comp_name'],
                                'designation' => $workExp['designation'],
                                'from_date' => $workExp['from_date'],
                                'to_date' => $workExp['to_date'],
                            ]);
                        }
                    }
                }

                if ($request->has('remove_edu_detail') &&
                    is_array($request->remove_edu_detail) &&
                    !empty($request->remove_edu_detail)
                ) {
                    EduDetail::whereIn('id', $request->remove_edu_detail)->delete();
                }

                if ($request->has('edu_detail') &&
                    is_array($request->edu_detail) &&
                    !empty($request->edu_detail)
                ) {
                    foreach($request->edu_detail as $eduDetail) {
                        if (isset($eduDetail['id'])) {
                            EduDetail::where('id', $eduDetail['id'])->update([
                                'job_application' => $jobAppication->id,
                                'board' => $eduDetail['board'],
                                'pass_year' => $eduDetail['pass_year'],
                                'result' => $eduDetail['result'],
                            ]);
                        } else {
                            EduDetail::create([
                                'job_application' => $jobAppication->id,
                                'board' => $eduDetail['board'],
                                'pass_year' => $eduDetail['pass_year'],
                                'result' => $eduDetail['result'],
                            ]);
                        }
                    }
                }

                if ($request->has('remove_key_skills') &&
                    is_array($request->remove_key_skills) &&
                    !empty($request->remove_key_skills)
                ) {
                    KeySkills::whereIn('id', $request->remove_key_skills)->delete();
                }

                if ($request->has('key_skills') &&
                    is_array($request->key_skills) &&
                    !empty($request->key_skills)
                ) {
                    foreach($request->key_skills as $skills) {
                        if (isset($skills['id'])) {
                            KeySkills::where('id', $skills['id'])->update([
                                'skill' => $skills['skill'],
                                'level' => $skills['level'],
                            ]);
                        } else {
                            KeySkills::create([
                                'job_application' => $jobAppication->id,
                                'skill' => $skills['skill'],
                                'level' => $skills['level'],
                            ]);
                        }
                    }
                }

                if ($request->has('remove_known_lang') &&
                    is_array($request->remove_known_lang) &&
                    !empty($request->remove_known_lang)
                ) {
                    KnownLang::whereIn('id', $request->remove_known_lang)->delete();
                }

                if ($request->has('known_lang') &&
                    is_array($request->known_lang) &&
                    !empty($request->known_lang)
                ) {
                    foreach($request->known_lang as $lang) {
                        if (isset($lang['id'])) {
                            KnownLang::where('id', $lang['id'])->update([
                                'job_application' => $jobAppication->id,
                                'lang' => $lang['lang'],
                                'ability' => json_encode($lang['ability']),
                            ]);
                        } else {
                            KnownLang::create([
                                'job_application' => $jobAppication->id,
                                'lang' => $lang['lang'],
                                'ability' => json_encode($lang['ability']),
                            ]);
                        }
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'result' => $jobAppication
                ], Response::HTTP_OK);
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Bad request',
                    'result' => null
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'result' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified job application from storage.
     */
    public function deleteJobApplication(string $id)
    {
        DB::beginTransaction();
        try {
            $jobAppication = JobApplications::find($id)->delete();
            WorkExp::where('job_application', $id)->delete();
            EduDetail::where('job_application', $id)->delete();
            KeySkills::where('job_application', $id)->delete();
            KnownLang::where('job_application', $id)->delete();

            if ($jobAppication) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Job application deleted successfully',
                    'result' => null
                ], Response::HTTP_OK);
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Error in deletion Job application',
                    'result' => null
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'result' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
