<?php

namespace BookStack\Http\Controllers;

use Illuminate\Support\Facades\DB;
use BookStack\Imports\ImportData;
use Session;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use BookStack\Entities\Repos\BookshelfRepo;
use BookStack\Entities\Repos\BookRepo;
use BookStack\Uploads\ImageRepo;
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
            DB::table('data_import')->truncate();
            if (DB::table('data_import')->count() == 0) {
                $picNameWithExt = $request->file('coursePic')->getClientOriginalName();
                $picName = pathinfo($picNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('coursePic')->getClientOriginalExtension();
                if ($extension == 'xlsx' || $extension == 'xls') {
                    $picNameToStore = $picName . time() . "." . $extension;
                    $request->file('coursePic')->move(base_path() . '/public/coursePic/', $picNameToStore);
                    Excel::import(new ImportData, base_path() . '/public/coursePic/' . $picNameToStore);
                } else {
                    echo 'only support .xlsx .xls file.';
                    exit;
                }
            }

            $pages = DB::table('data_import')->get();

            foreach ($pages as $page) {
                $shelf = null;
                $book = null;
                $chapter = null;
                $shelf_title = '';
                $book_title = '';
                $chapter_title = '';
                if ($page->root) {
                    $shelf_title = $page->root;
                    $book_title = $page->shelf;
                    $chapter_title = $page->chapter;
                } else if ($page->shelf) {
                    $shelf_title = $page->shelf;
                    $book_title = $page->book;
                    $chapter_title = $page->chapter;
                } else if ($page->book) {
                    $shelf_title = $page->book;
                    $book_title = $page->chapter;
                    if ($page->page_description != $page->page_title) {
                        $chapter_title = $page->page_description;
                    }
                } else if ($page->chapter) {
                    $shelf_title = $page->chapter;
                    $book_title = $page->page_description;
                } else if ($page->page_description) {
                    $shelf_title = $page->page_description;
                    $book_title = $page->page_title;
                }

                # code...

                if ($shelf_title) {
                    $shelf = $this->bookshelfRepo->getByName($shelf_title);
                    if (!$shelf) {
                        $arr = array();
                        $arr['name'] = $shelf_title;
                        $shelf = $this->bookshelfRepo->create($arr, []);
                    }
                }
                if ($book_title) {
                    $book = $this->bookRepo->getByName($book_title);
                    if (!$book) {
                        $arr = array();
                        $arr['name'] = $book_title;
                        $book = $this->bookRepo->create($arr);
                        $shelf->appendBook($book);
                    }
                }

                if ($chapter_title) {
                    $chapter = $this->chapterRepo->getByName($book->id, $chapter_title);
                    if (!$chapter) {
                        $arr = array();
                        $arr['name'] = $chapter_title;
                        $chapter = $this->chapterRepo->create($arr, $book);
                    }
                }

                $chapter_slug = null;
                if ($chapter) {
                    $chapter_slug = $chapter->slug;
                }

                $parent = $this->pageRepo->getParentFromSlugs($book->slug, $chapter_slug);

                $this->checkOwnablePermission('page-create', $parent);

                if ($this->isSignedIn()) {
                    $draft = $this->pageRepo->getNewDraftPage($parent);

                    $this->pageRepo->publishDraft($draft, [
                        'name' => $page->page_title,
                        'html' => str_replace("]]>", "", str_replace("<![CDATA[", "", $page->page_content)),
                    ]);
                }
            }
            echo 'import data success.';
        } else {
            echo 'file not found.';
        }
    }
}
