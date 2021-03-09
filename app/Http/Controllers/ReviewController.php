<?php

namespace App\Http\Controllers;
use App\Exceptions\NotBelongsToUser;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewResource;
use App\Http\Requests\ReviewRequest;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index','show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        return ReviewResource::collection($product->reviews);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReviewRequest $request, Product $product)
    {
        $review = new Review();
        $review->customer_id = Auth::id();
        $review->star = $request->star;
        $review->review = $request->review;
        // $request->all();
        // return $review;
        $product->reviews()->save($review);
        return response([
            'data' => new ReviewResource($review)
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Review $review)
    {
        $this->ProductUserCheck($review);
        $review['star'] = $request->star;
        $request['review'] = $request->review;
        $review->update($request->all());
        // return $review;
        return response([
            'data' => new ReviewResource($review)
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Review $review)
    {
        $this->ProductUserCheck($review);
        $review->delete();
        return response([
            'data' => new ReviewResource($review)
        ],204);
    }

    public function ProductUserCheck($review)
    {
        if (Auth::id() !== $review->customer_id) {
            throw new NotBelongsToUser;
        }

    }
}
