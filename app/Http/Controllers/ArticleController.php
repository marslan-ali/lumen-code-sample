<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Tag;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\RealWorld\Filters\ArticleFilter;
use App\Http\Controllers\Concerns\GetsArticles;
use App\Http\Validators\ValidatesArticleRequests;

/**
 * Class ArticleController
 *
 * @package App\Http\Controllers
 * @author  Arslan Ali
 * @email   marslan.ali@gmail.com
 */
class ArticleController extends Controller
{
    use GetsArticles, ValidatesArticleRequests;

    /**
     * ArticleController constructor.
     *
     * @param ArticleFilter $filter
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'index',
            'show',
            'tags'
        ]]);
        $this->middleware('auth:optional', ['only' => [
            'index',
            'show'
        ]]);
    }

    /**
     * Get all the articles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ArticleFilter $filter)
    {
        $articles = $this->paginate(Article::filter($filter));
        return ArticleResource::collection($articles);
    }

    /**
     * Create a new article and return the article if successful.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateNew($request);

        $article = Article::create([
            'title' => $request->input('article.title'),
            'description' => $request->input('article.description'),
            'body' => $request->input('article.body'),
        ]);

        Auth::user()->articles()->save($article);

        $inputTags = $request->input('article.tagList');

        if ($inputTags && ! empty($inputTags)) {
            foreach ($inputTags as $name) {
                $article->tags()->attach(new Tag(['name' => $name]));
            }
        }
        return (new ArticleResource($article))
                ->response()
                ->header('Status', 201);
    }

    /**
     * Get the article given by its slug.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $article = $this->getArticleBySlug($slug);
        return new ArticleResource($article);
    }

    /**
     * Update the article given by its slug and return the article if successful.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $slug)
    {
        $this->validateUpdate($request);

        if ($request->has('article')) {
            $article = $this->getArticleBySlug($slug);
            if ($request->user()->cannot('update-article', $article)) {
                abort(401);
            }
            $article->update($request->get('article'));
        }
        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $slug)
    {
        $article = $this->getArticleBySlug($slug);
        if ($request->user()->cannot('delete-article', $article)) {
            abort(403);
        }
        
        $article->delete();
        return $this->respondSuccess();
    }

    /**
     * Get all the articles of users that are followed by the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function feed()
    {
        $articles = $this->paginate(Auth::user()->feed());
        return ArticleResource::collection($articles);
    }

    /**
     * Favorite the article given by its slug and return the article if successful.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFavorite(Request $request, string $slug)
    {
        $article = $this->getArticleBySlug($slug);
        if ($request->user()->can('favorite-article', $article)) {
            $request->user()->favorite($article);
        }
        return new ArticleResource($article);
    }

    /**
     * Unfavorite the article given by its slug and return the article if successful.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function unFavorite(string $slug)
    {
        $article = $this->getArticleBySlug($slug);
        Auth::user()->unFavorite($article);
        $article->save();
        
        return new ArticleResource($article);
    }

    /**
     * Get all the tags.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tags()
    {
        $names = Article::distinct('tags')->get()->pluck('name');
        $tags = $names->unique()->sort()->values()->all();

        return $this->respond(['tags' => $tags]);
    }
}
