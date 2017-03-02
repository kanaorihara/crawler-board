<?php
/**
 * Created by PhpStorm.
 * User: kana
 * Date: 2017/02/17
 * Time: 18:27
 */

namespace App\Http\Controllers;

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Program;
use App\Http\Requests;
use App\Comment;

class ProgramsController extends Controller
{
    protected $article;

    public function __construct(Program $program)
    {
        $this->program = $program;
    }

    public function index()
    {
        $programs = Program::all();
        return view('index')->with([
           'programs' => $programs,
        ]);
    }

    public function show($id)
    {
        return view('index', ['id' => Program::findOrFail($id)]);
    }

    public function detail()
    {
        $programId = $_REQUEST['program_id'];
        $programs = Program::where('id', $programId)->first();
        $comments = Comment::where('program_id', $programId)->get();
        $episodes = $programs['episodes'];
        $episodes = json_decode($episodes, true);
        return view('detail', ['id' => $programId])->with([
            'program_id' => $programId,
            'comments' => $comments,
            'episodes' => $episodes,
        ]);
    }

    public function postDetail()
    {
        $programId = $_POST['program_id'];
        $parts = [];
        if (array_key_exists('comment', $_POST)) {
            $parts['program_id'] = $programId;
            $parts['comment'] = $_POST['comment'];
            Comment::insert($parts);
        }

        $programs = Program::where('id', $programId)->first();
        $comments = Comment::where('program_id', $programId)->get();
        $episodes = $programs['episodes'];
        $episodes = json_decode($episodes, true);
        return view('detail', ['id' => $programId])->with([
            'program_id' => $programId,
            'comments' => $comments,
            'episodes' => $episodes,
        ]);
    }
}
