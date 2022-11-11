<?php

namespace classes;

use PDO;

class Book {
	public int|null $id;
	public string $name;
	public string $author;
	public string $description;
	public float $price;
	public string $imageUrl;
	public int $stock;
	public string $dateCreated;
	public int $visits;

	public function __construct(array $data) {
		$this->id = $data["id"] ?? null;
		$this->name = $data["name"];
		$this->author = $data["author"];
		$this->description = $data["description"];
		$this->price = $data["price"];
		$this->imageUrl = $data["imageUrl"];
		$this->stock = $data["stock"];
		$this->dateCreated = $data["dateCreated"];
		$this->visits = $data["visits"];
	}

	/**
	 * @param PDO $db
	 *
	 * @return array<Book>
	 */
	public static function getBooks(PDO $db): array {
		$sql = "SELECT * from books";
		$stmt = $db->prepare($sql);
		$stmt->execute([]);

		$books = [];

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$books[] = new Book($row);
		}

		return $books;
	}

	public static function addBook(PDO $db, Book $book): Response {
		$sql = "INSERT INTO books 
    				(name, description, price, imageUrl, stock)
					values (:name, :description, :price, :imageUrl, :stock)";
		$stmt = $db->prepare($sql);
		if ($stmt->execute(["name" => $book->name, "description" => $book->description, "price" => $book->price,
							"imageUrl" => $book->imageUrl, "stock" => $book->stock])){
			return new Response(1, "Successfully added a book");
		} else {
			return new Response(1, "Failed to add a book");
		}
	}

}
