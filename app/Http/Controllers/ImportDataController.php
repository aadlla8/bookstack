<?php

namespace BookStack\Http\Controllers;


use Illuminate\Support\Facades\DB;
use BookStack\Imports\ImportData;
use Bookstack\DataImport;
use Session;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use BookStack\Entities\Repos\BookshelfRepo;
use BookStack\Entities\Repos\BookRepo;
use BookStack\Uploads\ImageRepo;
use BookStack\Entities\Models\Bookshelf;
use BookStack\Entities\Models\Book;
use BookStack\Entities\Models\Chapter;
use BookStack\Entities\Models\Page;
use BookStack\Entities\Tools\ShelfContext;
use BookStack\Entities\Repos\ChapterRepo;
use BookStack\Entities\Repos\PageRepo;

class ImportDataController extends Controller
{
    protected $bookshelfRepo;
    protected $entityContextManager;
    protected $imageRepo;
    protected $bookRepo;
    protected $chapterRepo;
    protected $pageRepo;
    public function __construct(BookshelfRepo $bookshelfRepo, ShelfContext $entityContextManager, ImageRepo $imageRepo, BookRepo $bookRepo, ChapterRepo $chapterRepo, PageRepo $pageRepo)
    {
        $this->bookshelfRepo = $bookshelfRepo;
        $this->entityContextManager = $entityContextManager;
        $this->imageRepo = $imageRepo;
        $this->bookRepo = $bookRepo;
        $this->chapterRepo = $chapterRepo;
        $this->pageRepo = $pageRepo;
    }

    public function index()
    {
        //
    }

    public function create()
    {

        return view('DataImport.create');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('coursePic')) {
            $picNameWithExt = $request->file('coursePic')->getClientOriginalName();
            $picName = pathinfo($picNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('coursePic')->getClientOriginalExtension();
            if ($extension != 'xlsx') {
                echo 'only support .xlsx file.';
                exit;
            }
            $picNameToStore = $picName . time() . "." . $extension;
            $request->file('coursePic')->move(base_path() . '/public/coursePic/', $picNameToStore);

            DB::table('data_import')->truncate();
            Excel::import(new ImportData, base_path() . '/public/coursePic/' . $picNameToStore);

            $pages = DB::table('data_import')->get();

            foreach ($pages as $page) {
                $shelf = null;
                $book = null;
                $chapter = null;

                # code...
                if ($page->shelf && $page->book && $page->chapter) {
                    if ($page->shelf) {
                        $shelf = $this->bookshelfRepo->getByName($page->shelf);
                        if (!$shelf) {
                            $arr = array();
                            $arr['name'] = $page->shelf;
                            $arr['description'] = $page->shelf;
                            $shelf = $this->bookshelfRepo->create($arr, []);
                        }
                    }
                    if ($page->book) {
                        $book = $this->bookRepo->getByName($page->book);
                        if (!$book) {
                            $arr = array();
                            $arr['name'] = $page->book;
                            $arr['description'] = $page->book;
                            $book = $this->bookRepo->create($arr);
                            $shelf->appendBook($book);
                        }
                    }
                    if ($page->chapter) {
                        $chapter = $this->chapterRepo->getByName($book->id, $page->chapter);
                        if (!$chapter) {
                            $arr = array();
                            $arr['name'] = $page->chapter;
                            $arr['description'] = $page->chapter;
                            $chapter = $this->chapterRepo->create($arr, $book);
                        }
                    }
                } else if ($page->book && $page->chapter) {
                    if ($page->book) {
                        $book = $this->bookRepo->getByName($page->book);
                        if (!$book) {
                            $arr = array();
                            $arr['name'] = $page->book;
                            $arr['description'] = $page->book;
                            $book = $this->bookRepo->create($arr);
                        }
                    }
                    if ($page->chapter) {
                        $chapter = $this->chapterRepo->getByName($book->id, $page->chapter);
                        if (!$chapter) {
                            $arr = array();
                            $arr['name'] = $page->chapter;
                            $arr['description'] = $page->chapter;
                            $chapter = $this->chapterRepo->create($arr, $book);
                        }
                    }
                } else if ($page->chapter) {
                    if ($page->chapter) {
                        $book = $this->bookRepo->getByName($page->chapter);
                        if (!$book) {
                            $arr = array();
                            $arr['name'] = $page->chapter;
                            $arr['description'] = $page->chapter;
                            $book = $this->bookRepo->create($arr);
                        }
                    }
                }
                $chapterslug = '';
                if ($chapter)
                    $chapterslug = $chapter->slug;


                $parent = $this->pageRepo->getParentFromSlugs($book->slug, $chapterslug);

                $this->checkOwnablePermission('page-create', $parent);
               
                if ($this->isSignedIn()) {
                    $draft = $this->pageRepo->getNewDraftPage($parent);
                   
                    $this->pageRepo->publishDraft($draft, [
                        'name' => $page->page_title,
                        'html' => $page->page_content,
                    ]);

                }
            }
            echo 'import data success.';
        } else {
            echo 'file not found.';
        }
    }
}
