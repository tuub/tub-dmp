<?php

namespace App\Http\Controllers;

use App\HtmlOutputFilter;
use App\Http\Requests\Request;
use App\Http\Requests\CreatePlanRequest;
use App\Http\Requests\EmailPlanRequest;
use App\Http\Requests\VersionPlanRequest;
use App\Http\Requests\UpdatePlanRequest;

use App\IvmcMapping;
use App\Plan;
use App\User;
use App\Question;
use App\Answer;
use App\Template;

//use PhpSpec\Process\Shutdown\UpdateConsoleAction;
use Jenssegers\Optimus\Optimus;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Laracasts\Flash\Flash;

use Auth;
//use Exporters;
use Redirect;
use Log;
use Mail;
use Notifier;
use View;

/**
 * Class PlanController
 *
 * @package App\Http\Controllers
 */
class PlanController extends Controller
{
    protected $plan;
    protected $template;


    public function index()
    {
        $internal_templates = $this->template->where( 'institution_id', 1 )->where('is_active', 1)->lists( 'name', 'id' )->toArray();
        $external_templates = $this->template->where( 'institution_id', 1 )->where('is_active', 1)->lists( 'name', 'id' )->toArray();
        //$template_selector = [ 'TU Berlin' => $internal_templates ] + [ 'Other Organisations' => $external_templates ];
        $template_selector = $internal_templates;
        $user_selector = User::active()->lists('real_name','id')->toArray();
        $plans = $this->plan->getPlans();
        return view('dashboard', compact('plans', 'template_selector', 'user_selector'));
    }


    public function create(CreatePlanRequest $request)
    {
        if($this->plan->createPlan($request)) {
            $msg = 'Plan created successfully!';
            Notifier::success( $msg )->flash()->create();
        } else {
            $msg = 'There is already a plan with this project number / version!';
            Notifier::error( $msg )->flash()->create();
        }
        return Redirect::route( 'dashboard' );
    }


    public function show($id) {
        $plan = $this->plan->findOrFail($id);
        if( $plan ) {
            return view('plan.show', compact('plan'));
        }
        throw new NotFoundHttpException;
    }


    public function edit($id) {
        $plan = $this->plan->findOrFail($id);
        if( $plan ) {
            return view('plan.edit', compact('plan'));
        }
        throw new NotFoundHttpException;
    }


    public function update(UpdatePlanRequest $request)
    {
        if($this->plan->updatePlan($request)) {
            $msg = 'Save';
            if ($request->ajax()) {
                return response()->json(['message' => $msg]);
            }
            Notifier::success($msg)->flash()->create();
        } else {
            $msg = 'Not saved!';
            if ($request->ajax()) {
                return response()->json(['message' => $msg]);
            }
            Notifier::error($msg)->flash()->create();
        }
        return Redirect::back();
    }


    public function toggle($id)
    {
        $plan = $this->plan->findOrFail($id);
        if( $plan ) {
            if ( $plan->isFinal() ) {
                $plan->setFinalFlag(false);
                Notifier::success('Plan unfinalized successfully!')->flash()->create();
            } else {
                $plan->setFinalFlag(true);
                Notifier::success('Plan finalized successfully!')->flash()->create();
            }
        } else {
            Notifier::error( 'Error while finalizing plan!' )->flash()->create();
        }
        return Redirect::route( 'dashboard' );
    }


    public function email(EmailPlanRequest $request)
    {
        if($this->plan->emailPlan($request)) {
            $msg = 'Emailed successfully!';
            Notifier::success($msg)->flash()->create();
        } else {
            $msg = 'Emailing failed!';
            Notifier::error($msg)->flash()->create();
        }
        return Redirect::route('dashboard');
    }


    /**
     * @param $project_number
     * @param $version
     * @param $format
     * @param $download
     *
     * @return Redirect
     */
    public function export($id, $format = null, $download = true)
    {
        if($this->plan->exportPlan($id, $format, $download)) {
            //$msg = 'Exported successfully!';
            //Notifier::success( $msg )->flash()->create();
        } else {
            //$msg = 'Export failed!';
            //Notifier::error( $msg )->flash()->create();
        }
        return Redirect::route('dashboard');
    }


    public function version(VersionPlanRequest $request)
    {
        if($this->plan->createVersion($request)) {
            $msg = 'New version added!';
            Notifier::success( $msg )->flash()->create();
        } else {
            $msg = 'Versioning failed!';
            Notifier::error( $msg )->flash()->create();
        }
        return Redirect::route('dashboard');
    }


    public function destroy( $id ) {
        //
    }
}