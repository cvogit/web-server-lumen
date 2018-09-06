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
		// Check request are made with file or base64
		if($request->hasFile('image_1') && $request->hasFile('image_1') && $request->has('title'))
		{
			$file_1 = $request->file('image_1');
	    $extension = $file_1->getClientOriginalExtension();
	    $fileNameWithExt = $file_1->getClientOriginalName();
	    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
	    $fileName = time() . '_' . mt_rand() . '.' . $extension;
	    $folderpath  = base_path().'/storage/app/images/users/';
	    $file_1->move($folderpath, $fileName);

	    $image_1 = Image::create([
						    	'path' => $folderpath.$fileName,
						    	]);


	    $file_2 = $request->file('image_2');
	    $extension = $file_2->getClientOriginalExtension();
	    $fileNameWithExt = $file_2->getClientOriginalName();
	    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
	    $fileName = time() . '_' . mt_rand() . '.' . $extension;
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
	    	'title' 			 => $request->title,
	    		'image_1_id' => $image_1->id,
	    		'image_2_id' => $image_2->id
	    	]);

	    $user = $request['req']->getUser();
	    $userPost = UserPost::create([
	    	'user_id' => $user->id,
	    	'post_id' => $post->id
	    	]);

			return response()->json(['message' => "Post submitted successful."], 200);
		}
		else if($request->has('image_1') && $request->has('image_1') && $request->has('title')) {

			$binary_1 	= $request->image_1;
			$extension_1 = 'png';
			if( strpos($binary_1, 'data:image/jpeg;base64') !== false ) {
				$extension_1 = 'jpeg';
			}

			$binary_1 		= preg_replace('/^data:image\/[a-z]+;base64,/', '', $binary_1);
			$tempImage_1 	= base64_decode(str_replace(' ', '+', $binary_1));
	    
	    $imageName_1	= time() . '_' . mt_rand() . '.' .$extension_1;
			$imagePath_1 	= base_path().'/storage/app/images/users/'.$imageName_1;
			file_put_contents($imagePath_1, $tempImage_1);

	    $image_1 = Image::create([
						    	'path' => $imagePath_1,
						    	]);


	    $binary_2 		= $request->image_2;
			$extension_2 	= 'png';
			if( strpos($binary_2, 'data:image/jpeg;base64') !== false ) {
				$extension_2 = 'jpeg';
			}

			$binary_2 		= preg_replace('/^data:image\/[a-z]+;base64,/', '', $binary_2);
			$tempImage_2 	= base64_decode(str_replace(' ', '+', $binary_2));
	    
	    $imageName_2	= time() . '_' . mt_rand() . '.' . $extension_2;
			$imagePath_2 	= base_path().'/storage/app/images/users/'.$imageName_2;
			file_put_contents($imagePath_2, $tempImage_2);

	    $image_2 = Image::create([
						    	'path' => $imagePath_2,
						    	]);


	    if( $image_1->id == null || $image_2->id == null ) {
				unlink($image_1->path);
				unlink($image_2->path);

	    	$image_1->delete();
	    	$image_2->delete();
	    	return response()->json(['message' => 'Unable to create post. Image storage errors.'], 404);
	    }

	    $post = Post::create([
	    	'title' 			 => $request->title,
	    		'image_1_id' => $image_1->id,
	    		'image_2_id' => $image_2->id
	    	]);

	    $user = $request['req']->getUser();
	    $userPost = UserPost::create([
	    	'user_id' => $user->id,
	    	'post_id' => $post->id
	    	]);

			return response()->json(['message' => "Post submitted successful."], 200);
		} else {
	    	return response()->json(['message' => 'Unable to create post. Incorrect request'], 404);

		}
	}
}