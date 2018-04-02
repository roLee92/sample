<?php

// Create and include a configuration file with the database connection
include('config.php');

// Include functions
include('functions.php');

// Get the book isbn from the url
$isbn = get('isbn');

// Get a list of books from the database with the isbn passed in the URL
$sql = file_get_contents('sql/getBook.sql');
$params = array(
	'isbn' => $isbn
);
$statement = $database->prepare($sql);
$statement->execute($params);
$books = $statement->fetchAll(PDO::FETCH_ASSOC);

// Set $book equal to the first book in $books
$book = $books[0];

// Get categories of book from the database
$sql = file_get_contents('sql/getBookCategories.sql');
$params = array(
	'isbn' => $isbn
);
$statement = $database->prepare($sql);
$statement->execute($params);
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

/* In the HTML:
	- Print the book title, author, price
	- List the categories associated with this book
*/
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
  	<title>Book</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">

	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1><?php echo $book['title'] ?></h1>
		<p>
			<?php echo $book['author']; ?><br />
			<?php echo $book['price']; ?><br />
		</p>
		
		<ul>
			<?php foreach($categories as $category) : ?>
				<li><?php echo $category['name'] ?></li>
			<?php endforeach; ?>
		</ul>
		
	</div>
</body>
</html>