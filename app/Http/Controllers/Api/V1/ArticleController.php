<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\Article;
use App\Transformers\ArticleTransformer;

class ArticleController extends BaseController
{
    protected $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function getArticles()
    {
        $articles = Article::first();
        return $this->response->item($articles, new ArticleTransformer);
        // $article = (new Article)->with('belongsToSort')->first();
        // return $article;
    }

    /**
     * @api {get} /articles 文章列表
     * @apiDescription 文章列表
     * @apiGroup Articles
     * @apiPermission none
     * @apiVersion 1.0.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": [
     *         {
     *           "id": 6,
     *           "title": "jquery判断checkbox是否选中及改变checkbox状态",
     *           "content": "jquery判断checked的三种方法...",
     *           "tags": "4",
     *           "thumb": "571cc87d44f09.jpg",
     *           "type": 1,
     *           "sort": 1,
     *           "time": 1461504138,
     *           "count": 178,
     *           "sort_name": "后端"
     *         },
     *          {
     *           "id": 5,
     *           "title": "MYSQL数据库数据拆分之分库分表总结",
     *           "content": "本文通过实战经验总结了mysql数据库的数据拆分思路...",
     *           "tags": "8",
     *            "sort": 2,
     *           "time": 1461504064,
     *           "count": 104,
     *           "sort_name": "后端"
     *         },
     *       "meta": {
     *         "pagination": {
     *           "total": 9,
     *           "count": 3,
     *           "per_page": 3,
     *           "current_page": 2,
     *           "total_pages": 3,
     *             "previous": "http://192.168.42.133/code/lumen/public/api/articles?page=1",
     *             "next": "http://192.168.42.133/code/lumen/public/api/articles?page=3"
     *           }
     *         }
     *       }
     *     }
     */
    public function index()
    {
        $articles = $this->article->articlesPaginate(3);

        return $this->response->paginator($articles, (new ArticleTransformer));
    }

    /**
     * @api {get} /articles/{id} 文章显示
     * @apiDescription 文章显示
     * @apiGroup Articles
     * @apiPermission none
     * @apiParam {Int} id 文章id
     * @apiVersion 1.0.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *           "id": 6,
     *           "title": "jquery判断checkbox是否选中及改变checkbox状态",
     *           "content": "jquery判断checked的三种方法...",
     *           "tags": "4",
     *           "thumb": "571cc87d44f09.jpg",
     *           "type": 1,
     *           "sort": 1,
     *           "time": 1461504138,
     *           "count": 178,
     *           "sort_name": "后端"
     *       }
     *    }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 error not found
     *     {
     *         "error": {
     *         "message": "不存在此文章",
     *         "status_code": 404
     *       }
     *     }
     */
    public function show($id)
    {
        $article = $this->article->find($id);
        if ($article) {
            return $this->response->item($article, new ArticleTransformer);
        } else {
            $this->response->errorNotFound('不存在此文章');
        }
    }


}
