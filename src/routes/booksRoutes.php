<?php

use classes\Book;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


return function (App $app) {
	$app->get('/get-books', function (Request $request, Response $response){
		$getBooksResponse = Book::getBooks($this->db);

		return $response->withJson($getBooksResponse);
	});
};
