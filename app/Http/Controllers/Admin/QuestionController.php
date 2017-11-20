<?php
/* FIXME! */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Question;
use App\Template;
use App\Section;
use App\ContentType;

use App\Http\Requests\Admin\CreateQuestionRequest;
use App\Http\Requests\Admin\UpdateQuestionRequest;

use Illuminate\Http\Request;
use Redirect;
use View;
use Illuminate\Support\Facades\Session;

class QuestionController extends Controller {

    protected $question;
    protected $template;
    protected $section;
    protected $content_type;

    public function __construct( Question $question, Template $template, Section $section, ContentType $content_type )
    {
        $this->question = $question;
        $this->template = $template;
        $this->section = $section;
        $this->content_type = $content_type;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Section $section)
    {
        $questions = $this->question->roots()->where('section_id', $section->id)->orderBy('order', 'asc')->get();
        $template = $section->template;
        return view('admin.question.index', compact('section', 'questions', 'template'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $section = $this->section->find($request->section);
        $question = new $this->question;

        $template = $section->template;
        $sections = $template->sections()->get()->pluck('name', 'id');
        $questions = $section->questions()->get()->pluck('text', 'id');
        $content_types = $this->content_type->get()->pluck('title', 'id');
        return view('admin.question.new', compact('question','questions','template', 'section','sections', 'content_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateQuestionRequest $request)
    {
        // FIXME: VALIDATE!
        $data = array_filter($request->all(), 'strlen');
        array_walk($data, function (&$item) {
            $item = ($item == '') ? null : $item;
        });

        $section = $this->section->where('id', $data['section_id'])->first();
        $data['order'] = $this->question->getNextOrderPosition($section);

        if ($question = $this->question->create($data)) {
            $notification = [
                'status' => 200,
                'message' => 'Successfully created the question!',
                'alert-type' => 'success'
            ];
        } else {
            $notification = [
                'status' => 500,
                'message' => 'Error while creating the question!',
                'alert-type' => 'error'
            ];
        }

        /* Create Flash session with return values for notification */
        $request->session()->flash('message', $notification['message']);
        $request->session()->flash('alert-type', $notification['alert-type']);

        return redirect()->route('admin.section.questions.index', $question->section->id);



        /*
        $section = $this->section->where('id', $request->get('section_id'))->first();
        $question = new $this->question;
        $question->text       = $request->get('text');
        $question->keynumber = $request->get('keynumber');
        $question->order = $this->question->getNextOrderPosition($section);
        $question->template_id      = $request->get('template_id');
        $question->section_id      = $request->get('section_id');
        $question->parent_id      = $request->get('parent_id');
        $question->output_text      = $request->get('output_text');
        $question->content_type_id      = $request->get('content_type_id');
        $question->default      = $request->get('default');
        $question->prepend      = $request->get('prepend');
        $question->append      = $request->get('append');
        $question->comment      = $request->get('comment');
        $question->reference      = $request->get('reference');
        $question->guidance      = $request->get('guidance');
        $question->hint      = $request->get('hint');
        $question->is_mandatory      = $request->get('is_mandatory');
        $question->is_active      = $request->get('is_active');
        $question->read_only      = $request->get('read_only');

        //dd($section);
        //($question);

        $question->save();
        Session::flash('message', 'Successfully created the question!');
        return redirect()->route('admin.section.questions.index', $section);
        */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = $this->question->find($id);
        $template = $question->template;
        $sections = $template->sections()->get()->pluck('name', 'id');
        $questions = $question->section->questions()->get()->pluck('text', 'id');
        $content_types = $this->content_type->get()->pluck('title', 'id');
        return view('admin.question.edit', compact('question','template', 'questions', 'sections', 'content_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestionRequest $request, $id)
    {
        // FIXME: VALIDATE!
        $data = array_filter($request->all(), 'strlen');
        array_walk($data, function (&$item) {
            $item = ($item == '') ? null : $item;
        });

        $question = $this->question->findOrFail($id);
        $section = $this->section->where('id', $data['section_id'])->first();
        // FIXME: ORDER! $data['order'] = $this->question->getNextOrderPosition($section);

        if ($question->update($data)) {
            $notification = [
                'status' => 200,
                'message' => 'Successfully updated the question!',
                'alert-type' => 'success'
            ];
        } else {
            $notification = [
                'status' => 500,
                'message' => 'Error while updating the question!',
                'alert-type' => 'error'
            ];
        }

        /* Create Flash session with return values for notification */
        $request->session()->flash('message', $notification['message']);
        $request->session()->flash('alert-type', $notification['alert-type']);

        return redirect()->route('admin.section.questions.index', $question->section->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = $this->question->find($id);
        $section = $question->section;
        $question->delete();
        Session::flash('message', 'Successfully deleted question!');
        return Redirect::route('admin.section.questions.index', $section);
    }

    /**
     * Updates positions of the given sections set.
     *
     * @uses \App\Question::updatePositions
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort(Request $request) {
        $data = $request->all();

        if ($this->question->updatePositions($data)) {
            $notification = [
                'status' => 200,
                'message' => 'Sorting updated!',
                'alert-type' => 'success'
            ];
        } else {
            $notification = [
                'status' => 500,
                'message' => 'Sorting not updated!',
                'alert-type' => 'error'
            ];
        }

        /* Create Flash session with return values for notification */
        $request->session()->flash('message', $notification['message']);
        $request->session()->flash('alert-type', $notification['alert-type']);

        /* Create the response in JSON */
        if ($request->ajax()) {
            return response()->json([
                'response' => $notification['status'],
                'message' => $notification['message']
            ]);
        } else {
            abort(403, 'Direct access is not allowed.');
        };
    }
}