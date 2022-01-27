<?php

namespace BookStack\Http\Controllers\Api;

use BookStack\Entities\Models\Book;
use BookStack\Entities\Models\Bookshelf;
use BookStack\Entities\Models\Chapter;
use BookStack\Entities\Models\Page;
use Illuminate\Http\Request;

class MenuItemsApiController extends ApiController
{
    public function update(Request $request)
    {
        $this->checkPermission('settings-manage');

        $show = $request->get('show');
        $type = $request->get('type');
        $id = $request->get('id');

        if ($type == 'page') {
            $page = Page::find($id);
            if ($page) {
                $page->showonmenu = $show;
                $page->save();
                $result = 'page save';
            } else {
                $result = 'page not found';
            }
        } elseif ($type == 'chapter') {
            $page = Chapter::find($id);
            if ($page) {
                $page->showonmenu = $show;
                $page->save();
                $result = 'chapter save';
            } else {
                $result = 'chapter not found';
            }
        } elseif ($type == 'book') {
            $page = Book::find($id);
            if ($page) {
                $page->showonmenu = $show;
                $page->save();
                $result = 'book save';
            } else {
                $result = 'book not found';
            }
        } elseif ($type == 'shelf') {
            $page = Bookshelf::find($id);
            if ($page) {
                $page->showonmenu = $show;
                $page->save();
                $result = 'shelf save';
            } else {
                $result = 'shelf not found';
            }
        } else {
            $result = $type . ':type is not support';
        }

        return response()->json($result, 200);
    }
    public function list()
    {
        $content = "";
        foreach (allshelfs() as $shelf) {
            $s = str_replace('"', '', $shelf->name);
            $content .= "{\"item\":  { \"id\":  \"shelf_{$shelf->id}\", \"label\": \"{$s}\", \"checked\": {$shelf->showonmenu} }";
            if (count($shelf->books) > 0) {
                $content .= ",\"children\":[";
                foreach ($shelf->books as $book) {
                    $s = str_replace('"', '', $book->name);
                    $content .= "{\"item\":  { \"id\":  \"book_{$book->id}\", \"label\": \"{$s}\", \"checked\": {$book->showonmenu} }";
                    if (count($book->chapters) > 0 || count($book->directPages) > 0) {
                        $content .= ",\"children\":[";
                        foreach ($book->chapters as $chapter) {
                            $s = str_replace('"', '', $chapter->name);
                            $content .= "{\"item\":  { \"id\":  \"chapter_{$chapter->id}\", \"label\": \"{$s}\", \"checked\": {$chapter->showonmenu} }";
                            if (count($chapter->pages) > 0) {
                                $content .= ",\"children\":[";
                                foreach ($chapter->pages as $page) {
                                    $s = str_replace('"', '', $page->name);
                                    $content .= "{\"item\":  { \"id\":  \"page_{$page->id}\", \"label\": \"{$s}\", \"checked\": {$page->showonmenu} } },";
                                }
                                $content = rtrim($content, ",") . "]";
                            }
                            $content .= " },";
                        }
                        foreach ($book->directPages as $page) {
                            $s = str_replace('"', '', $page->name);
                            $content .= "{\"item\":  { \"id\":  \"page_{$page->id}\", \"label\": \"{$s}\", \"checked\": {$page->showonmenu} } },";
                        }
                        $content =  rtrim($content, ",") . "]";
                    }
                    $content .= " },";
                }
                $content =  rtrim($content, ",") . "]";
            }
            $content .= "},";
        }

        return response(json_encode("[" .  rtrim($content, ",")  . "]"), 200);
    }
}
