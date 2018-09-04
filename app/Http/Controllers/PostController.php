<?php

namespace App\Http\Controllers;

use App\User;
use App\UserPost;
use App\UserVote;
use App\Post;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class PostController extends Controller
{
	/**
	 * Create a new post.
	 *
	 * @param \Illuminate\Http\Request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		// Check file exist an are images
		if(!$request->hasFile('image_1') && !$request->hasFile('image_2'))
		{
			$file_1 = $request->file('image_1');
			$file_2 = $request->file('image_2');
			if (!substr($file_1->getMimeType(), 0, 5) == 'image' && !substr($file_2->getMimeType(), 0, 5) == 'image')
				return response()->json(['message' => 'Incorrect post submission. Images submitted incorrectly.'], 404);
		}

		$file_1 = $request->file('image_1');
    $extension = $file_1->getClientOriginalExtension();
    $fileNameWithExt = $file_1->getClientOriginalName();
    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
    $fileName = time().'.'.$extension;
    $folderpath  = base_path().'/storage/app/images/users/';
    $file_1->move($folderpath, $fileName);

    $image_1 = Image::create([
					    	'path' => $folderpath.$fileName,
					    	]);


    $file_2 = $request->file('image_2');
    $extension = $file_2->getClientOriginalExtension();
    $fileNameWithExt = $file_2->getClientOriginalName();
    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
    $fileName = time().'.'.$extension;
    $folderpath  = base_path().'/storage/app/images/users/';
    $file_2->move($folderpath, $fileName);

    $image_2 = Image::create([
					    	'path' => $folderpath.$fileName,
					    	]);


    if( $image_1->id == null || $image_2->id == null ) {
			unlink($image_1->path);
			unlink($image_2->path);

    	$image_1->delete();
    	$image_2->delete();
    	return response()->json(['message' => 'Unable to create post. Image storage errors.'], 404);
    }

    $post = Post::create([
    		'image_1_id' => $image_1->id,
    		'image_2_id' => $image_2->id,
    	]);

    $user = $request['req']->getUser();
    $userPost = UserPost::create([
    	'user_id' => $user->id,
    	'post_id' => $post->id
    	]);

		return response()->json(['message' => "Post submitted successful."], 200);
	}
}