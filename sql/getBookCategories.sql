SELECT *
FROM book_categories
JOIN categories on book_categories.categoryid = categories.categoryid
WHERE isbn = :isbn